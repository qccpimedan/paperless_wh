<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganChemical;
use App\Models\Shift;
use App\Models\Chemical;
use App\Models\Produsen;
use App\Models\User;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monarobase\CountryList\CountryListFacade as Countries;

class PemeriksaanKedatanganChemicalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanKedatanganChemical::with(['user.role', 'user.plant', 'shift'])
                ->latest()
                ->paginate(10);
        } else {
            $pemeriksaans = PemeriksaanKedatanganChemical::with(['user.role', 'user.plant', 'shift'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->paginate(10);
        }

        return view('qc-sistem.pemeriksaan-kedatangan-chemical.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $chemicals = Chemical::with(['user.plant'])->get();
            $produsens = Produsen::with(['user.plant'])->get();
            $distributors = Distributor::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $chemicals = Chemical::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $countries = Countries::getList('en', 'php');
        }

        return view('qc-sistem.pemeriksaan-kedatangan-chemical.create', compact('shifts', 'chemicals', 'produsens', 'distributors', 'countries'));
    }

    private function checkPlantAccess($pemeriksaan)
    {
        $user = Auth::user();
        
        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            if ($pemeriksaan->user->id_plant !== $user->id_plant) {
                abort(403, 'Unauthorized access to different plant data.');
            }
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'id_shift' => 'nullable|exists:shifts,id',
            // Validasi array untuk dynamic rows
            'id_chemical' => 'required|array',
            'id_chemical.*' => 'required|exists:chemicals,id',
            'kondisi_chemical' => 'nullable|array',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'negara_produsen' => 'nullable|array',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
            'kode_produksi' => 'nullable|array',
            'expire_date' => 'nullable|array',
            'jumlah_datang' => 'nullable|array',
            'jumlah_sampling' => 'nullable|array',
            'kondisi_fisik_kemasan' => 'nullable|array',
            'kondisi_fisik_warna' => 'nullable|array',
            'persyaratan_dokumen_halal' => 'nullable|array',
            'coa' => 'nullable|array',
            'status_baris' => 'required|array',
            'status_baris.*' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|array',
        ]);

        // Process kondisi mobil (11 items)
        $kondisiMobil = [
            'bersih' => $request->input('kondisi_mobil.bersih') === '1',
            'bebas_hama' => $request->input('kondisi_mobil.bebas_hama') === '1',
            'tidak_kondensasi' => $request->input('kondisi_mobil.tidak_kondensasi') === '1',
            'bebas_produk_halal' => $request->input('kondisi_mobil.bebas_produk_halal') === '1',
            'tidak_berbau' => $request->input('kondisi_mobil.tidak_berbau') === '1',
            'tidak_ada_sampah' => $request->input('kondisi_mobil.tidak_ada_sampah') === '1',
            'tidak_ada_mikroba' => $request->input('kondisi_mobil.tidak_ada_mikroba') === '1',
            'lampu_cover_utuh' => $request->input('kondisi_mobil.lampu_cover_utuh') === '1',
            'pallet_utuh' => $request->input('kondisi_mobil.pallet_utuh') === '1',
            'tertutup_rapat' => $request->input('kondisi_mobil.tertutup_rapat') === '1',
            'bebas_kontaminan' => $request->input('kondisi_mobil.bebas_kontaminan') === '1',
        ];

        // Process detail chemicals dari dynamic rows
        $detailChemicals = [];
        $idChemicals = $request->input('id_chemical', []);
        
        foreach ($idChemicals as $index => $idChemical) {
            $detailChemicals[] = [
                'id_chemical' => $idChemical,
                'kondisi_chemical' => $request->input('kondisi_chemical.' . $index),
                'id_produsen' => $request->input('id_produsen.' . $index),
                'negara_produsen' => $request->input('negara_produsen.' . $index),
                'id_distributor' => $request->input('id_distributor.' . $index),
                'kode_produksi' => $request->input('kode_produksi.' . $index),
                'expire_date' => $request->input('expire_date.' . $index),
                'jumlah_datang' => $request->input('jumlah_datang.' . $index),
                'jumlah_sampling' => $request->input('jumlah_sampling.' . $index),
                'kondisi_fisik' => [
                    'kemasan' => $request->input('kondisi_fisik_kemasan.' . $index) === '1',
                    'warna' => $request->input('kondisi_fisik_warna.' . $index) === '1',
                ],
                'persyaratan_dokumen_halal' => $request->input('persyaratan_dokumen_halal.' . $index) === '1',
                'coa' => $request->input('coa.' . $index) === '1',
                'status' => $request->input('status_baris.' . $index),
                'keterangan' => $request->input('keterangan.' . $index),
            ];
        }

        // Create data
        $data = [
            'id_user' => Auth::id(),
            'id_shift' => $request->input('id_shift'),
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => $kondisiMobil,
            'detail_chemicals' => $detailChemicals,
        ];

        PemeriksaanKedatanganChemical::create($data);

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Data pemeriksaan kedatangan chemical berhasil ditambahkan!');
    }

    public function show(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        // Load hanya relasi yang masih digunakan (tidak ada relasi chemical, produsen, distributor lagi)
        $pemeriksaanChemical->load(['user.plant', 'shift']);
        
        return view('qc-sistem.pemeriksaan-kedatangan-chemical.show', compact('pemeriksaanChemical'));
    }

    public function edit(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $chemicals = Chemical::with(['user.plant'])->get();
            $produsens = Produsen::with(['user.plant'])->get();
            $distributors = Distributor::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $chemicals = Chemical::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }
        
        $countries = Countries::getList('en', 'php');

        return view('qc-sistem.pemeriksaan-kedatangan-chemical.edit', compact('pemeriksaanChemical', 'shifts', 'chemicals', 'produsens', 'distributors', 'countries'));
    }

    public function update(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'id_shift' => 'nullable|exists:shifts,id',
            // Validasi array untuk dynamic rows
            'id_chemical' => 'required|array',
            'id_chemical.*' => 'required|exists:chemicals,id',
            'kondisi_chemical' => 'nullable|array',
            'id_produsen' => 'nullable|array',
            'id_produsen.*' => 'nullable|exists:produsens,id',
            'negara_produsen' => 'nullable|array',
            'id_distributor' => 'nullable|array',
            'id_distributor.*' => 'nullable|exists:distributors,id',
            'kode_produksi' => 'nullable|array',
            'expire_date' => 'nullable|array',
            'jumlah_datang' => 'nullable|array',
            'jumlah_sampling' => 'nullable|array',
            'kondisi_fisik_kemasan' => 'nullable|array',
            'kondisi_fisik_warna' => 'nullable|array',
            'persyaratan_dokumen_halal' => 'nullable|array',
            'coa' => 'nullable|array',
            'status_baris' => 'required|array',
            'status_baris.*' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|array',
        ]);

        // Process kondisi mobil (11 items)
        $kondisiMobil = [
            'bersih' => $request->input('kondisi_mobil.bersih') === '1',
            'bebas_hama' => $request->input('kondisi_mobil.bebas_hama') === '1',
            'tidak_kondensasi' => $request->input('kondisi_mobil.tidak_kondensasi') === '1',
            'bebas_produk_halal' => $request->input('kondisi_mobil.bebas_produk_halal') === '1',
            'tidak_berbau' => $request->input('kondisi_mobil.tidak_berbau') === '1',
            'tidak_ada_sampah' => $request->input('kondisi_mobil.tidak_ada_sampah') === '1',
            'tidak_ada_mikroba' => $request->input('kondisi_mobil.tidak_ada_mikroba') === '1',
            'lampu_cover_utuh' => $request->input('kondisi_mobil.lampu_cover_utuh') === '1',
            'pallet_utuh' => $request->input('kondisi_mobil.pallet_utuh') === '1',
            'tertutup_rapat' => $request->input('kondisi_mobil.tertutup_rapat') === '1',
            'bebas_kontaminan' => $request->input('kondisi_mobil.bebas_kontaminan') === '1',
        ];

        // Process detail chemicals dari dynamic rows
        $detailChemicals = [];
        $idChemicals = $request->input('id_chemical', []);
        
        foreach ($idChemicals as $index => $idChemical) {
            $detailChemicals[] = [
                'id_chemical' => $idChemical,
                'kondisi_chemical' => $request->input('kondisi_chemical.' . $index),
                'id_produsen' => $request->input('id_produsen.' . $index),
                'negara_produsen' => $request->input('negara_produsen.' . $index),
                'id_distributor' => $request->input('id_distributor.' . $index),
                'kode_produksi' => $request->input('kode_produksi.' . $index),
                'expire_date' => $request->input('expire_date.' . $index),
                'jumlah_datang' => $request->input('jumlah_datang.' . $index),
                'jumlah_sampling' => $request->input('jumlah_sampling.' . $index),
                'kondisi_fisik' => [
                    'kemasan' => $request->input('kondisi_fisik_kemasan.' . $index) === '1',
                    'warna' => $request->input('kondisi_fisik_warna.' . $index) === '1',
                ],
                'persyaratan_dokumen_halal' => $request->input('persyaratan_dokumen_halal.' . $index) === '1',
                'coa' => $request->input('coa.' . $index) === '1',
                'status' => $request->input('status_baris.' . $index),
                'keterangan' => $request->input('keterangan.' . $index),
            ];
        }

        // Update data
        $data = [
            'id_shift' => $request->input('id_shift'),
            'tanggal' => $request->input('tanggal'),
            'jenis_mobil' => $request->input('jenis_mobil'),
            'no_mobil' => $request->input('no_mobil'),
            'nama_supir' => $request->input('nama_supir'),
            'segel_gembok' => $request->input('segel_gembok'),
            'no_segel' => $request->input('no_segel'),
            'kondisi_mobil' => $kondisiMobil,
            'detail_chemicals' => $detailChemicals,
        ];

        $pemeriksaanChemical->update($data);

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Data pemeriksaan kedatangan chemical berhasil diupdate!');
    }

    public function destroy(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        $pemeriksaanChemical->delete();

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Data pemeriksaan kedatangan chemical berhasil dihapus!');
    }

    public function sendToProduksi(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now()
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
        ]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        $pemeriksaanChemical->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes')
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

        $query = PemeriksaanKedatanganChemical::with([
            'user.role', 
            'user.plant', 
            'chemical', 
            'shift', 
            'verifiedBy.role'
        ])->with([
            'qcVerifier' => function($q) {
                $q->select('id', 'name');
            },
            'produksiVerifier' => function($q) {
                $q->select('id', 'name');
            },
            'spvVerifier' => function($q) {
                $q->select('id', 'name');
            }
        ]);

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        if ($id_shift) {
            $query->where('id_shift', $id_shift);
        }

        // Filter tanggal berdasarkan shift
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
                // Shift 2/3 dan lainnya: single date
                if ($tanggal) {
                    $query->whereDate('tanggal', $tanggal);
                }
            }
        } else {
            // Jika shift tidak dipilih, fallback single date jika ada
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
        
        if($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if($qcUserData) {
                $qcUser = $qcUserData->name;
            }
        }
        
        if($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if($produksiUserData) {
                $produksiUser = $produksiUserData->name;
            }
        }
        
        if($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if($spvUserData) {
                $spvQcUser = $spvUserData->name;
            }
        }

        $pdf = \PDF::loadView('qc-sistem.pemeriksaan-kedatangan-chemical.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-chemical-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }
}