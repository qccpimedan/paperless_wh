<?php

namespace App\Http\Controllers;

use App\Models\Produsen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdusenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $produsens = Produsen::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $produsens = Produsen::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-produsen.index', compact('produsens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-produsen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produsen' => 'required|array|min:1',
            'nama_produsen.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaProdusen = array_filter($request->nama_produsen, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaProdusen)) {
            return back()->withErrors(['nama_produsen' => 'Minimal harus ada satu nama produsen.']);
        }

        // Create separate record for each nama_produsen
        foreach ($namaProdusen as $nama) {
            Produsen::create([
                'id_user' => Auth::id(),
                'nama_produsen' => trim($nama),
            ]);
        }

        return redirect()->route('produsens.index')->with('success', 'Produsen berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produsen $produsen)
    {
        // Check access based on plant
        $this->checkPlantAccess($produsen);
        
        $produsen->load('user');
        return view('super-admin.input-produsen.show', compact('produsen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produsen $produsen)
    {
        // Check access based on plant
        $this->checkPlantAccess($produsen);
        
        return view('super-admin.input-produsen.edit', compact('produsen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produsen $produsen)
    {
        // Check access based on plant
        $this->checkPlantAccess($produsen);
        
        $request->validate([
            'nama_produsen' => 'required|string|max:255',
        ]);

        $produsen->update([
            'nama_produsen' => trim($request->nama_produsen),
        ]);

        return redirect()->route('produsens.index')->with('success', 'Produsen berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produsen $produsen)
    {
        // Check access based on plant
        $this->checkPlantAccess($produsen);
        
        $produsen->delete();
        return redirect()->route('produsens.index')->with('success', 'Produsen berhasil dihapus!');
    }

    /**
     * Check if user has access to produsen based on plant
     */
    private function checkPlantAccess(Produsen $produsen)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($produsen->user->id_plant !== $user->id_plant) {
            abort(403, 'Unauthorized action.');
        }
    }
}