<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganChemical;
use App\Models\Shift;
use App\Models\Chemical;
use App\Models\Produsen;
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
            $pemeriksaans = PemeriksaanKedatanganChemical::with(['user.role', 'user.plant', 'chemical', 'produsen', 'distributor', 'shift'])
                ->latest()
                ->paginate(10);
        } else {
            $pemeriksaans = PemeriksaanKedatanganChemical::with(['user.role', 'user.plant', 'chemical', 'produsen', 'distributor', 'shift'])
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
            'id_chemical' => 'nullable|exists:chemicals,id',
            'id_produsen' => 'nullable|exists:produsens,id',
            'negara_produsen' => 'nullable|string|max:255',
            'id_distributor' => 'nullable|exists:distributors,id',
            'kode_produksi' => 'nullable|string|max:255',
            'expire_date' => 'nullable|date',
            'kondisi_chemical' => 'nullable|in:Cair,Serbuk',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'status' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
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

        // Process kondisi fisik (2 items)
        $kondisiFisik = [
            'kemasan' => $request->input('kondisi_fisik.kemasan') === '1',
            'warna' => $request->input('kondisi_fisik.warna') === '1',
        ];

        $data = $request->all();
        $data['id_user'] = Auth::id();
        $data['segel_gembok'] = $request->input('segel_gembok');
        $data['persyaratan_dokumen_halal'] = $request->input('persyaratan_dokumen_halal') === '1';
        $data['coa'] = $request->input('coa') === '1';
        $data['kondisi_mobil'] = $kondisiMobil;
        $data['kondisi_fisik'] = $kondisiFisik;

        PemeriksaanKedatanganChemical::create($data);

        return redirect()->route('pemeriksaan-chemical.index')
            ->with('success', 'Data pemeriksaan kedatangan chemical berhasil ditambahkan!');
    }

    public function show(PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $this->checkPlantAccess($pemeriksaanChemical);
        
        $pemeriksaanChemical->load(['user.plant', 'shift', 'chemical', 'produsen', 'distributor']);
        
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
            'id_chemical' => 'nullable|exists:chemicals,id',
            'id_produsen' => 'nullable|exists:produsens,id',
            'negara_produsen' => 'nullable|string|max:255',
            'id_distributor' => 'nullable|exists:distributors,id',
            'kode_produksi' => 'nullable|string|max:255',
            'expire_date' => 'nullable|date',
            'kondisi_chemical' => 'nullable|in:Cair,Serbuk',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'status' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
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

        // Process kondisi fisik (2 items)
        $kondisiFisik = [
            'kemasan' => $request->input('kondisi_fisik.kemasan') === '1',
            'warna' => $request->input('kondisi_fisik.warna') === '1',
        ];

        $data = $request->all();
        $data['segel_gembok'] = $request->input('segel_gembok');
        $data['persyaratan_dokumen_halal'] = $request->input('persyaratan_dokumen_halal') === '1';
        $data['coa'] = $request->input('coa') === '1';
        $data['kondisi_mobil'] = $kondisiMobil;
        $data['kondisi_fisik'] = $kondisiFisik;

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
        $pemeriksaanChemical->update(['status_verifikasi' => 'sent_to_produksi', 'verified_by' => $user->id, 'verified_at' => now()]);
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        $pemeriksaanChemical->update(['status_verifikasi' => 'approved_produksi', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
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
        $pemeriksaanChemical->update(['status_verifikasi' => 'rejected_produksi', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanKedatanganChemical $pemeriksaanChemical)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanChemical);
        if ($pemeriksaanChemical->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        $pemeriksaanChemical->update(['status_verifikasi' => 'approved_spv', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
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
        $pemeriksaanChemical->update(['status_verifikasi' => 'rejected_spv', 'verified_by' => $user->id, 'verified_at' => now(), 'verification_notes' => $request->input('notes')]);
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}