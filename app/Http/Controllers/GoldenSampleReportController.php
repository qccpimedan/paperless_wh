<?php

namespace App\Http\Controllers;

use App\Models\GoldenSampleReport;
use App\Models\Plant;
use App\Models\Shift;
use App\Models\InputDeskripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class GoldenSampleReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));

        $query = GoldenSampleReport::with(['user.role', 'user.plant', 'plant', 'shift']);

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

                $q->orWhere('sample_type', 'like', '%' . $search . '%')
                    ->orWhere('masa_penyimpanan', 'like', '%' . $search . '%')
                    ->orWhere('plant_manual', 'like', '%' . $search . '%')
                    ->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhere('verification_notes', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('plant', function ($qp) use ($search) {
                        $qp->where('plant', 'like', '%' . $search . '%');
                    });
            });
        }


        $reports = $query->latest()->paginate(25);

        return view('qc-sistem.golden-sample-retort.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get plants untuk dropdown
        $query = Plant::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->where('id', $user->getEffectivePlantId());
        }
        $plants = $query->get();
        
        // Get deskripsis untuk multiple select
        $deskripsiQuery = InputDeskripsi::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $deskripsiQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }
        $deskripsis = $deskripsiQuery->latest()->get();
        
        // Get shifts - Mengikuti pola dari PemeriksaanKedatanganChemicalController
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
        }
        
        return view('qc-sistem.golden-sample-retort.create', compact('plants', 'deskripsis', 'shifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_plant' => 'required|string',
            'id_shift' => 'required|exists:shifts,id',  // Added shift validation
            'tanggal' => 'required|date',  // Added tanggal validation
            'plant_manual' => 'nullable|string|max:255|required_if:id_plant,other',
            'sample_type' => 'required|string|max:255',
            'masa_penyimpanan' => 'required|string|max:255',
            'sample_storage' => 'required|array|min:1',
            'sample_storage.*' => 'string|in:Frozen,Chilled,Ambient',
            'samples' => 'required|array|min:1',
            'samples.*.id_deskripsi' => 'required|array|min:1',
            'samples.*.id_deskripsi.*' => 'exists:input_deskripsis,uuid',
            'samples.*.id_supplier' => 'required|string|max:255',
            'samples.*.kode_produksi' => 'required|string|max:255',
            'samples.*.best_before' => 'required|date',
            'samples.*.qty' => 'required|string|max:100',
            'samples.*.diserahkan' => 'required|string|max:255',
            'samples.*.diterima' => 'required|string|max:255',
        ]);

        $idPlant = $request->id_plant === 'other' ? null : $request->id_plant;
        
        GoldenSampleReport::create([
            'id_user' => Auth::id(),
            'id_plant' => $idPlant,
            'id_shift' => $request->id_shift,  // Added shift_id
            'plant_manual' => $request->id_plant === 'other' ? $request->plant_manual : null,
            'sample_type' => $request->sample_type,
            'masa_penyimpanan' => $request->masa_penyimpanan,
            'tanggal' => \Carbon\Carbon::parse($request->tanggal)->format('Y-m-d'),  // Ensure proper date format
            'sample_storage' => $request->sample_storage,
            'samples' => $request->samples,
        ]);

        return redirect()->route('golden-sample-reports.index')
                    ->with('success', 'Golden Sample Report berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(GoldenSampleReport $goldenSampleReport)
    {
        $this->checkPlantAccess($goldenSampleReport);
        $goldenSampleReport->load(['user.role', 'user.plant', 'plant']);
        
        return view('qc-sistem.golden-sample-retort.show', compact('goldenSampleReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GoldenSampleReport $goldenSampleReport)
    {
        $this->checkPlantAccess($goldenSampleReport);
        
        $user = Auth::user();
        
        // Get plants untuk dropdown
        $query = Plant::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->where('id', $user->getEffectivePlantId());
        }
        $plants = $query->get();
        
        // Get deskripsis untuk multiple select
        $deskripsiQuery = InputDeskripsi::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $deskripsiQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }
        $deskripsis = $deskripsiQuery->latest()->get();
        
        // Get shifts
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->getEffectivePlantId());
            })->with(['user.plant'])->get();
        }
        
        return view('qc-sistem.golden-sample-retort.edit', compact('goldenSampleReport', 'plants', 'deskripsis', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GoldenSampleReport $goldenSampleReport)
    {
        $this->checkPlantAccess($goldenSampleReport);
        
        $request->validate([
            'id_plant' => 'required|string',
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'plant_manual' => 'nullable|string|max:255|required_if:id_plant,other',
            'sample_type' => 'required|string|max:255',
            'masa_penyimpanan' => 'required|string|max:255',
            'sample_storage' => 'required|array|min:1',
            'sample_storage.*' => 'string|in:Frozen,Chilled,Ambient',
            'samples' => 'required|array|min:1',
            'samples.*.id_deskripsi' => 'required|array|min:1',
            'samples.*.id_deskripsi.*' => 'exists:input_deskripsis,uuid',
            'samples.*.id_supplier' => 'required|string|max:255',
            'samples.*.kode_produksi' => 'required|string|max:255',
            'samples.*.best_before' => 'required|date',
            'samples.*.qty' => 'required|string|max:100',
            'samples.*.diserahkan' => 'required|string|max:255',
            'samples.*.diterima' => 'required|string|max:255',
        ]);

        $idPlant = $request->id_plant === 'other' ? null : $request->id_plant;
        
        $goldenSampleReport->update([
            'id_plant' => $idPlant,
            'id_shift' => $request->id_shift,
            'plant_manual' => $request->id_plant === 'other' ? $request->plant_manual : null,
            'sample_type' => $request->sample_type,
            'tanggal' => $request->tanggal,
            'masa_penyimpanan' => $request->masa_penyimpanan,
            'sample_storage' => $request->sample_storage,
            'samples' => $request->samples,
        ]);

        return redirect()->route('golden-sample-reports.index')
                       ->with('success', 'Golden Sample Report berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoldenSampleReport $goldenSampleReport)
    {
        $this->checkPlantAccess($goldenSampleReport);
        
        $goldenSampleReport->delete();
        
        return redirect()->route('golden-sample-reports.index')
                       ->with('success', 'Golden Sample Report berhasil dihapus!');
    }

    /**
     * Check if user has access based on plant
     */
    private function checkPlantAccess(GoldenSampleReport $goldenSampleReport)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat akses semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }
        
        // Admin dan role lain hanya dapat akses data dari plant mereka
        if ($goldenSampleReport->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
    public function getDeskripsiByPlant($plantId)
    {
        $user = Auth::user();
        
        $query = InputDeskripsi::query();
        
        // Filter berdasarkan plant
        if ($plantId !== 'other') {
            $query->whereHas('user', function($q) use ($plantId) {
                $q->where('id_plant', $plantId);
            });
        } else {
            // Jika "other", tampilkan semua deskripsi sesuai user
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->whereHas('user', function($q) use ($user) {
                    $q->where('id_plant', $user->getEffectivePlantId());
                });
            }
        }
        
        return response()->json($query->latest()->get(['uuid', 'nama_deskripsi']));
    }

    /**
     * Send report to Produksi for verification
     */
    public function sendToProduksi(GoldenSampleReport $goldenSampleReport)
    {
        $user = Auth::user();
        $this->checkPlantAccess($goldenSampleReport);
        
        // Only pending status can be sent
        if ($goldenSampleReport->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya report dengan status pending yang dapat dikirim.');
        }
        
        $goldenSampleReport->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Report berhasil dikirim ke Produksi.');
    }

    /**
     * Approve report from Produksi
     */
    public function approveProduksi(Request $request, GoldenSampleReport $goldenSampleReport)
    {
        $user = Auth::user();
        $this->checkPlantAccess($goldenSampleReport);
        
        // Only sent_to_produksi status can be approved
        if ($goldenSampleReport->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status report tidak valid untuk di-approve.');
        }
        
        $goldenSampleReport->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Report berhasil di-approve oleh Produksi.');
    }

    /**
     * Reject report from Produksi
     */
    public function rejectProduksi(Request $request, GoldenSampleReport $goldenSampleReport)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($goldenSampleReport);
        
        // Only sent_to_produksi status can be rejected
        if ($goldenSampleReport->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status report tidak valid untuk di-reject.');
        }
        
        $goldenSampleReport->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Report ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    /**
     * Approve report from SPV QC (final verification)
     */
    public function approveSPV(Request $request, GoldenSampleReport $goldenSampleReport)
    {
        $user = Auth::user();
        $this->checkPlantAccess($goldenSampleReport);
        
        // Only approved_produksi status can be approved by SPV
        if ($goldenSampleReport->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Report harus disetujui Produksi terlebih dahulu.');
        }
        
        $goldenSampleReport->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Report berhasil diverifikasi oleh SPV QC.');
    }

    /**
     * Reject report from SPV QC (final verification)
     */
    public function rejectSPV(Request $request, GoldenSampleReport $goldenSampleReport)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        $this->checkPlantAccess($goldenSampleReport);
        
        // Only approved_produksi status can be rejected by SPV
        if ($goldenSampleReport->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status report tidak valid untuk di-reject.');
        }
        
        $goldenSampleReport->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Report ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }

    public function exportPDF(Request $request)
    {
        $user = Auth::user();

        $id_shift = $request->input('id_shift');
        $tanggalDari = $request->input('tanggal_dari');
        $tanggalSampai = $request->input('tanggal_sampai');
        $tanggal = $request->input('tanggal');

        $query = GoldenSampleReport::with([
            'user.role',
            'user.plant',
            'plant',
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

        $reports = $query->latest()->get();

        $shift = $id_shift ? Shift::find($id_shift) : null;

        $allDeskripsiUuids = $reports
            ->flatMap(function ($report) {
                $samples = is_array($report->samples) ? $report->samples : [];
                return collect($samples)->flatMap(function ($s) {
                    $ids = (isset($s['id_deskripsi']) && is_array($s['id_deskripsi'])) ? $s['id_deskripsi'] : [];
                    return collect($ids);
                });
            })
            ->filter()
            ->unique()
            ->values();

        $deskripsiMap = [];
        if ($allDeskripsiUuids->count() > 0) {
            $deskripsiMap = InputDeskripsi::whereIn('uuid', $allDeskripsiUuids->toArray())
                ->pluck('nama_deskripsi', 'uuid')
                ->toArray();
        }

        $pdf = PDF::loadView('qc-sistem.golden-sample-retort.pdf-report', [
            'reports' => $reports,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
            'deskripsiMap' => $deskripsiMap,
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-golden-sample-' . $filenameDate . '.pdf';
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

        $query = GoldenSampleReport::query();

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