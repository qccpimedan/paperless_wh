<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanSuhuRuangV2;
use App\Models\PemeriksaanSuhuRuangV2History;
use App\Models\Shift;
use App\Models\Produk;
use App\Traits\EditablePer2JamTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PemeriksaanSuhuRuangV2Controller extends Controller
{
    use EditablePer2JamTrait;
    
    protected function getEditRouteName()
    {
        return 'pemeriksaan-suhu-ruang-v2.edit';
    }
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));
        
        $query = PemeriksaanSuhuRuangV2::with(['user.role', 'user.plant', 'shift', 'produk']);

        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereDate('tanggal', $search)
                    ->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('produk', function ($qp) use ($search) {
                        $qp->where('nama_produk', 'like', '%' . $search . '%')
                            ->orWhere('kategori_code', 'like', '%' . $search . '%');
                    });
            });
        }

        $pemeriksaans = $query->latest()->paginate(25);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v2.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        
        $produks = Produk::query()
            ->orderBy('nama_produk')
            ->get();

        $produkKategoriOptions = $produks
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkByKategori = $produks
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            })
            ->all();

        $produkKategoriById = $produks
            ->pluck('kategori_code', 'id')
            ->all();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v2.create', compact(
            'shifts',
            'produks',
            'produkKategoriOptions',
            'produkByKategori',
            'produkKategoriById'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'id_produk' => 'required|exists:produks,id',
            'tanggal' => 'required|date',
        ]);

        $suhuData = $this->prepareSuhuData($request);

        PemeriksaanSuhuRuangV2::create([
            'id_user' => Auth::id(),
            'id_shift' => $request->id_shift,
            'id_produk' => $request->id_produk,
            'tanggal' => $request->tanggal,
            'suhu_cold_storage' => $suhuData['suhu_cold_storage'] ?? null,
            'suhu_anteroom_loading' => $suhuData['suhu_anteroom_loading'] ?? null,
            'suhu_pre_loading' => $suhuData['suhu_pre_loading'] ?? null,
            'suhu_prestaging' => $suhuData['suhu_prestaging'] ?? null,
            'suhu_anteroom_ekspansi_abf' => $suhuData['suhu_anteroom_ekspansi_abf'] ?? null,
            'suhu_chillroom_rm' => $suhuData['suhu_chillroom_rm'] ?? null,
            'suhu_chillroom_domestik' => $suhuData['suhu_chillroom_domestik'] ?? null,
            'keterangan' => $request->keterangan,
            'tindakan_koreksi' => $request->tindakan_koreksi,
        ]);

        return redirect()->route('pemeriksaan-suhu-ruang-v2.index')->with('success', 'Pemeriksaan suhu ruang V2 berhasil dibuat!');
    }

    public function show(PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        $pemeriksaanSuhuRuangV2->load(['user', 'shift', 'produk']);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v2.show', compact('pemeriksaanSuhuRuangV2'));
    }

    public function edit(PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        $pemeriksaanSuhuRuangV2->load(['shift', 'produk']);
        
        $user = Auth::user();
        
        // Check if can edit (2 hour validation)
        $lastUpdated = $pemeriksaanSuhuRuangV2->updated_at;
        $now = now();
        $hoursDiff = $lastUpdated->diffInHours($now);
        $canEdit = $hoursDiff >= 2;
        $nextEditTime = $lastUpdated->addHours(2);
        $editPer2Jam = request()->query('edit_per_2jam') == 1;
        
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        
        $produks = Produk::query()
            ->orderBy('nama_produk')
            ->get();

        $produkKategoriOptions = $produks
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkByKategori = $produks
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            })
            ->all();

        $produkKategoriById = $produks
            ->pluck('kategori_code', 'id')
            ->all();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v2.edit', compact(
            'pemeriksaanSuhuRuangV2',
            'shifts',
            'produks',
            'canEdit',
            'nextEditTime',
            'hoursDiff',
            'editPer2Jam',
            'produkKategoriOptions',
            'produkByKategori',
            'produkKategoriById'
        ));
    }

    public function update(Request $request, PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $suhuData = $this->prepareSuhuData($request);

        // Simpan history sebelum update
        PemeriksaanSuhuRuangV2History::create([
            'id_pemeriksaan_suhu_ruang_v2' => $pemeriksaanSuhuRuangV2->id,
            'id_user' => Auth::id(),
            'suhu_cold_storage_lama' => $pemeriksaanSuhuRuangV2->suhu_cold_storage,
            'suhu_cold_storage_baru' => $suhuData['suhu_cold_storage'] ?? null,
            'suhu_anteroom_loading_lama' => $pemeriksaanSuhuRuangV2->suhu_anteroom_loading,
            'suhu_anteroom_loading_baru' => $suhuData['suhu_anteroom_loading'] ?? null,
            'suhu_pre_loading_lama' => $pemeriksaanSuhuRuangV2->suhu_pre_loading,
            'suhu_pre_loading_baru' => $suhuData['suhu_pre_loading'] ?? null,
            'suhu_prestaging_lama' => $pemeriksaanSuhuRuangV2->suhu_prestaging,
            'suhu_prestaging_baru' => $suhuData['suhu_prestaging'] ?? null,
            'suhu_anteroom_ekspansi_abf_lama' => $pemeriksaanSuhuRuangV2->suhu_anteroom_ekspansi_abf,
            'suhu_anteroom_ekspansi_abf_baru' => $suhuData['suhu_anteroom_ekspansi_abf'] ?? null,
            'suhu_chillroom_rm_lama' => $pemeriksaanSuhuRuangV2->suhu_chillroom_rm,
            'suhu_chillroom_rm_baru' => $suhuData['suhu_chillroom_rm'] ?? null,
            'suhu_chillroom_domestik_lama' => $pemeriksaanSuhuRuangV2->suhu_chillroom_domestik,
            'suhu_chillroom_domestik_baru' => $suhuData['suhu_chillroom_domestik'] ?? null,
            'keterangan_lama' => $pemeriksaanSuhuRuangV2->keterangan,
            'keterangan_baru' => $request->keterangan,
            'tindakan_koreksi_lama' => $pemeriksaanSuhuRuangV2->tindakan_koreksi,
            'tindakan_koreksi_baru' => $request->tindakan_koreksi,
        ]);

        $pemeriksaanSuhuRuangV2->update([
            'tanggal' => $request->tanggal,
            'suhu_cold_storage' => $suhuData['suhu_cold_storage'] ?? null,
            'suhu_anteroom_loading' => $suhuData['suhu_anteroom_loading'] ?? null,
            'suhu_pre_loading' => $suhuData['suhu_pre_loading'] ?? null,
            'suhu_prestaging' => $suhuData['suhu_prestaging'] ?? null,
            'suhu_anteroom_ekspansi_abf' => $suhuData['suhu_anteroom_ekspansi_abf'] ?? null,
            'suhu_chillroom_rm' => $suhuData['suhu_chillroom_rm'] ?? null,
            'suhu_chillroom_domestik' => $suhuData['suhu_chillroom_domestik'] ?? null,
            'keterangan' => $request->keterangan,
            'tindakan_koreksi' => $request->tindakan_koreksi,
        ]);

        return redirect()->route('pemeriksaan-suhu-ruang-v2.index')->with('success', 'Pemeriksaan suhu ruang V2 berhasil diupdate!');
    }

    public function history(PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        $pemeriksaanSuhuRuangV2->load(['user', 'shift', 'produk']);
        $histories = $pemeriksaanSuhuRuangV2->histories()->with('user')->latest()->get();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v2.history', compact('pemeriksaanSuhuRuangV2', 'histories'));
    }

    public function destroy(PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        
        $pemeriksaanSuhuRuangV2->delete();
        return redirect()->route('pemeriksaan-suhu-ruang-v2.index')->with('success', 'Pemeriksaan suhu ruang V2 berhasil dihapus!');
    }

    private function prepareSuhuData(Request $request)
    {
        $suhuData = [];
        $allData = $request->all();

        // Cold Storage (1-4)
        for ($i = 1; $i <= 4; $i++) {
            $setting = $request->input("cold_storage_{$i}_setting");
            $display = $request->input("cold_storage_{$i}_display");
            $actual = $request->input("cold_storage_{$i}_actual");
            
            if ($setting || $display || $actual) {
                if (!isset($suhuData['suhu_cold_storage'])) {
                    $suhuData['suhu_cold_storage'] = [];
                }
                $suhuData['suhu_cold_storage'][$i] = [
                    'setting' => $setting,
                    'display' => $display,
                    'actual' => $actual,
                ];
            }
        }

        // Anteroom Loading (1-4)
        for ($i = 1; $i <= 4; $i++) {
            $setting = $request->input("anteroom_loading_{$i}_setting");
            $display = $request->input("anteroom_loading_{$i}_display");
            $actual = $request->input("anteroom_loading_{$i}_actual");
            
            if ($setting || $display || $actual) {
                if (!isset($suhuData['suhu_anteroom_loading'])) {
                    $suhuData['suhu_anteroom_loading'] = [];
                }
                $suhuData['suhu_anteroom_loading'][$i] = [
                    'setting' => $setting,
                    'display' => $display,
                    'actual' => $actual,
                ];
            }
        }

        // Pre Loading
        $suhuData['suhu_pre_loading'] = [
            'setting' => $request->input('pre_loading_setting'),
            'display' => $request->input('pre_loading_display'),
            'actual' => $request->input('pre_loading_actual'),
        ];

        // Prestaging
        $suhuData['suhu_prestaging'] = [
            'setting' => $request->input('prestaging_setting'),
            'display' => $request->input('prestaging_display'),
            'actual' => $request->input('prestaging_actual'),
        ];

        // Anteroom Ekspansi ABF
        $suhuData['suhu_anteroom_ekspansi_abf'] = [
            'setting' => $request->input('anteroom_ekspansi_abf_setting'),
            'display' => $request->input('anteroom_ekspansi_abf_display'),
            'actual' => $request->input('anteroom_ekspansi_abf_actual'),
        ];

        // Chillroom RM
        $suhuData['suhu_chillroom_rm'] = [
            'setting' => $request->input('chillroom_rm_setting'),
            'display' => $request->input('chillroom_rm_display'),
            'actual' => $request->input('chillroom_rm_actual'),
        ];

        // Chillroom Domestik
        $suhuData['suhu_chillroom_domestik'] = [
            'setting' => $request->input('chillroom_domestik_setting'),
            'display' => $request->input('chillroom_domestik_display'),
            'actual' => $request->input('chillroom_domestik_actual'),
        ];

        return $suhuData;
    }

    private function checkPlantAccess(PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        if ($pemeriksaanSuhuRuangV2->user->id_plant !== $user->id_plant) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
    public function checkEditableRecords()
    {
        $count = $this->countEditableRecordsWithin10Min(PemeriksaanSuhuRuangV2::class);
        return response()->json(['count' => $count]);
    }

    public function getEditableRecordsApi()
    {
        $records = $this->getEditableRecordsForApi(PemeriksaanSuhuRuangV2::class);
        return response()->json(['records' => $records]);
    }

    /**
     * Send pemeriksaan to Produksi for verification
     */
    public function sendToProduksi(PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        
        // Only pending status can be sent
        if ($pemeriksaanSuhuRuangV2->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanSuhuRuangV2->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    /**
     * Approve pemeriksaan from Produksi
     */
    public function approveProduksi(Request $request, PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        
        // Only sent_to_produksi status can be approved
        if ($pemeriksaanSuhuRuangV2->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanSuhuRuangV2->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    /**
     * Reject pemeriksaan from Produksi
     */
    public function rejectProduksi(Request $request, PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        
        // Only sent_to_produksi status can be rejected
        if ($pemeriksaanSuhuRuangV2->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuangV2->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    /**
     * Approve pemeriksaan from SPV QC (final verification)
     */
    public function approveSPV(Request $request, PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        
        // Only approved_produksi status can be approved by SPV
        if ($pemeriksaanSuhuRuangV2->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanSuhuRuangV2->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    /**
     * Reject pemeriksaan from SPV QC (final verification)
     */
    public function rejectSPV(Request $request, PemeriksaanSuhuRuangV2 $pemeriksaanSuhuRuangV2)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV2);
        
        // Only approved_produksi status can be rejected by SPV
        if ($pemeriksaanSuhuRuangV2->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuangV2->update([
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

        $query = PemeriksaanSuhuRuangV2::with([
            'user.role',
            'user.plant',
            'produk',
            'shift',
            'verifiedBy.role'
        ])->with([
            'qcVerifier' => function ($q) {
                $q->select('id', 'name');
            },
            'produksiVerifier' => function ($q) {
                $q->select('id', 'name');
            },
            'spvVerifier' => function ($q) {
                $q->select('id', 'name');
            },
            'histories' => function ($q) {
                $q->select(
                    'id',
                    'id_pemeriksaan_suhu_ruang_v2',
                    'created_at',
                    'suhu_cold_storage_lama',
                    'suhu_cold_storage_baru',
                    'suhu_anteroom_loading_lama',
                    'suhu_anteroom_loading_baru',
                    'suhu_pre_loading_lama',
                    'suhu_pre_loading_baru',
                    'suhu_prestaging_lama',
                    'suhu_prestaging_baru',
                    'suhu_anteroom_ekspansi_abf_lama',
                    'suhu_anteroom_ekspansi_abf_baru',
                    'suhu_chillroom_rm_lama',
                    'suhu_chillroom_rm_baru',
                    'suhu_chillroom_domestik_lama',
                    'suhu_chillroom_domestik_baru'
                )
                    ->orderByDesc('id');
            }
        ]);

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

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

        $shift = $id_shift ? Shift::find($id_shift) : null;

        $qcUser = null;
        $produksiUser = null;
        $spvQcUser = null;

        $allQcIds = $pemeriksaans->pluck('verified_by_qc')->filter()->unique();
        $allProduksiIds = $pemeriksaans->pluck('verified_by_produksi')->filter()->unique();
        $allSpvIds = $pemeriksaans->pluck('verified_by_spv')->filter()->unique();

        if ($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if ($qcUserData) {
                $qcUser = $qcUserData->name;
            }
        }

        if ($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if ($produksiUserData) {
                $produksiUser = $produksiUserData->name;
            }
        }

        if ($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if ($spvUserData) {
                $spvQcUser = $spvUserData->name;
            }
        }

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-suhu-ruang-v2.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser,
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-suhu-ruang-v2-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }
}