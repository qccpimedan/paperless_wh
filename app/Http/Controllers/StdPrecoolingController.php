<?php

namespace App\Http\Controllers;

use App\Models\StdPrecooling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StdPrecoolingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $stdPrecoolings = StdPrecooling::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $stdPrecoolings = StdPrecooling::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-std-precooling.index', compact('stdPrecoolings'));
    }

    public function create()
    {
        return view('super-admin.input-std-precooling.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_std_precooling' => 'required|array|min:1',
            'nama_std_precooling.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaStdPrecooling = array_filter($request->nama_std_precooling, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaStdPrecooling)) {
            return back()->withErrors(['nama_std_precooling' => 'Minimal harus ada satu nama std precooling.']);
        }

        // Create separate record for each nama_std_precooling
        foreach ($namaStdPrecooling as $nama) {
            StdPrecooling::create([
                'id_user' => Auth::id(),
                'nama_std_precooling' => trim($nama),
            ]);
        }

        return redirect()->route('std-precoolings.index')->with('success', 'Std Precooling berhasil ditambahkan!');
    }

    public function show(StdPrecooling $stdPrecooling)
    {
        $this->checkPlantAccess($stdPrecooling);
        $stdPrecooling->load('user');
        return view('super-admin.input-std-precooling.show', compact('stdPrecooling'));
    }

    public function edit(StdPrecooling $stdPrecooling)
    {
        $this->checkPlantAccess($stdPrecooling);
        return view('super-admin.input-std-precooling.edit', compact('stdPrecooling'));
    }

    public function update(Request $request, StdPrecooling $stdPrecooling)
    {
        $this->checkPlantAccess($stdPrecooling);
        
        $request->validate([
            'nama_std_precooling' => 'required|string|max:255',
        ]);

        $stdPrecooling->update([
            'nama_std_precooling' => trim($request->nama_std_precooling),
        ]);

        return redirect()->route('std-precoolings.index')->with('success', 'Std Precooling berhasil diupdate!');
    }

    public function destroy(StdPrecooling $stdPrecooling)
    {
        $this->checkPlantAccess($stdPrecooling);
        $stdPrecooling->delete();
        return redirect()->route('std-precoolings.index')->with('success', 'Std Precooling berhasil dihapus!');
    }

    private function checkPlantAccess(StdPrecooling $stdPrecooling)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($stdPrecooling->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}