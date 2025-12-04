<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanSuhuRuang;
use App\Models\PemeriksaanSuhuRuangHistory;
use App\Models\Shift;
use App\Models\Bahan;
use App\Models\InputArea;
use App\Traits\EditablePer2JamTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanSuhuRuangController extends Controller
{
    use EditablePer2JamTrait;

    protected function getEditRouteName()
    {
        return 'pemeriksaan-suhu-ruang.edit';
    }
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanSuhuRuang::with(['user.role', 'user.plant', 'shift', 'produk', 'area'])->latest()->get();
        } else {
            $pemeriksaans = PemeriksaanSuhuRuang::with(['user.role', 'user.plant', 'shift', 'produk', 'area'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Filter berdasarkan plant user yang login
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        $produks = Bahan::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        $areas = InputArea::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.create', compact('shifts', 'produks', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'id_produk' => 'required|exists:bahans,id',
            'id_area' => 'required|exists:input_areas,id',
            'tanggal' => 'required|date',
        ]);

        $suhuData = $this->prepareSuhuData($request);

        PemeriksaanSuhuRuang::create([
            'id_user' => Auth::id(),
            'id_shift' => $request->id_shift,
            'id_produk' => $request->id_produk,
            'id_area' => $request->id_area,
            'tanggal' => $request->tanggal,
            'suhu_data' => $suhuData,
            'keterangan' => $request->keterangan,
            'tindakan_koreksi' => $request->tindakan_koreksi,
        ]);

        return redirect()->route('pemeriksaan-suhu-ruang.index')->with('success', 'Pemeriksaan suhu ruang berhasil dibuat!');
    }

    public function show(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        $pemeriksaanSuhuRuang->load(['user', 'shift', 'produk', 'area']);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.show', compact('pemeriksaanSuhuRuang'));
    }

    public function edit(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        $pemeriksaanSuhuRuang->load(['shift', 'produk', 'area']);
        
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
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        $produks = Bahan::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        $areas = InputArea::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.edit', compact('pemeriksaanSuhuRuang', 'shifts', 'produks', 'areas', 'canEdit', 'nextEditTime', 'hoursDiff', 'editPer2Jam'));
    }

    public function update(Request $request, PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuang);
        
        $request->validate([
            'tanggal' => 'required|date',
        ]);

        $suhuData = $this->prepareSuhuData($request);

        // Simpan history sebelum update
        PemeriksaanSuhuRuangHistory::create([
            'id_pemeriksaan_suhu_ruang' => $pemeriksaanSuhuRuang->id,
            'id_user' => Auth::id(),
            'suhu_data_lama' => $pemeriksaanSuhuRuang->suhu_data,
            'suhu_data_baru' => $suhuData,
            'keterangan_lama' => $pemeriksaanSuhuRuang->keterangan,
            'keterangan_baru' => $request->keterangan,
            'tindakan_koreksi_lama' => $pemeriksaanSuhuRuang->tindakan_koreksi,
            'tindakan_koreksi_baru' => $request->tindakan_koreksi,
        ]);

        $pemeriksaanSuhuRuang->update([
            'tanggal' => $request->tanggal,
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
        $pemeriksaanSuhuRuang->load(['user', 'shift', 'produk', 'area']);
        $histories = $pemeriksaanSuhuRuang->histories()->with('user')->latest()->get();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang.history', compact('pemeriksaanSuhuRuang', 'histories'));
    }

    private function checkPlantAccess(PemeriksaanSuhuRuang $pemeriksaanSuhuRuang)
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->id_plant) {
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
                $q->where('id_plant', $user->id_plant);
            });
        }
        
        $recordsV2Data = $recordsV2Query->get();
        
        $recordsV2 = $recordsV2Data->map(function($record) {
            return [
                'uuid' => $record->uuid,
                'tanggal' => $record->tanggal->format('Y-m-d'),
                'area' => $record->area->nama_area ?? 'N/A',
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
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->id_plant && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only pending status can be sent
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanSuhuRuang->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
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
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->id_plant && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only sent_to_produksi status can be approved
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanSuhuRuang->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
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
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->id_plant && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only sent_to_produksi status can be rejected
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuang->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
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
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->id_plant && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only approved_produksi status can be approved by SPV
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanSuhuRuang->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
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
        if ($pemeriksaanSuhuRuang->user->id_plant !== $user->id_plant && !($user->role && strtolower($user->role->role) === 'superadmin')) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
        
        // Only approved_produksi status can be rejected by SPV
        if ($pemeriksaanSuhuRuang->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuang->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}
