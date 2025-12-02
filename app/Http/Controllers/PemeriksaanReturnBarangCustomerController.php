<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanReturnBarangCustomer;
use App\Models\Shift;
use App\Models\Ekspedisi;
use App\Models\Customer;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanReturnBarangCustomerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanReturnBarangCustomer::with([
                'user.role',
                'user.plant',
                'shift',
                'ekspedisi',
                'customer',
                'produk'
            ])->latest()->get();
        } else {
            // Admin dan role lain hanya melihat data sesuai plant mereka
            $pemeriksaans = PemeriksaanReturnBarangCustomer::with([
                'user.role',
                'user.plant',
                'shift',
                'ekspedisi',
                'customer',
                'produk'
            ])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->get();
        }

        return view('qc-sistem.pemeriksaan-return-barang-customer.index', compact('pemeriksaans'));
    }

    public function create()
    {
        $user = Auth::user();

        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $ekspedisis = Ekspedisi::with(['user.plant'])->get();
            $customers = Customer::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $ekspedisis = Ekspedisi::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $customers = Customer::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $produks = Produk::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        return view('qc-sistem.pemeriksaan-return-barang-customer.create', compact(
            'shifts',
            'ekspedisis',
            'customers',
            'produks'
        ));
    }

    public function store(Request $request)
    {
        // Validasi dinamis berdasarkan pilihan ekspedisi
        $rules = [
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'no_polisi' => 'required|string|max:20',
            'nama_supir' => 'required|string|max:100',
            'waktu_kedatangan' => 'nullable|date_format:H:i',
            'suhu_mobil' => 'required|string|max:50',
            'id_customer' => 'required|exists:customers,id',
            'alasan_return' => 'required|string|max:255',
            'produk_data' => 'required|array|min:1',
            'produk_data.*.kondisi_produk' => 'required|in:Frozen,Fresh,Dry',
            'produk_data.*.id_produk' => 'required|exists:produks,id',
            'produk_data.*.suhu_produk' => 'nullable|string|max:50',
            'produk_data.*.kode_produksi' => 'required|string|max:100',
            'produk_data.*.expired_date' => 'required|date',
            'produk_data.*.jumlah_barang' => 'required|string|max:100',
            'produk_data.*.kondisi_kemasan' => 'required|boolean',
            'produk_data.*.kondisi_produk_check' => 'required|boolean',
            'produk_data.*.rekomendasi' => 'required|string|max:255',
            'produk_data.*.keterangan' => 'nullable|string|max:500',
        ];

        // Jika pilih input manual ekspedisi, validasi nama_ekspedisi_manual
        if ($request->id_ekspedisi === 'other') {
            $rules['nama_ekspedisi_manual'] = 'required|string|max:100';
        } else {
            $rules['id_ekspedisi'] = 'nullable|exists:ekspedisis,id';
        }

        $validated = $request->validate($rules);

        // Cek apakah ekspedisi diinput manual
        if ($request->id_ekspedisi === 'other') {
            // Jika input manual diisi
            if ($request->nama_ekspedisi_manual) {
                // Buat record baru di tabel ekspedisis
                $ekspedisi = Ekspedisi::create([
                    'nama_ekspedisi' => $request->nama_ekspedisi_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_ekspedisi'] = $ekspedisi->id;
            } else {
                // Jika input manual tidak diisi, set id_ekspedisi ke null
                $validated['id_ekspedisi'] = null;
            }
        }

        $validated['id_user'] = Auth::id();
        
        // Process produk_data array
        $produkData = [];
        if ($request->has('produk_data')) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'kondisi_produk' => $produk['kondisi_produk'],
                        'id_produk' => $produk['id_produk'],
                        'suhu_produk' => $produk['suhu_produk'] ?? null,
                        'kode_produksi' => $produk['kode_produksi'],
                        'expired_date' => $produk['expired_date'],
                        'jumlah_barang' => $produk['jumlah_barang'],
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : false,
                        'kondisi_produk_check' => isset($produk['kondisi_produk_check']) ? (bool)$produk['kondisi_produk_check'] : false,
                        'rekomendasi' => $produk['rekomendasi'],
                        'keterangan' => $produk['keterangan'] ?? null,
                    ];
                }
            }
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;
        // Remove old fields if they exist
        unset($validated['kondisi_produk'], $validated['id_produk'], $validated['suhu_produk'], 
              $validated['kode_produksi'], $validated['expired_date'], $validated['jumlah_barang'],
              $validated['kondisi_kemasan'], $validated['kondisi_produk_check'], $validated['rekomendasi'], $validated['keterangan']);

        PemeriksaanReturnBarangCustomer::create($validated);

        return redirect()->route('return-barang.index')->with('success', 'Pemeriksaan return barang berhasil ditambahkan!');
    }

    public function show(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        $pemeriksaanReturnBarangCustomer->load('user', 'shift', 'ekspedisi', 'customer', 'produk');
        return view('qc-sistem.pemeriksaan-return-barang-customer.show', compact('pemeriksaanReturnBarangCustomer'));
    }

    public function edit(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        $user = Auth::user();

        // SuperAdmin dapat melihat semua data
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
            $ekspedisis = Ekspedisi::with(['user.plant'])->get();
            $customers = Customer::with(['user.plant'])->get();
            $produks = Produk::with(['user.plant'])->get();
        } else {
            // Filter berdasarkan plant user yang login
            $shifts = Shift::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $ekspedisis = Ekspedisi::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $customers = Customer::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();

            $produks = Produk::whereHas('user', function ($query) use ($user) {
                $query->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        return view('qc-sistem.pemeriksaan-return-barang-customer.edit', compact(
            'pemeriksaanReturnBarangCustomer',
            'shifts',
            'ekspedisis',
            'customers',
            'produks'
        ));
    }

    public function update(Request $request, PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);

        // Validasi dinamis berdasarkan pilihan ekspedisi
        $rules = [
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'no_polisi' => 'required|string|max:20',
            'nama_supir' => 'required|string|max:100',
            'waktu_kedatangan' => 'nullable|date_format:H:i',
            'suhu_mobil' => 'required|string|max:50',
            'id_customer' => 'required|exists:customers,id',
            'alasan_return' => 'required|string|max:255',
            'produk_data' => 'required|array|min:1',
            'produk_data.*.kondisi_produk' => 'required|in:Frozen,Fresh,Dry',
            'produk_data.*.id_produk' => 'required|exists:produks,id',
            'produk_data.*.suhu_produk' => 'nullable|string|max:50',
            'produk_data.*.kode_produksi' => 'required|string|max:100',
            'produk_data.*.expired_date' => 'required|date',
            'produk_data.*.jumlah_barang' => 'required|string|max:100',
            'produk_data.*.kondisi_kemasan' => 'required|boolean',
            'produk_data.*.kondisi_produk_check' => 'required|boolean',
            'produk_data.*.rekomendasi' => 'required|string|max:255',
            'produk_data.*.keterangan' => 'nullable|string|max:500',
        ];

        // Jika pilih input manual ekspedisi, validasi nama_ekspedisi_manual
        if ($request->id_ekspedisi === 'other') {
            $rules['nama_ekspedisi_manual'] = 'required|string|max:100';
        } else {
            $rules['id_ekspedisi'] = 'nullable|exists:ekspedisis,id';
        }

        $validated = $request->validate($rules);

        // Cek apakah ekspedisi diinput manual
        if ($request->id_ekspedisi === 'other') {
            // Jika input manual diisi
            if ($request->nama_ekspedisi_manual) {
                // Buat record baru di tabel ekspedisis
                $ekspedisi = Ekspedisi::create([
                    'nama_ekspedisi' => $request->nama_ekspedisi_manual,
                    'id_user' => Auth::id(),
                ]);
                
                // Gunakan ID dari record baru
                $validated['id_ekspedisi'] = $ekspedisi->id;
            } else {
                // Jika input manual tidak diisi, set id_ekspedisi ke null
                $validated['id_ekspedisi'] = null;
            }
        }

        // Process produk_data array
        $produkData = [];
        if ($request->has('produk_data')) {
            foreach ($request->produk_data as $produk) {
                if (!empty($produk['id_produk'])) {
                    $produkData[] = [
                        'kondisi_produk' => $produk['kondisi_produk'],
                        'id_produk' => $produk['id_produk'],
                        'suhu_produk' => $produk['suhu_produk'] ?? null,
                        'kode_produksi' => $produk['kode_produksi'],
                        'expired_date' => $produk['expired_date'],
                        'jumlah_barang' => $produk['jumlah_barang'],
                        'kondisi_kemasan' => isset($produk['kondisi_kemasan']) ? (bool)$produk['kondisi_kemasan'] : false,
                        'kondisi_produk_check' => isset($produk['kondisi_produk_check']) ? (bool)$produk['kondisi_produk_check'] : false,
                        'rekomendasi' => $produk['rekomendasi'],
                        'keterangan' => $produk['keterangan'] ?? null,
                    ];
                }
            }
        }
        
        $validated['produk_data'] = !empty($produkData) ? $produkData : null;
        // Remove old fields if they exist
        unset($validated['kondisi_produk'], $validated['id_produk'], $validated['suhu_produk'], 
              $validated['kode_produksi'], $validated['expired_date'], $validated['jumlah_barang'],
              $validated['kondisi_kemasan'], $validated['kondisi_produk_check'], $validated['rekomendasi'], $validated['keterangan']);

        $pemeriksaanReturnBarangCustomer->update($validated);

        return redirect()->route('return-barang.index')->with('success', 'Pemeriksaan return barang berhasil diperbarui!');
    }

    public function destroy(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $this->checkPlantAccess($pemeriksaanReturnBarangCustomer);
        $pemeriksaanReturnBarangCustomer->delete();
        return redirect()->route('return-barang.index')->with('success', 'Pemeriksaan return barang berhasil dihapus!');
    }

    protected function checkPlantAccess(PemeriksaanReturnBarangCustomer $pemeriksaanReturnBarangCustomer)
    {
        $user = Auth::user();

        // SuperAdmin dapat akses semua
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }

        // Cek apakah data milik plant user
        if ($pemeriksaanReturnBarangCustomer->user->id_plant !== $user->id_plant) {
            abort(403, 'Unauthorized access');
        }
    }
}