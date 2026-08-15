<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanSuhuRuangV3;
use App\Models\PemeriksaanSuhuRuangV3History;
use App\Models\Shift;
use App\Exports\PemeriksaanSuhuRuangV3Export;
use App\Exports\PemeriksaanSuhuRuangV3TemplateExport;
use App\Imports\PemeriksaanSuhuRuangV3Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PemeriksaanSuhuRuangV3Controller extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));
        
        $query = PemeriksaanSuhuRuangV3::with(['user.role', 'user.plant', 'shift']);

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
                    });
            });
        }


        $pemeriksaans = $query->latest()->paginate(25);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->getEffectivePlantId());
            }
        })->get();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
            'suhu_premix_*_setting' => 'nullable|string',
            'suhu_premix_*_display' => 'nullable|string',
            'suhu_premix_*_actual' => 'nullable|string',
            'suhu_seasoning_*_setting' => 'nullable|string',
            'suhu_seasoning_*_display' => 'nullable|string',
            'suhu_seasoning_*_actual' => 'nullable|string',
            'suhu_dry_*_setting' => 'nullable|string',
            'suhu_dry_*_display' => 'nullable|string',
            'suhu_dry_*_actual' => 'nullable|string',
            'suhu_cassing_*_setting' => 'nullable|string',
            'suhu_cassing_*_display' => 'nullable|string',
            'suhu_cassing_*_actual' => 'nullable|string',
            'suhu_beef_*_setting' => 'nullable|string',
            'suhu_beef_*_display' => 'nullable|string',
            'suhu_beef_*_actual' => 'nullable|string',
            'suhu_packaging_*_setting' => 'nullable|string',
            'suhu_packaging_*_display' => 'nullable|string',
            'suhu_packaging_*_actual' => 'nullable|string',
            'suhu_ruang_chemical_*_setting' => 'nullable|string',
            'suhu_ruang_chemical_*_display' => 'nullable|string',
            'suhu_ruang_chemical_*_actual' => 'nullable|string',
            'suhu_ruang_seasoning_*_setting' => 'nullable|string',
            'suhu_ruang_seasoning_*_display' => 'nullable|string',
            'suhu_ruang_seasoning_*_actual' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'tindakan_koreksi' => 'nullable|string',
        ]);

        $data = [
            'id_user' => Auth::id(),
            'id_shift' => $request->id_shift,
            'tanggal' => $request->tanggal,
            'pukul' => $request->pukul,
            'keterangan' => $request->keterangan,
            'tindakan_koreksi' => $request->tindakan_koreksi,
        ];

        // Process semua field suhu
        $suhuFields = ['suhu_premix', 'suhu_seasoning', 'suhu_dry', 'suhu_cassing', 
                       'suhu_beef', 'suhu_packaging', 'suhu_ruang_chemical', 'suhu_ruang_seasoning'];

        foreach ($suhuFields as $field) {
            $suhuData = [];
            for ($i = 1; $i <= 4; $i++) {
                $setting = $request->input("{$field}_{$i}_setting");
                $display = $request->input("{$field}_{$i}_display");
                $actual = $request->input("{$field}_{$i}_actual");

                // Handle manual input
                if ($setting === 'manual') {
                    $setting = $request->input("{$field}_{$i}_setting_manual");
                }
                if ($display === 'manual') {
                    $display = $request->input("{$field}_{$i}_display_manual");
                }
                if ($actual === 'manual') {
                    $actual = $request->input("{$field}_{$i}_actual_manual");
                }

                // Hanya simpan jika ada data
                if ($setting || $display || $actual) {
                    $suhuData["unit_{$i}"] = [
                        'setting' => $setting,
                        'display' => $display,
                        'actual' => $actual,
                    ];
                }
            }

            $data[$field] = !empty($suhuData) ? $suhuData : null;
        }

        PemeriksaanSuhuRuangV3::create($data);

        return redirect()->route('pemeriksaan-suhu-ruang-v3.index')->with('success', 'Pemeriksaan suhu ruang V3 berhasil dibuat!');
    }

    public function show(PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        $pemeriksaanSuhuRuangV3->load(['user', 'shift']);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.show', compact('pemeriksaanSuhuRuangV3'));
    }

    public function edit(PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        $pemeriksaanSuhuRuangV3->load(['shift']);
        
        $user = Auth::user();
        
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->getEffectivePlantId());
            }
        })->get();

        // Check if edit per 2 jam is allowed
        $canEdit = false;
        $nextEditTime = now();

        if (request()->query('edit_per_2jam')) {
            $lastHistory = $pemeriksaanSuhuRuangV3->histories()->latest()->first();
            
            if ($lastHistory) {
                // Ada history, cek apakah sudah 2 jam
                $nextEditTime = $lastHistory->created_at->addHours(2);
                $canEdit = now()->greaterThanOrEqualTo($nextEditTime);
            } else {
                // Tidak ada history, berarti baru pertama kali dibuat
                // Hitung 2 jam dari created_at record utama
                $nextEditTime = $pemeriksaanSuhuRuangV3->created_at->addHours(2);
                $canEdit = now()->greaterThanOrEqualTo($nextEditTime);
            }
        }
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.edit', compact('pemeriksaanSuhuRuangV3', 'shifts', 'canEdit', 'nextEditTime'));
    }

    public function update(Request $request, PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);

        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'pukul' => 'nullable',
            'suhu_premix_*_setting' => 'nullable|string',
            'suhu_premix_*_display' => 'nullable|string',
            'suhu_premix_*_actual' => 'nullable|string',
            'suhu_seasoning_*_setting' => 'nullable|string',
            'suhu_seasoning_*_display' => 'nullable|string',
            'suhu_seasoning_*_actual' => 'nullable|string',
            'suhu_dry_*_setting' => 'nullable|string',
            'suhu_dry_*_display' => 'nullable|string',
            'suhu_dry_*_actual' => 'nullable|string',
            'suhu_cassing_*_setting' => 'nullable|string',
            'suhu_cassing_*_display' => 'nullable|string',
            'suhu_cassing_*_actual' => 'nullable|string',
            'suhu_beef_*_setting' => 'nullable|string',
            'suhu_beef_*_display' => 'nullable|string',
            'suhu_beef_*_actual' => 'nullable|string',
            'suhu_packaging_*_setting' => 'nullable|string',
            'suhu_packaging_*_display' => 'nullable|string',
            'suhu_packaging_*_actual' => 'nullable|string',
            'suhu_ruang_chemical_*_setting' => 'nullable|string',
            'suhu_ruang_chemical_*_display' => 'nullable|string',
            'suhu_ruang_chemical_*_actual' => 'nullable|string',
            'suhu_ruang_seasoning_*_setting' => 'nullable|string',
            'suhu_ruang_seasoning_*_display' => 'nullable|string',
            'suhu_ruang_seasoning_*_actual' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'tindakan_koreksi' => 'nullable|string',
        ]);

        $data = [
            'id_shift' => $request->id_shift,
            'tanggal' => $request->tanggal,
            'pukul' => $request->pukul,
            'keterangan' => $request->keterangan,
            'tindakan_koreksi' => $request->tindakan_koreksi,
        ];

        // Process semua field suhu
        $suhuFields = ['suhu_premix', 'suhu_seasoning', 'suhu_dry', 'suhu_cassing', 
                       'suhu_beef', 'suhu_packaging', 'suhu_ruang_chemical', 'suhu_ruang_seasoning'];

        foreach ($suhuFields as $field) {
            $suhuData = [];
            for ($i = 1; $i <= 4; $i++) {
                $setting = $request->input("{$field}_{$i}_setting");
                $display = $request->input("{$field}_{$i}_display");
                $actual = $request->input("{$field}_{$i}_actual");

                // Handle manual input
                if ($setting === 'manual') {
                    $setting = $request->input("{$field}_{$i}_setting_manual");
                }
                if ($display === 'manual') {
                    $display = $request->input("{$field}_{$i}_display_manual");
                }
                if ($actual === 'manual') {
                    $actual = $request->input("{$field}_{$i}_actual_manual");
                }

                // Hanya simpan jika ada data
                if ($setting || $display || $actual) {
                    $suhuData["unit_{$i}"] = [
                        'setting' => $setting,
                        'display' => $display,
                        'actual' => $actual,
                    ];
                }
            }

            $data[$field] = !empty($suhuData) ? $suhuData : null;
        }

        // Save history before updating
        $this->saveHistory($pemeriksaanSuhuRuangV3, $data);
        
        $pemeriksaanSuhuRuangV3->update($data);

        return redirect()->route('pemeriksaan-suhu-ruang-v3.index')->with('success', 'Pemeriksaan suhu ruang V3 berhasil diperbarui!');
    }

    public function destroy(PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        $pemeriksaanSuhuRuangV3->delete();

        return redirect()->route('pemeriksaan-suhu-ruang-v3.index')->with('success', 'Pemeriksaan suhu ruang V3 berhasil dihapus!');
    }

    /**
     * Show history of changes
     */
    public function history(PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        $pemeriksaanSuhuRuangV3->load(['user', 'shift']);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.history', compact('pemeriksaanSuhuRuangV3'));
    }
    /**
     * Save history of changes
     */
    private function saveHistory($pemeriksaan, $newData)
    {
        $historyData = [
            'id_pemeriksaan_suhu_ruang_v3' => $pemeriksaan->id,
            'id_user' => Auth::id(),
            'keterangan_lama' => $pemeriksaan->keterangan,
            'keterangan_baru' => $newData['keterangan'] ?? null,
            'tindakan_koreksi_lama' => $pemeriksaan->tindakan_koreksi,
            'tindakan_koreksi_baru' => $newData['tindakan_koreksi'] ?? null,
        ];

        // Add suhu fields
        $suhuFields = ['suhu_premix', 'suhu_seasoning', 'suhu_dry', 'suhu_cassing', 
                    'suhu_beef', 'suhu_packaging', 'suhu_ruang_chemical', 'suhu_ruang_seasoning'];
        
        foreach ($suhuFields as $field) {
            $historyData[$field . '_lama'] = $pemeriksaan->$field;
            $historyData[$field . '_baru'] = $newData[$field] ?? null;
        }

        PemeriksaanSuhuRuangV3History::create($historyData);
    }
    /**
     * Check plant access
     */
    private function checkPlantAccess($pemeriksaan)
    {
        $user = Auth::user();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            if ($pemeriksaan->user->id_plant !== $user->getEffectivePlantId()) {
                abort(403, 'Unauthorized');
            }
        }
    }

    /**
     * Send pemeriksaan to Produksi for verification
     */
    public function sendToProduksi(PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        
        // Only pending status can be sent
        if ($pemeriksaanSuhuRuangV3->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanSuhuRuangV3->update([
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
    public function approveProduksi(Request $request, PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        
        // Only sent_to_produksi status can be approved
        if ($pemeriksaanSuhuRuangV3->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanSuhuRuangV3->update([
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
    public function rejectProduksi(Request $request, PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        
        // Only sent_to_produksi status can be rejected
        if ($pemeriksaanSuhuRuangV3->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuangV3->update([
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
    public function approveSPV(Request $request, PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        
        // Only approved_produksi status can be approved by SPV
        if ($pemeriksaanSuhuRuangV3->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanSuhuRuangV3->update([
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
    public function rejectSPV(Request $request, PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        
        // Only approved_produksi status can be rejected by SPV
        if ($pemeriksaanSuhuRuangV3->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanSuhuRuangV3->update([
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

        if ($id_shift === 'all') {
            return $this->exportPDFAllShift($request, $user, $tanggalDari, $tanggalSampai);
        }

        $query = PemeriksaanSuhuRuangV3::with([
            'user.role',
            'user.plant',
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

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-suhu-ruang-v3.pdf-report', [
            'pemeriksaans'   => $pemeriksaans,
            'tanggal'        => $tanggal,
            'tanggal_dari'   => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift'          => $shift,
            'isAllShift'     => false,
            'dataPerShift'   => [],
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-suhu-ruang-v3-' . $filenameDate . '.pdf';
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
            $query = PemeriksaanSuhuRuangV3::with([
                'user.role', 'user.plant', 'shift', 'histories',
                'verifiedByQc.role', 'verifiedByProduksi.role', 'verifiedBySpv.role',
            ]);
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->whereHas('user', fn($q) => $q->where('id_plant', $user->getEffectivePlantId()));
            }
            $query->where('id_shift', $shift->id);
            if ($tanggalDari && $tanggalSampai) { $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]); }
            elseif ($tanggalDari) { $query->whereDate('tanggal', '>=', $tanggalDari); }
            elseif ($tanggalSampai) { $query->whereDate('tanggal', '<=', $tanggalSampai); }
            $records = $query->latest()->get();
            if ($records->isEmpty()) continue;
            $dataPerShift[] = ['shift' => $shift, 'pemeriksaans' => $records];
        }

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-suhu-ruang-v3.pdf-report', [
            'pemeriksaans'   => collect(),
            'tanggal'        => null,
            'tanggal_dari'   => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift'          => null,
            'isAllShift'     => true,
            'dataPerShift'   => $dataPerShift,
        ]);

        return $pdf->download('laporan-semua-shift-suhu-ruang-v3-' . ($tanggalDari ?? date('Y-m-d')) . ($tanggalSampai ? '-to-' . $tanggalSampai : '') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        $query = PemeriksaanSuhuRuangV3::with([
            'user.role',
            'user.plant',
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
        }

        if ($id_shift && $id_shift !== 'all') {
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
            // Semua Shift - handle both range tanggal dan single date
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

        $params = [
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
        ];

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-suhu-ruang-v3-' . $filenameDate . '.xlsx';
        
        return Excel::download(new PemeriksaanSuhuRuangV3Export($pemeriksaans, $params), $filename);
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

        $query = PemeriksaanSuhuRuangV3::query();

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

    public function printPDF(PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        
        $pemeriksaanSuhuRuangV3->load([
            'user.role', 
            'user.plant', 
            'shift', 
            'histories',
            'verifiedByQc.role',
            'verifiedByProduksi.role',
            'verifiedBySpv.role'
        ]);

        $pemeriksaans = collect([$pemeriksaanSuhuRuangV3]);

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-suhu-ruang-v3.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $pemeriksaanSuhuRuangV3->tanggal,
            'shift' => $pemeriksaanSuhuRuangV3->shift,
        ]);

        return $pdf->stream('pemeriksaan-suhu-ruang-v3-' . $pemeriksaanSuhuRuangV3->uuid . '.pdf');
    }

    /**
     * Download template Excel untuk import data
     */
    public function downloadTemplate()
    {
        return Excel::download(new PemeriksaanSuhuRuangV3TemplateExport(), 'template-pemeriksaan-suhu-ruang-v3.xlsx');
    }

    /**
     * Import data dari file Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:5120', // 5MB
        ], [
            'file.required' => 'File wajib diunggah',
            'file.mimes' => 'File harus berformat Excel (xlsx atau xls)',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $import = new PemeriksaanSuhuRuangV3Import();
            Excel::import($import, $request->file('file'));

            $message = "Data berhasil diimpor! ";
            if ($import->inserted > 0) {
                $message .= "{$import->inserted} data berhasil ditambahkan. ";
            }
            if ($import->skipped > 0) {
                $message .= "{$import->skipped} data sudah ada dan tidak ditambahkan. ";
            }

            if (!empty($import->errors)) {
                return redirect()->route('pemeriksaan-suhu-ruang-v3.index')->with([
                    'success' => true,
                    'message' => $message,
                    'errors' => $import->errors,
                    'show_errors' => true,
                ]);
            }

            return redirect()->route('pemeriksaan-suhu-ruang-v3.index')->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('pemeriksaan-suhu-ruang-v3.index')->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }
}