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

        $query = PemeriksaanKebersihanArea::with(['user.role', 'user.plant', 'shift', 'area', 'masterForm']);

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
                    ->orWhereHas('area', function ($qa) use ($search) {
                        $qa->where('nama_area', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('masterForm', function ($qm) use ($search) {
                        $qm->where('nama_form', 'like', '%' . $search . '%');
                    });
            });
        }


        $pemeriksaans = $query->latest()->paginate(25);

        return view('qc-sistem.pemeriksaan-kebersihan-area.index', compact('pemeriksaans'));
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
            'items.*.id_master_form' => 'required|exists:input_master_forms,id',
            'items.*.id_area' => 'required|exists:input_areas,id',
        ]);

        foreach ($request->items as $item) {
            $jamSebelum = !empty($item['jam_sebelum_proses']) ? substr((string) $item['jam_sebelum_proses'], 0, 5) : null;
            $jamSaat = !empty($item['jam_saat_proses']) ? substr((string) $item['jam_saat_proses'], 0, 5) : null;

            // Create pemeriksaan
            $pemeriksaan = PemeriksaanKebersihanArea::create([
                'id_user' => Auth::id(),
                'id_shift' => $request->id_shift,
                'id_area' => $item['id_area'],
                'id_master_form' => $item['id_master_form'],
                'tanggal' => $request->tanggal,
                'jam_sebelum_proses' => $jamSebelum,
                'jam_saat_proses' => $jamSaat,
                'verifikasi_hasil' => isset($item['verifikasi_hasil']) ? (bool) $item['verifikasi_hasil'] : null,
            ]);

            // Create details for each field
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
                    
                    PemeriksaanKebersihanAreaDetail::create([
                        'id_pemeriksaan' => $pemeriksaan->id,
                        'id_master_form_field' => $field->id,
                        'status' => $legacyStatus,
                        'status_sebelum_proses' => $statusSebelum,
                        'status_saat_proses' => $statusSaat,
                        'verifikasi_hasil' => $verifikasiHasil,
                        'keterangan' => $item[$keteranganKey] ?? null,
                        'tindakan_koreksi' => $item[$tindakanKey] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('pemeriksaan-kebersihan-area.index')->with('success', 'Pemeriksaan berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        $pemeriksaanKebersihanArea->load(['details.field', 'masterForm', 'shift', 'area', 'user']);
        
        return view('qc-sistem.pemeriksaan-kebersihan-area.show', compact('pemeriksaanKebersihanArea'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        $pemeriksaanKebersihanArea->load(['details.field', 'masterForm', 'shift', 'area']);
        
        return view('qc-sistem.pemeriksaan-kebersihan-area.edit', compact('pemeriksaanKebersihanArea'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PemeriksaanKebersihanArea $pemeriksaanKebersihanArea)
    {
        $this->checkPlantAccess($pemeriksaanKebersihanArea);
        
        $request->validate([
            'tanggal' => 'required|date',
            'jam_sebelum_proses' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'jam_saat_proses' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
        ]);

        $jamSebelum = $request->jam_sebelum_proses ? substr((string) $request->jam_sebelum_proses, 0, 5) : null;
        $jamSaat = $request->jam_saat_proses ? substr((string) $request->jam_saat_proses, 0, 5) : null;

        // Update pemeriksaan
        $pemeriksaanKebersihanArea->update([
            'tanggal' => $request->tanggal,
            'jam_sebelum_proses' => $jamSebelum,
            'jam_saat_proses' => $jamSaat,
        ]);

        // Update details
        foreach ($pemeriksaanKebersihanArea->details as $detail) {
            $statusSebelumKey = 'status_sebelum_' . $detail->id;
            $statusSaatKey = 'status_saat_' . $detail->id;
            $verifikasiKey = 'verifikasi_' . $detail->id;
            $keteranganKey = 'keterangan_' . $detail->id;
            $tindakanKey = 'tindakan_koreksi_' . $detail->id;

            $statusSebelum = $request->has($statusSebelumKey) ? (int) $request->input($statusSebelumKey) : null;
            $statusSaat = $request->has($statusSaatKey) ? (int) $request->input($statusSaatKey) : null;

            $legacyStatus = null;
            if ($statusSebelum !== null && $statusSaat !== null) {
                $legacyStatus = ($statusSebelum === 1 && $statusSaat === 1) ? 1 : 0;
            }

            $verifikasiHasil = $request->has($verifikasiKey) ? (int) $request->input($verifikasiKey) : null;

            $detail->update([
                'status' => $legacyStatus,
                'status_sebelum_proses' => $statusSebelum,
                'status_saat_proses' => $statusSaat,
                'verifikasi_hasil' => $verifikasiHasil,
                'keterangan' => $request->input($keteranganKey),
                'tindakan_koreksi' => $request->input($tindakanKey),
            ]);
        }

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
     * Export data to PDF based on filters
     */
    public function exportPDF(Request $request)
    {
        $user = Auth::user();

        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        $query = PemeriksaanKebersihanArea::with([
            'user.role',
            'user.plant',
            'shift',
            'area.locations',
            'masterForm',
            'details.field',
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

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-kebersihan-area.pdf-report', [
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
        $filename = 'laporan-pemeriksaan-kebersihan-area-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }
}