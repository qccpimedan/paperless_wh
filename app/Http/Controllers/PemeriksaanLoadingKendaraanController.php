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
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PemeriksaanLoadingKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanLoadingKendaraan::with([
            'user.role',
            'user.plant',
            'ekspedisi',
            'kendaraan',
            'tujuanPengiriman',
            'stdPrecooling'
        ]);

        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                // Konversi format d/m/Y ke Y-m-d
                $tanggalSearch = null;
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $search)) {
                    $parts = explode('/', $search);
                    $tanggalSearch = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) {
                    $tanggalSearch = $search;
                }

                if ($tanggalSearch) {
                    $q->whereDate('tanggal', $tanggalSearch);
                }

                $q->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhere('verification_notes', 'like', '%' . $search . '%')
                    ->orWhere('no_segel', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('ekspedisi', function ($qe) use ($search) {
                        $qe->where('nama_ekspedisi', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kendaraan', function ($qk) use ($search) {
                        $qk->where('jenis_kendaraan', 'like', '%' . $search . '%')
                            ->orWhere('no_kendaraan', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('tujuanPengiriman', function ($qt) use ($search) {
                        $qt->where('nama_tujuan', 'like', '%' . $search . '%');
                    });
            });
        }


        $pemeriksaans = $query->latest()->paginate(25);
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
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $tujuanPengirimens = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $stdPrecoolings = StdPrecooling::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
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
            'id_kendaraan' => [
                'required',
                $request->input('id_kendaraan') !== 'other' ? 'exists:jenis_kendaraans,id' : '',
            ],
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_tujuan_pengiriman' => 'required|exists:tujuan_pengirimen,id',
            'id_std_precooling' => 'required|exists:std_precoolings,id',
            'id_shift' => 'nullable|exists:shifts,id',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i',
            'suhu_precooling' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
        ]);

        $idKendaraan = $request->id_kendaraan;
        if ($idKendaraan === 'other') {
            $kendaraan = JenisKendaraan::create([
                'jenis_kendaraan' => $request->jenis_kendaraan_manual,
                'no_kendaraan' => $request->no_kendaraan_manual,
                'id_user' => Auth::id(),
            ]);
            $idKendaraan = $kendaraan->id;
        }

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
            'id_kendaraan' => $idKendaraan,
            'id_tujuan_pengiriman' => $request->id_tujuan_pengiriman,
            'id_std_precooling' => $request->id_std_precooling,
            'id_shift' => $request->id_shift,
            'kondisi_kebersihan_mobil' => json_encode($kondisiKebersihanMobil),
            'kondisi_mobil' => json_encode($kondisiMobil),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'suhu_precooling' => $request->suhu_precooling,
            'keterangan' => $request->keterangan,
            'segel_gembok' => $request->input('segel_gembok') === 'segel' ? true : ($request->input('segel_gembok') === 'gembok' ? false : null),
            'no_segel' => $request->input('segel_gembok') === 'segel' ? $request->no_segel : null,
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
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $tujuanPengirimens = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $stdPrecoolings = StdPrecooling::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();

            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
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
            'id_kendaraan' => [
                'required',
                $request->input('id_kendaraan') !== 'other' ? 'exists:jenis_kendaraans,id' : '',
            ],
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_tujuan_pengiriman' => 'required|exists:tujuan_pengirimen,id',
            'id_std_precooling' => 'required|exists:std_precoolings,id',
            'id_shift' => 'nullable|exists:shifts,id',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'suhu_precooling' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
        ]);

        $idKendaraan = $request->id_kendaraan;
        if ($idKendaraan === 'other') {
            $kendaraan = JenisKendaraan::create([
                'jenis_kendaraan' => $request->jenis_kendaraan_manual,
                'no_kendaraan' => $request->no_kendaraan_manual,
                'id_user' => Auth::id(),
            ]);
            $idKendaraan = $kendaraan->id;
        }

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
            'id_kendaraan' => $idKendaraan,
            'id_tujuan_pengiriman' => $request->id_tujuan_pengiriman,
            'id_std_precooling' => $request->id_std_precooling,
            'id_shift' => $request->id_shift,
            'kondisi_kebersihan_mobil' => json_encode($kondisiKebersihanMobil),
            'kondisi_mobil' => json_encode($kondisiMobil),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'suhu_precooling' => $request->suhu_precooling,
            'keterangan' => $request->keterangan,
            'segel_gembok' => $request->input('segel_gembok') === 'segel' ? true : ($request->input('segel_gembok') === 'gembok' ? false : null),
            'no_segel' => $request->input('segel_gembok') === 'segel' ? $request->no_segel : null,
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
        if ($pemeriksaanLoadingKendaraan->user->id_plant !== $user->getEffectivePlantId()) {
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
            'verified_by_qc' => $user->id,
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
            'verified_by_produksi' => $user->id,
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
            'verified_by_produksi' => $user->id,
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
            'verified_by_spv' => $user->id,
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
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }

    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        $query = PemeriksaanLoadingKendaraan::with([
            'user.role',
            'user.plant',
            'ekspedisi',
            'kendaraan',
            'tujuanPengiriman',
            'stdPrecooling',
            'shift',
            'verifiedBy',
            'qcVerifier',
            'produksiVerifier',
            'spvVerifier',
        ]);

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

        if ($id_shift) {
            $shift = Shift::find($id_shift);
            $shiftName = $shift ? trim(strtolower((string) $shift->shift)) : null;

            $isShift1 = $shift && $shift->is_date_range;

            if ($isShift1) {
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
        if ($pemeriksaans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang sesuai dengan filter yang dipilih.');
        }

        $shift = $id_shift ? Shift::find($id_shift) : null;

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-loading-kendaraan.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-loading-kendaraan-' . $filenameDate . '.pdf';
        $filename = 'laporan-pemeriksaan-loading-kendaraan-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }

    public function batchVerify(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role ? strtolower($user->role->role) : null;
        $id_shift = $request->input('id_shift');
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');
        $selected_uuids = $request->input('selected_uuids');

        $query = PemeriksaanLoadingKendaraan::query();

        if ($userRole !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if (!empty($selected_uuids)) {
            $query->whereIn('uuid', $selected_uuids);
        } else {
            if (!$id_shift) {
                return back()->with('error', 'Silakan pilih shift dan filter tanggal untuk verifikasi massal.');
            }

            $query->where('id_shift', $id_shift);
            $shift = \App\Models\Shift::find($id_shift);
            if ($shift && $shift->is_date_range) {
                if ($tanggal_dari && $tanggal_sampai) {
                    $query->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai]);
                } else {
                    return back()->with('error', 'Rentang tanggal harus diisi untuk Shift 1.');
                }
            } else {
                if ($tanggal) {
                    $query->whereDate('tanggal', $tanggal);
                } else {
                    return back()->with('error', 'Tanggal harus diisi.');
                }
            }
        }

        $fromStatus = null;
        $updateData = [];

        if ($userRole === 'qc inspector') {
            $fromStatus = ['pending', null];
            $updateData = [
                'status_verifikasi' => 'sent_to_produksi',
                'verified_by' => $user->id,
                'verified_by_qc' => $user->id,
                'verified_at' => now()
            ];
        } elseif ($userRole === 'produksi' || $userRole === 'warehouse' || $userRole === 'produksi/warehouse') {
            $fromStatus = ['sent_to_produksi'];
            $updateData = [
                'status_verifikasi' => 'approved_produksi',
                'verified_by' => $user->id,
                'verified_by_produksi' => $user->id,
                'verified_at' => now()
            ];
        } elseif ($userRole === 'spv qc' || $userRole === 'superadmin') {
            $fromStatus = ['approved_produksi'];
            $updateData = [
                'status_verifikasi' => 'approved_spv',
                'verified_by' => $user->id,
                'verified_by_spv' => $user->id,
                'verified_at' => now()
            ];
        }

        if (!$fromStatus) {
            return back()->with('error', 'Role Anda tidak diizinkan melakukan verifikasi.');
        }

        $query->whereIn('status_verifikasi', (array) $fromStatus);
        $count = $query->count();

        if ($count > 0) {
            $query->update($updateData);
            return back()->with('success', "$count data pemeriksaan berhasil diverifikasi secara massal.");
        }

        return back()->with('info', 'Tidak ada data yang memenuhi syarat untuk diverifikasi pada filter tersebut.');
    }
}