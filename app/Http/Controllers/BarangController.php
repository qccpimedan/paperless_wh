<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $barangs = Barang::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $barangs = Barang::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|array|min:1',
            'nama_barang.*' => 'required|string|max:255',
            'jumlah_barang' => 'required|array|min:1',
            'jumlah_barang.*' => 'required|integer|min:0',
        ]);

        // Filter empty values
        $namaBarang = array_filter($request->nama_barang, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaBarang)) {
            return back()->withErrors(['nama_barang' => 'Minimal harus ada satu nama barang.']);
        }

        // Create separate record for each nama_barang
        foreach ($namaBarang as $index => $nama) {
            Barang::create([
                'id_user' => Auth::id(),
                'nama_barang' => trim($nama),
                'jumlah_barang' => $request->jumlah_barang[$index] ?? 0,
            ]);
        }

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        // Check access based on plant
        $this->checkPlantAccess($barang);
        
        $barang->load('user');
        return view('super-admin.input-barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        // Check access based on plant
        $this->checkPlantAccess($barang);
        
        return view('super-admin.input-barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        // Check access based on plant
        $this->checkPlantAccess($barang);
        
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah_barang' => 'required|integer|min:0',
        ]);

        $barang->update([
            'nama_barang' => trim($request->nama_barang),
            'jumlah_barang' => $request->jumlah_barang,
        ]);

        return redirect()->route('barangs.index')->with('success', 'Barang berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        // Check access based on plant
        $this->checkPlantAccess($barang);
        
        $barang->delete();
        return redirect()->route('barangs.index')->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Check if user has access to barang based on plant
     */
    private function checkPlantAccess(Barang $barang)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($barang->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
