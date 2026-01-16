<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanLoadingProduk;
use App\Models\Shift;
use App\Models\TujuanPengiriman;
use App\Models\JenisKendaraan;
use App\Models\Supir;
use App\Models\Customer;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
            $tujuanPengirimans = TujuanPengiriman::with(['user.plant', 'customer'])->get();
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
            })->with(['user.plant', 'customer'])->get();
            
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

        $produkKategoriOptions = $produks
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkByKategori = $produks
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            })
            ->all();

        $produkKategoriById = $produks
            ->pluck('kategori_code', 'id')
            ->all();

        return view('qc-sistem.pemeriksaan-loading-produk.create', compact(
            'shifts', 
            'tujuanPengirimans', 
            'kendaraans', 
            'supirs', 
            'produks',
            'produkKategoriOptions',
            'produkByKategori',
            'produkKategoriById'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_tujuan_pengiriman' => [
                'nullable',
                Rule::when(
                    $request->input('id_tujuan_pengiriman') !== 'other',
                    Rule::exists('tujuan_pengirimen', 'id')
                ),
            ],
            'nama_customer_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'nama_tujuan_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'id_kendaraan' => 'nullable',
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_supir' => [
                'nullable',
                Rule::when(
                    $request->input('id_supir') !== 'other',
                    Rule::exists('supirs', 'id')
                ),
            ],
            'nama_supir_manual' => 'nullable|required_if:id_supir,other|string|max:255',
            'star_loading' => 'nullable',
            'selesai_loading' => 'nullable',
            'temperature_mobil' => 'nullable|string|max:255',
            'temperature_produk' => 'nullable|array',
            'temperature_produk.*' => 'nullable|string|max:255',
            'kondisi_produk' => 'nullable|in:Frozen,Fresh,Dry',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
            'id_produk' => 'required|exists:produks,id',
            'produk_detail' => 'nullable|array|min:1',
            'produk_detail.*.kode_produksi' => 'nullable|string|max:255',
            'produk_detail.*.best_before' => 'nullable|date',
            'produk_detail.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_detail.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_detail.*.berat_perkarung' => 'nullable|string|max:255',
            'produk_detail.*.kondisi_kemasan' => 'nullable|boolean',
            'produk_detail.*.keterangan' => 'nullable|string',
            'produk_data' => 'nullable|array|min:1',
            'produk_data.*.id_produk' => 'nullable|exists:produks,id',
            'produk_data.*.kode_produksi' => 'nullable|string|max:255',
            'produk_data.*.best_before' => 'nullable|date',
            'produk_data.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_data.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_data.*.berat_perkarung' => 'nullable|string|max:255',
            'produk_data.*.kondisi_kemasan' => 'nullable|boolean',
            'produk_data.*.keterangan' => 'nullable|string',
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

        if ($request->id_tujuan_pengiriman === 'other') {
            if ($request->nama_customer_manual && $request->nama_tujuan_manual) {
                $customer = Customer::create([
                    'nama_cust' => $request->nama_customer_manual,
                    'id_user' => Auth::id(),
                ]);

                $tujuan = TujuanPengiriman::create([
                    'id_user' => Auth::id(),
                    'id_customer' => $customer->id,
                    'nama_tujuan' => $request->nama_tujuan_manual,
                ]);

                $validated['id_tujuan_pengiriman'] = $tujuan->id;
            } else {
                $validated['id_tujuan_pengiriman'] = null;
            }
        }

        if ($request->id_supir === 'other') {
            if ($request->nama_supir_manual) {
                $supir = Supir::create([
                    'nama_supir' => $request->nama_supir_manual,
                    'id_user' => Auth::id(),
                ]);

                $validated['id_supir'] = $supir->id;
            } else {
                $validated['id_supir'] = null;
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
        $validated['segel_gembok'] = $request->input('segel_gembok') === 'segel';
        $validated['temperature_produk'] = !empty($temperatureProduk) ? $temperatureProduk : null;
        
        // Process product data
        // Priority: opsi 2 (single id_produk select + produk_data[*] rows with hidden id_produk)
        $produkData = [];
        if ($request->has('produk_data') && is_array($request->produk_data)) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'id_produk' => $produk['id_produk'],
                        'kode_produksi' => $produk['kode_produksi'] ?? null,
                        'best_before' => $produk['best_before'] ?? null,
                        'jumlah_kemasan' => $produk['jumlah_kemasan'] ?? null,
                        'jumlah_sampling' => $produk['jumlah_sampling'] ?? null,
                        'berat_perkarung' => $produk['berat_perkarung'] ?? null,
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : true,
                        'keterangan' => $produk['keterangan'] ?? null,
                    ];
                }
            }
        } elseif ($request->filled('id_produk') && $request->has('produk_detail') && is_array($request->produk_detail)) {
            // Backward compatibility: opsi 1 (id_produk + produk_detail[])
            foreach ($request->produk_detail as $detail) {
                $produkData[] = [
                    'id_produk' => $request->id_produk,
                    'kode_produksi' => $detail['kode_produksi'] ?? null,
                    'best_before' => $detail['best_before'] ?? null,
                    'jumlah_kemasan' => $detail['jumlah_kemasan'] ?? null,
                    'jumlah_sampling' => $detail['jumlah_sampling'] ?? null,
                    'berat_perkarung' => $detail['berat_perkarung'] ?? null,
                    'kondisi_kemasan' => isset($detail['kondisi_kemasan']) ? (bool)$detail['kondisi_kemasan'] : true,
                    'keterangan' => $detail['keterangan'] ?? null,
                ];
            }
        }

        if (empty($produkData)) {
            return back()
                ->withErrors(['id_produk' => 'Data produk wajib diisi (minimal 1 detail).'])
                ->withInput();
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
            'tujuanPengiriman.customer', 
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
            $tujuanPengirimans = TujuanPengiriman::with(['user.plant', 'customer'])->get();
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
            })->with(['user.plant', 'customer'])->get();
            
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

        $produkKategoriOptions = $produks
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $produkByKategori = $produks
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            })
            ->all();

        $produkKategoriById = $produks
            ->pluck('kategori_code', 'id')
            ->all();

        return view('qc-sistem.pemeriksaan-loading-produk.edit', [
            'pemeriksaanLoading' => $pemeriksaan_loading_produk,
            'shifts' => $shifts,
            'tujuanPengirimans' => $tujuanPengirimans,
            'kendaraans' => $kendaraans,
            'supirs' => $supirs,
            'produks' => $produks,
            'produkKategoriOptions' => $produkKategoriOptions,
            'produkByKategori' => $produkByKategori,
            'produkKategoriById' => $produkKategoriById,
        ]);
    }
    public function update(Request $request, PemeriksaanLoadingProduk $pemeriksaan_loading_produk)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_tujuan_pengiriman' => [
                'nullable',
                Rule::when(
                    $request->input('id_tujuan_pengiriman') !== 'other',
                    Rule::exists('tujuan_pengirimen', 'id')
                ),
            ],
            'nama_customer_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'nama_tujuan_manual' => 'nullable|required_if:id_tujuan_pengiriman,other|string|max:255',
            'id_kendaraan' => 'nullable',
            'jenis_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'no_kendaraan_manual' => 'nullable|required_if:id_kendaraan,other|string|max:255',
            'id_supir' => [
                'nullable',
                Rule::when(
                    $request->input('id_supir') !== 'other',
                    Rule::exists('supirs', 'id')
                ),
            ],
            'nama_supir_manual' => 'nullable|required_if:id_supir,other|string|max:255',
            'star_loading' => 'nullable',
            'selesai_loading' => 'nullable',
            'temperature_mobil' => 'nullable|string|max:255',
            'temperature_produk' => 'nullable|array',
            'temperature_produk.*' => 'nullable|string|max:255',
            'kondisi_produk' => 'nullable|in:Frozen,Fresh,Dry',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
            'produk_data' => 'nullable|array|min:1',
            'produk_data.*.id_produk' => 'nullable|exists:produks,id',
            'produk_data.*.kode_produksi' => 'nullable|string|max:255',
            'produk_data.*.best_before' => 'nullable|date',
            'produk_data.*.jumlah_kemasan' => 'nullable|string|max:255',
            'produk_data.*.jumlah_sampling' => 'nullable|string|max:255',
            'produk_data.*.berat_perkarung' => 'nullable|string|max:255',
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

        if ($request->id_tujuan_pengiriman === 'other') {
            if ($request->nama_customer_manual && $request->nama_tujuan_manual) {
                $customer = Customer::create([
                    'nama_cust' => $request->nama_customer_manual,
                    'id_user' => Auth::id(),
                ]);

                $tujuan = TujuanPengiriman::create([
                    'id_user' => Auth::id(),
                    'id_customer' => $customer->id,
                    'nama_tujuan' => $request->nama_tujuan_manual,
                ]);

                $validated['id_tujuan_pengiriman'] = $tujuan->id;
            } else {
                $validated['id_tujuan_pengiriman'] = null;
            }
        }

        if ($request->id_supir === 'other') {
            if ($request->nama_supir_manual) {
                $supir = Supir::create([
                    'nama_supir' => $request->nama_supir_manual,
                    'id_user' => Auth::id(),
                ]);

                $validated['id_supir'] = $supir->id;
            } else {
                $validated['id_supir'] = null;
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
        $validated['segel_gembok'] = $request->input('segel_gembok') === 'segel';
        
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
                        'berat_perkarung' => $produk['berat_perkarung'] ?? null,
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

    public function sendToProduksi(PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $user = Auth::user();
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $user = Auth::user();
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $user = Auth::user();
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanLoadingProduk $pemeriksaanLoadingProduk)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        
        if ($pemeriksaanLoadingProduk->status_verifikasi !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }
        
        $pemeriksaanLoadingProduk->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}