<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKebersihanArea;
use App\Models\PemeriksaanKebersihanAreaDetail;
use App\Models\InputMasterForm;
use App\Models\InputArea;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanKebersihanAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanKebersihanArea::with(['user.role', 'user.plant', 'shift', 'area', 'masterForm'])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $pemeriksaans = PemeriksaanKebersihanArea::with(['user.role', 'user.plant', 'shift', 'area', 'masterForm'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }
        
        return view('qc-sistem.pemeriksaan-kebersihan-area.index', compact('pemeriksaans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $masterForms = InputMasterForm::all();
        $areas = InputArea::all();
        $shifts = Shift::all();
        
        return view('qc-sistem.pemeriksaan-kebersihan-area.create', compact('masterForms', 'areas', 'shifts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_master_form' => 'required|exists:input_master_forms,id',
            'id_area' => 'required|exists:input_areas,id',
            'id_shift' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'jam_sebelum_proses' => 'nullable|date_format:H:i',
            'jam_saat_proses' => 'nullable|date_format:H:i',
        ]);

        // Create pemeriksaan
        $pemeriksaan = PemeriksaanKebersihanArea::create([
            'id_user' => Auth::id(),
            'id_shift' => $request->id_shift,
            'id_area' => $request->id_area,
            'id_master_form' => $request->id_master_form,
            'tanggal' => $request->tanggal,
            'jam_sebelum_proses' => $request->jam_sebelum_proses,
            'jam_saat_proses' => $request->jam_saat_proses,
        ]);

        // Create details for each field
        $masterForm = InputMasterForm::find($request->id_master_form);
        foreach ($masterForm->fields as $field) {
            $statusKey = 'field_status_' . $field->id;
            $keteranganKey = 'field_keterangan_' . $field->id;
            $tindakanKey = 'field_tindakan_' . $field->id;
            
            PemeriksaanKebersihanAreaDetail::create([
                'id_pemeriksaan' => $pemeriksaan->id,
                'id_master_form_field' => $field->id,
                'status' => $request->has($statusKey) ? (int)$request->input($statusKey) : null,
                'keterangan' => $request->input($keteranganKey),
                'tindakan_koreksi' => $request->input($tindakanKey),
            ]);
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
            'jam_sebelum_proses' => 'nullable|',
            'jam_saat_proses' => 'nullable|',
        ]);

        // Update pemeriksaan
        $pemeriksaanKebersihanArea->update([
            'tanggal' => $request->tanggal,
            'jam_sebelum_proses' => $request->jam_sebelum_proses,
            'jam_saat_proses' => $request->jam_saat_proses,
        ]);

        // Update details
        foreach ($pemeriksaanKebersihanArea->details as $detail) {
            $statusKey = 'status_' . $detail->id;
            $keteranganKey = 'keterangan_' . $detail->id;
            $tindakanKey = 'tindakan_koreksi_' . $detail->id;

            $detail->update([
                'status' => $request->has($statusKey) ? (bool)$request->input($statusKey) : null,
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
        if ($pemeriksaanKebersihanArea->user->id_plant !== $user->id_plant) {
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
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}