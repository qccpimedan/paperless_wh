<?php

namespace App\Http\Controllers;

use App\Models\JenisKendaraan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisKendaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $jenisKendaraans = JenisKendaraan::with(['user.role', 'user.plant'])->latest()->get();
        } else {
        // Admin dan role lain hanya melihat data sesuai plant mereka
            $jenisKendaraans = JenisKendaraan::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-jenis-kendaraan.index', compact('jenisKendaraans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-jenis-kendaraan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|array|min:1',
            'jenis_kendaraan.*' => 'required|string|max:255',
            'no_kendaraan' => 'required|array|min:1',
            'no_kendaraan.*' => 'required|string|max:255',
        ]);

        // Filter empty values
        $jenisKendaraan = array_filter($request->jenis_kendaraan, function($value) {
            return !empty(trim($value));
        });
        
        $noKendaraan = array_filter($request->no_kendaraan, function($value) {
            return !empty(trim($value));
        });

        if (empty($jenisKendaraan) || empty($noKendaraan)) {
            return back()->withErrors(['jenis_kendaraan' => 'Minimal harus ada satu jenis dan nomor kendaraan.']);
        }

        // Ensure both arrays have the same length
        if (count($jenisKendaraan) !== count($noKendaraan)) {
            return back()->withErrors(['jenis_kendaraan' => 'Jumlah jenis dan nomor kendaraan harus sama.']);
        }

        // Create separate record for each jenis_kendaraan and no_kendaraan pair
        $jenisArray = array_values($jenisKendaraan);
        $noArray = array_values($noKendaraan);
        
        for ($i = 0; $i < count($jenisArray); $i++) {
            JenisKendaraan::create([
                'id_user' => Auth::id(),
                'jenis_kendaraan' => trim($jenisArray[$i]),
                'no_kendaraan' => trim($noArray[$i]),
            ]);
        }

        return redirect()->route('jenis-kendaraans.index')->with('success', 'Jenis Kendaraan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisKendaraan $jenisKendaraan)
    {
        // Check access based on plant
        $this->checkPlantAccess($jenisKendaraan);
        
        $jenisKendaraan->load('user');
        return view('super-admin.input-jenis-kendaraan.show', compact('jenisKendaraan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisKendaraan $jenisKendaraan)
    {
        // Check access based on plant
        $this->checkPlantAccess($jenisKendaraan);
        
        return view('super-admin.input-jenis-kendaraan.edit', compact('jenisKendaraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisKendaraan $jenisKendaraan)
    {
        // Check access based on plant
        $this->checkPlantAccess($jenisKendaraan);
        
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255',
            'no_kendaraan' => 'required|string|max:255',
        ]);

        $jenisKendaraan->update([
            'jenis_kendaraan' => trim($request->jenis_kendaraan),
            'no_kendaraan' => trim($request->no_kendaraan),
        ]);

        return redirect()->route('jenis-kendaraans.index')->with('success', 'Jenis Kendaraan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisKendaraan $jenisKendaraan)
    {
        // Check access based on plant
        $this->checkPlantAccess($jenisKendaraan);
        
        $jenisKendaraan->delete();
        return redirect()->route('jenis-kendaraans.index')->with('success', 'Jenis Kendaraan berhasil dihapus!');
    }

    /**
     * Check if user has access to jenis kendaraan based on plant
     */
    private function checkPlantAccess(JenisKendaraan $jenisKendaraan)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($jenisKendaraan->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}