<?php

namespace App\Http\Controllers;

use App\Models\TujuanPengiriman;
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
            $tujuanPengirimans = TujuanPengiriman::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $tujuanPengirimans = TujuanPengiriman::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
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
        return view('super-admin.input-tujuan-pengiriman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_tujuan' => 'required|array|min:1',
            'nama_tujuan.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaTujuan = array_filter($request->nama_tujuan, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaTujuan)) {
            return back()->withErrors(['nama_tujuan' => 'Minimal harus ada satu nama tujuan.']);
        }

        // Create separate record for each nama_tujuan
        foreach ($namaTujuan as $nama) {
            TujuanPengiriman::create([
                'id_user' => Auth::id(),
                'nama_tujuan' => trim($nama),
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
        
        return view('super-admin.input-tujuan-pengiriman.edit', compact('tujuanPengiriman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TujuanPengiriman $tujuanPengiriman)
    {
        // Check access based on plant
        $this->checkPlantAccess($tujuanPengiriman);
        
        $request->validate([
            'nama_tujuan' => 'required|string|max:255',
        ]);

        $tujuanPengiriman->update([
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
        if ($tujuanPengiriman->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}