<?php

namespace App\Http\Controllers;

use App\Models\TujuanPengiriman;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TujuanPengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $tujuanPengirimans = TujuanPengiriman::with(['user.role', 'user.plant', 'customer'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $tujuanPengirimans = TujuanPengiriman::with(['user.role', 'user.plant', 'customer'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-tujuan-pengiriman.index', compact('tujuanPengirimans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $customers = Customer::with(['user.plant'])->latest()->get();
        } else {
            $customers = Customer::with(['user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();
        }

        return view('super-admin.input-tujuan-pengiriman.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_customer' => 'nullable|array',
            'id_customer.*' => 'nullable|exists:customers,id',
            'nama_tujuan' => 'required|array|min:1',
            'nama_tujuan.*' => 'required|string|max:255',
        ]);

        $namaTujuanArray = $request->input('nama_tujuan', []);
        $idCustomerArray = $request->input('id_customer', []);

        $hasAtLeastOneTujuan = collect($namaTujuanArray)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => $v !== '')
            ->isNotEmpty();

        if (!$hasAtLeastOneTujuan) {
            return back()->withErrors(['nama_tujuan' => 'Minimal harus ada satu nama tujuan.']);
        }

        // Create separate record for each nama_tujuan
        foreach ($namaTujuanArray as $index => $nama) {
            $nama = trim((string) $nama);
            if ($nama === '') {
                continue;
            }

            TujuanPengiriman::create([
                'id_user' => Auth::id(),
                'id_customer' => $idCustomerArray[$index] ?? null,
                'nama_tujuan' => $nama,
            ]);
        }

        return redirect()->route('tujuan-pengirimans.index')->with('success', 'Tujuan Pengiriman berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TujuanPengiriman $tujuanPengiriman)
    {
        // Check access based on plant
        $this->checkPlantAccess($tujuanPengiriman);
        
        $tujuanPengiriman->load('user');
        return view('super-admin.input-tujuan-pengiriman.show', compact('tujuanPengiriman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TujuanPengiriman $tujuanPengiriman)
    {
        // Check access based on plant
        $this->checkPlantAccess($tujuanPengiriman);

        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $customers = Customer::with(['user.plant'])->latest()->get();
        } else {
            $customers = Customer::with(['user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->getEffectivePlantId());
                })
                ->latest()
                ->get();
        }

        return view('super-admin.input-tujuan-pengiriman.edit', compact('tujuanPengiriman', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TujuanPengiriman $tujuanPengiriman)
    {
        // Check access based on plant
        $this->checkPlantAccess($tujuanPengiriman);
        
        $request->validate([
            'id_customer' => 'nullable|exists:customers,id',
            'nama_tujuan' => 'required|string|max:255',
        ]);

        $tujuanPengiriman->update([
            'id_customer' => $request->input('id_customer'),
            'nama_tujuan' => trim($request->nama_tujuan),
        ]);

        return redirect()->route('tujuan-pengirimans.index')->with('success', 'Tujuan Pengiriman berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TujuanPengiriman $tujuanPengiriman)
    {
        // Check access based on plant
        $this->checkPlantAccess($tujuanPengiriman);
        
        $tujuanPengiriman->delete();
        return redirect()->route('tujuan-pengirimans.index')->with('success', 'Tujuan Pengiriman berhasil dihapus!');
    }

    /**
     * Check if user has access to tujuan pengiriman based on plant
     */
    private function checkPlantAccess(TujuanPengiriman $tujuanPengiriman)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($tujuanPengiriman->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}