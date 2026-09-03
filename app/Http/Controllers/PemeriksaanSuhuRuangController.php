<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanSuhuRuang;
use App\Models\PemeriksaanSuhuRuangHistory;
use App\Models\Shift;
use App\Models\Produk;
use App\Traits\EditablePer2JamTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PemeriksaanSuhuRuangExport;

class PemeriksaanSuhuRuangController extends Controller
{
    use EditablePer2JamTrait;

    protected function getEditRouteName()
    {
        return 'pemeriksaan-suhu-ruang.edit';
    }
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));
        
        $query = PemeriksaanSuhuRuang::with(['user.role', 'user.plant', 'shift', 'produk']);

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

                $q->orWhere('pukul', 'like', '%' . $search . '%')
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
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Filter berdasarkan plant user yang login
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->getEffectivePlantId());
            }
        })->get();
        $produks = Produk::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->getEffectivePlantId());
            }
        })->get();

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkKategoriOptions = $produkList
            ->whereNotNull('kategori_code')
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkByKategori = $produkList
            ->whereNotNull('kategori_code')
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

        $produkKategoriById = $produkList
            ->whereNotNull('kategori_code')
            ->pluck('kategori_code', 'id')
            ->all();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.create', compact(
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
            'suhu_produk' => 'nullable|string',
            'pukul' => 'nullable|date_format:H:i',
        ]);

        $suhuData = $this->prepareSuhuData($request);

        PemeriksaanSuhuRuang::create([
            'id_user' => Auth::id(),
            'id_shift' => $request->id_shift,
            'id_produk' => $request->id_produk,
            'tanggal' => $request->tanggal,
            'suhu_produk' => $request->suhu_produk,
            'pukul' => $request->pukul,
            'suhu_data' => $suhuData,
            'keterangan' => $request->keterangan,
            'tindakan_koreksi' => $request->tindakan_koreksi,
        ]);

        return redirect()->route('pemeriksaan-suhu-ruang.index')->with('success', 'Pemeriksaan suhu ruang berhasil dibuat!');
    }

    public function show(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        $pemeriksaanSuhuRuang->load(['user', 'shift', 'produk']);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.show', compact('pemeriksaanSuhuRuang'));
    }

    public function edit(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        $pemeriksaanSuhuRuang->load(['shift', 'produk']);
        
        $user = Auth::user();
        
        // Check if can edit (2 hour validation)
        $lastUpdated = $pemeriksaanSuhuRuang->updated_at;
        $now = now();
        $hoursDiff = $lastUpdated->diffInHours($now);
        $canEdit = $hoursDiff >= 2;
        $nextEditTime = $lastUpdated->addHours(2);
        $editPer2Jam = request()->query('edit_per_2jam') == 1;
        
        // Filter berdasarkan plant user yang login
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->getEffectivePlantId());
            }
        })->get();
        $produks = Produk::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->getEffectivePlantId());
            }
        })->get();

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkKategoriOptions = $produkList
            ->whereNotNull('kategori_code')
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkByKategori = $produkList
            ->whereNotNull('kategori_code')
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

        $produkKategoriById = $produkList
            ->whereNotNull('kategori_code')
            ->pluck('kategori_code', 'id')
            ->all();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.edit', compact(
            'pemeriksaanSuhuRuang',
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

    public function update(Request $request, PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        
        $request->validate([
            'tanggal' => 'required|date',
            'suhu_produk' => 'nullable|string',
            'pukul' => 'nullable|date_format:H:i',
        ]);

        $suhuData = $this->prepareSuhuData($request);

        // Simpan history sebelum update
        PemeriksaanSuhuRuangHistory::create([
            'id_pemeriksaan_suhu_ruang' => $pemeriksaanSuhuRuang->id,
            'id_user' => Auth::id(),
            'pukul_lama' => $pemeriksaanSuhuRuang->pukul,
            'pukul_baru' => $request->pukul,
            'suhu_produk_lama' => $pemeriksaanSuhuRuang->suhu_produk,
            'suhu_produk_baru' => $request->suhu_produk,
            'suhu_data_lama' => $pemeriksaanSuhuRuang->suhu_data,
            'suhu_data_baru' => $suhuData,
            'keterangan_lama' => $pemeriksaanSuhuRuang->keterangan,
            'keterangan_baru' => $request->keterangan,
            'tindakan_koreksi_lama' => $pemeriksaanSuhuRuang->tindakan_koreksi,
            'tindakan_koreksi_baru' => $request->tindakan_koreksi,
        ]);

        $pemeriksaanSuhuRuang->update([
            'tanggal' => $request->tanggal,
            'suhu_produk' => $request->suhu_produk,
            'pukul' => $request->pukul,
            'suhu_data' => $suhuData,
            'keterangan' => $request->keterangan,
            'tindakan_koreksi' => $request->tindakan_koreksi,
        ]);

        return redirect()->route('pemeriksaan-suhu-ruang.index')->with('success', 'Pemeriksaan suhu ruang berhasil diupdate!');
    }

    public function destroy(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        
        $pemeriksaanSuhuRuang->delete();
        return redirect()->route('pemeriksaan-suhu-ruang.index')->with('success', 'Pemeriksaan suhu ruang berhasil dihapus!');
    }

    private function prepareSuhuData(Request $request)
    {
        $suhuData = [];

        $coldStorage = [];
        for ($i = 1; $i <= 4; $i++) {
            $setting = $request->input("cold_storage_{$i}_setting");
            $display = $request->input("cold_storage_{$i}_display");
            $actual = $request->input("cold_storage_{$i}_actual");
            
            if ($setting || $display || $actual) {
                $coldStorage[] = [
                    'unit' => $i,
                    'setting' => $setting,
                    'display' => $display,
                    'actual' => $actual,
                ];
            }
        }
        if (!empty($coldStorage)) {
            $suhuData['cold_storage'] = $coldStorage;
        }

        $anteroomLoading = [];
        for ($i = 1; $i <= 4; $i++) {
            $setting = $request->input("anteroom_loading_{$i}_setting");
            $display = $request->input("anteroom_loading_{$i}_display");
            $actual = $request->input("anteroom_loading_{$i}_actual");
            
            if ($setting || $display || $actual) {
                $anteroomLoading[] = [
                    'unit' => $i,
                    'setting' => $setting,
                    'display' => $display,
                    'actual' => $actual,
                ];
            }
        }
        if (!empty($anteroomLoading)) {
            $suhuData['anteroom_loading'] = $anteroomLoading;
        }

        $preLoadingSetting = $request->input('pre_loading_setting');
        $preLoadingDisplay = $request->input('pre_loading_display');
        $preLoadingActual = $request->input('pre_loading_actual');
        if ($preLoadingSetting || $preLoadingDisplay || $preLoadingActual) {
            $suhuData['pre_loading'] = [
                'setting' => $preLoadingSetting,
                'display' => $preLoadingDisplay,
                'actual' => $preLoadingActual,
            ];
        }

        $prestagingSetting = $request->input('prestaging_setting');
        $prestagingDisplay = $request->input('prestaging_display');
        if ($prestagingSetting || $prestagingDisplay) {
            $suhuData['prestaging'] = [
                'setting' => $prestagingSetting,
                'display' => $prestagingDisplay,
            ];
        }

        $anteroomEkspansiFurtherSetting = $request->input('anteroom_ekspansi_further_setting');
        $anteroomEkspansiFurtherDisplay = $request->input('anteroom_ekspansi_further_display');
        $anteroomEkspansiFurtherActual = $request->input('anteroom_ekspansi_further_actual');
        if ($anteroomEkspansiFurtherSetting || $anteroomEkspansiFurtherDisplay || $anteroomEkspansiFurtherActual) {
            $suhuData['anteroom_ekspansi_further'] = [
                'setting' => $anteroomEkspansiFurtherSetting,
                'display' => $anteroomEkspansiFurtherDisplay,
                'actual' => $anteroomEkspansiFurtherActual,
            ];
        }

        $anteroomEkspansiSausageSetting = $request->input('anteroom_ekspansi_sausage_setting');
        $anteroomEkspansiSausageDisplay = $request->input('anteroom_ekspansi_sausage_display');
        $anteroomEkspansiSausageActual = $request->input('anteroom_ekspansi_sausage_actual');
        if ($anteroomEkspansiSausageSetting || $anteroomEkspansiSausageDisplay || $anteroomEkspansiSausageActual) {
            $suhuData['anteroom_ekspansi_sausage'] = [
                'setting' => $anteroomEkspansiSausageSetting,
                'display' => $anteroomEkspansiSausageDisplay,
                'actual' => $anteroomEkspansiSausageActual,
            ];
        }

        return !empty($suhuData) ? $suhuData : null;
    }

    public function history(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        $pemeriksaanSuhuRuang->load(['user', 'shift', 'produk']);
        $histories = $pemeriksaanSuhuRuang->histories()->with('user')->latest()->get();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.history', compact('pemeriksaanSuhuRuang', 'histories'));
    }

    private function checkPlantAccess(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    public function checkEditableRecords()
    {
        $count = $this->countEditableRecordsWithin10Min(PemeriksaanSuhuRuang::class);
        return response()->json(['count' => $count]);
    }

    public function getEditableRecordsApi()
    {
        $records = $this->getEditableRecordsForApi(PemeriksaanSuhuRuang::class);
        return response()->json(['records' => $records]);
    }

    public function getEditableRecordsApiCombined()
    {
        // Get V1 records
        $recordsV1 = $this->getEditableRecordsForApi(PemeriksaanSuhuRuang::class);
        
        // Get V2 records dengan override route name
        $user = Auth::user();
        $twoHoursAgo = now()->subHours(2);
        
        $recordsV2Query = PemeriksaanSuhuRuangV2::where('updated_at', '<=', $twoHoursAgo);
        
        // Filter berdasarkan plant jika bukan superadmin
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $recordsV2Query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }
        
        $recordsV2Data = $recordsV2Query->get();
        
        $recordsV2 = $recordsV2Data->map(function($record) {
            return [
                'uuid' => $record->uuid,
                'tanggal' => $record->tanggal->format('Y-m-d'),
                'area' => 'N/A',
                'shift' => $record->shift->shift ?? 'N/A',
                'updated_at' => $record->updated_at->format('Y-m-d H:i'),
                'next_edit_time' => $record->updated_at->copy()->addHours(2)->format('Y-m-d H:i'),
                'minutes_until_edit' => $record->updated_at->copy()->addHours(2)->diffInMinutes(now()),
                'time_formatted' => $this->getTimeUntilNextEditFormatted($record),
                'edit_url' => route('pemeriksaan-suhu-ruang-v2.edit', [$record->uuid]) . '?edit_per_2jam=1',
            ];
        });
        
        // Combine both
        $allRecords = $recordsV1->concat($recordsV2)->sortByDesc('updated_at');
        
        return response()->json(['records' => $allRecords->values()]);
    }

    /**
     * Send pemeriksaan to Produksi for verification
     */
    public function sendToProduksi(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $user = Auth::user();
        
        // Check plant access
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->getEffectivePlantId() && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only pending status can be sent
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanSuhuRuang->update([
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
    public function approveProduksi(Request $request, PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $user = Auth::user();
        
        // Check plant access
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->getEffectivePlantId() && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only sent_to_produksi status can be approved
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanSuhuRuang->update([
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
    public function rejectProduksi(Request $request, PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        
        // Check plant access
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->getEffectivePlantId() && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only sent_to_produksi status can be rejected
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuang->update([
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
    public function approveSPV(Request $request, PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $user = Auth::user();
        
        // Check plant access
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->getEffectivePlantId() && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only approved_produksi status can be approved by SPV
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanSuhuRuang->update([
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
    public function rejectSPV(Request $request, PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        
        // Check plant access
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->getEffectivePlantId() && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only approved_produksi status can be rejected by SPV
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuang->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }

    public function printPDF(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        
        $pemeriksaanSuhuRuang->load([
            'user.role',
            'user.plant',
            'produk',
            'shift',
            'histories',
            'verifiedByQc.role',
            'verifiedByProduksi.role',
            'verifiedBySpv.role',
        ]);

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-suhu-ruang.pdf-report', [
            'pemeriksaans' => collect([$pemeriksaanSuhuRuang]),
            'tanggal' => $pemeriksaanSuhuRuang->tanggal->format('Y-m-d'),
            'shift' => $pemeriksaanSuhuRuang->shift,
        ]);

        $filename = 'pemeriksaan-suhu-ruang-' . $pemeriksaanSuhuRuang->uuid . '.pdf';
        return $pdf->stream($filename);
    }

    public function exportPDF(Request $request)
    {
        $user = Auth::user();
        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        // === MODE: ALL SHIFT ===
        if ($id_shift === 'all') {
            return $this->exportPDFAllShift($request, $user, $tanggalDari, $tanggalSampai);
        }

        $query = PemeriksaanSuhuRuang::with([
            'user.role',
            'user.plant',
            'produk',
            'shift',
            'histories',
            'verifiedByQc.role',
            'verifiedByProduksi.role',
            'verifiedBySpv.role',
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
        $shift = $id_shift ? Shift::find($id_shift) : null;

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-suhu-ruang.pdf-report', [
            'pemeriksaans'   => $pemeriksaans,
            'tanggal'        => $tanggal,
            'tanggal_dari'   => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift'          => $shift,
            'isAllShift'     => false,
            'dataPerShift'   => [],
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-suhu-ruang-' . $filenameDate . '.pdf';
        $filename = 'laporan-pemeriksaan-suhu-ruang-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Export PDF semua shift dikelompokkan per shift
     */
    private function exportPDFAllShift($request, $user, $tanggalDari, $tanggalSampai)
    {
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $allShifts = Shift::all();
        } else {
            $allShifts = Shift::query()
                ->when($user->id_plant, fn($q) => $q->whereHas('user', fn($qu) => $qu->where('id_plant', $user->id_plant)))
                ->get();
        }

        $dataPerShift = [];

        foreach ($allShifts as $shift) {
            $query = PemeriksaanSuhuRuang::with([
                'user.role', 'user.plant', 'produk', 'shift', 'histories',
                'verifiedByQc.role', 'verifiedByProduksi.role', 'verifiedBySpv.role',
            ]);

            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->whereHas('user', fn($q) => $q->where('id_plant', $user->getEffectivePlantId()));
            }

            $query->where('id_shift', $shift->id);

            if ($tanggalDari && $tanggalSampai) {
                $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
            } elseif ($tanggalDari) {
                $query->whereDate('tanggal', '>=', $tanggalDari);
            } elseif ($tanggalSampai) {
                $query->whereDate('tanggal', '<=', $tanggalSampai);
            }

            $records = $query->latest()->get();
            if ($records->isEmpty()) continue;

            $dataPerShift[] = [
                'shift'       => $shift,
                'pemeriksaans' => $records,
            ];
        }

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-suhu-ruang.pdf-report', [
            'pemeriksaans'   => collect(),
            'tanggal'        => null,
            'tanggal_dari'   => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift'          => null,
            'isAllShift'     => true,
            'dataPerShift'   => $dataPerShift,
        ]);

        $filename = 'laporan-semua-shift-suhu-ruang-'
            . ($tanggalDari ?? date('Y-m-d'))
            . ($tanggalSampai ? '-to-' . $tanggalSampai : '')
            . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel dengan filter
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        $query = PemeriksaanSuhuRuang::with([
            'user.role',
            'user.plant',
            'produk',
            'shift',
            'histories',
            'verifiedByQc.role',
            'verifiedByProduksi.role',
            'verifiedBySpv.role',
        ]);

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if ($id_shift && $id_shift !== 'all') {
            $query->where('id_shift', $id_shift);
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
            // All shifts
            if ($tanggalDari && $tanggalSampai) {
                $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
            } elseif ($tanggalDari) {
                $query->whereDate('tanggal', '>=', $tanggalDari);
            } elseif ($tanggalSampai) {
                $query->whereDate('tanggal', '<=', $tanggalSampai);
            } elseif ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            }
        }

        $pemeriksaans = $query->latest()->get();
        $shift = ($id_shift && $id_shift !== 'all') ? Shift::find($id_shift) : null;

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'pemeriksaan-suhu-ruang-' . $filenameDate . '.xlsx';

        return Excel::download(
            new PemeriksaanSuhuRuangExport($pemeriksaans, [
                'tanggal' => $tanggal,
                'tanggal_dari' => $tanggalDari,
                'tanggal_sampai' => $tanggalSampai,
                'shift' => $shift,
            ]),
            $filename
        );
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

        $query = PemeriksaanSuhuRuang::query();

        if ($userRole !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if (!empty($selected_uuids)) {
            $query->whereIn('uuid', $selected_uuids);
        } else {
            // JIKA MENGGUNAKAN KONTROL RANGE TANGGAL (FALLBACK)
            $tanggal_dari = $request->input('tanggal_dari');
            $tanggal_sampai = $request->input('tanggal_sampai');

            if (!$tanggal_dari || !$tanggal_sampai) {
                return back()->with('error', 'Silakan tentukan rentang tanggal atau gunakan checkbox untuk memilih data.');
            }

            $query->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai]);
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
