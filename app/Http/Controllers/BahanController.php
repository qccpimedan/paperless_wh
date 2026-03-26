<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Distributor;
use App\Models\Produsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\BahanImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BahanTemplateExport;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahans = Bahan::with(['user.role', 'user.plant'])->latest()->get();
        
        return view('super-admin.input-bahan.index', compact('bahans'));
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

        return view('super-admin.input-bahan.create', compact('distributors', 'produsens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kategori_code' => 'required|string|in:WHSE,RT01,CR01,CR02,SHTS,SHCS,OTRM,SHCS & OTRM',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
        ]);

        $bahan = Bahan::create([
            'id_user' => Auth::id(),
            'nama_bahan' => trim($request->nama_bahan),
            'kategori_code' => $request->kategori_code,
        ]);

        $plantId = Auth::user()->id_plant;

        $bahan->produsens()->wherePivot('id_plant', $plantId)->detach();
        $bahan->distributors()->wherePivot('id_plant', $plantId)->detach();

        $produsenIds = array_values(array_filter($request->input('id_produsen', [])));
        $distributorIds = array_values(array_filter($request->input('id_distributor', [])));

        if (!empty($produsenIds)) {
            $bahan->produsens()->attach(collect($produsenIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        if (!empty($distributorIds)) {
            $bahan->distributors()->attach(collect($distributorIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        return redirect()->route('bahans.index')->with('success', 'Bahan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bahan $bahan)
    {
        $bahan->load('user');
        return view('super-admin.input-bahan.show', compact('bahan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bahan $bahan)
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
        $selectedProdusenIds = $bahan->produsens()->wherePivot('id_plant', $plantId)->pluck('produsens.id')->toArray();
        $selectedDistributorIds = $bahan->distributors()->wherePivot('id_plant', $plantId)->pluck('distributors.id')->toArray();

        return view('super-admin.input-bahan.edit', compact('bahan', 'distributors', 'produsens', 'selectedProdusenIds', 'selectedDistributorIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bahan $bahan)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'kategori_code' => 'required|string|in:WHSE,RT01,CR01,CR02,SHTS,SHCS,OTRM,SHCS & OTRM',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
        ]);

        $bahan->update([
            'nama_bahan' => trim($request->nama_bahan),
            'kategori_code' => $request->kategori_code,
        ]);

        $plantId = Auth::user()->id_plant;

        $bahan->produsens()->wherePivot('id_plant', $plantId)->detach();
        $bahan->distributors()->wherePivot('id_plant', $plantId)->detach();

        $produsenIds = array_values(array_filter($request->input('id_produsen', [])));
        $distributorIds = array_values(array_filter($request->input('id_distributor', [])));

        if (!empty($produsenIds)) {
            $bahan->produsens()->attach(collect($produsenIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        if (!empty($distributorIds)) {
            $bahan->distributors()->attach(collect($distributorIds)->mapWithKeys(function ($id) use ($plantId) {
                return [$id => ['id_plant' => $plantId]];
            })->all());
        }

        return redirect()->route('bahans.index')->with('success', 'Bahan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bahan $bahan)
    {
        $bahan->delete();
        return redirect()->route('bahans.index')->with('success', 'Bahan berhasil dihapus!');
    }

    /**
     * Check if user has access to bahan based on plant
     */
    private function checkPlantAccess(Bahan $bahan)
    {
        return;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new BahanImport();
        Excel::import($import, $request->file('file'));

        $message = "Import selesai. Inserted: {$import->inserted}. Skipped: {$import->skipped}.";

        if (!empty($import->errors)) {
            return redirect()->route('bahans.index')
                ->with('success', $message)
                ->with('import_errors', $import->errors);
        }

        return redirect()->route('bahans.index')->with('success', $message);
    }

    public function template()
    {
        return Excel::download(new BahanTemplateExport(), 'template_import_bahan.xlsx');
    }
}
