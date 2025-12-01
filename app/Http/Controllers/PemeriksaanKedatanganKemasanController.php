<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganKemasan;
use App\Models\Bahan;
use App\Models\Shift;
use App\Models\Produsen;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanKedatanganKemasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanKedatanganKemasan::with(['user.role', 'user.plant', 'bahan', 'shift'])
                ->latest() // Data terbaru muncul paling atas
                ->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $pemeriksaans = PemeriksaanKedatanganKemasan::with(['user.role', 'user.plant', 'bahan', 'shift'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest() // Data terbaru muncul paling atas
                ->get();
        }
        
        return view('qc-sistem.pemeriksaan-kedatangan-kemasan.index', compact('pemeriksaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Debug: Log user info
        \Log::info('User creating pemeriksaan:', [
            'user_id' => $user->id,
            'id_plant' => $user->id_plant,
            'role' => $user->role ? $user->role->role : 'no role'
        ]);
        
        // Get bahans, shifts, produsens, and distributors based on plant access
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $bahans = Bahan::with('user.plant')->get();
            $shifts = Shift::with('user.plant')->get();
            $produsens = Produsen::with('user.plant')->get();
            $distributors = Distributor::with('user.plant')->get();
        } else {
            if ($user->id_plant) {
                // Filter berdasarkan plant
                $bahans = Bahan::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
                
                $shifts = Shift::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
                
                $produsens = Produsen::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
                
                $distributors = Distributor::whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })->with('user.plant')->get();
            } else {
                // Fallback: User has no plant, get all
                $bahans = Bahan::all();
                $shifts = Shift::all();
                $produsens = Produsen::all();
                $distributors = Distributor::all();
            }
        }
        
        // Fallback if no data found
        if ($bahans->isEmpty()) {
            $bahans = Bahan::all();
        }
        if ($shifts->isEmpty()) {
            $shifts = Shift::all();
        }
        if ($produsens->isEmpty()) {
            $produsens = Produsen::all();
        }
        if ($distributors->isEmpty()) {
            $distributors = Distributor::all();
        }
        
        \Log::info('Data for create view:', [
            'bahans_count' => $bahans->count(),
            'shifts_count' => $shifts->count(),
            'produsens_count' => $produsens->count(),
            'distributors_count' => $distributors->count()
        ]);
        
        return view('qc-sistem.pemeriksaan-kedatangan-kemasan.create', compact('bahans', 'shifts', 'produsens', 'distributors'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'produsen' => 'nullable|string|max:255',
            'distributor' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|string|max:255',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'ketebalan_micron' => 'nullable|numeric',
            'status' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_bahan' => 'nullable|exists:bahans,id',
        ]);
    
        // Process kondisi mobil dan fisik dengan logic yang benar
        $kondisiMobil = [
            'bersih' => $request->input('kondisi_mobil.bersih') === '1',
            'bebas_hama' => $request->input('kondisi_mobil.bebas_hama') === '1',
            'tidak_kondensasi' => $request->input('kondisi_mobil.tidak_kondensasi') === '1',
            'bebas_produk_halal' => $request->input('kondisi_mobil.bebas_produk_halal') === '1',
            'tidak_berbau' => $request->input('kondisi_mobil.tidak_berbau') === '1',
            'tidak_ada_sampah' => $request->input('kondisi_mobil.tidak_ada_sampah') === '1',
            'tidak_ada_mikroba' => $request->input('kondisi_mobil.tidak_ada_mikroba') === '1',
            'lampu_cover_utuh' => $request->input('kondisi_mobil.lampu_cover_utuh') === '1',
            'pallet_utuh' => $request->input('kondisi_mobil.pallet_utuh') === '1',
            'tertutup_rapat' => $request->input('kondisi_mobil.tertutup_rapat') === '1',
            'bebas_kontaminan' => $request->input('kondisi_mobil.bebas_kontaminan') === '1',
        ];
    
        $kondisiFisik = [
            'penampakan' => $request->input('kondisi_fisik.penampakan') === '1',
            'sealing' => $request->input('kondisi_fisik.sealing') === '1',
            'cetakan' => $request->input('kondisi_fisik.cetakan') === '1',
        ];
    
        $data = $request->all();
        $data['id_user'] = Auth::id();
        $data['segel_gembok'] = $request->has('segel_gembok');
        $data['logo_halal'] = $request->input('logo_halal') === '1';
        $data['dokumen_halal'] = $request->input('dokumen_halal') === '1';
        $data['coa'] = $request->input('coa') === '1';
        $data['kondisi_mobil'] = $kondisiMobil;
        $data['kondisi_fisik'] = $kondisiFisik;
    
        PemeriksaanKedatanganKemasan::create($data);
    
        return redirect()->route('pemeriksaan-kedatangan-kemasan.index')
            ->with('success', 'Data pemeriksaan kedatangan kemasan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $pemeriksaanKedatanganKemasan->load(['user', 'bahan', 'shift']);
        return view('qc-sistem.pemeriksaan-kedatangan-kemasan.show', compact('pemeriksaanKedatanganKemasan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $user = Auth::user();
        
        // Get bahans, shifts, produsens, and distributors based on plant access (SAMA SEPERTI CREATE)
    if ($user->role && strtolower($user->role->role) === 'superadmin') {
        $bahans = Bahan::with('user.plant')->get();
        $shifts = Shift::with('user.plant')->get();
        $produsens = Produsen::with('user.plant')->get();
        $distributors = Distributor::with('user.plant')->get();
    } else {
        if ($user->id_plant) {
            // Filter berdasarkan plant
            $bahans = Bahan::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with('user.plant')->get();
            
            $shifts = Shift::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with('user.plant')->get();
            
            $produsens = Produsen::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with('user.plant')->get();
            
            $distributors = Distributor::whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with('user.plant')->get();
        } else {
            // Fallback: User has no plant, get all
            $bahans = Bahan::all();
            $shifts = Shift::all();
            $produsens = Produsen::all();
            $distributors = Distributor::all();
        }
    }

    // Fallback if no data found
    if ($bahans->isEmpty()) {
        $bahans = Bahan::all();
    }
    if ($shifts->isEmpty()) {
        $shifts = Shift::all();
    }
    if ($produsens->isEmpty()) {
        $produsens = Produsen::all();
    }
    if ($distributors->isEmpty()) {
        $distributors = Distributor::all();
    }

    return view('qc-sistem.pemeriksaan-kedatangan-kemasan.edit', 
    compact('pemeriksaanKedatanganKemasan', 'bahans', 'shifts', 'produsens', 'distributors'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'produsen' => 'nullable|string|max:255',
            'distributor' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|string|max:255',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'ketebalan_micron' => 'nullable|numeric',
            'status' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_bahan' => 'nullable|exists:bahans,id',
        ]);
    
        // Process kondisi mobil dan fisik dengan logic yang benar
        $kondisiMobil = [
            'bersih' => $request->input('kondisi_mobil.bersih') === '1',
            'bebas_hama' => $request->input('kondisi_mobil.bebas_hama') === '1',
            'tidak_kondensasi' => $request->input('kondisi_mobil.tidak_kondensasi') === '1',
            'bebas_produk_halal' => $request->input('kondisi_mobil.bebas_produk_halal') === '1',
            'tidak_berbau' => $request->input('kondisi_mobil.tidak_berbau') === '1',
            'tidak_ada_sampah' => $request->input('kondisi_mobil.tidak_ada_sampah') === '1',
            'tidak_ada_mikroba' => $request->input('kondisi_mobil.tidak_ada_mikroba') === '1',
            'lampu_cover_utuh' => $request->input('kondisi_mobil.lampu_cover_utuh') === '1',
            'pallet_utuh' => $request->input('kondisi_mobil.pallet_utuh') === '1',
            'tertutup_rapat' => $request->input('kondisi_mobil.tertutup_rapat') === '1',
            'bebas_kontaminan' => $request->input('kondisi_mobil.bebas_kontaminan') === '1',
        ];
    
        $kondisiFisik = [
            'penampakan' => $request->input('kondisi_fisik.penampakan') === '1',
            'sealing' => $request->input('kondisi_fisik.sealing') === '1',
            'cetakan' => $request->input('kondisi_fisik.cetakan') === '1',
        ];
    
        $data = $request->all();
        $data['segel_gembok'] = $request->has('segel_gembok');
        $data['logo_halal'] = $request->input('logo_halal') === '1';
        $data['dokumen_halal'] = $request->input('dokumen_halal') === '1';
        $data['coa'] = $request->input('coa') === '1';
        $data['kondisi_mobil'] = $kondisiMobil;
        $data['kondisi_fisik'] = $kondisiFisik;
    
        $pemeriksaanKedatanganKemasan->update($data);
    
        return redirect()->route('pemeriksaan-kedatangan-kemasan.index')
            ->with('success', 'Data pemeriksaan kedatangan kemasan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        // Check access based on plant
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        
        $pemeriksaanKedatanganKemasan->delete();
        return redirect()->route('pemeriksaan-kedatangan-kemasan.index')
            ->with('success', 'Data pemeriksaan kedatangan kemasan berhasil dihapus!');
    }

    /**
     * Check if user has access to pemeriksaan based on plant
     */
    private function checkPlantAccess(PemeriksaanKedatanganKemasan $pemeriksaan)
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
}
