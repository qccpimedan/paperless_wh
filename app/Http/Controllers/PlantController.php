<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua plant
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $plants = Plant::latest()->get();
        } else {
            // Admin dan role lain hanya melihat plant mereka sendiri
            $plants = Plant::where('id', $user->id_plant)->latest()->get();
        }
        
        return view('super-admin.input-plant.index', compact('plants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya SuperAdmin yang dapat membuat plant baru
        $this->checkSuperAdminAccess();
        
        return view('super-admin.input-plant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hanya SuperAdmin yang dapat membuat plant baru
        $this->checkSuperAdminAccess();
        
        $request->validate([
            'plant' => 'required|string|max:255|unique:plants,plant'
        ]);

        Plant::create([
            'plant' => $request->plant
        ]);

        return redirect()->route('plants.index')->with('success', 'Plant berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plant $plant)
    {
        // Check access based on plant
        $this->checkPlantAccess($plant);
        
        return view('super-admin.input-plant.show', compact('plant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plant $plant)
    {
        // Check access based on plant
        $this->checkPlantAccess($plant);
        
        return view('super-admin.input-plant.edit', compact('plant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        // Check access based on plant
        $this->checkPlantAccess($plant);
        
        $request->validate([
            'plant' => 'required|string|max:255|unique:plants,plant,' . $plant->id
        ]);

        $plant->update([
            'plant' => $request->plant
        ]);

        return redirect()->route('plants.index')->with('success', 'Plant berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant)
    {
        // Hanya SuperAdmin yang dapat menghapus plant
        $this->checkSuperAdminAccess();
        
        $plant->delete();
        return redirect()->route('plants.index')->with('success', 'Plant berhasil dihapus!');
    }

    /**
     * Check if user is SuperAdmin
     */
    private function checkSuperAdminAccess()
    {
        $user = Auth::user();
        
        if (!$user->role || strtolower($user->role->role) !== 'superadmin') {
            abort(403, 'Hanya SuperAdmin yang dapat melakukan aksi ini.');
        }
    }

    /**
     * Check if user has access to plant
     */
    private function checkPlantAccess(Plant $plant)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua plant
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses plant mereka sendiri
        if ($plant->id !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke plant ini.');
        }
    }
}
