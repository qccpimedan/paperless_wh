<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKebersihanArea;
use App\Models\PemeriksaanKebersihanAreaDetail;
use App\Models\InputMasterForm;
use App\Models\InputArea;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PemeriksaanKebersihanAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanKebersihanArea::with(['user.role', 'user.plant', 'shift']);

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
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    })
                    ->orWhere('area_data', 'like', '%' . $search . '%'); // Search directly in json blob
            });
        }


        $pemeriksaans = $query->latest()->paginate(25);

        $areaNamaById = InputArea::pluck('nama_area', 'id')->all();
        $masterFormNamaById = InputMasterForm::pluck('nama_form', 'id')->all();

        return view('qc-sistem.pemeriksaan-kebersihan-area.index', compact('pemeriksaans', 'areaNamaById', 'masterFormNamaById'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $masterForms = InputMasterForm::all();
            $areas = InputArea::all();
            $shifts = Shift::all();
        } else {
            $masterForms = InputMasterForm::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->get();

            $areas = InputArea::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->get();

            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->get();
        }
        
        return view('qc-sistem.pemeriksaan-kebersihan-area.create', compact('masterForms', 'areas', 'shifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
        ]);

        $areaData = [];

        foreach ($request->items as $item) {
            $jamSebelum = !empty($item['jam_sebelum_proses']) ? substr((string) $item['jam_sebelum_proses'], 0, 5) : null;
            $jamSaat = !empty($item['jam_saat_proses']) ? substr((string) $item['jam_saat_proses'], 0, 5) : null;

            $areaRecord = [
                'id_area' => $item['id_area'] ?? null,
                'id_master_form' => $item['id_master_form'] ?? null,
                'jam_sebelum_proses' => $jamSebelum,
                'jam_saat_proses' => $jamSaat,
                'fields' => []
            ];

            // Extract details for each field
            if (!empty($item['id_master_form'])) {
                $masterForm = InputMasterForm::find($item['id_master_form']);
                if ($masterForm && $masterForm->fields) {
                    foreach ($masterForm->fields as $field) {
                        $statusSebelumKey = 'field_status_sebelum_' . $field->id;
                        $statusSaatKey = 'field_status_saat_' . $field->id;
                        $verifikasiKey = 'field_verifikasi_' . $field->id;
                        $keteranganKey = 'field_keterangan_' . $field->id;
                        $tindakanKey = 'field_tindakan_' . $field->id;

                        $statusSebelum = isset($item[$statusSebelumKey]) ? (int) $item[$statusSebelumKey] : null;
                        $statusSaat = isset($item[$statusSaatKey]) ? (int) $item[$statusSaatKey] : null;

                        $legacyStatus = null;
                        if ($statusSebelum !== null && $statusSaat !== null) {
                            $legacyStatus = ($statusSebelum === 1 && $statusSaat === 1) ? 1 : 0;
                        }

                        $verifikasiHasil = isset($item[$verifikasiKey]) ? (int) $item[$verifikasiKey] : null;
                        
                        $areaRecord['fields'][] = [
                            'id_master_form_field' => $field->id,
                            'status' => $legacyStatus,
                            'status_sebelum_proses' => $statusSebelum,
                            'status_saat_proses' => $statusSaat,
                            'verifikasi_hasil' => $verifikasiHasil,
                            'keterangan' => $item[$keteranganKey] ?? null,
                            'tindakan_koreksi' => $item[$tindakanKey] ?? null,
                        ];
                    }
                }
            }

            $areaData[] = $areaRecord;
        }

        // Create pemeriksaan single header
        PemeriksaanKebersihanArea::create([
            'id_user' => Auth::id(),
            'id_shift' => $request->id_shift,
            'tanggal' => $request->tanggal,
            'area_data' => $areaData,
        ]);

        return redirect()->route('pemeriksaan-kebersihan-area.index')->with('success', 'Pemeriksaan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        $pemeriksaanKebersihanArea->load(['shift', 'user']);
        
        $areas = InputArea::all();
        $masterForms = InputMasterForm::with('fields')->get();

        return view('qc-sistem.pemeriksaan-kebersihan-area.show', compact('pemeriksaanKebersihanArea', 'areas', 'masterForms'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        $pemeriksaanKebersihanArea->load(['shift']);

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $masterForms = InputMasterForm::with('fields')->get();
            $areas = InputArea::all();
            $shifts = Shift::all();
        } else {
            $masterForms = InputMasterForm::with('fields')->whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->get();

            $areas = InputArea::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->get();

            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->get();
        }
        
        return view('qc-sistem.pemeriksaan-kebersihan-area.edit', compact('pemeriksaanKebersihanArea', 'masterForms', 'areas', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        $request->validate([
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
        ]);

        $areaData = [];

        foreach ($request->items as $item) {
            $jamSebelum = !empty($item['jam_sebelum_proses']) ? substr((string) $item['jam_sebelum_proses'], 0, 5) : null;
            $jamSaat = !empty($item['jam_saat_proses']) ? substr((string) $item['jam_saat_proses'], 0, 5) : null;

            $areaRecord = [
                'id_area' => $item['id_area'] ?? null,
                'id_master_form' => $item['id_master_form'] ?? null,
                'jam_sebelum_proses' => $jamSebelum,
                'jam_saat_proses' => $jamSaat,
                'fields' => []
            ];

            if (!empty($item['id_master_form'])) {
                $masterForm = InputMasterForm::find($item['id_master_form']);
                if ($masterForm && $masterForm->fields) {
                    foreach ($masterForm->fields as $field) {
                        $statusSebelumKey = 'field_status_sebelum_' . $field->id;
                        $statusSaatKey = 'field_status_saat_' . $field->id;
                        $verifikasiKey = 'field_verifikasi_' . $field->id;
                        $keteranganKey = 'field_keterangan_' . $field->id;
                        $tindakanKey = 'field_tindakan_' . $field->id;

                        $statusSebelum = isset($item[$statusSebelumKey]) ? (int) $item[$statusSebelumKey] : null;
                        $statusSaat = isset($item[$statusSaatKey]) ? (int) $item[$statusSaatKey] : null;

                        $legacyStatus = null;
                        if ($statusSebelum !== null && $statusSaat !== null) {
                            $legacyStatus = ($statusSebelum === 1 && $statusSaat === 1) ? 1 : 0;
                        }

                        $verifikasiHasil = isset($item[$verifikasiKey]) ? (int) $item[$verifikasiKey] : null;
                        
                        $areaRecord['fields'][] = [
                            'id_master_form_field' => $field->id,
                            'status' => $legacyStatus,
                            'status_sebelum_proses' => $statusSebelum,
                            'status_saat_proses' => $statusSaat,
                            'verifikasi_hasil' => $verifikasiHasil,
                            'keterangan' => $item[$keteranganKey] ?? null,
                            'tindakan_koreksi' => $item[$tindakanKey] ?? null,
                        ];
                    }
                }
            }

            $areaData[] = $areaRecord;
        }

        // Update pemeriksaan
        $pemeriksaanKebersihanArea->update([
            'id_shift' => $request->id_shift,
            'tanggal' => $request->tanggal,
            'area_data' => $areaData,
        ]);

        return redirect()->route('pemeriksaan-kebersihan-area.index')->with('success', 'Pemeriksaan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        $pemeriksaanKebersihanArea->delete();
        return redirect()->route('pemeriksaan-kebersihan-area.index')->with('success', 'Pemeriksaan berhasil dihapus!');
    }

    /**
     * Check if user has access to pemeriksaan based on plant
     */
    private function checkPlantAccess(PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($pemeriksaanKebersihanArea->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    /**
     * Send pemeriksaan to Produksi for verification
     */
    public function sendToProduksi(PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        // Only pending status can be sent
        if ($pemeriksaanKebersihanArea->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanKebersihanArea->update([
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
    public function approveProduksi(Request $request, PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        // Only sent_to_produksi status can be approved
        if ($pemeriksaanKebersihanArea->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanKebersihanArea->update([
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
    public function rejectProduksi(Request $request, PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        // Only sent_to_produksi status can be rejected
        if ($pemeriksaanKebersihanArea->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanKebersihanArea->update([
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
    public function approveSPV(Request $request, PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        // Only approved_produksi status can be approved by SPV
        if ($pemeriksaanKebersihanArea->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanKebersihanArea->update([
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
    public function rejectSPV(Request $request, PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        // Only approved_produksi status can be rejected by SPV
        if ($pemeriksaanKebersihanArea->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanKebersihanArea->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }

    /**
     * Export data to PDF. Supports single record or multiple based on filters.
     */
    public function exportPDF(Request $request, $uuid = null)
    {
        $user = Auth::user();

        if ($uuid) {
            // Penarikan data per data (single record)
            $query = PemeriksaanKebersihanArea::where('uuid', $uuid);
        } else {
            // Penarikan data berdasarkan filter (batch/search)
            $query = PemeriksaanKebersihanArea::query();
        }

        $query->with([
            'user.role',
            'user.plant',
            'shift',
            'qcVerifier',
            'produksiVerifier',
            'spvVerifier',
        ]);

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if (!$uuid) {
            $id_shift = $request->input('id_shift');
            $tanggalDari = $request->input('tanggal_dari');
            $tanggalSampai = $request->input('tanggal_sampai');
            $tanggal = $request->input('tanggal');

            if ($id_shift) {
                $query->where('id_shift', $id_shift);
                $shift = Shift::find($id_shift);
                
                if ($shift && $shift->is_date_range) {
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
        }

        $pemeriksaans = $query->latest()->get();

        if ($pemeriksaans->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk di-export.');
        }

        $firstP = $pemeriksaans->first();
        $shift = $firstP ? $firstP->shift : null;

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-kebersihan-area.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $uuid ? $firstP->tanggal->format('Y-m-d') : ($request->input('tanggal') ?? null),
            'tanggal_dari' => $uuid ? null : ($request->input('tanggal_dari') ?? null),
            'tanggal_sampai' => $uuid ? null : ($request->input('tanggal_sampai') ?? null),
            'shift' => $shift,
        ]);

        $filename = 'laporan-kebersihan-area-' . ($uuid ? $firstP->uuid : date('Ymd-His')) . '.pdf';
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

        $query = PemeriksaanKebersihanArea::query();

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