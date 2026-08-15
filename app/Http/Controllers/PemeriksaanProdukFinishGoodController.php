<?php

namespace App\Http\Controllers;

use App\Models\PemeriksaanProdukFinishGood;
use App\Models\Produk;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Monarobase\CountryList\CountryListFacade as Countries;

class PemeriksaanProdukFinishGoodController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = trim((string) $request->input('search', ''));

        $query = PemeriksaanProdukFinishGood::with(['user.role', 'user.plant', 'shift']);

        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        if ($search !== '') {
            $matchingProductIds = Produk::query()
                ->select('id')
                ->where('nama_produk', 'like', '%' . $search . '%')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $query->where(function ($q) use ($search, $matchingProductIds) {
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

                $q->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhere('verification_notes', 'like', '%' . $search . '%')
                    ->orWhere('kode_produksi_array', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    });

                if (!empty($matchingProductIds)) {
                    $q->orWhere(function ($qj) use ($matchingProductIds) {
                        foreach ($matchingProductIds as $pid) {
                            $qj->orWhereRaw(
                                "JSON_CONTAINS(id_produk_array, ?, '$')",
                                [json_encode($pid)]
                            );

                            $qj->orWhereRaw(
                                "JSON_CONTAINS(id_produk_array, ?, '$')",
                                [json_encode((string) $pid)]
                            );

                            $qj->orWhere('id_produk_array', 'like', '%"' . $pid . '"%');
                            $qj->orWhere('id_produk_array', 'like', '%,' . $pid . ',%');
                            $qj->orWhere('id_produk_array', 'like', '[' . $pid . ',%');
                            $qj->orWhere('id_produk_array', 'like', '%,' . $pid . ']');
                        }
                    });
                }
            });
        }

        $pemeriksaans = $query->latest()->paginate(25);

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

        
        $produkKategoriOptions = \App\Models\Produk::query()
            ->whereNotNull('kategori_code')
            ->select('kategori_code')
            ->distinct()
            ->orderBy('kategori_code')
            ->pluck('kategori_code')
            ->values();

        $produkList = \App\Models\Produk::query()
            ->select(['id', 'nama_produk', 'kategori_code'])
            ->orderBy('nama_produk')
            ->get();

                $produkByKategori = $produkList
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return ['id' => $p->id, 'nama' => $p->nama_produk];
                })->values();
            });

