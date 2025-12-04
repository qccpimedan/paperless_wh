<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanSuhuRuangV3;
use App\Models\PemeriksaanSuhuRuangV3History;
use App\Models\Shift;
use App\Models\InputArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanSuhuRuangV3Controller extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanSuhuRuangV3::with(['user.role', 'user.plant', 'shift', 'area'])->latest()->get();
        } else {
            $pemeriksaans = PemeriksaanSuhuRuangV3::with(['user.role', 'user.plant', 'shift', 'area'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        
        $areas = InputArea::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.create', compact('shifts', 'areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'id_area' => 'required|exists:input_areas,id',
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
            'id_area' => $request->id_area,
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
        $pemeriksaanSuhuRuangV3->load(['user', 'shift', 'area']);
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.show', compact('pemeriksaanSuhuRuangV3'));
    }

    public function edit(PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);
        $pemeriksaanSuhuRuangV3->load(['shift', 'area']);
        
        $user = Auth::user();
        
        $shifts = Shift::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
            }
        })->get();
        
        $areas = InputArea::whereHas('user', function($query) use ($user) {
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->where('id_plant', $user->id_plant);
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
        
        return view('qc-sistem.pemeriksaan-suhu-ruang-v3.edit', compact('pemeriksaanSuhuRuangV3', 'shifts', 'areas', 'canEdit', 'nextEditTime'));
    }

    public function update(Request $request, PemeriksaanSuhuRuangV3 $pemeriksaanSuhuRuangV3)
    {
        $this->checkPlantAccess($pemeriksaanSuhuRuangV3);

        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'id_area' => 'required|exists:input_areas,id',
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
            'id_area' => $request->id_area,
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
        $pemeriksaanSuhuRuangV3->load(['user', 'shift', 'area']);
        
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
            if ($pemeriksaan->user->id_plant !== $user->id_plant) {
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
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}