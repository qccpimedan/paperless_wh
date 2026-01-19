<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanProdukFinishGood;
use App\Models\Produk;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanProdukFinishGoodController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $pemeriksaans = PemeriksaanProdukFinishGood::with(['user.role', 'user.plant', 'shift'])
                ->latest()
                ->paginate(10);
        } else {
            $pemeriksaans = PemeriksaanProdukFinishGood::with(['user.role', 'user.plant', 'shift'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('id_plant', $user->id_plant);
                })
                ->latest()
                ->paginate(10);
        }

        $allProdukIds = [];
        foreach ($pemeriksaans as $p) {
            $ids = $p->id_produk_array;
            $ids = is_array($ids) ? $ids : [];
            foreach ($ids as $id) {
                if ($id !== null && $id !== '') {
                    $allProdukIds[] = (int) $id;
                }
            }
        }
        $allProdukIds = array_values(array_unique($allProdukIds));

        $produkNamaById = Produk::query()
            ->select(['id', 'nama_produk'])
            ->whereIn('id', $allProdukIds)
            ->get()
            ->mapWithKeys(function ($p) {
                return [$p->id => $p->nama_produk];
            })
            ->all();

        return view('qc-sistem.pemeriksaan-produk-finish-good.index', compact('pemeriksaans', 'produkNamaById'));
    }

    public function create()
    {
        $user = Auth::user();
        $plantId = $user->id_plant;

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        $produkKategoriOptions = Produk::query()
            ->whereNotNull('kategori_code')
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkByKategori = $produkList
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            });

        $produkKategoriById = $produkList->pluck('kategori_code', 'id');

        $produkMeta = Produk::with([
                'produsens' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
                'distributors' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
            ])
            ->get()
            ->mapWithKeys(function ($p) {
                return [
                    $p->id => [
                        'produsen' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        return view('qc-sistem.pemeriksaan-produk-finish-good.create', compact(
            'shifts',
            'produkKategoriOptions',
            'produkByKategori',
            'produkMeta',
            'produkKategoriById'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
            'kondisi_mobil' => 'nullable|array',

            'suhu_mobil_type' => 'nullable|array',
            'suhu_mobil_type.*' => 'nullable|in:Fresh,Frozen',
            'suhu_mobil_value' => 'nullable|array',
            'suhu_mobil_value.*' => 'nullable|string|max:255',

            'suhu_produk_type' => 'nullable|array',
            'suhu_produk_type.*' => 'nullable|in:Fresh,Frozen',
            'suhu_produk_value' => 'nullable|array',
            'suhu_produk_value.*' => 'nullable|string|max:255',

            'kondisi_produk' => 'nullable|array',
            'kondisi_produk.*' => 'nullable|in:Frozen,Fresh,Dry',

            'kondisi_produk_suhu_value' => 'nullable|array',
            'kondisi_produk_suhu_value.*' => 'nullable|string|max:255',

            'kategori_code' => 'nullable|array',
            'kategori_code.*' => 'nullable|string|max:255',
            'id_produk' => 'required|array|min:1',
            'id_produk.*' => 'nullable|exists:produks,id',

            'negara_produsen' => 'nullable|array',
            'negara_produsen.*' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|array',
            'kode_produksi.*' => 'nullable|string|max:255',
            'expire_date' => 'nullable|array',
            'expire_date.*' => 'nullable|date',
            'jumlah_datang' => 'nullable|array',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|array',
            'jumlah_sampling.*' => 'nullable|string|max:255',

            'kondisi_kemasan' => 'nullable|array',
            'kondisi_kemasan.*' => 'nullable|in:0,1',
            'kondisi_warna' => 'nullable|array',
            'kondisi_warna.*' => 'nullable|in:0,1',
            'kondisi_aroma' => 'nullable|array',
            'kondisi_aroma.*' => 'nullable|in:0,1',

            'logo_halal' => 'nullable|array',
            'logo_halal.*' => 'nullable|in:0,1',
            'dokumen_halal' => 'nullable|array',
            'dokumen_halal.*' => 'nullable|in:0,1',
            'coa' => 'nullable|array',
            'coa.*' => 'nullable|in:0,1',

            'status_baris' => 'nullable|array',
            'status_baris.*' => 'nullable|in:Release,Hold',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        $idProdukArr = $request->input('id_produk', []);
        $kategoriArr = $request->input('kategori_code', []);
        $suhuMobilTypeArr = $request->input('suhu_mobil_type', []);
        $suhuMobilValueArr = $request->input('suhu_mobil_value', []);
        $suhuProdukTypeArr = $request->input('suhu_produk_type', []);
        $suhuProdukValueArr = $request->input('suhu_produk_value', []);
        $kondisiProdukArr = $request->input('kondisi_produk', []);
        $kondisiProdukSuhuValueArr = $request->input('kondisi_produk_suhu_value', []);
        $negaraArr = $request->input('negara_produsen', []);
        $kodeArr = $request->input('kode_produksi', []);
        $expireArr = $request->input('expire_date', []);
        $jmlDatangArr = $request->input('jumlah_datang', []);
        $jmlSamplingArr = $request->input('jumlah_sampling', []);
        $kemasanArr = $request->input('kondisi_kemasan', []);
        $warnaArr = $request->input('kondisi_warna', []);
        $aromaArr = $request->input('kondisi_aroma', []);
        $logoArr = $request->input('logo_halal', []);
        $dokArr = $request->input('dokumen_halal', []);
        $coaArr = $request->input('coa', []);
        $statusArr = $request->input('status_baris', []);
        $ketArr = $request->input('keterangan', []);

        $produkIds = array_values(array_filter($idProdukArr, fn ($v) => $v !== null && $v !== ''));

        $produsenByProduk = [];
        $distributorByProduk = [];
        if (!empty($produkIds)) {
            $user = Auth::user();
            $plantId = $user->id_plant;
            $produkRows = Produk::with([
                    'produsens' => function ($q) use ($plantId) {
                        if ($plantId) {
                            $q->wherePivot('id_plant', $plantId);
                        }
                    },
                    'distributors' => function ($q) use ($plantId) {
                        if ($plantId) {
                            $q->wherePivot('id_plant', $plantId);
                        }
                    },
                ])
                ->whereIn('id', $produkIds)
                ->get();

            foreach ($produkRows as $p) {
                $produsenByProduk[$p->id] = $p->produsens->pluck('nama_produsen')->values()->toArray();
                $distributorByProduk[$p->id] = $p->distributors->pluck('nama_distributor')->values()->toArray();
            }
        }

        $produsenArray = [];
        $distributorArray = [];
        foreach ($idProdukArr as $idx => $pid) {
            $pid = $pid ? (int) $pid : null;
            $produsenArray[$idx] = $pid && isset($produsenByProduk[$pid]) ? $produsenByProduk[$pid] : [];
            $distributorArray[$idx] = $pid && isset($distributorByProduk[$pid]) ? $distributorByProduk[$pid] : [];
        }

        $pemeriksaan = PemeriksaanProdukFinishGood::create([
            'id_user' => Auth::id(),
            'id_shift' => $validated['id_shift'] ?? null,
            'tanggal' => $validated['tanggal'],
            'jenis_mobil' => $validated['jenis_mobil'] ?? null,
            'no_mobil' => $validated['no_mobil'] ?? null,
            'nama_supir' => $validated['nama_supir'] ?? null,
            'segel_gembok' => $validated['segel_gembok'] ?? null,
            'no_segel' => $validated['no_segel'] ?? null,
            'kondisi_mobil' => $validated['kondisi_mobil'] ?? null,

            'suhu_mobil' => $suhuMobilTypeArr[0] ?? null,
            'suhu_mobil_value' => $suhuMobilValueArr[0] ?? null,
            'suhu_produk' => $suhuProdukTypeArr[0] ?? null,
            'suhu_produk_value' => $suhuProdukValueArr[0] ?? null,
            'kondisi_produk' => $kondisiProdukArr[0] ?? null,

            'suhu_mobil_type_array' => $suhuMobilTypeArr,
            'suhu_mobil_value_array' => $suhuMobilValueArr,
            'suhu_produk_type_array' => $suhuProdukTypeArr,
            'suhu_produk_value_array' => $suhuProdukValueArr,
            'kondisi_produk_array' => $kondisiProdukArr,
            'kondisi_produk_suhu_value_array' => $kondisiProdukSuhuValueArr,

            'kategori_code_array' => $kategoriArr,
            'id_produk_array' => $idProdukArr,
            'produsen_array' => $produsenArray,
            'negara_produsen_array' => $negaraArr,
            'distributor_array' => $distributorArray,
            'kode_produksi_array' => $kodeArr,
            'expire_date_array' => $expireArr,
            'jumlah_datang_array' => $jmlDatangArr,
            'jumlah_sampling_array' => $jmlSamplingArr,
            'kondisi_kemasan_array' => $kemasanArr,
            'kondisi_warna_array' => $warnaArr,
            'kondisi_aroma_array' => $aromaArr,
            'logo_halal_array' => $logoArr,
            'dokumen_halal_array' => $dokArr,
            'coa_array' => $coaArr,
            'status_array' => $statusArr,
            'keterangan_array' => $ketArr,
        ]);

        return redirect()->route('pemeriksaan-produk-finish-good.show', $pemeriksaan->uuid)
            ->with('success', 'Data pemeriksaan Finish Good berhasil disimpan');
    }

    public function show(PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $pemeriksaanProdukFinishGood->load(['user.plant', 'shift']);

        $idProdukArr = $pemeriksaanProdukFinishGood->id_produk_array;
        $idProdukArr = is_array($idProdukArr) ? $idProdukArr : [];

        $produkRows = Produk::query()
            ->select(['id', 'nama_produk'])
            ->whereIn('id', array_filter($idProdukArr))
            ->get();

        $produkNamaById = $produkRows->mapWithKeys(function ($p) {
            return [$p->id => $p->nama_produk];
        })->all();

        return view('qc-sistem.pemeriksaan-produk-finish-good.show', compact('pemeriksaanProdukFinishGood', 'produkNamaById'));
    }

    public function edit(PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $user = Auth::user();
        $plantId = $user->id_plant;

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            })->with(['user.plant'])->get();
        }

        $produkKategoriOptions = Produk::query()
            ->whereNotNull('kategori_code')
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        $produkList = Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

        $produkByKategori = $produkList
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                    ];
                })->values();
            });

        $produkKategoriById = $produkList->pluck('kategori_code', 'id');

        $produkMeta = Produk::with([
                'produsens' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
                'distributors' => function ($q) use ($plantId) {
                    if ($plantId) {
                        $q->wherePivot('id_plant', $plantId);
                    }
                },
            ])
            ->get()
            ->mapWithKeys(function ($p) {
                return [
                    $p->id => [
                        'produsen' => $p->produsens->pluck('nama_produsen')->values()->toArray(),
                        'distributor' => $p->distributors->pluck('nama_distributor')->values()->toArray(),
                    ],
                ];
            });

        return view('qc-sistem.pemeriksaan-produk-finish-good.edit', compact(
            'pemeriksaanProdukFinishGood',
            'shifts',
            'produkKategoriOptions',
            'produkByKategori',
            'produkMeta',
            'produkKategoriById'
        ));
    }

    public function update(Request $request, PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'id_shift' => 'nullable|exists:shifts,id',
            'jenis_mobil' => 'nullable|string|max:255',
            'no_mobil' => 'nullable|string|max:255',
            'nama_supir' => 'nullable|string|max:255',
            'segel_gembok' => 'nullable|in:segel,gembok',
            'no_segel' => 'nullable|required_if:segel_gembok,segel|string|max:255',
            'kondisi_mobil' => 'nullable|array',

            'suhu_mobil_type' => 'nullable|array',
            'suhu_mobil_type.*' => 'nullable|in:Fresh,Frozen',
            'suhu_mobil_value' => 'nullable|array',
            'suhu_mobil_value.*' => 'nullable|string|max:255',

            'suhu_produk_type' => 'nullable|array',
            'suhu_produk_type.*' => 'nullable|in:Fresh,Frozen',
            'suhu_produk_value' => 'nullable|array',
            'suhu_produk_value.*' => 'nullable|string|max:255',

            'kondisi_produk' => 'nullable|array',
            'kondisi_produk.*' => 'nullable|in:Frozen,Fresh,Dry',

            'kondisi_produk_suhu_value' => 'nullable|array',
            'kondisi_produk_suhu_value.*' => 'nullable|string|max:255',

            'kategori_code' => 'nullable|array',
            'kategori_code.*' => 'nullable|string|max:255',
            'id_produk' => 'required|array|min:1',
            'id_produk.*' => 'nullable|exists:produks,id',

            'negara_produsen' => 'nullable|array',
            'negara_produsen.*' => 'nullable|string|max:255',
            'kode_produksi' => 'nullable|array',
            'kode_produksi.*' => 'nullable|string|max:255',
            'expire_date' => 'nullable|array',
            'expire_date.*' => 'nullable|date',
            'jumlah_datang' => 'nullable|array',
            'jumlah_datang.*' => 'nullable|string|max:255',
            'jumlah_sampling' => 'nullable|array',
            'jumlah_sampling.*' => 'nullable|string|max:255',

            'kondisi_kemasan' => 'nullable|array',
            'kondisi_kemasan.*' => 'nullable|in:0,1',
            'kondisi_warna' => 'nullable|array',
            'kondisi_warna.*' => 'nullable|in:0,1',
            'kondisi_aroma' => 'nullable|array',
            'kondisi_aroma.*' => 'nullable|in:0,1',

            'logo_halal' => 'nullable|array',
            'logo_halal.*' => 'nullable|in:0,1',
            'dokumen_halal' => 'nullable|array',
            'dokumen_halal.*' => 'nullable|in:0,1',
            'coa' => 'nullable|array',
            'coa.*' => 'nullable|in:0,1',

            'status_baris' => 'nullable|array',
            'status_baris.*' => 'nullable|in:Release,Hold',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
        ]);

        $idProdukArr = $request->input('id_produk', []);
        $kategoriArr = $request->input('kategori_code', []);
        $suhuMobilTypeArr = $request->input('suhu_mobil_type', []);
        $suhuMobilValueArr = $request->input('suhu_mobil_value', []);
        $suhuProdukTypeArr = $request->input('suhu_produk_type', []);
        $suhuProdukValueArr = $request->input('suhu_produk_value', []);
        $kondisiProdukArr = $request->input('kondisi_produk', []);
        $kondisiProdukSuhuValueArr = $request->input('kondisi_produk_suhu_value', []);
        $negaraArr = $request->input('negara_produsen', []);
        $kodeArr = $request->input('kode_produksi', []);
        $expireArr = $request->input('expire_date', []);
        $jmlDatangArr = $request->input('jumlah_datang', []);
        $jmlSamplingArr = $request->input('jumlah_sampling', []);
        $kemasanArr = $request->input('kondisi_kemasan', []);
        $warnaArr = $request->input('kondisi_warna', []);
        $aromaArr = $request->input('kondisi_aroma', []);
        $logoArr = $request->input('logo_halal', []);
        $dokArr = $request->input('dokumen_halal', []);
        $coaArr = $request->input('coa', []);
        $statusArr = $request->input('status_baris', []);
        $ketArr = $request->input('keterangan', []);

        $produkIds = array_values(array_filter($idProdukArr, fn ($v) => $v !== null && $v !== ''));

        $produsenByProduk = [];
        $distributorByProduk = [];
        if (!empty($produkIds)) {
            $user = Auth::user();
            $plantId = $user->id_plant;
            $produkRows = Produk::with([
                    'produsens' => function ($q) use ($plantId) {
                        if ($plantId) {
                            $q->wherePivot('id_plant', $plantId);
                        }
                    },
                    'distributors' => function ($q) use ($plantId) {
                        if ($plantId) {
                            $q->wherePivot('id_plant', $plantId);
                        }
                    },
                ])
                ->whereIn('id', $produkIds)
                ->get();

            foreach ($produkRows as $p) {
                $produsenByProduk[$p->id] = $p->produsens->pluck('nama_produsen')->values()->toArray();
                $distributorByProduk[$p->id] = $p->distributors->pluck('nama_distributor')->values()->toArray();
            }
        }

        $produsenArray = [];
        $distributorArray = [];
        foreach ($idProdukArr as $idx => $pid) {
            $pid = $pid ? (int) $pid : null;
            $produsenArray[$idx] = $pid && isset($produsenByProduk[$pid]) ? $produsenByProduk[$pid] : [];
            $distributorArray[$idx] = $pid && isset($distributorByProduk[$pid]) ? $distributorByProduk[$pid] : [];
        }

        $pemeriksaanProdukFinishGood->update([
            'id_shift' => $validated['id_shift'] ?? null,
            'tanggal' => $validated['tanggal'],
            'jenis_mobil' => $validated['jenis_mobil'] ?? null,
            'no_mobil' => $validated['no_mobil'] ?? null,
            'nama_supir' => $validated['nama_supir'] ?? null,
            'segel_gembok' => $validated['segel_gembok'] ?? null,
            'no_segel' => $validated['no_segel'] ?? null,
            'kondisi_mobil' => $validated['kondisi_mobil'] ?? null,

            'suhu_mobil' => $suhuMobilTypeArr[0] ?? null,
            'suhu_mobil_value' => $suhuMobilValueArr[0] ?? null,
            'suhu_produk' => $suhuProdukTypeArr[0] ?? null,
            'suhu_produk_value' => $suhuProdukValueArr[0] ?? null,
            'kondisi_produk' => $kondisiProdukArr[0] ?? null,

            'suhu_mobil_type_array' => $suhuMobilTypeArr,
            'suhu_mobil_value_array' => $suhuMobilValueArr,
            'suhu_produk_type_array' => $suhuProdukTypeArr,
            'suhu_produk_value_array' => $suhuProdukValueArr,
            'kondisi_produk_array' => $kondisiProdukArr,
            'kondisi_produk_suhu_value_array' => $kondisiProdukSuhuValueArr,

            'kategori_code_array' => $kategoriArr,
            'id_produk_array' => $idProdukArr,
            'produsen_array' => $produsenArray,
            'negara_produsen_array' => $negaraArr,
            'distributor_array' => $distributorArray,
            'kode_produksi_array' => $kodeArr,
            'expire_date_array' => $expireArr,
            'jumlah_datang_array' => $jmlDatangArr,
            'jumlah_sampling_array' => $jmlSamplingArr,
            'kondisi_kemasan_array' => $kemasanArr,
            'kondisi_warna_array' => $warnaArr,
            'kondisi_aroma_array' => $aromaArr,
            'logo_halal_array' => $logoArr,
            'dokumen_halal_array' => $dokArr,
            'coa_array' => $coaArr,
            'status_array' => $statusArr,
            'keterangan_array' => $ketArr,
        ]);

        return redirect()->route('pemeriksaan-produk-finish-good.index')
            ->with('success', 'Data pemeriksaan Finish Good berhasil diperbarui');
    }

    public function destroy(PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $pemeriksaanProdukFinishGood->delete();
        return redirect()->route('pemeriksaan-produk-finish-good.index')
            ->with('success', 'Data pemeriksaan Finish Good berhasil dihapus');
    }
}
