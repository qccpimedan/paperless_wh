<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanLoadingProduk;
use App\Models\Shift;
use App\Models\TujuanPengiriman;
use App\Models\JenisKendaraan;
use App\Models\Supir;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanLoadingProdukController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanLoadingProduk::with([
                'user.role', 
                'user.plant', 
                'shift', 
                'tujuanPengiriman', 
                'kendaraan', 
                'supir', 
                'produk'
            ])
            ->latest()
            ->paginate(10);
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $pemeriksaans = PemeriksaanLoadingProduk::with([
                'user.role', 
                'user.plant', 
                'shift', 
                'tujuanPengiriman', 
                'kendaraan', 
                'supir', 
                'produk'
            ])
            ->whereHas('user', function($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })
            ->latest()
            ->paginate(10);
        }

        return view('qc-sistem.pemeriksaan-loading-produk.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $tujuanPengirimans = TujuanPengiriman::with(['user.plant'])->get();
            $kendaraans = JenisKendaraan::with(['user.plant'])->get();
            $supirs = Supir::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $tujuanPengirimans = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $supirs = Supir::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $produks = Produk::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        return view('qc-sistem.pemeriksaan-loading-produk.create', compact(
            'shifts', 
            'tujuanPengirimans', 
            'kendaraans', 
            'supirs', 
            'produks'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_tujuan_pengiriman' => 'nullable|exists:tujuan_pengirimen,id',
            'id_kendaraan' => 'nullable',
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_supir' => 'nullable|exists:supirs,id',
            'no_po' => 'nullable|string|max:255',
            'star_loading' => 'nullable',
            'selesai_loading' => 'nullable',
            'temperature_mobil' => 'nullable|string|max:255',
            'temperature_produk' => 'nullable|array',
            'temperature_produk.*' => 'nullable|string|max:255',
            'kondisi_produk' => 'nullable|in:Frozen,Fresh,Dry',
            'segel_gembok' => 'nullable|boolean',
            'no_segel' => 'nullable|string|max:255',
            'produk_data' => 'nullable|array|min:1',
            'produk_data.*.id_produk' => 'nullable|exists:produks,id',
            'produk_data.*.kode_produksi' => 'nullable|string|max:255',
            'produk_data.*.best_before' => 'nullable|date',
            'produk_data.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_data.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_data.*.kondisi_kemasan' => 'nullable|boolean',
            'produk_data.*.keterangan' => 'nullable|string|max:500',
        ]);

        // Cek apakah kendaraan diinput manual
        if ($request->id_kendaraan === 'other') {
            // Jika input manual diisi
            if ($request->jenis_kendaraan_manual && $request->no_kendaraan_manual) {
                // Buat record baru di tabel master
                $kendaraan = JenisKendaraan::create([
                    'jenis_kendaraan' => $request->jenis_kendaraan_manual,
                    'no_kendaraan' => $request->no_kendaraan_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_kendaraan'] = $kendaraan->id;
            } else {
                // Jika input manual tidak diisi, set id_kendaraan ke null
                $validated['id_kendaraan'] = null;
            }
        }

        // Process temperature_produk array
        $temperatureProduk = [];
        if ($request->has('temperature_produk')) {
            foreach ($request->temperature_produk as $temp) {
                if (!empty($temp)) {
                    $temperatureProduk[] = $temp;
                }
            }
        }

        $validated['id_user'] = Auth::id();
        $validated['segel_gembok'] = $request->has('segel_gembok');
        $validated['temperature_produk'] = !empty($temperatureProduk) ? $temperatureProduk : null;
        
        // Process produk_data array untuk multiple produk
        $produkData = [];
        if ($request->has('produk_data')) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'id_produk' => $produk['id_produk'],
                        'kode_produksi' => $produk['kode_produksi'] ?? null,
                        'best_before' => $produk['best_before'] ?? null,
                        'jumlah_kemasan' => $produk['jumlah_kemasan'] ?? null,
                        'jumlah_sampling' => $produk['jumlah_sampling'] ?? null,
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : true,
                        'keterangan' => $produk['keterangan'] ?? null,
                    ];
                }
            }
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;

        PemeriksaanLoadingProduk::create($validated);

        return redirect()->route('pemeriksaan-loading-produk.index')
            ->with('success', 'Data pemeriksaan loading produk berhasil ditambahkan.');
    }

    public function show(PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $pemeriksaan_loading_produk->load([
            'user.plant', 
            'shift', 
            'tujuanPengiriman', 
            'kendaraan', 
            'supir', 
            'produk'
        ]);
        
        return view('qc-sistem.pemeriksaan-loading-produk.show', [
            'pemeriksaanLoading' => $pemeriksaan_loading_produk
        ]);
    }

    public function edit(PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $user = Auth::user();
        
        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $tujuanPengirimans = TujuanPengiriman::with(['user.plant'])->get();
            $kendaraans = JenisKendaraan::with(['user.plant'])->get();
            $supirs = Supir::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $tujuanPengirimans = TujuanPengiriman::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $kendaraans = JenisKendaraan::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $supirs = Supir::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
            
            $produks = Produk::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        return view('qc-sistem.pemeriksaan-loading-produk.edit', [
            'pemeriksaanLoading' => $pemeriksaan_loading_produk,
            'shifts' => $shifts,
            'tujuanPengirimans' => $tujuanPengirimans,
            'kendaraans' => $kendaraans,
            'supirs' => $supirs,
            'produks' => $produks
        ]);
    }
    public function update(Request $request, PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_tujuan_pengiriman' => 'nullable|exists:tujuan_pengirimen,id',
            'id_kendaraan' => 'nullable',
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_supir' => 'nullable|exists:supirs,id',
            'no_po' => 'nullable|string|max:255',
            'star_loading' => 'nullable',
            'selesai_loading' => 'nullable',
            'temperature_mobil' => 'nullable|string|max:255',
            'temperature_produk' => 'nullable|array',
            'temperature_produk.*' => 'nullable|string|max:255',
            'kondisi_produk' => 'nullable|in:Frozen,Fresh,Dry',
            'segel_gembok' => 'nullable|boolean',
            'no_segel' => 'nullable|string|max:255',
            'produk_data' => 'nullable|array|min:1',
            'produk_data.*.id_produk' => 'nullable|exists:produks,id',
            'produk_data.*.kode_produksi' => 'nullable|string|max:255',
            'produk_data.*.best_before' => 'nullable|date',
            'produk_data.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_data.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_data.*.kondisi_kemasan' => 'nullable|boolean',
            'produk_data.*.keterangan' => 'nullable|string|max:500',
        ]);

        // Cek apakah kendaraan diinput manual
        if ($request->id_kendaraan === 'other') {
            // Jika input manual diisi
            if ($request->jenis_kendaraan_manual && $request->no_kendaraan_manual) {
                // Buat record baru di tabel master
                $kendaraan = JenisKendaraan::create([
                    'jenis_kendaraan' => $request->jenis_kendaraan_manual,
                    'no_kendaraan' => $request->no_kendaraan_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_kendaraan'] = $kendaraan->id;
            } else {
                // Jika input manual tidak diisi, set id_kendaraan ke null
                $validated['id_kendaraan'] = null;
            }
        }

        // Process temperature_produk array
        $temperatureProduk = [];
        if ($request->has('temperature_produk')) {
            foreach ($request->temperature_produk as $temp) {
                if (!empty($temp)) {
                    $temperatureProduk[] = $temp;
                }
            }
        }

        $validated['temperature_produk'] = !empty($temperatureProduk) ? $temperatureProduk : null;
        
        // Process produk_data array untuk multiple produk
        $produkData = [];
        if ($request->has('produk_data')) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'id_produk' => $produk['id_produk'],
                        'kode_produksi' => $produk['kode_produksi'] ?? null,
                        'best_before' => $produk['best_before'] ?? null,
                        'jumlah_kemasan' => $produk['jumlah_kemasan'] ?? null,
                        'jumlah_sampling' => $produk['jumlah_sampling'] ?? null,
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : true,
                        'keterangan' => $produk['keterangan'] ?? null,
                    ];
                }
            }
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;
        
        // Hapus keterangan dari validated karena sudah di produk_data
        unset($validated['keterangan']);

        $pemeriksaan_loading_produk->update($validated);

        return redirect()->route('pemeriksaan-loading-produk.index')
            ->with('success', 'Data pemeriksaan loading produk berhasil diupdate.');
    }
    public function destroy(PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $pemeriksaan_loading_produk->delete();

        return redirect()->route('pemeriksaan-loading-produk.index')
            ->with('success', 'Data pemeriksaan loading produk berhasil dihapus.');
    }
}