<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganKemasan;
use App\Models\Bahan;
use App\Models\Shift;
use App\Models\Produsen;
use App\Models\User;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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
            'id_bahan.*' => 'nullable|exists:bahans,id',
            'produsen.*' => 'nullable|string|max:255',
            'distributor.*' => 'nullable|string|max:255',
            'kode_produksi.*' => 'nullable|string|max:255',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'spesifikasi.*' => 'nullable|string',
            'penampakan.*' => 'nullable|in:0,1',
            'sealing.*' => 'nullable|in:0,1',
            'cetakan.*' => 'nullable|in:0,1',
            'ketebalan_micron.*' => 'nullable|numeric',
            'dimensi.*' => 'nullable|string|max:255',
            'status.*' => 'nullable|in:Release,Hold',
            'logo_halal.*' => 'nullable|in:0,1',
            'dokumen_halal.*' => 'nullable|in:0,1',
            'coa.*' => 'nullable|in:0,1',
            'keterangan.*' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
        ]);
    
        // Process kondisi mobil dengan logic yang benar
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
    
        // Get array data from dynamic form
        $id_bahans = $request->input('id_bahan', []);
        $produsens = $request->input('produsen', []);
        $distributors = $request->input('distributor', []);
        $kode_produksis = $request->input('kode_produksi', []);
        $jumlah_datangs = $request->input('jumlah_datang', []);
        $jumlah_samplings = $request->input('jumlah_sampling', []);
        $spesifikasis = $request->input('spesifikasi', []);
        $penampakans = $request->input('penampakan', []);
        $sealings = $request->input('sealing', []);
        $cetakans = $request->input('cetakan', []);
        $ketebalan_microns = $request->input('ketebalan_micron', []);
        $dimensis = $request->input('dimensi', []);
        $statuses = $request->input('status', []);
        $logo_halals = $request->input('logo_halal', []);
        $dokumen_halals = $request->input('dokumen_halal', []);
        $coas = $request->input('coa', []);
        $keterangans = $request->input('keterangan', []);
    
        // Ensure all arrays are properly formatted as JSON
        $data = [
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'jenis_pemeriksaan' => $request->input('jenis_pemeriksaan'),
            'no_po' => $request->input('no_po'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => json_encode($kondisiMobil),
            'id_user' => Auth::id(),
            'id_shift' => $request->input('id_shift'),
            'id_bahan_array' => json_encode(is_array($id_bahans) ? $id_bahans : []),
            'produsen_array' => json_encode(is_array($produsens) ? $produsens : []),
            'distributor_array' => json_encode(is_array($distributors) ? $distributors : []),
            'kode_produksi_array' => json_encode(is_array($kode_produksis) ? $kode_produksis : []),
            'jumlah_datang_array' => json_encode(is_array($jumlah_datangs) ? $jumlah_datangs : []),
            'jumlah_sampling_array' => json_encode(is_array($jumlah_samplings) ? $jumlah_samplings : []),
            'spesifikasi_array' => json_encode(is_array($spesifikasis) ? $spesifikasis : []),
            'penampakan_array' => json_encode(is_array($penampakans) ? $penampakans : []),
            'sealing_array' => json_encode(is_array($sealings) ? $sealings : []),
            'cetakan_array' => json_encode(is_array($cetakans) ? $cetakans : []),
            'ketebalan_micron_array' => json_encode(is_array($ketebalan_microns) ? $ketebalan_microns : []),
            'dimensi_array' => json_encode(is_array($dimensis) ? $dimensis : []),
            'status_array' => json_encode(is_array($statuses) ? $statuses : []),
            'logo_halal_array' => json_encode(is_array($logo_halals) ? $logo_halals : []),
            'dokumen_halal_array' => json_encode(is_array($dokumen_halals) ? $dokumen_halals : []),
            'coa_array' => json_encode(is_array($coas) ? $coas : []),
            'keterangan_array' => json_encode(is_array($keterangans) ? $keterangans : []),
        ];
    
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
            'id_bahan.*' => 'nullable|exists:bahans,id',
            'produsen.*' => 'nullable|string|max:255',
            'distributor.*' => 'nullable|string|max:255',
            'kode_produksi.*' => 'nullable|string|max:255',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling.*' => 'nullable|string|max:255',
            'spesifikasi.*' => 'nullable|string',
            'penampakan.*' => 'nullable|in:0,1',
            'sealing.*' => 'nullable|in:0,1',
            'cetakan.*' => 'nullable|in:0,1',
            'ketebalan_micron.*' => 'nullable|numeric',
            'dimensi.*' => 'nullable|string|max:255',
            'status.*' => 'nullable|in:Release,Hold',
            'logo_halal.*' => 'nullable|in:0,1',
            'dokumen_halal.*' => 'nullable|in:0,1',
            'coa.*' => 'nullable|in:0,1',
            'keterangan.*' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
        ]);
    
        // Process kondisi mobil dengan logic yang benar
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
    
        // Get array data from dynamic form
        $id_bahans = $request->input('id_bahan', []);
        $produsens = $request->input('produsen', []);
        $distributors = $request->input('distributor', []);
        $kode_produksis = $request->input('kode_produksi', []);
        $jumlah_datangs = $request->input('jumlah_datang', []);
        $jumlah_samplings = $request->input('jumlah_sampling', []);
        $spesifikasis = $request->input('spesifikasi', []);
        $penampakans = $request->input('penampakan', []);
        $sealings = $request->input('sealing', []);
        $cetakans = $request->input('cetakan', []);
        $ketebalan_microns = $request->input('ketebalan_micron', []);
        $dimensis = $request->input('dimensi', []);
        $statuses = $request->input('status', []);
        $logo_halals = $request->input('logo_halal', []);
        $dokumen_halals = $request->input('dokumen_halal', []);
        $coas = $request->input('coa', []);
        $keterangans = $request->input('keterangan', []);
    
        // Ensure all arrays are properly formatted as JSON
        $data = [
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'jenis_pemeriksaan' => $request->input('jenis_pemeriksaan'),
            'no_po' => $request->input('no_po'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => json_encode($kondisiMobil),
            'id_shift' => $request->input('id_shift'),
            'id_bahan_array' => json_encode(is_array($id_bahans) ? $id_bahans : []),
            'produsen_array' => json_encode(is_array($produsens) ? $produsens : []),
            'distributor_array' => json_encode(is_array($distributors) ? $distributors : []),
            'kode_produksi_array' => json_encode(is_array($kode_produksis) ? $kode_produksis : []),
            'jumlah_datang_array' => json_encode(is_array($jumlah_datangs) ? $jumlah_datangs : []),
            'jumlah_sampling_array' => json_encode(is_array($jumlah_samplings) ? $jumlah_samplings : []),
            'spesifikasi_array' => json_encode(is_array($spesifikasis) ? $spesifikasis : []),
            'penampakan_array' => json_encode(is_array($penampakans) ? $penampakans : []),
            'sealing_array' => json_encode(is_array($sealings) ? $sealings : []),
            'cetakan_array' => json_encode(is_array($cetakans) ? $cetakans : []),
            'ketebalan_micron_array' => json_encode(is_array($ketebalan_microns) ? $ketebalan_microns : []),
            'dimensi_array' => json_encode(is_array($dimensis) ? $dimensis : []),
            'status_array' => json_encode(is_array($statuses) ? $statuses : []),
            'logo_halal_array' => json_encode(is_array($logo_halals) ? $logo_halals : []),
            'dokumen_halal_array' => json_encode(is_array($dokumen_halals) ? $dokumen_halals : []),
            'coa_array' => json_encode(is_array($coas) ? $coas : []),
            'keterangan_array' => json_encode(is_array($keterangans) ? $keterangans : []),
        ];
    
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

    public function sendToProduksi(PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        $pemeriksaanKedatanganKemasan->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now()
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        $pemeriksaanKedatanganKemasan->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanKedatanganKemasan->update(['status_verifikasi' => 'rejected_produksi', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        $pemeriksaanKedatanganKemasan->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanKedatanganKemasan $pemeriksaanKedatanganKemasan)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKedatanganKemasan);
        if ($pemeriksaanKedatanganKemasan->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanKedatanganKemasan->update(['status_verifikasi' => 'rejected_spv', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }

    /**
     * Export data to PDF based on filters
     */
    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        // Build query
        $query = PemeriksaanKedatanganKemasan::with([
            'user.role', 
            'user.plant', 
            'bahan', 
            'shift', 
            'verifiedBy.role'
        ])->with([
            'qcVerifier' => function($q) {
                $q->select('id', 'name');
            },
            'produksiVerifier' => function($q) {
                $q->select('id', 'name');
            },
            'spvVerifier' => function($q) {
                $q->select('id', 'name');
            }
        ]);

        // Filter by plant access
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        // Filter by shift
        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

        // Filter tanggal berdasarkan shift
        if ($id_shift) {
            $shift = Shift::find($id_shift);
            $shiftName = $shift ? trim(strtolower((string) $shift->shift)) : null;

            if ($shiftName === '1' || $shiftName === 'shift 1') {
                if ($tanggalDari && $tanggalSampai) {
                    $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
                } elseif ($tanggalDari) {
                    $query->whereDate('tanggal', '>=', $tanggalDari);
                } elseif ($tanggalSampai) {
                    $query->whereDate('tanggal', '<=', $tanggalSampai);
                }
            } else {
                if ($tanggal) {
                    $query->whereDate('tanggal', $tanggal);
                }
            }
        } else {
            if ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            }
        }

        $pemeriksaans = $query->latest()->get();

        // Get shift name for display
        $shift = $id_shift ? Shift::find($id_shift) : null;
        
        // Get signature users from verified_by fields
        $qcUser = null;
        $produksiUser = null;
        $spvQcUser = null;
        
        // Collect all unique verified_by IDs
        $allQcIds = $pemeriksaans->pluck('verified_by_qc')->filter()->unique();
        $allProduksiIds = $pemeriksaans->pluck('verified_by_produksi')->filter()->unique();
        $allSpvIds = $pemeriksaans->pluck('verified_by_spv')->filter()->unique();
        
        \Log::info('Verified IDs:', [
            'qc_ids' => $allQcIds->toArray(),
            'produksi_ids' => $allProduksiIds->toArray(),
            'spv_ids' => $allSpvIds->toArray()
        ]);
        
        // Get QC user with role
        if($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if($qcUserData) {
                $qcUser = $qcUserData->name;
                \Log::info('Found QC user: ' . $qcUser . ' (ID: ' . $qcUserData->id . ')');
            }
        }
        
        // Get Produksi user with role
        if($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if($produksiUserData) {
                $produksiUser = $produksiUserData->name;
                \Log::info('Found Produksi user: ' . $produksiUser . ' (ID: ' . $produksiUserData->id . ')');
            }
        }
        
        // Get SPV user with role
        if($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if($spvUserData) {
                $spvQcUser = $spvUserData->name;
                \Log::info('Found SPV user: ' . $spvQcUser . ' (ID: ' . $spvUserData->id . ')');
            }
        }

        // Generate PDF
        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-kedatangan-kemasan.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-kemasan-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }
}
