<?php

namespace App\Http\Controllers;

use App\Models\Supir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $supirs = Supir::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $supirs = Supir::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-supir.index', compact('supirs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-supir.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_supir' => 'required|array|min:1',
            'nama_supir.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaSupir = array_filter($request->nama_supir, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaSupir)) {
            return back()->withErrors(['nama_supir' => 'Minimal harus ada satu nama supir.']);
        }

        // Create separate record for each nama_supir
        foreach ($namaSupir as $nama) {
            Supir::create([
                'id_user' => Auth::id(),
                'nama_supir' => trim($nama),
            ]);
        }

        return redirect()->route('supirs.index')->with('success', 'Supir berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supir $supir)
    {
        // Check access based on plant
        $this->checkPlantAccess($supir);
        
        $supir->load('user');
        return view('super-admin.input-supir.show', compact('supir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supir $supir)
    {
        // Check access based on plant
        $this->checkPlantAccess($supir);
        
        return view('super-admin.input-supir.edit', compact('supir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supir $supir)
    {
        // Check access based on plant
        $this->checkPlantAccess($supir);
        
        $request->validate([
            'nama_supir' => 'required|string|max:255',
        ]);

        $supir->update([
            'nama_supir' => trim($request->nama_supir),
        ]);

        return redirect()->route('supirs.index')->with('success', 'Supir berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supir $supir)
    {
        // Check access based on plant
        $this->checkPlantAccess($supir);
        
        $supir->delete();
        return redirect()->route('supirs.index')->with('success', 'Supir berhasil dihapus!');
    }

    /**
     * Check if user has access to supir based on plant
     */
    private function checkPlantAccess(Supir $supir)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($supir->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}