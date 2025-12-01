<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanKedatanganBahanBakuPenunjang;
use App\Models\Shift;
use App\Models\Bahan;
use App\Models\Produsen;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monarobase\CountryList\CountryListFacade as Countries; // Menggunakan Facade


class PemeriksaanKedatanganBahanBakuPenunjangController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanKedatanganBahanBakuPenunjang::with(['user.role', 'user.plant', 'bahan', 'shift'])
                ->latest()
                ->paginate(10);
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $pemeriksaans = PemeriksaanKedatanganBahanBakuPenunjang::with(['user.role', 'user.plant', 'bahan', 'shift'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
                })
                ->latest()
                ->paginate(10);
        }

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $bahans = Bahan::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::all();
            $distributors = Distributor::all();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            
            $bahans = Bahan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
        }

        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.create', compact('shifts', 'bahans', 'countries', 'produsens', 'distributors'));
    }

    private function checkPlantAccess($pemeriksaan)
    {
        $user = Auth::user();
        
        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            if ($pemeriksaan->user->id_plant !== $user->id_plant) { // ✅ GUNAKAN id_plant
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
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'suhu_mobil' => 'nullable|string|max:255',
            'suhu_mobil_type' => 'nullable|in:Fresh,Frozen',
            'kondisi_produk' => 'nullable|string|max:255',
            'suhu_produk' => 'nullable|string|max:255',
            'suhu_produk_type' => 'nullable|in:Fresh,Frozen',
            'kondisi_produk_suhu' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'produsen' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|string|max:255',
            'distributor' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|string|max:255',
            'expire_date' => 'nullable|date',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'hasil_uji_ffa' => 'nullable|string|max:255',
            'status' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_bahan' => 'nullable|exists:bahans,id',
        ]);

        // Process kondisi mobil dan fisik dengan logic yang benar
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

        $kondisiFisik = [
            'kemasan' => $request->input('kondisi_fisik.kemasan') === '1',
            'warna' => $request->input('kondisi_fisik.warna') === '1',
            'benda_asing' => $request->input('kondisi_fisik.benda_asing') === '1',
            'aroma' => $request->input('kondisi_fisik.aroma') === '1',
        ];

        $data = $request->all();
        $data['id_user'] = Auth::id();
        $data['segel_gembok'] = $request->has('segel_gembok');
        $data['logo_halal'] = $request->input('logo_halal') === '1';
        $data['dokumen_halal'] = $request->input('dokumen_halal') === '1';
        $data['coa'] = $request->input('coa') === '1';
        $data['kondisi_mobil'] = $kondisiMobil;
        $data['kondisi_fisik'] = $kondisiFisik;

        PemeriksaanKedatanganBahanBakuPenunjang::create($data);

        return redirect()->route('pemeriksaan-bahan-baku.index')
            ->with('success', 'Data pemeriksaan kedatangan bahan baku penunjang berhasil ditambahkan!');
    }

    public function show(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $pemeriksaanBahanBaku->load(['user.plant', 'shift', 'bahan']);
        
        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.show', compact('pemeriksaanBahanBaku'));
    }

    public function edit(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $user = Auth::user();
        
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $bahans = Bahan::with(['user.plant'])->get();
            $countries = Countries::getList('en', 'php');
            $produsens = Produsen::all();
            $distributors = Distributor::all();
        } else {
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            
            $bahans = Bahan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->with(['user.plant'])->get();
            $produsens = Produsen::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
            $distributors = Distributor::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant); // ✅ GUNAKAN id_plant
            })->get();
            $countries = Countries::getList('en', 'php');
        }
    
        return view('qc-sistem.pemeriksaan-kedatangan-bahan-baku-penunjang.edit', compact('pemeriksaanBahanBaku', 'shifts', 'bahans', 'produsens', 'distributors', 'countries'));
    }

    public function update(Request $request, PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'jenis_pemeriksaan' => 'nullable|string|max:255',
            'no_po' => 'nullable|string|max:255',
            'suhu_mobil' => 'nullable|string|max:255',
            'suhu_mobil_type' => 'nullable|in:Fresh,Frozen',
            'kondisi_produk' => 'nullable|string|max:255',
            'suhu_produk' => 'nullable|string|max:255',
            'suhu_produk_type' => 'nullable|in:Fresh,Frozen',
            'kondisi_produk_suhu' => 'nullable|string|max:255',
            'spesifikasi' => 'nullable|string',
            'produsen' => 'nullable|string|max:255',
            'negara_produsen' => 'nullable|string|max:255',
            'distributor' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|string|max:255',
            'expire_date' => 'nullable|date',
            'jumlah_datang' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|string|max:255',
            'hasil_uji_ffa' => 'nullable|string|max:255',
            'status' => 'required|in:Release,Hold',
            'keterangan' => 'nullable|string',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_bahan' => 'nullable|exists:bahans,id',
        ]);

        // Process kondisi mobil dan fisik dengan logic yang benar
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

        $kondisiFisik = [
            'kemasan' => $request->input('kondisi_fisik.kemasan') === '1',
            'warna' => $request->input('kondisi_fisik.warna') === '1',
            'benda_asing' => $request->input('kondisi_fisik.benda_asing') === '1',
            'aroma' => $request->input('kondisi_fisik.aroma') === '1',
        ];

        $data = $request->all();
        $data['segel_gembok'] = $request->has('segel_gembok');
        $data['logo_halal'] = $request->input('logo_halal') === '1';
        $data['dokumen_halal'] = $request->input('dokumen_halal') === '1';
        $data['coa'] = $request->input('coa') === '1';
        $data['kondisi_mobil'] = $kondisiMobil;
        $data['kondisi_fisik'] = $kondisiFisik;

        $pemeriksaanBahanBaku->update($data);

        return redirect()->route('pemeriksaan-bahan-baku.index')
        ->with('success', 'Data pemeriksaan kedatangan bahan baku penunjang berhasil diupdate!');
    }

    public function destroy(PemeriksaanKedatanganBahanBakuPenunjang $pemeriksaanBahanBaku)
    {
        $this->checkPlantAccess($pemeriksaanBahanBaku);
        
        $pemeriksaanBahanBaku->delete();

        return redirect()->route('pemeriksaan-bahan-baku.index')
        ->with('success', 'Data pemeriksaan kedatangan bahan baku penunjang berhasil dihapus!');
    }
}