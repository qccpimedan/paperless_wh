<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanLoadingKendaraan;
use App\Models\Ekspedisi;
use App\Models\JenisKendaraan;
use App\Models\TujuanPengiriman;
use App\Models\StdPrecooling;
use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;

class PemeriksaanLoadingKendaraanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanLoadingKendaraan::with([
                'user.role',
                'user.plant',
                'ekspedisi',
                'kendaraan',
                'tujuanPengiriman',
                'stdPrecooling'
            ])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $pemeriksaans = PemeriksaanLoadingKendaraan::with([
                'user.role',
                'user.plant',
                'ekspedisi',
                'kendaraan',
                'tujuanPengiriman',
                'stdPrecooling'
            ])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }

        return view('qc-sistem.pemeriksaan-loading-kendaraan.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();

        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $ekspedisis = Ekspedisi::with(['user.plant'])->get();
            $kendaraans = JenisKendaraan::with(['user.plant'])->get();
            $tujuanPengirimens = TujuanPengiriman::with(['user.plant'])->get();
            $stdPrecoolings = StdPrecooling::with(['user.plant'])->get();
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $ekspedisis = Ekspedisi::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $tujuanPengirimens = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $stdPrecoolings = StdPrecooling::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        return view('qc-sistem.pemeriksaan-loading-kendaraan.create', compact(
            'ekspedisis',
            'kendaraans',
            'shifts',
            'tujuanPengirimens',
            'stdPrecoolings'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'id_ekspedisi' => 'required|exists:ekspedisis,id',
            'id_kendaraan' => 'required|exists:jenis_kendaraans,id',
            'id_tujuan_pengiriman' => 'required|exists:tujuan_pengirimen,id',
            'id_std_precooling' => 'required|exists:std_precoolings,id',
            'id_shift' => 'nullable|exists:shifts,id',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'suhu_precooling' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Prepare kondisi data
        $kondisiKebersihanMobil = [
            'berdebu' => $request->input('kondisi_kebersihan_mobil.berdebu'),
            'noda' => $request->input('kondisi_kebersihan_mobil.noda'),
            'mikroorganisme' => $request->input('kondisi_kebersihan_mobil.mikroorganisme'),
            'pallet_kotor' => $request->input('kondisi_kebersihan_mobil.pallet_kotor'),
            'aktivitas_binatang' => $request->input('kondisi_kebersihan_mobil.aktivitas_binatang'),
        ];

        $kondisiMobil = [
            'kaca_pecah' => $request->input('kondisi_mobil.kaca_pecah'),
            'dinding_rusak' => $request->input('kondisi_mobil.dinding_rusak'),
            'lampu_pecah' => $request->input('kondisi_mobil.lampu_pecah'),
            'karet_pintu_rusak' => $request->input('kondisi_mobil.karet_pintu_rusak'),
            'pintu_rusak' => $request->input('kondisi_mobil.pintu_rusak'),
            'seal_tidak_utuh' => $request->input('kondisi_mobil.seal_tidak_utuh'),
            'terdapat_celah' => $request->input('kondisi_mobil.terdapat_celah'),
        ];

        PemeriksaanLoadingKendaraan::create([
            'id_user' => Auth::id(),
            'tanggal' => $request->tanggal,
            'id_ekspedisi' => $request->id_ekspedisi,
            'id_kendaraan' => $request->id_kendaraan,
            'id_tujuan_pengiriman' => $request->id_tujuan_pengiriman,
            'id_std_precooling' => $request->id_std_precooling,
            'id_shift' => $request->id_shift,
            'kondisi_kebersihan_mobil' => json_encode($kondisiKebersihanMobil),
            'kondisi_mobil' => json_encode($kondisiMobil),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'suhu_precooling' => $request->suhu_precooling,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pemeriksaan-loading-kendaraan.index')->with('success', 'Pemeriksaan loading kendaraan berhasil ditambahkan!');
    }

    public function show(PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        $pemeriksaanLoadingKendaraan->load('user', 'ekspedisi', 'kendaraan', 'tujuanPengiriman', 'stdPrecooling', 'shift');
        return view('qc-sistem.pemeriksaan-loading-kendaraan.show', compact('pemeriksaanLoadingKendaraan'));
    }

    public function edit(PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        $user = Auth::user();

        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $ekspedisis = Ekspedisi::with(['user.plant'])->get();
            $kendaraans = JenisKendaraan::with(['user.plant'])->get();
            $tujuanPengirimens = TujuanPengiriman::with(['user.plant'])->get();
            $stdPrecoolings = StdPrecooling::with(['user.plant'])->get();
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $ekspedisis = Ekspedisi::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $tujuanPengirimens = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $stdPrecoolings = StdPrecooling::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }
        return view('qc-sistem.pemeriksaan-loading-kendaraan.edit', compact(
            'pemeriksaanLoadingKendaraan',
            'shifts',
            'ekspedisis',
            'kendaraans',
            'tujuanPengirimens',
            'stdPrecoolings'
        ));
    }

    public function update(Request $request, PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);

        $request->validate([
            'tanggal' => 'required|date',
            'id_ekspedisi' => 'required|exists:ekspedisis,id',
            'id_kendaraan' => 'required|exists:jenis_kendaraans,id',
            'id_tujuan_pengiriman' => 'required|exists:tujuan_pengirimen,id',
            'id_std_precooling' => 'required|exists:std_precoolings,id',
            'id_shift' => 'nullable|exists:shifts,id',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'suhu_precooling' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Prepare kondisi data
        $kondisiKebersihanMobil = [
            'berdebu' => $request->input('kondisi_kebersihan_mobil.berdebu'),
            'noda' => $request->input('kondisi_kebersihan_mobil.noda'),
            'mikroorganisme' => $request->input('kondisi_kebersihan_mobil.mikroorganisme'),
            'pallet_kotor' => $request->input('kondisi_kebersihan_mobil.pallet_kotor'),
            'aktivitas_binatang' => $request->input('kondisi_kebersihan_mobil.aktivitas_binatang'),
        ];

        $kondisiMobil = [
            'kaca_pecah' => $request->input('kondisi_mobil.kaca_pecah'),
            'dinding_rusak' => $request->input('kondisi_mobil.dinding_rusak'),
            'lampu_pecah' => $request->input('kondisi_mobil.lampu_pecah'),
            'karet_pintu_rusak' => $request->input('kondisi_mobil.karet_pintu_rusak'),
            'pintu_rusak' => $request->input('kondisi_mobil.pintu_rusak'),
            'seal_tidak_utuh' => $request->input('kondisi_mobil.seal_tidak_utuh'),
            'terdapat_celah' => $request->input('kondisi_mobil.terdapat_celah'),
        ];

        $pemeriksaanLoadingKendaraan->update([
            'tanggal' => $request->tanggal,
            'id_ekspedisi' => $request->id_ekspedisi,
            'id_kendaraan' => $request->id_kendaraan,
            'id_tujuan_pengiriman' => $request->id_tujuan_pengiriman,
            'id_std_precooling' => $request->id_std_precooling,
            'id_shift' => $request->id_shift,
            'kondisi_kebersihan_mobil' => json_encode($kondisiKebersihanMobil),
            'kondisi_mobil' => json_encode($kondisiMobil),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'suhu_precooling' => $request->suhu_precooling,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('pemeriksaan-loading-kendaraan.index')->with('success', 'Pemeriksaan loading kendaraan berhasil diupdate!');
    }

    public function destroy(PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        $pemeriksaanLoadingKendaraan->delete();
        return redirect()->route('pemeriksaan-loading-kendaraan.index')->with('success', 'Pemeriksaan loading kendaraan berhasil dihapus!');
    }

    private function checkPlantAccess(PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $user = Auth::user();

        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }

        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($pemeriksaanLoadingKendaraan->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    public function sendToProduksi(PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        
        if ($pemeriksaanLoadingKendaraan->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanLoadingKendaraan->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        
        if ($pemeriksaanLoadingKendaraan->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanLoadingKendaraan->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        
        if ($pemeriksaanLoadingKendaraan->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanLoadingKendaraan->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        
        if ($pemeriksaanLoadingKendaraan->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanLoadingKendaraan->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanLoadingKendaraan $pemeriksaanLoadingKendaraan)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanLoadingKendaraan);
        
        if ($pemeriksaanLoadingKendaraan->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanLoadingKendaraan->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}