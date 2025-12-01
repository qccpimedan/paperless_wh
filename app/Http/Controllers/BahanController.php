<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $bahans = Bahan::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $bahans = Bahan::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-bahan.index', compact('bahans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-bahan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|array|min:1',
            'nama_bahan.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaBahan = array_filter($request->nama_bahan, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaBahan)) {
            return back()->withErrors(['nama_bahan' => 'Minimal harus ada satu nama bahan.']);
        }

        // Create separate record for each nama_bahan
        foreach ($namaBahan as $nama) {
            Bahan::create([
                'id_user' => Auth::id(),
                'nama_bahan' => trim($nama),
            ]);
        }

        return redirect()->route('bahans.index')->with('success', 'Bahan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bahan $bahan)
    {
        // Check access based on plant
        $this->checkPlantAccess($bahan);
        
        $bahan->load('user');
        return view('super-admin.input-bahan.show', compact('bahan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bahan $bahan)
    {
        // Check access based on plant
        $this->checkPlantAccess($bahan);
        
        return view('super-admin.input-bahan.edit', compact('bahan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bahan $bahan)
    {
        // Check access based on plant
        $this->checkPlantAccess($bahan);
        
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
        ]);

        $bahan->update([
            'nama_bahan' => trim($request->nama_bahan),
        ]);

        return redirect()->route('bahans.index')->with('success', 'Bahan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bahan $bahan)
    {
        // Check access based on plant
        $this->checkPlantAccess($bahan);
        
        $bahan->delete();
        return redirect()->route('bahans.index')->with('success', 'Bahan berhasil dihapus!');
    }

    /**
     * Check if user has access to bahan based on plant
     */
    private function checkPlantAccess(Bahan $bahan)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($bahan->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
