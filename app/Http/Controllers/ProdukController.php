<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use App\Models\Produsen;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProdukTemplateExport;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedKategori = $request->input('kategori_code');

        $plantId = Auth::user()->id_plant;

        $kategoriOptions = [
            'WHSE',
            'WHD2',
            'WHDS',
            'RT01',
            'CR01',
            'CR02',
            'SHTS',
            'SHCS & OTRM',
            'CHEMICAL',
        ];

        if ($request->ajax()) {
            $query = Produk::with([
                'user.role',
                'user.plant',
                'produsens' => function ($q) use ($plantId) {
                    $q->wherePivot('id_plant', $plantId);
                },
                'distributors' => function ($q) use ($plantId) {
                    $q->wherePivot('id_plant', $plantId);
                },
            ]);

            if ($selectedKategori) {
                $query->where('kategori_code', $selectedKategori);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('nama_produk', function($row) {
                    return "<strong>" . htmlspecialchars($row->nama_produk) . "</strong>";
                })
                ->editColumn('kategori_code', function($row) {
                    return htmlspecialchars($row->kategori_code ?? '-');
                })
                ->addColumn('produsen', function($row) {
                    return $row->produsens->count() > 0 ? htmlspecialchars($row->produsens->pluck('nama_produsen')->implode(', ')) : '-';
                })
                ->addColumn('distributor', function($row) {
                    return $row->distributors->count() > 0 ? htmlspecialchars($row->distributors->pluck('nama_distributor')->implode(', ')) : '-';
                })
                ->addColumn('action', function($row) {
                    $editUrl = route('produks.edit', $row->uuid);
                    $deleteUrl = route('produks.destroy', $row->uuid);
                    
                    $btns = "<div class='btn-vertical'>";
                    if (Auth::user()->can('edit_produks')) {
                        $btns .= "<a href='{$editUrl}' class='btn btn-sm btn-warning mb-1' title='Edit Data'><i class='bi bi-pencil'></i></a> ";
                    }
                    if (Auth::user()->can('delete_produks')) {
                        $csrf = csrf_token();
                        $btns .= "<form action='{$deleteUrl}' method='POST' style='display: inline-block;' onsubmit='return confirm(\"Yakin ingin menghapus produk " . htmlspecialchars($row->nama_produk) . "?\")'>
                                    <input type='hidden' name='_token' value='{$csrf}'>
                                    <input type='hidden' name='_method' value='DELETE'>
                                    <button type='submit' class='btn btn-sm btn-danger mb-1' title='Hapus Data'>
                                        <i class='bi bi-trash'></i>
                                    </button>
                                  </form>";
                    }
                    $btns .= "</div>";
                    return $btns;
                })
                ->filterColumn('nama_produk', function($query, $keyword) {
                    $query->where('nama_produk', 'like', "%{$keyword}%");
                })
                ->filterColumn('kategori_code', function($query, $keyword) {
                    $query->where('kategori_code', 'like', "%{$keyword}%");
                })
                ->rawColumns(['nama_produk', 'action'])
                ->make(true);
        }

        // For initial page load (non-AJAX request)
        return view('super-admin.input-produk.index', compact('selectedKategori', 'kategoriOptions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        $produk->load('user');
        return view('super-admin.input-produk.show', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $distributors = Distributor::with(['user.plant'])->latest()->get();
            $produsens = Produsen::with(['user.plant'])->latest()->get();
        } else {
            $distributors = Distributor::with(['user.plant'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();

            $produsens = Produsen::with(['user.plant'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }

        return view('super-admin.input-produk.create', compact('distributors', 'produsens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_code' => 'required|string|in:WHSE,WHD2,WHDS,RT01,CR01,CR02,SHTS,SHCS,OTRM,SHCS & OTRM,CHEMICAL',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
        ]);

        $produk = Produk::create([
            'id_user' => Auth::id(),
            'nama_produk' => trim($request->nama_produk),
            'kategori_code' => $request->kategori_code,
        ]);

        $plantId = Auth::user()->id_plant;

        $produk->produsens()->wherePivot('id_plant', $plantId)->detach();
        $produk->distributors()->wherePivot('id_plant', $plantId)->detach();

        $produsenIds = array_values(array_filter($request->input('id_produsen', [])));
        $distributorIds = array_values(array_filter($request->input('id_distributor', [])));

        if (!empty($produsenIds)) {
            $produk->produsens()->attach(collect($produsenIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        if (!empty($distributorIds)) {
            $produk->distributors()->attach(collect($distributorIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        return redirect()->route('produks.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Produk $produk)
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $distributors = Distributor::with(['user.plant'])->latest()->get();
            $produsens = Produsen::with(['user.plant'])->latest()->get();
        } else {
            $distributors = Distributor::with(['user.plant'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();

            $produsens = Produsen::with(['user.plant'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }

        $plantId = $user->id_plant;
        $selectedProdusenIds = $produk->produsens()->wherePivot('id_plant', $plantId)->pluck('produsens.id')->toArray();
        $selectedDistributorIds = $produk->distributors()->wherePivot('id_plant', $plantId)->pluck('distributors.id')->toArray();

        return view('super-admin.input-produk.edit', compact('produk', 'distributors', 'produsens', 'selectedProdusenIds', 'selectedDistributorIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_code' => 'required|string|in:WHSE,WHD2,WHDS,RT01,CR01,CR02,SHTS,SHCS,OTRM,SHCS & OTRM,CHEMICAL',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
        ]);

        $produk->update([
            'nama_produk' => trim($request->nama_produk),
            'kategori_code' => $request->kategori_code,
        ]);

        $plantId = Auth::user()->id_plant;

        $produk->produsens()->wherePivot('id_plant', $plantId)->detach();
        $produk->distributors()->wherePivot('id_plant', $plantId)->detach();

        $produsenIds = array_values(array_filter($request->input('id_produsen', [])));
        $distributorIds = array_values(array_filter($request->input('id_distributor', [])));

        if (!empty($produsenIds)) {
            $produk->produsens()->attach(collect($produsenIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        if (!empty($distributorIds)) {
            $produk->distributors()->attach(collect($distributorIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        return redirect()->route('produks.index')->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produks.index')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Check if user has access to produk based on plant
     */
    private function checkPlantAccess(Produk $produk)
    {
        return;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new ProdukImport();
        Excel::import($import, $request->file('file'));

        $message = "Import selesai. Inserted: {$import->inserted}. Skipped: {$import->skipped}.";

        if (!empty($import->errors)) {
            return redirect()->route('produks.index')
                ->with('success', $message)
                ->with('import_errors', $import->errors);
        }

        return redirect()->route('produks.index')->with('success', $message);
    }

    public function template()
    {
        return Excel::download(new ProdukTemplateExport(), 'template_import_produk.xlsx');
    }
}