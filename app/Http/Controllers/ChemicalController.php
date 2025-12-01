<?php

namespace App\Http\Controllers;

use App\Models\Chemical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChemicalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $chemicals = Chemical::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $chemicals = Chemical::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-chemical.index', compact('chemicals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-chemical.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_chemical' => 'required|array|min:1',
            'nama_chemical.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaChemical = array_filter($request->nama_chemical, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaChemical)) {
            return back()->withErrors(['nama_chemical' => 'Minimal harus ada satu nama chemical.']);
        }

        // Create separate record for each nama_chemical
        foreach ($namaChemical as $nama) {
            Chemical::create([
                'id_user' => Auth::id(),
                'nama_chemical' => trim($nama),
            ]);
        }

        return redirect()->route('chemicals.index')->with('success', 'Chemical berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chemical $chemical)
    {
        // Check access based on plant
        $this->checkPlantAccess($chemical);
        
        $chemical->load('user');
        return view('super-admin.input-chemical.show', compact('chemical'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chemical $chemical)
    {
        // Check access based on plant
        $this->checkPlantAccess($chemical);
        
        return view('super-admin.input-chemical.edit', compact('chemical'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chemical $chemical)
    {
        // Check access based on plant
        $this->checkPlantAccess($chemical);
        
        $request->validate([
            'nama_chemical' => 'required|string|max:255',
        ]);

        $chemical->update([
            'nama_chemical' => trim($request->nama_chemical),
        ]);

        return redirect()->route('chemicals.index')->with('success', 'Chemical berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chemical $chemical)
    {
        // Check access based on plant
        $this->checkPlantAccess($chemical);
        
        $chemical->delete();
        return redirect()->route('chemicals.index')->with('success', 'Chemical berhasil dihapus!');
    }

    /**
     * Check if user has access to chemical based on plant
     */
    private function checkPlantAccess(Chemical $chemical)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($chemical->user->id_plant !== $user->id_plant) {
            abort(403, 'Unauthorized action.');
        }
    }
}