return view('qc-sistem.pemeriksaan-produk-finish-good.index', compact('pemeriksaans', 'produkNamaById', 'produkKategoriOptions', 'produkList', 'produkByKategori'));
    }

    public function create()
    {
        $user = Auth::user();
        $plantId = $user->getEffectivePlantId();

        $countries = Countries::getList('en', 'php');

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
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
            'countries',
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
            'id_shift' => 'required|exists:shifts,id',
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
            'image_finish_good' => 'nullable|array',
            'image_finish_good.*' => 'nullable|image|max:1024',
            'upload_coa' => 'nullable|array',
            'upload_coa.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
        $unitDatangArr = $request->input('unit_datang', []);
        $jmlSamplingArr = $request->input('jumlah_sampling', []);
        $unitSamplingArr = $request->input('unit_sampling', []);
        $kemasanArr = $request->input('kondisi_kemasan', []);
        $warnaArr = $request->input('kondisi_warna', []);
        $aromaArr = $request->input('kondisi_aroma', []);
        $logoArr = $request->input('logo_halal', []);
        $dokArr = $request->input('dokumen_halal', []);
        $coaArr = $request->input('coa', []);
        $statusArr = $request->input('status_baris', []);
        $ketArr = $request->input('keterangan', []);

        $uploadedImages = (array) $request->file('image_finish_good', []);
        $imagePaths = [];
        foreach ($idProdukArr as $idx => $pid) {
            $file = $uploadedImages[$idx] ?? null;
            $imagePaths[$idx] = $file ? $file->storePublicly('pemeriksaan-produk-finish-good/images', 'public') : null;
        }

        $uploadedCoas = (array) $request->file('upload_coa', []);
        $coaPaths = [];
        foreach ($idProdukArr as $idx => $pid) {
            $file = $uploadedCoas[$idx] ?? null;
            $coaPaths[$idx] = $file ? $file->storePublicly('pemeriksaan-produk-finish-good/coas', 'public') : null;
        }

        $produkIds = array_values(array_filter($idProdukArr, fn ($v) => $v !== null && $v !== ''));

        $produsenByProduk = [];
        $distributorByProduk = [];
        if (!empty($produkIds)) {
            $user = Auth::user();
            $plantId = $user->getEffectivePlantId();
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
            'unit_datang_array' => $unitDatangArr,
            'jumlah_sampling_array' => $jmlSamplingArr,
            'unit_sampling_array' => $unitSamplingArr,
            'kondisi_kemasan_array' => $kemasanArr,
            'kondisi_warna_array' => $warnaArr,
            'kondisi_aroma_array' => $aromaArr,
            'logo_halal_array' => $logoArr,
            'dokumen_halal_array' => $dokArr,
            'coa_array' => $coaArr,
            'status_array' => $statusArr,
            'keterangan_array' => $ketArr,
            'image_finish_good_array' => $imagePaths,
            'upload_coa_array' => $coaPaths,
        ]);

        return redirect()->route('pemeriksaan-produk-finish-good.index', $pemeriksaan->uuid)
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
        $plantId = $user->getEffectivePlantId();

        $countries = Countries::getList('en', 'php');

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $shifts = Shift::with(['user.plant'])->get();
        } else {
            $shifts = Shift::whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
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
            'countries',
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
            'id_shift' => 'required|exists:shifts,id',
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
            'image_finish_good' => 'nullable|array',
            'image_finish_good.*' => 'nullable|image|max:1024',
            'upload_coa' => 'nullable|array',
            'upload_coa.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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
        $unitDatangArr = $request->input('unit_datang', []);
        $jmlSamplingArr = $request->input('jumlah_sampling', []);
        $unitSamplingArr = $request->input('unit_sampling', []);
        $kemasanArr = $request->input('kondisi_kemasan', []);
        $warnaArr = $request->input('kondisi_warna', []);
        $aromaArr = $request->input('kondisi_aroma', []);
        $logoArr = $request->input('logo_halal', []);
        $dokArr = $request->input('dokumen_halal', []);
        $coaArr = $request->input('coa', []);
        $statusArr = $request->input('status_baris', []);
        $ketArr = $request->input('keterangan', []);

        $uploadedImages = (array) $request->file('image_finish_good', []);
        $existingImages = $pemeriksaanProdukFinishGood->image_finish_good_array;
        $existingImages = is_array($existingImages) ? $existingImages : [];
        $imagePaths = [];
        foreach ($idProdukArr as $idx => $pid) {
            $file = $uploadedImages[$idx] ?? null;
            $prev = $existingImages[$idx] ?? null;
            $imagePaths[$idx] = $file
                ? $file->storePublicly('pemeriksaan-produk-finish-good/images', 'public')
                : $prev;
        }

        $uploadedCoas = (array) $request->file('upload_coa', []);
        $existingCoas = $pemeriksaanProdukFinishGood->upload_coa_array;
        $existingCoas = is_array($existingCoas) ? $existingCoas : [];
        $coaPaths = [];
        foreach ($idProdukArr as $idx => $pid) {
            $file = $uploadedCoas[$idx] ?? null;
            $prev = $existingCoas[$idx] ?? null;
            $coaPaths[$idx] = $file
                ? $file->storePublicly('pemeriksaan-produk-finish-good/coas', 'public')
                : $prev;
        }

        $produkIds = array_values(array_filter($idProdukArr, fn ($v) => $v !== null && $v !== ''));

        $produsenByProduk = [];
        $distributorByProduk = [];
        if (!empty($produkIds)) {
            $user = Auth::user();
            $plantId = $user->getEffectivePlantId();
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
            'unit_datang_array' => $unitDatangArr,
            'jumlah_sampling_array' => $jmlSamplingArr,
            'unit_sampling_array' => $unitSamplingArr,
            'kondisi_kemasan_array' => $kemasanArr,
            'kondisi_warna_array' => $warnaArr,
            'kondisi_aroma_array' => $aromaArr,
            'logo_halal_array' => $logoArr,
            'dokumen_halal_array' => $dokArr,
            'coa_array' => $coaArr,
            'status_array' => $statusArr,
            'keterangan_array' => $ketArr,
            'image_finish_good_array' => $imagePaths,
            'upload_coa_array' => $coaPaths,
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

    /**
     * Check if user has access to pemeriksaan based on plant
     */
    private function checkPlantAccess(PemeriksaanProdukFinishGood $pemeriksaan)
    {
        $user = Auth::user();

        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            return;
        }

        if ($pemeriksaan->user && $pemeriksaan->user->id_plant !== $user->getEffectivePlantId()) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }

    public function sendToProduksi(PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanProdukFinishGood);

        $userRole = $user->role ? strtolower($user->role->role) : null;
        if ($userRole !== 'qc inspector') {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        if (($pemeriksaanProdukFinishGood->status_verifikasi ?? 'pending') !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pemeriksaan dengan status pending yang dapat dikirim.');
        }

        $pemeriksaanProdukFinishGood->update([
            'status_verifikasi' => 'sent_to_produksi',
            'verified_by' => $user->id,
            'verified_by_qc' => $user->id,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pemeriksaan berhasil dikirim ke Produksi.');
    }

    public function approveProduksi(Request $request, PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanProdukFinishGood);

        $userRole = $user->role ? strtolower($user->role->role) : null;
        if ($userRole !== 'produksi') {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        if (($pemeriksaanProdukFinishGood->status_verifikasi ?? 'pending') !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-approve.');
        }

        $pemeriksaanProdukFinishGood->update([
            'status_verifikasi' => 'approved_produksi',
            'verified_by' => $user->id,
            'verified_by_produksi' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Pemeriksaan berhasil di-approve oleh Produksi.');
    }

    public function rejectProduksi(Request $request, PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanProdukFinishGood);

        $userRole = $user->role ? strtolower($user->role->role) : null;
        if ($userRole !== 'produksi') {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        if (($pemeriksaanProdukFinishGood->status_verifikasi ?? 'pending') !== 'sent_to_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }

        $pemeriksaanProdukFinishGood->update([
            'status_verifikasi' => 'rejected_produksi',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('error', 'Pemeriksaan ditolak oleh Produksi. Silakan perbaiki dan kirim ulang.');
    }

    public function approveSPV(Request $request, PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanProdukFinishGood);

        $userRole = $user->role ? strtolower($user->role->role) : null;
        if ($userRole !== 'spv qc') {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        if (($pemeriksaanProdukFinishGood->status_verifikasi ?? 'pending') !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Pemeriksaan harus disetujui Produksi terlebih dahulu.');
        }

        $pemeriksaanProdukFinishGood->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_by_spv' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Pemeriksaan berhasil diverifikasi oleh SPV QC.');
    }

    public function rejectSPV(Request $request, PemeriksaanProdukFinishGood $pemeriksaanProdukFinishGood)
    {
        $request->validate(['notes' => 'required|string|min:5']);
        $user = Auth::user();
        $this->checkPlantAccess($pemeriksaanProdukFinishGood);

        $userRole = $user->role ? strtolower($user->role->role) : null;
        if ($userRole !== 'spv qc') {
            abort(403, 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        if (($pemeriksaanProdukFinishGood->status_verifikasi ?? 'pending') !== 'approved_produksi') {
            return redirect()->back()->with('error', 'Status pemeriksaan tidak valid untuk di-reject.');
        }

        $pemeriksaanProdukFinishGood->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
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
        $id_produk = $request->input('id_produk');
        $kategori_code = $request->input('kategori_code');

        // === MODE: ALL SHIFT ===
        if ($id_shift === 'all') {
            return $this->exportPDFAllShift($request, $user, $tanggalDari, $tanggalSampai, $id_produk, $kategori_code);
        }

        $query = PemeriksaanProdukFinishGood::with([
            'user.role',
            'user.plant',
            'shift',
            'verifiedBy.role',
        ])->with([
            'qcVerifier' => function ($q) {
                $q->select('id', 'name');
            },
            'produksiVerifier' => function ($q) {
                $q->select('id', 'name');
            },
            'spvVerifier' => function ($q) {
                $q->select('id', 'name');
            },
        ]);

        if ($user->role && strtolower($user->role->role) !== 'superadmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->getEffectivePlantId());
            });
        }

        
        // Filter by produk / kategori
        if ($id_produk) {
            $query->where(function ($q) use ($id_produk) {
                $q->whereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((int)$id_produk)])
                  ->orWhereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((string)$id_produk)])
                  ->orWhere('id_produk_array', 'like', '%"' . $id_produk . '"%')
                  ->orWhere('id_produk_array', 'like', '%,' . $id_produk . ',%')
                  ->orWhere('id_produk_array', 'like', '[' . $id_produk . ',%')
                  ->orWhere('id_produk_array', 'like', '%,' . $id_produk . ']');
            });
        } elseif ($kategori_code) {
            // Because JSON array only stores ID, we must find matching produk IDs first to filter by category
            $matchedIds = \App\Models\Produk::where('kategori_code', $kategori_code)->pluck('id')->toArray();
            if (!empty($matchedIds)) {
                $query->where(function ($q) use ($matchedIds) {
                    foreach ($matchedIds as $pid) {
                        $q->orWhereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((int)$pid)])
                          ->orWhereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((string)$pid)])
                          ->orWhere('id_produk_array', 'like', '%"' . $pid . '"%')
                          ->orWhere('id_produk_array', 'like', '%,' . $pid . ',%')
                          ->orWhere('id_produk_array', 'like', '[' . $pid . ',%')
                          ->orWhere('id_produk_array', 'like', '%,' . $pid . ']');
                    }
                });
            } else {
                // If category has no products, return no results
                $query->whereRaw('1 = 0');
            }
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

        $pemeriksaans = $query->latest()->get();

        if ($pemeriksaans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data yang sesuai dengan filter yang dipilih. Pastikan data sudah memiliki Shift dan Tanggal yang benar.');
        }

        $shift = $id_shift ? Shift::find($id_shift) : null;

        $qcUser = null;
        $produksiUser = null;
        $spvQcUser = null;

        $allQcIds = $pemeriksaans->pluck('verified_by_qc')->filter()->unique();
        $allProduksiIds = $pemeriksaans->pluck('verified_by_produksi')->filter()->unique();
        $allSpvIds = $pemeriksaans->pluck('verified_by_spv')->filter()->unique();

        if ($allQcIds->count() > 0) {
            $qcUserData = User::with('role')->whereIn('id', $allQcIds->toArray())->first();
            if ($qcUserData) $qcUser = $qcUserData->name;
        }

        if ($allProduksiIds->count() > 0) {
            $produksiUserData = User::with('role')->whereIn('id', $allProduksiIds->toArray())->first();
            if ($produksiUserData) $produksiUser = $produksiUserData->name;
        }

        if ($allSpvIds->count() > 0) {
            $spvUserData = User::with('role')->whereIn('id', $allSpvIds->toArray())->first();
            if ($spvUserData) $spvQcUser = $spvUserData->name;
        }

        $allProdukIds = $pemeriksaans
            ->flatMap(function ($p) {
                $ids = $p->id_produk_array;
                $ids = is_array($ids) ? $ids : [];
                return $ids;
            })
            ->filter(fn ($id) => !empty($id))
            ->unique()
            ->values();

        $produkMap = $allProdukIds->isNotEmpty()
            ? Produk::whereIn('id', $allProdukIds->toArray())->pluck('nama_produk', 'id')->toArray()
            : [];

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-produk-finish-good.pdf-report', [
            'pemeriksaans' => $pemeriksaans,
            'tanggal' => $tanggal,
            'tanggal_dari' => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift' => $shift,
            'qcUser' => $qcUser,
            'produksiUser' => $produksiUser,
            'spvQcUser' => $spvQcUser,
            'produkMap' => $produkMap,
            'isAllShift' => false,
            'dataPerShift' => [[
                'pemeriksaans' => $pemeriksaans,
                'shift' => $shift,
                'qcUser' => $qcUser,
                'produksiUser' => $produksiUser,
                'spvQcUser' => $spvQcUser,
                'produkMap' => $produkMap,
            ]],
        ]);

        $filenameDate = $tanggal ?? $tanggalDari ?? date('Y-m-d');
        $filename = 'laporan-pemeriksaan-finish-good-' . $filenameDate . '.pdf';
        return $pdf->download($filename);
    }

    private function exportPDFAllShift($request, $user, $tanggalDari, $tanggalSampai, $id_produk, $kategori_code)
    {
        // Ambil semua shift yang accessible
        if ($user->role && strtolower($user->role->role) === 'superadmin') {
            $allShifts = Shift::all();
        } else {
            $allShifts = Shift::query()
                ->when($user->id_plant, function ($q) use ($user) {
                    $q->whereHas('user', function ($qu) use ($user) {
                        $qu->where('id_plant', $user->id_plant);
                    });
                })
                ->get();
        }

        // Kumpulkan data per shift
        $dataPerShift = [];

        foreach ($allShifts as $shift) {
            $query = PemeriksaanProdukFinishGood::with([
                'user.role',
                'user.plant',
                'shift',
                'qcVerifier'       => fn($q) => $q->select('id', 'name'),
                'produksiVerifier' => fn($q) => $q->select('id', 'name'),
                'spvVerifier'      => fn($q) => $q->select('id', 'name'),
            ]);

            // Plant filter
            if ($user->role && strtolower($user->role->role) !== 'superadmin') {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('id_plant', $user->getEffectivePlantId());
                });
            }

            // Filter shift
            $query->where('id_shift', $shift->id);

            // Filter tanggal (rentang)
            if ($tanggalDari && $tanggalSampai) {
                $query->whereBetween('tanggal', [$tanggalDari, $tanggalSampai]);
            } elseif ($tanggalDari) {
                $query->whereDate('tanggal', '>=', $tanggalDari);
            } elseif ($tanggalSampai) {
                $query->whereDate('tanggal', '<=', $tanggalSampai);
            }

            // Filter produk / kategori
            if ($id_produk) {
                $query->where(function ($q) use ($id_produk) {
                    $q->whereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((int)$id_produk)])
                      ->orWhereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((string)$id_produk)])
                      ->orWhere('id_produk_array', 'like', '%"' . $id_produk . '"%')
                      ->orWhere('id_produk_array', 'like', '%,' . $id_produk . ',%')
                      ->orWhere('id_produk_array', 'like', '[' . $id_produk . ',%')
                      ->orWhere('id_produk_array', 'like', '%,' . $id_produk . ']');
                });
            } elseif ($kategori_code) {
                $matchedIds = \App\Models\Produk::where('kategori_code', $kategori_code)->pluck('id')->toArray();
                if (!empty($matchedIds)) {
                    $query->where(function ($q) use ($matchedIds) {
                        foreach ($matchedIds as $pid) {
                            $q->orWhereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((int)$pid)])
                              ->orWhereRaw("JSON_CONTAINS(id_produk_array, ?, '$')", [json_encode((string)$pid)])
                              ->orWhere('id_produk_array', 'like', '%"' . $pid . '"%')
                              ->orWhere('id_produk_array', 'like', '%,' . $pid . ',%')
                              ->orWhere('id_produk_array', 'like', '[' . $pid . ',%')
                              ->orWhere('id_produk_array', 'like', '%,' . $pid . ']');
                        }
                    });
                } else {
                    // Jika kategori tidak punya produk, return no results
                    $query->whereRaw('1 = 0');
                }
            }

            $records = $query->latest()->get();

            // Hanya masukkan jika ada data
            if ($records->isNotEmpty()) {
                // Kumpulkan verifier names
                $qcUser = null; $produksiUser = null; $spvQcUser = null;
                $qcId = $records->pluck('verified_by_qc')->filter()->unique()->first();
                $prodId = $records->pluck('verified_by_produksi')->filter()->unique()->first();
                $spvId = $records->pluck('verified_by_spv')->filter()->unique()->first();
                if ($qcId) $qcUser = optional(User::find($qcId))->name;
                if ($prodId) $produksiUser = optional(User::find($prodId))->name;
                if ($spvId) $spvQcUser = optional(User::find($spvId))->name;

                // Produk map untuk nama produk di PDF
                $allProdukIds = $records
                    ->flatMap(function ($p) {
                        $ids = $p->id_produk_array;
                        return is_array($ids) ? $ids : [];
                    })
                    ->filter(fn ($id) => !empty($id))
                    ->unique()
                    ->values();

                $produkMap = $allProdukIds->isNotEmpty()
                    ? Produk::whereIn('id', $allProdukIds->toArray())->pluck('nama_produk', 'id')->toArray()
                    : [];

                $dataPerShift[] = [
                    'shift'        => $shift,
                    'pemeriksaans' => $records,
                    'qcUser'       => $qcUser,
                    'produksiUser' => $produksiUser,
                    'spvQcUser'    => $spvQcUser,
                    'produkMap'    => $produkMap,
                ];
            }
        }

        $pdf = PDF::loadView('qc-sistem.pemeriksaan-produk-finish-good.pdf-report', [
            'dataPerShift'   => $dataPerShift,
            'tanggal'        => null,
            'tanggal_dari'   => $tanggalDari,
            'tanggal_sampai' => $tanggalSampai,
            'shift'          => null,
            'pemeriksaans'   => collect(),
            'qcUser'         => null,
            'produksiUser'   => null,
            'spvQcUser'      => null,
            'produkMap'      => [],
            'isAllShift'     => true,
        ]);

        $filename = 'laporan-semua-shift-finish-good-'
            . ($tanggalDari ?? date('Y-m-d'))
            . ($tanggalSampai ? '-to-' . $tanggalSampai : '')
            . '.pdf';

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

        $query = PemeriksaanProdukFinishGood::query();

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
        } elseif ($userRole === 'produksi' || $userRole === 'warehouse') {
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
