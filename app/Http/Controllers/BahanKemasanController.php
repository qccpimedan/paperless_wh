<?php

namespace App\Http\Controllers;

use App\Models\BahanKemasan;
use App\Models\Distributor;
use App\Models\Produsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BahanKemasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $bahanKemasans = BahanKemasan::with(['user.role', 'user.plant', 'distributor', 'produsen'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $bahanKemasans = BahanKemasan::with(['user.role', 'user.plant', 'distributor', 'produsen'])
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }

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

        return view('super-admin.input-bahan-kemasan.create', compact('distributors', 'produsens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'nama_kemasan' => 'required|array|min:1',
            'nama_kemasan.*' => 'required|string|max:255',
        ]);

        $namaKemasanArray = $request->input('nama_kemasan', []);
        $idDistributorArray = $request->input('id_distributor', []);
        $idProdusenArray = $request->input('id_produsen', []);

        $hasAtLeastOneKemasan = collect($namaKemasanArray)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => $v !== '')
            ->isNotEmpty();

        if (!$hasAtLeastOneKemasan) {
            return back()->withErrors(['nama_kemasan' => 'Minimal harus ada satu nama kemasan.']);
        }

        // Create separate record for each nama_kemasan
        foreach ($namaKemasanArray as $index => $nama) {
            $nama = trim((string) $nama);
            if ($nama === '') {
                continue;
            }

            BahanKemasan::create([
                'id_user' => Auth::id(),
                'id_distributor' => $idDistributorArray[$index] ?? null,
                'id_produsen' => $idProdusenArray[$index] ?? null,
                'nama_kemasan' => $nama,
            ]);
        }

        return redirect()->route('bahan-kemasans.index')->with('success', 'Bahan Kemasan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BahanKemasan $bahanKemasan)
    {
        $this->checkPlantAccess($bahanKemasan);

        $bahanKemasan->load(['user', 'distributor', 'produsen']);
        return view('super-admin.input-bahan-kemasan.show', compact('bahanKemasan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BahanKemasan $bahanKemasan)
    {
        $this->checkPlantAccess($bahanKemasan);

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

        return view('super-admin.input-bahan-kemasan.edit', compact('bahanKemasan', 'distributors', 'produsens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BahanKemasan $bahanKemasan)
    {
        $this->checkPlantAccess($bahanKemasan);

        $request->validate([
            'id_distributor' => 'nullable|exists:distributors,id',
            'id_produsen' => 'nullable|exists:produsens,id',
            'nama_kemasan' => 'required|string|max:255',
        ]);

        $bahanKemasan->update([
            'id_distributor' => $request->id_distributor,
            'id_produsen' => $request->id_produsen,
            'nama_kemasan' => trim($request->nama_kemasan),
        ]);

        return redirect()->route('bahan-kemasans.index')->with('success', 'Bahan Kemasan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BahanKemasan $bahanKemasan)
    {
        $this->checkPlantAccess($bahanKemasan);

        $bahanKemasan->delete();
        return redirect()->route('bahan-kemasans.index')->with('success', 'Bahan Kemasan berhasil dihapus!');
    }

    /**
     * Check if user has access to bahan kemasan based on plant
     */
    private function checkPlantAccess(BahanKemasan $bahanKemasan)
    {
        $user = Auth::user();

        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }

        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($bahanKemasan->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
