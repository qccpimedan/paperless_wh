<?php

namespace App\Http\Controllers;

use App\Models\GoldenSampleReport;
use App\Models\Plant;
use App\Models\Shift;
use App\Models\InputDeskripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoldenSampleReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $reports = GoldenSampleReport::with(['user.role', 'user.plant', 'plant', 'shift'])
            ->latest()
            ->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $reports = GoldenSampleReport::with(['user.role', 'user.plant', 'plant', 'shift'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
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
            $query->where('id', $user->id_plant);
        }
        $plants = $query->get();
        
        // Get deskripsis untuk multiple select
        $deskripsiQuery = InputDeskripsi::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $deskripsiQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $deskripsis = $deskripsiQuery->latest()->get();
        
        // Get shifts - Mengikuti pola dari PemeriksaanKedatanganChemicalController
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
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
            'collection_date_from' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'collection_date_to' => 'required|string|regex:/^\d{4}-\d{2}$/',
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
            'collection_date_from' => $request->collection_date_from,
            'collection_date_to' => $request->collection_date_to,
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
            $query->where('id', $user->id_plant);
        }
        $plants = $query->get();
        
        // Get deskripsis untuk multiple select
        $deskripsiQuery = InputDeskripsi::query();
        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $deskripsiQuery->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $deskripsis = $deskripsiQuery->latest()->get();
        
        return view('qc-sistem.golden-sample-retort.edit', compact('goldenSampleReport', 'plants', 'deskripsis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GoldenSampleReport $goldenSampleReport)
    {
        $this->checkPlantAccess($goldenSampleReport);
        
        $request->validate([
            'id_plant' => 'required|string',
            'plant_manual' => 'nullable|string|max:255|required_if:id_plant,other',
            'sample_type' => 'required|string|max:255',
            'collection_date_from' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'collection_date_to' => 'required|string|regex:/^\d{4}-\d{2}$/',
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
            'plant_manual' => $request->id_plant === 'other' ? $request->plant_manual : null,
            'sample_type' => $request->sample_type,
            'collection_date_from' => $request->collection_date_from,
            'tanggal' => $request->tanggal,
            'collection_date_to' => $request->collection_date_to,
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
        if ($goldenSampleReport->user->id_plant !== $user->id_plant) {
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
                    $q->where('id_plant', $user->id_plant);
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
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Report ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}