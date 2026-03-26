<?php

namespace App\Http\Controllers;

use App\Models\BahanKemasan;
use App\Models\Distributor;
use App\Models\Produsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\BahanKemasanImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BahanKemasanTemplateExport;

class BahanKemasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plantId = Auth::user()->id_plant;

        $bahanKemasans = BahanKemasan::with([
            'user.role',
            'user.plant',
            'produsens' => function ($q) use ($plantId) {
                $q->wherePivot('id_plant', $plantId);
            },
            'distributors' => function ($q) use ($plantId) {
                $q->wherePivot('id_plant', $plantId);
            },
        ])->latest()->get();

        return view('super-admin.input-bahan-kemasan.index', compact('bahanKemasans'));
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
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();

            $produsens = Produsen::with(['user.plant'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();
        }

        return view('super-admin.input-bahan-kemasan.create', compact('distributors', 'produsens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kemasan' => 'required|string|max:255',
            'kategori_code' => 'required|string|in:WHD2,WHDS',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
        ]);

        $bahanKemasan = BahanKemasan::create([
            'id_user' => Auth::id(),
            'nama_kemasan' => trim($request->nama_kemasan),
            'kategori_code' => $request->kategori_code,
        ]);

        $plantId = Auth::user()->id_plant;

        $bahanKemasan->produsens()->wherePivot('id_plant', $plantId)->detach();
        $bahanKemasan->distributors()->wherePivot('id_plant', $plantId)->detach();

        $produsenIds = array_values(array_filter($request->input('id_produsen', [])));
        $distributorIds = array_values(array_filter($request->input('id_distributor', [])));

        if (!empty($produsenIds)) {
            $bahanKemasan->produsens()->attach(collect($produsenIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        if (!empty($distributorIds)) {
            $bahanKemasan->distributors()->attach(collect($distributorIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        return redirect()->route('bahan-kemasans.index')->with('success', 'Bahan Kemasan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BahanKemasan $bahanKemasan)
    {
        $plantId = Auth::user()->id_plant;
        $bahanKemasan->load([
            'user',
            'produsens' => function ($q) use ($plantId) {
                $q->wherePivot('id_plant', $plantId);
            },
            'distributors' => function ($q) use ($plantId) {
                $q->wherePivot('id_plant', $plantId);
            },
        ]);
        return view('super-admin.input-bahan-kemasan.show', compact('bahanKemasan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BahanKemasan $bahanKemasan)
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $distributors = Distributor::with(['user.plant'])->latest()->get();
            $produsens = Produsen::with(['user.plant'])->latest()->get();
        } else {
            $distributors = Distributor::with(['user.plant'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();

            $produsens = Produsen::with(['user.plant'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();
        }

        $plantId = $user->getEffectivePlantId();
        $selectedProdusenIds = $bahanKemasan->produsens()->wherePivot('id_plant', $plantId)->pluck('produsens.id')->toArray();
        $selectedDistributorIds = $bahanKemasan->distributors()->wherePivot('id_plant', $plantId)->pluck('distributors.id')->toArray();

        return view('super-admin.input-bahan-kemasan.edit', compact('bahanKemasan', 'distributors', 'produsens', 'selectedProdusenIds', 'selectedDistributorIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BahanKemasan $bahanKemasan)
    {
        $request->validate([
            'nama_kemasan' => 'required|string|max:255',
            'kategori_code' => 'required|string|in:WHD2,WHDS',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
        ]);

        $bahanKemasan->update([
            'nama_kemasan' => trim($request->nama_kemasan),
            'kategori_code' => $request->kategori_code,
        ]);

        $plantId = Auth::user()->id_plant;

        $bahanKemasan->produsens()->wherePivot('id_plant', $plantId)->detach();
        $bahanKemasan->distributors()->wherePivot('id_plant', $plantId)->detach();

        $produsenIds = array_values(array_filter($request->input('id_produsen', [])));
        $distributorIds = array_values(array_filter($request->input('id_distributor', [])));

        if (!empty($produsenIds)) {
            $bahanKemasan->produsens()->attach(collect($produsenIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        if (!empty($distributorIds)) {
            $bahanKemasan->distributors()->attach(collect($distributorIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        return redirect()->route('bahan-kemasans.index')->with('success', 'Bahan Kemasan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanKemasan $bahanKemasan)
    {
        $bahanKemasan->delete();
        return redirect()->route('bahan-kemasans.index')->with('success', 'Bahan Kemasan berhasil dihapus!');
    }

    /**
     * Check if user has access to bahan kemasan based on plant
     */
    private function checkPlantAccess(BahanKemasan $bahanKemasan)
    {
        return;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new BahanKemasanImport();
        Excel::import($import, $request->file('file'));

        $message = "Import selesai. Inserted: {$import->inserted}. Skipped: {$import->skipped}.";

        if (!empty($import->errors)) {
            return redirect()->route('bahan-kemasans.index')
                ->with('success', $message)
                ->with('import_errors', $import->errors);
        }

        return redirect()->route('bahan-kemasans.index')->with('success', $message);
    }

    public function template()
    {
        return Excel::download(new BahanKemasanTemplateExport(), 'template_import_bahan_kemasan.xlsx');
    }
}
