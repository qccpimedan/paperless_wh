<?php

namespace App\Http\Controllers;

use App\Models\Ekspedisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EkspedisiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $ekspedisis = Ekspedisi::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $ekspedisis = Ekspedisi::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-ekspedisi.index', compact('ekspedisis'));
    }

    public function create()
    {
        return view('super-admin.input-ekspedisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ekspedisi' => 'required|array|min:1',
            'nama_ekspedisi.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $namaEkspedisi = array_filter($request->nama_ekspedisi, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaEkspedisi)) {
            return back()->withErrors(['nama_ekspedisi' => 'Minimal harus ada satu nama ekspedisi.']);
        }

        // Create separate record for each nama_ekspedisi
        foreach ($namaEkspedisi as $nama) {
            Ekspedisi::create([
                'id_user' => Auth::id(),
                'nama_ekspedisi' => trim($nama),
            ]);
        }

        return redirect()->route('ekspedisis.index')->with('success', 'Ekspedisi berhasil ditambahkan!');
    }

    public function show(Ekspedisi $ekspedisi)
    {
        $this->checkPlantAccess($ekspedisi);
        $ekspedisi->load('user');
        return view('super-admin.input-ekspedisi.show', compact('ekspedisi'));
    }

    public function edit(Ekspedisi $ekspedisi)
    {
        $this->checkPlantAccess($ekspedisi);
        return view('super-admin.input-ekspedisi.edit', compact('ekspedisi'));
    }

    public function update(Request $request, Ekspedisi $ekspedisi)
    {
        $this->checkPlantAccess($ekspedisi);
        
        $request->validate([
            'nama_ekspedisi' => 'required|string|max:255',
        ]);

        $ekspedisi->update([
            'nama_ekspedisi' => trim($request->nama_ekspedisi),
        ]);

        return redirect()->route('ekspedisis.index')->with('success', 'Ekspedisi berhasil diupdate!');
    }

    public function destroy(Ekspedisi $ekspedisi)
    {
        $this->checkPlantAccess($ekspedisi);
        $ekspedisi->delete();
        return redirect()->route('ekspedisis.index')->with('success', 'Ekspedisi berhasil dihapus!');
    }

    private function checkPlantAccess(Ekspedisi $ekspedisi)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($ekspedisi->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}