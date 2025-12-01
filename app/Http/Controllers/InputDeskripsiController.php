<?php

namespace App\Http\Controllers;

use App\Models\InputDeskripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputDeskripsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $deskripsis = InputDeskripsi::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $deskripsis = InputDeskripsi::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-deskripsi.index', compact('deskripsis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-deskripsi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_deskripsi' => 'required|array|min:1',
            'nama_deskripsi.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaDeskripsi = array_filter($request->nama_deskripsi, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaDeskripsi)) {
            return back()->withErrors(['nama_deskripsi' => 'Minimal harus ada satu deskripsi.']);
        }

        // Create separate record for each nama_deskripsi
        foreach ($namaDeskripsi as $nama) {
            InputDeskripsi::create([
                'id_user' => Auth::id(),
                'nama_deskripsi' => trim($nama),
            ]);
        }

        return redirect()->route('input-deskripsis.index')->with('success', 'Deskripsi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InputDeskripsi $inputDeskripsi)
    {
        // Check access based on plant
        $this->checkPlantAccess($inputDeskripsi);
        
        return view('super-admin.input-deskripsi.edit', compact('inputDeskripsi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InputDeskripsi $inputDeskripsi)
    {
        // Check access based on plant
        $this->checkPlantAccess($inputDeskripsi);
        
        $request->validate([
            'nama_deskripsi' => 'required|string|max:255',
        ]);

        $inputDeskripsi->update([
            'nama_deskripsi' => trim($request->nama_deskripsi),
        ]);

        return redirect()->route('input-deskripsis.index')->with('success', 'Deskripsi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputDeskripsi $inputDeskripsi)
    {
        // Check access based on plant
        $this->checkPlantAccess($inputDeskripsi);
        
        $inputDeskripsi->delete();
        return redirect()->route('input-deskripsis.index')->with('success', 'Deskripsi berhasil dihapus!');
    }

    /**
     * Check if user has access to input deskripsi based on plant
     */
    private function checkPlantAccess(InputDeskripsi $inputDeskripsi)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($inputDeskripsi->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}