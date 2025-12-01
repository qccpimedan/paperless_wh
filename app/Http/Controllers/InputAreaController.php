<?php

namespace App\Http\Controllers;

use App\Models\InputArea;
use App\Models\InputAreaLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $inputAreas = InputArea::with(['user.role', 'user.plant'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $inputAreas = InputArea::with(['user.role', 'user.plant'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('super-admin.input-area.index', compact('inputAreas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.input-area.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|array|min:1',
            'nama_area.*' => 'required|string|max:255',
            'lokasi_area' => 'nullable|array',
            'lokasi_area.*' => 'nullable|string|max:255',
        ]);

        // Filter empty values
        $namaArea = array_filter($request->nama_area, function($value) {
            return !empty(trim($value));
        });

        if (empty($namaArea)) {
            return back()->withErrors(['nama_area' => 'Minimal harus ada satu nama area.']);
        }

        // Filter lokasi_area
        $lokasiArea = array_filter($request->lokasi_area ?? [], function($value) {
            return !empty(trim($value));
        });

        // Create separate record for each nama_area
        foreach ($namaArea as $nama) {
            $inputArea = InputArea::create([
                'id_user' => Auth::id(),
                'nama_area' => trim($nama),
            ]);

            // Create lokasi_area if provided
            foreach ($lokasiArea as $lokasi) {
                InputAreaLocation::create([
                    'id_input_area' => $inputArea->id,
                    'lokasi_area' => trim($lokasi),
                ]);
            }
        }

        return redirect()->route('input-areas.index')->with('success', 'Area berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InputArea $inputArea)
    {
        // Check access based on plant
        $this->checkPlantAccess($inputArea);
        $inputArea->load('locations');
        
        return view('super-admin.input-area.edit', compact('inputArea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InputArea $inputArea)
    {
        // Check access based on plant
        $this->checkPlantAccess($inputArea);
        
        $request->validate([
            'nama_area' => 'required|string|max:255',
            'lokasi_area' => 'nullable|array',
            'lokasi_area.*' => 'nullable|string|max:255',
        ]);

        $inputArea->update([
            'nama_area' => trim($request->nama_area),
        ]);

        // Delete existing locations
        $inputArea->locations()->delete();

        // Create new locations if provided
        if (!empty($request->lokasi_area)) {
            $lokasiArea = array_filter($request->lokasi_area, function($value) {
                return !empty(trim($value));
            });

            foreach ($lokasiArea as $lokasi) {
                InputAreaLocation::create([
                    'id_input_area' => $inputArea->id,
                    'lokasi_area' => trim($lokasi),
                ]);
            }
        }

        return redirect()->route('input-areas.index')->with('success', 'Area berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputArea $inputArea)
    {
        // Check access based on plant
        $this->checkPlantAccess($inputArea);
        
        $inputArea->delete();
        return redirect()->route('input-areas.index')->with('success', 'Area berhasil dihapus!');
    }

    /**
     * Check if user has access to input area based on plant
     */
    private function checkPlantAccess(InputArea $inputArea)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($inputArea->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}