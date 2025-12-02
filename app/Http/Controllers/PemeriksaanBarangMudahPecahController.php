<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanBarangMudahPecah;
use App\Models\PemeriksaanBarangMudahPecahDetail;
use App\Models\Shift;
use App\Models\InputArea;
use App\Models\InputAreaLocation;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemeriksaanBarangMudahPecahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        $query = PemeriksaanBarangMudahPecah::with(['user', 'shift', 'area', 'details.barang', 'details.areaLocation']);
        
        // Filter berdasarkan role dan plant
        if ($user->role) {
            $role = strtolower($user->role->role);
            
            // SuperAdmin: lihat semua data
            if ($role !== 'superadmin') {
                // Admin dan role lain: lihat data dari plant mereka saja
                $query->whereHas('user', function($q) use ($user) {
                    $q->where('id_plant', $user->id_plant);
                });
            }
        }
        
        $pemeriksaans = $query->latest()->paginate(15);
        
        return view('qc-sistem.pemeriksaan-barang-mudah-pecah.index', compact('pemeriksaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get shifts berdasarkan plant user
        $shiftQuery = Shift::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $shiftQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $shifts = $shiftQuery->get();
        
        // Get areas berdasarkan plant user
        $areaQuery = InputArea::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $areaQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $areas = $areaQuery->get();
        
        // Get barangs berdasarkan plant user
        $barangQuery = Barang::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $barangQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $barangs = $barangQuery->get();
        
        return view('qc-sistem.pemeriksaan-barang-mudah-pecah.create', compact('shifts', 'areas', 'barangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDASI BASIC FIELDS
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'id_area' => 'required|exists:input_areas,id',
            'details' => 'required|array|min:1',
        ]);

        // 2. PROSES DETAILS - TERIMA DATA APA ADANYA
        $validDetails = [];
        
        foreach ($request->details as $index => $detail) {
            // Skip jika tidak ada lokasi area (REQUIRED)
            if (empty($detail['id_input_area_locations'] ?? null)) {
                continue;
            }
            // Awal dan Akhir sekarang NULLABLE - tidak perlu skip
            
            // Tentukan apakah manual atau database
            $isManual = !empty($detail['nama_barang_manual'] ?? null);
            
            if ($isManual) {
                // MANUAL MODE
                $validDetails[] = [
                    'id_barang' => null,
                    'nama_barang_manual' => $detail['nama_barang_manual'],
                    'jumlah_barang' => $detail['jumlah_manual'] ?? 0,
                    'id_input_area_locations' => $detail['id_input_area_locations'],
                    'awal' => $detail['awal'] ?? null,  // Nullable
                    'akhir' => $detail['akhir'] ?? null,  // Nullable
                    'temuan_ketidaksesuaian' => $detail['temuan_ketidaksesuaian'] ?? null,
                    'tindakan_koreksi' => $detail['tindakan_koreksi'] ?? null,
                    'nama_karyawan' => $detail['nama_karyawan'] ?? null,
                ];
            } else {
                // DATABASE MODE
                $barang = Barang::find($detail['id_barang'] ?? null);
                if (!$barang) {
                    continue;
                }
                
                $validDetails[] = [
                    'id_barang' => $barang->id,
                    'nama_barang_manual' => null,
                    'jumlah_barang' => $barang->jumlah_barang ?? 0,
                    'id_input_area_locations' => $detail['id_input_area_locations'],
                    'awal' => $detail['awal'] ?? null,  // Nullable
                    'akhir' => $detail['akhir'] ?? null,  // Nullable
                    'temuan_ketidaksesuaian' => $detail['temuan_ketidaksesuaian'] ?? null,
                    'tindakan_koreksi' => $detail['tindakan_koreksi'] ?? null,
                    'nama_karyawan' => $detail['nama_karyawan'] ?? null,
                ];
            }
        }
        
        // 3. CEK APAKAH ADA DETAIL YANG VALID
        if (empty($validDetails)) {
            return back()
                ->withErrors(['error' => 'Minimal harus ada 1 detail barang yang lengkap.'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // 4. CREATE MAIN RECORD
            $pemeriksaan = PemeriksaanBarangMudahPecah::create([
                'uuid' => Str::uuid(),
                'id_user' => Auth::id(),
                'id_shift' => $request->id_shift,
                'tanggal' => $request->tanggal,
                'id_area' => $request->id_area,
            ]);

            // 5. CREATE DETAIL RECORDS
            foreach ($validDetails as $detail) {
                PemeriksaanBarangMudahPecahDetail::create([
                    'id_pemeriksaan' => $pemeriksaan->id,
                    'id_barang' => $detail['id_barang'],
                    'nama_barang_manual' => $detail['nama_barang_manual'],
                    'id_input_area_locations' => $detail['id_input_area_locations'],
                    'jumlah_barang' => $detail['jumlah_barang'],
                    'awal' => $detail['awal'],
                    'akhir' => $detail['akhir'],
                    'temuan_ketidaksesuaian' => $detail['temuan_ketidaksesuaian'],
                    'tindakan_koreksi' => $detail['tindakan_koreksi'],
                    'nama_karyawan' => $detail['nama_karyawan'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pemeriksaan-barang-mudah-pecah.index')
                ->with('success', 'Pemeriksaan barang berhasil ditambahkan!');
                    
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PemeriksaanBarangMudahPecah $pemeriksaanBarangMudahPecah)
    {
        $this->checkPlantAccess($pemeriksaanBarangMudahPecah);
        
        $pemeriksaanBarangMudahPecah->load(['user', 'shift', 'area', 'details.barang', 'details.areaLocation']);
        
        return view('qc-sistem.pemeriksaan-barang-mudah-pecah.show', compact('pemeriksaanBarangMudahPecah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemeriksaanBarangMudahPecah $pemeriksaanBarangMudahPecah)
    {
        $this->checkPlantAccess($pemeriksaanBarangMudahPecah);
        
        $user = Auth::user();
        
        $shiftQuery = Shift::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $shiftQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $shifts = $shiftQuery->get();
        
        $areaQuery = InputArea::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $areaQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $areas = $areaQuery->get();
        
        // Get barangs berdasarkan plant user
        $barangQuery = Barang::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $barangQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $barangs = $barangQuery->get();
        
        $pemeriksaanBarangMudahPecah->load('details');
        
        return view('qc-sistem.pemeriksaan-barang-mudah-pecah.edit', compact('pemeriksaanBarangMudahPecah', 'shifts', 'areas', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemeriksaanBarangMudahPecah $pemeriksaanBarangMudahPecah)
    {
        // Validasi basic fields
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'id_area' => 'required|exists:input_areas,id',
            'details' => 'required|array|min:1',
        ]);

        // Validasi details dengan conditional logic
        foreach ($request->details as $index => $detail) {
            $isManual = !empty($detail['nama_barang_manual'] ?? null);
            
            $rules = [
                'details.' . $index . '.id_input_area_locations' => 'required|exists:input_area_locations,id',
                'details.' . $index . '.awal' => 'required|in:baik,tidak-baik',
                'details.' . $index . '.akhir' => 'required|in:baik,tidak-baik',
                'details.' . $index . '.temuan_ketidaksesuaian' => 'nullable|string',
                'details.' . $index . '.tindakan_koreksi' => 'nullable|string',
                'details.' . $index . '.nama_karyawan' => 'nullable|string|max:255',
            ];
            
            if ($isManual) {
                // Jika manual input
                $rules['details.' . $index . '.nama_barang_manual'] = 'required|string|max:255';
                $rules['details.' . $index . '.jumlah_manual'] = 'required|integer|min:1';
            } else {
                // Jika dari database
                $rules['details.' . $index . '.id_barang'] = 'required|exists:barangs,id';
            }
            
            $request->validate($rules);
        }

        try {
            DB::beginTransaction();

            // Update main record
            $pemeriksaanBarangMudahPecah->update([
                'id_shift' => $request->id_shift,
                'tanggal' => $request->tanggal,
                'id_area' => $request->id_area,
            ]);

            // Delete old details
            $pemeriksaanBarangMudahPecah->details()->delete();

            // Create new detail records
            foreach ($request->details as $detail) {
                // Tentukan apakah menggunakan input manual atau dari database
                $isManual = !empty($detail['nama_barang_manual']);
                
                if ($isManual) {
                    // Input manual
                    $jumlahBarang = $detail['jumlah_manual'] ?? 0;
                    $namaBarang = $detail['nama_barang_manual'];
                    $idBarang = null;
                } else {
                    // Dari database
                    $barang = Barang::find($detail['id_barang']);
                    $jumlahBarang = $barang->jumlah_barang ?? 0;
                    $namaBarang = $barang->nama_barang;
                    $idBarang = $detail['id_barang'];
                }
                
                PemeriksaanBarangMudahPecahDetail::create([
                    'id_pemeriksaan' => $pemeriksaanBarangMudahPecah->id,
                    'id_barang' => $idBarang,
                    'nama_barang_manual' => $isManual ? $namaBarang : null,
                    'id_input_area_locations' => $detail['id_input_area_locations'],
                    'jumlah_barang' => $jumlahBarang,
                    'awal' => $detail['awal'],
                    'akhir' => $detail['akhir'],
                    'temuan_ketidaksesuaian' => $detail['temuan_ketidaksesuaian'] ?? null,
                    'tindakan_koreksi' => $detail['tindakan_koreksi'] ?? null,
                    'nama_karyawan' => $detail['nama_karyawan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('pemeriksaan-barang-mudah-pecah.index')
                           ->with('success', 'Pemeriksaan barang berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PemeriksaanBarangMudahPecah $pemeriksaanBarangMudahPecah)
    {
        $pemeriksaanBarangMudahPecah->delete();
        
        return redirect()->route('pemeriksaan-barang-mudah-pecah.index')
                       ->with('success', 'Pemeriksaan barang berhasil dihapus!');
    }

    /**
     * AJAX: Get area locations by area
     */
    /**
     * Check if user has access to pemeriksaan based on plant
     */
    private function checkPlantAccess(PemeriksaanBarangMudahPecah $pemeriksaan)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($pemeriksaan->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    public function getAreaLocations($idArea)
    {
        $locations = InputAreaLocation::where('id_input_area', $idArea)->get(['id', 'lokasi_area']);
        return response()->json($locations);
    }

    /**
     * AJAX: Get barang details
     */
    public function getBarangDetails($idBarang)
    {
        $barang = Barang::find($idBarang);
        
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'jumlah_barang' => $barang->jumlah_barang ?? 0,
        ]);
    }
}