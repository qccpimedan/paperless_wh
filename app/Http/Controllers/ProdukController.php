<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $produks = Produk::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $produks = Produk::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-produk.index', compact('produks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|array|min:1',
            'nama_produk.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaProduk = array_filter($request->nama_produk, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaProduk)) {
            return back()->withErrors(['nama_produk' => 'Minimal harus ada satu nama produk.']);
        }

        // Create separate record for each nama_produk
        foreach ($namaProduk as $nama) {
            Produk::create([
                'id_user' => Auth::id(),
                'nama_produk' => trim($nama),
            ]);
        }

        return redirect()->route('produks.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        // Check access based on plant
        $this->checkPlantAccess($produk);
        
        $produk->load('user');
        return view('super-admin.input-produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        // Check access based on plant
        $this->checkPlantAccess($produk);
        
        return view('super-admin.input-produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        // Check access based on plant
        $this->checkPlantAccess($produk);
        
        $request->validate([
            'nama_produk' => 'required|string|max:255',
        ]);

        $produk->update([
            'nama_produk' => trim($request->nama_produk),
        ]);

        return redirect()->route('produks.index')->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        // Check access based on plant
        $this->checkPlantAccess($produk);
        
        $produk->delete();
        return redirect()->route('produks.index')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Check if user has access to produk based on plant
     */
    private function checkPlantAccess(Produk $produk)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($produk->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}