<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $distributors = Distributor::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $distributors = Distributor::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-distributor.index', compact('distributors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-distributor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_distributor' => 'required|array|min:1',
            'nama_distributor.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaDistributor = array_filter($request->nama_distributor, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaDistributor)) {
            return back()->withErrors(['nama_distributor' => 'Minimal harus ada satu nama distributor.']);
        }

        // Create separate record for each nama_distributor
        foreach ($namaDistributor as $nama) {
            Distributor::create([
                'id_user' => Auth::id(),
                'nama_distributor' => trim($nama),
            ]);
        }

        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Distributor $distributor)
    {
        // Check access based on plant
        $this->checkPlantAccess($distributor);
        
        $distributor->load('user');
        return view('super-admin.input-distributor.show', compact('distributor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Distributor $distributor)
    {
        // Check access based on plant
        $this->checkPlantAccess($distributor);
        
        return view('super-admin.input-distributor.edit', compact('distributor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Distributor $distributor)
    {
        // Check access based on plant
        $this->checkPlantAccess($distributor);
        
        $request->validate([
            'nama_distributor' => 'required|string|max:255',
        ]);

        $distributor->update([
            'nama_distributor' => trim($request->nama_distributor),
        ]);

        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distributor $distributor)
    {
        // Check access based on plant
        $this->checkPlantAccess($distributor);
        
        $distributor->delete();
        return redirect()->route('distributors.index')->with('success', 'Distributor berhasil dihapus!');
    }

    /**
     * Check if user has access to distributor based on plant
     */
    private function checkPlantAccess(Distributor $distributor)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($distributor->user->id_plant !== $user->id_plant) {
            abort(403, 'Unauthorized action.');
        }
    }
}