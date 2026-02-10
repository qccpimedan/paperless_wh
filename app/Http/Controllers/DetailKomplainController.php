<?php

namespace App\Http\Controllers;

use App\Models\DetailKomplain;
use App\Models\Produk;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DetailKomplainController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = trim((string) $request->input('search', ''));

        $query = DetailKomplain::with(['user.role', 'user.plant', 'shift']);
        
        // Role-based filtering
        if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
            // Admin dan role lain hanya lihat data sesuai plant
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereDate('tanggal_kedatangan', $search)
                    ->orWhere('nama_supplier', 'like', '%' . $search . '%')
                    ->orWhere('no_po', 'like', '%' . $search . '%')
                    ->orWhere('nama_produk', 'like', '%' . $search . '%')
                    ->orWhere('kode_produksi', 'like', '%' . $search . '%')
                    ->orWhere('status_verifikasi', 'like', '%' . $search . '%')
                    ->orWhere('verification_notes', 'like', '%' . $search . '%')
                    ->orWhereHas('shift', function ($qs) use ($search) {
                        $qs->where('shift', 'like', '%' . $search . '%');
                    });
            });
        }
        
        $komplains = $query->latest()->paginate(25);
        return view('qc-sistem.detail-komplain.index', compact('komplains'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // Get shifts based on user's plant
        $shifts = Shift::query();
        if ($user->role->role !== 'SuperAdmin') {
            $shifts->whereHas('user', function($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $shifts = $shifts->get();
        
        // Get products based on user's plant
        $query = Produk::query();
        if ($user->role->role !== 'SuperAdmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $produks = $query->latest()->get();

        $produkKategoriOptions = $produks->pluck('kategori_code')
            ->filter(function ($v) {
                return $v !== null && $v !== '';
            })
            ->unique()
            ->values();

        $produkByKategori = $produks
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                        'kategori_code' => $p->kategori_code,
                    ];
                })->values();
            });

        return view('qc-sistem.detail-komplain.create', compact('produks', 'shifts', 'produkKategoriOptions', 'produkByKategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'tanggal_kedatangan' => 'required|date',
            'id_shift' => 'required|exists:shifts,id',  // Added shift validation
            'no_po' => 'required|string|max:100',
            'kategori_code' => 'nullable|array',
            'kategori_code.*' => 'nullable|string|max:255',
            'id_produk' => 'required|array|min:1',
            'id_produk.*' => 'required|exists:produks,id',
            'kode_produksi' => 'required|array|min:1',
            'kode_produksi.*' => 'required|string|max:100',
            'expired_date' => 'required|array|min:1',
            'expired_date.*' => 'required|date',
            'jumlah_datang' => 'required|array|min:1',
            'jumlah_datang.*' => 'required|string|max:100',
            'jumlah_di_tolak' => 'required|array|min:1',
            'jumlah_di_tolak.*' => 'required|string|max:100',
            'dokumentasi' => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:1024',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
            'di_buat_oleh' => 'nullable|array',
            'di_buat_oleh.*' => 'nullable|string|max:255',
            'setujui_oleh' => 'nullable|array',
            'setujui_oleh.*' => 'nullable|string|max:255',
        ]);

        $idProdukArr = array_values((array) $request->input('id_produk', []));
        $kategoriCodeArr = array_values((array) $request->input('kategori_code', []));
        $kodeProduksiArr = (array) $request->input('kode_produksi', []);
        $expiredDateArr = (array) $request->input('expired_date', []);
        $jumlahDatangArr = (array) $request->input('jumlah_datang', []);
        $jumlahDitolakArr = (array) $request->input('jumlah_di_tolak', []);
        $keteranganArr = (array) $request->input('keterangan', []);
        $dibuatArr = (array) $request->input('di_buat_oleh', []);
        $setujuiArr = (array) $request->input('setujui_oleh', []);

        $produkMetaById = Produk::whereIn('id', $idProdukArr)
            ->get(['id', 'nama_produk', 'kategori_code'])
            ->keyBy('id');

        $produkNamaById = $produkMetaById
            ->mapWithKeys(function ($p) {
                return [(string) $p->id => $p->nama_produk];
            })
            ->toArray();

        $produkKategoriById = $produkMetaById
            ->mapWithKeys(function ($p) {
                return [(string) $p->id => $p->kategori_code];
            })
            ->toArray();
        $namaProdukArr = array_map(function ($id) use ($produkNamaById) {
            $key = (string) $id;
            return $produkNamaById[$key] ?? null;
        }, $idProdukArr);

        // Fill kategori from user input; if missing/empty then derive from produk master
        $kategoriCodeArr = array_map(function ($idx) use ($kategoriCodeArr, $idProdukArr, $produkKategoriById) {
            $val = $kategoriCodeArr[$idx] ?? null;
            $val = is_string($val) ? trim($val) : $val;
            if ($val !== null && $val !== '') return $val;

            $id = $idProdukArr[$idx] ?? null;
            $key = $id !== null ? (string) $id : '';
            $derived = $key !== '' ? ($produkKategoriById[$key] ?? null) : null;
            return $derived;
        }, array_keys($idProdukArr));

        $dokumentasiPaths = [];
        $fileArr = (array) $request->file('dokumentasi', []);
        $rowCount = max(
            count($idProdukArr),
            count($kodeProduksiArr),
            count($expiredDateArr),
            count($jumlahDatangArr),
            count($jumlahDitolakArr),
            count($fileArr)
        );
        for ($i = 0; $i < $rowCount; $i++) {
            $file = $fileArr[$i] ?? null;
            if ($file) {
                $dokumentasiPaths[$i] = $file->storePublicly('komplain/dokumentasi', 'public');
            } else {
                $dokumentasiPaths[$i] = null;
            }
        }

        $firstNamaProduk = $namaProdukArr[0] ?? null;
        $firstKodeProduksi = $kodeProduksiArr[0] ?? null;
        $firstExpiredDate = $expiredDateArr[0] ?? null;
        $firstJumlahDatang = $jumlahDatangArr[0] ?? null;
        $firstJumlahDitolak = $jumlahDitolakArr[0] ?? null;
        $firstDokumentasi = $dokumentasiPaths[0] ?? null;
        $firstKeterangan = $keteranganArr[0] ?? null;
        $firstDibuat = $dibuatArr[0] ?? null;
        $firstSetujui = $setujuiArr[0] ?? null;

        DetailKomplain::create([
            'nama_supplier' => $request->nama_supplier,
            'tanggal_kedatangan' => $request->tanggal_kedatangan,
            'id_shift' => $request->id_shift,
            'no_po' => $request->no_po,

            // legacy scalar fields (keep compatibility with existing index/show)
            'nama_produk' => $firstNamaProduk,
            'kode_produksi' => $firstKodeProduksi,
            'expired_date' => $firstExpiredDate,
            'jumlah_datang' => $firstJumlahDatang,
            'jumlah_di_tolak' => $firstJumlahDitolak,
            'dokumentasi' => $firstDokumentasi,
            'keterangan' => $firstKeterangan,
            'di_buat_oleh' => $firstDibuat,
            'setujui_oleh' => $firstSetujui,

            // array/json fields (kemasan concept)
            'id_produk_array' => array_values($idProdukArr),
            'kategori_code_array' => array_values($kategoriCodeArr),
            'nama_produk_array' => array_values($namaProdukArr),
            'kode_produksi_array' => array_values($kodeProduksiArr),
            'expired_date_array' => array_values($expiredDateArr),
            'jumlah_datang_array' => array_values($jumlahDatangArr),
            'jumlah_di_tolak_array' => array_values($jumlahDitolakArr),
            'dokumentasi_array' => array_values($dokumentasiPaths),
            'keterangan_array' => array_values($keteranganArr),
            'di_buat_oleh_array' => array_values($dibuatArr),
            'setujui_oleh_array' => array_values($setujuiArr),

            'id_user' => Auth::id(),
        ]);

        return redirect()->route('detail-komplain.index')
                       ->with('success', 'Komplain berhasil ditambahkan');
    }

    public function show(DetailKomplain $detailKomplain)
    {
        $idProdukArr = is_array($detailKomplain->id_produk_array ?? null) ? $detailKomplain->id_produk_array : [];
        $idProdukArr = array_values(array_filter($idProdukArr, function ($v) {
            return $v !== null && $v !== '';
        }));

        $produkNamaById = !empty($idProdukArr)
            ? Produk::whereIn('id', $idProdukArr)->pluck('nama_produk', 'id')->toArray()
            : [];

        return view('qc-sistem.detail-komplain.show', compact('detailKomplain', 'produkNamaById'));
    }

    public function edit(DetailKomplain $detailKomplain)
    {
        $user = Auth::user();
        if ($user->role->role !== 'SuperAdmin' && $user->id_plant !== $detailKomplain->user->id_plant) {
            abort(403, 'Unauthorized');
        }

        $query = Produk::query();
        if ($user->role->role !== 'SuperAdmin') {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }

        $produks = $query->latest()->get();

        $produkKategoriOptions = $produks
            ->pluck('kategori_code')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $produkByKategori = $produks
            ->groupBy('kategori_code')
            ->map(function ($items) {
                return $items->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'nama' => $p->nama_produk,
                        'kategori_code' => $p->kategori_code,
                    ];
                })->values();
            });

        return view('qc-sistem.detail-komplain.edit', compact('detailKomplain', 'produks', 'produkKategoriOptions', 'produkByKategori'));
    }

    public function update(Request $request, DetailKomplain $detailKomplain)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'tanggal_kedatangan' => 'required|date',
            'no_po' => 'required|string|max:100',
            'kategori_code' => 'nullable|array',
            'kategori_code.*' => 'nullable|string|max:255',
            'id_produk' => 'required|array|min:1',
            'id_produk.*' => 'required|exists:produks,id',
            'kode_produksi' => 'required|array|min:1',
            'kode_produksi.*' => 'required|string|max:100',
            'expired_date' => 'required|array|min:1',
            'expired_date.*' => 'required|date',
            'jumlah_datang' => 'required|array|min:1',
            'jumlah_datang.*' => 'required|string|max:100',
            'jumlah_di_tolak' => 'required|array|min:1',
            'jumlah_di_tolak.*' => 'required|string|max:100',
            'dokumentasi_existing' => 'nullable|array',
            'dokumentasi_existing.*' => 'nullable|string',
            'dokumentasi' => 'nullable|array',
            'dokumentasi.*' => 'nullable|image|max:1024',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string',
            'di_buat_oleh' => 'nullable|array',
            'di_buat_oleh.*' => 'nullable|string|max:255',
            'setujui_oleh' => 'nullable|array',
            'setujui_oleh.*' => 'nullable|string|max:255',
        ]);

        $idProdukArr = array_values((array) $request->input('id_produk', []));
        $kategoriCodeArr = array_values((array) $request->input('kategori_code', []));
        $kodeProduksiArr = (array) $request->input('kode_produksi', []);
        $expiredDateArr = (array) $request->input('expired_date', []);
        $jumlahDatangArr = (array) $request->input('jumlah_datang', []);
        $jumlahDitolakArr = (array) $request->input('jumlah_di_tolak', []);
        $keteranganArr = (array) $request->input('keterangan', []);
        $dibuatArr = (array) $request->input('di_buat_oleh', []);
        $setujuiArr = (array) $request->input('setujui_oleh', []);

        $produkMetaById = Produk::whereIn('id', $idProdukArr)
            ->get(['id', 'nama_produk', 'kategori_code'])
            ->keyBy('id');

        $produkNamaById = $produkMetaById
            ->mapWithKeys(function ($p) {
                return [(string) $p->id => $p->nama_produk];
            })
            ->toArray();

        $produkKategoriById = $produkMetaById
            ->mapWithKeys(function ($p) {
                return [(string) $p->id => $p->kategori_code];
            })
            ->toArray();

        $namaProdukArr = array_map(function ($id) use ($produkNamaById) {
            $key = (string) $id;
            return $produkNamaById[$key] ?? null;
        }, $idProdukArr);

        // Fill kategori from user input; if missing/empty then derive from produk master
        $kategoriCodeArr = array_map(function ($idx) use ($kategoriCodeArr, $idProdukArr, $produkKategoriById) {
            $val = $kategoriCodeArr[$idx] ?? null;
            $val = is_string($val) ? trim($val) : $val;
            if ($val !== null && $val !== '') return $val;

            $id = $idProdukArr[$idx] ?? null;
            $key = $id !== null ? (string) $id : '';
            $derived = $key !== '' ? ($produkKategoriById[$key] ?? null) : null;
            return $derived;
        }, array_keys($idProdukArr));

        $existingDokArr = (array) $request->input('dokumentasi_existing', []);
        $uploadedFiles = (array) $request->file('dokumentasi', []);

        $rowCount = max(
            count($idProdukArr),
            count($kodeProduksiArr),
            count($expiredDateArr),
            count($jumlahDatangArr),
            count($jumlahDitolakArr),
            count($existingDokArr),
            count($uploadedFiles)
        );

        $dokumentasiPaths = [];
        $currentStored = is_array($detailKomplain->dokumentasi_array ?? null) ? $detailKomplain->dokumentasi_array : [];

        for ($i = 0; $i < $rowCount; $i++) {
            $oldPath = $existingDokArr[$i] ?? ($currentStored[$i] ?? null);
            $file = $uploadedFiles[$i] ?? null;

            if ($file) {
                $newPath = $file->storePublicly('komplain/dokumentasi', 'public');
                $dokumentasiPaths[$i] = $newPath;

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            } else {
                $dokumentasiPaths[$i] = $oldPath;
            }
        }

        $firstNamaProduk = $namaProdukArr[0] ?? null;
        $firstKodeProduksi = $kodeProduksiArr[0] ?? null;
        $firstExpiredDate = $expiredDateArr[0] ?? null;
        $firstJumlahDatang = $jumlahDatangArr[0] ?? null;
        $firstJumlahDitolak = $jumlahDitolakArr[0] ?? null;
        $firstDokumentasi = $dokumentasiPaths[0] ?? null;
        $firstKeterangan = $keteranganArr[0] ?? null;
        $firstDibuat = $dibuatArr[0] ?? null;
        $firstSetujui = $setujuiArr[0] ?? null;

        $detailKomplain->update([
            'nama_supplier' => $request->nama_supplier,
            'tanggal_kedatangan' => $request->tanggal_kedatangan,
            'no_po' => $request->no_po,

            'nama_produk' => $firstNamaProduk,
            'kode_produksi' => $firstKodeProduksi,
            'expired_date' => $firstExpiredDate,
            'jumlah_datang' => $firstJumlahDatang,
            'jumlah_di_tolak' => $firstJumlahDitolak,
            'dokumentasi' => $firstDokumentasi,
            'keterangan' => $firstKeterangan,
            'di_buat_oleh' => $firstDibuat,
            'setujui_oleh' => $firstSetujui,

            'id_produk_array' => array_values($idProdukArr),
            'kategori_code_array' => array_values($kategoriCodeArr),
            'nama_produk_array' => array_values($namaProdukArr),
            'kode_produksi_array' => array_values($kodeProduksiArr),
            'expired_date_array' => array_values($expiredDateArr),
            'jumlah_datang_array' => array_values($jumlahDatangArr),
            'jumlah_di_tolak_array' => array_values($jumlahDitolakArr),
            'dokumentasi_array' => array_values($dokumentasiPaths),
            'keterangan_array' => array_values($keteranganArr),
            'di_buat_oleh_array' => array_values($dibuatArr),
            'setujui_oleh_array' => array_values($setujuiArr),
        ]);

        return redirect()->route('detail-komplain.index')
                       ->with('success', 'Komplain berhasil diupdate');
    }

    public function uploadSuplier(Request $request, DetailKomplain $detailKomplain)
    {
        $request->validate([
            'upload_suplier' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ]);

        // Hapus file lama jika ada
        if ($detailKomplain->upload_suplier) {
            Storage::disk('public')->delete($detailKomplain->upload_suplier);
        }

        // Upload file baru
        $file = $request->file('upload_suplier');
        $uploadPath = $file->store('komplain/upload-suplier', 'public');

        // Update database
        $detailKomplain->update(['upload_suplier' => $uploadPath]);

        return redirect()->route('detail-komplain.index')
                       ->with('success', 'File supplier berhasil diupload');
    }

    public function destroy(DetailKomplain $detailKomplain)
    {
        $user = Auth::user();
        // SuperAdmin bisa hapus semua, Admin hanya sesuai plant
        if ($user->role->role !== 'SuperAdmin' && $user->id_plant !== $detailKomplain->user->id_plant) {
            abort(403, 'Unauthorized');
        }
        
        // Hapus file dokumentasi jika ada
        if ($detailKomplain->dokumentasi) {
            Storage::disk('public')->delete($detailKomplain->dokumentasi);
        }

        // Hapus file upload supplier jika ada
        if ($detailKomplain->upload_suplier) {
            Storage::disk('public')->delete($detailKomplain->upload_suplier);
        }

        $detailKomplain->delete();

        return redirect()->route('detail-komplain.index')
                       ->with('success', 'Komplain berhasil dihapus');
    }

    public function exportPdf(DetailKomplain $detailKomplain)
    {
        $user = Auth::user();
        // SuperAdmin bisa export semua, Admin hanya sesuai plant
        if ($user->role->role !== 'SuperAdmin' && $user->id_plant !== $detailKomplain->user->id_plant) {
            abort(403, 'Unauthorized');
        }

        $pdf = Pdf::loadView('qc-sistem.detail-komplain.eksport_pdf', compact('detailKomplain'));
        return $pdf->download('komplain-' . $detailKomplain->uuid . '.pdf');
    }

    /**
     * Send komplain to QC for verification
     */
    public function sendToQC(DetailKomplain $detailKomplain)
    {
        $user = Auth::user();
        
        // Only pending status can be sent
        if ($detailKomplain->status_verifikasi !== 'pending') {
            return redirect()->back()->with('error', 'Hanya komplain dengan status pending yang dapat dikirim.');
        }
        
        $detailKomplain->update([
            'status_verifikasi' => 'sent_to_qc',
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Komplain berhasil dikirim ke QC.');
    }

    /**
     * Approve komplain from QC
     */
    public function approveQC(Request $request, DetailKomplain $detailKomplain)
    {
        $user = Auth::user();
        
        // Only sent_to_qc status can be approved
        if ($detailKomplain->status_verifikasi !== 'sent_to_qc') {
            return redirect()->back()->with('error', 'Status komplain tidak valid untuk di-approve.');
        }
        
        $detailKomplain->update([
            'status_verifikasi' => 'approved_qc',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Komplain berhasil di-approve oleh QC.');
    }

    /**
     * Reject komplain from QC
     */
    public function rejectQC(Request $request, DetailKomplain $detailKomplain)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        
        // Only sent_to_qc status can be rejected
        if ($detailKomplain->status_verifikasi !== 'sent_to_qc') {
            return redirect()->back()->with('error', 'Status komplain tidak valid untuk di-reject.');
        }
        
        $detailKomplain->update([
            'status_verifikasi' => 'rejected_qc',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Komplain ditolak oleh QC. Silakan perbaiki dan kirim ulang.');
    }

    /**
     * Approve komplain from SPV QC (final verification)
     */
    public function approveSPV(Request $request, DetailKomplain $detailKomplain)
    {
        $user = Auth::user();
        
        // Only approved_qc status can be approved by SPV
        if ($detailKomplain->status_verifikasi !== 'approved_qc') {
            return redirect()->back()->with('error', 'Komplain harus disetujui QC terlebih dahulu.');
        }
        
        $detailKomplain->update([
            'status_verifikasi' => 'approved_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('success', 'Komplain berhasil diverifikasi oleh SPV QC.');
    }

    /**
     * Reject komplain from SPV QC (final verification)
     */
    public function rejectSPV(Request $request, DetailKomplain $detailKomplain)
    {
        $request->validate([
            'notes' => 'required|string|min:5',
        ]);
        
        $user = Auth::user();
        
        // Only approved_qc status can be rejected by SPV
        if ($detailKomplain->status_verifikasi !== 'approved_qc') {
            return redirect()->back()->with('error', 'Status komplain tidak valid untuk di-reject.');
        }
        
        $detailKomplain->update([
            'status_verifikasi' => 'rejected_spv',
            'verified_by' => $user->id,
            'verified_at' => now(),
            'verification_notes' => $request->input('notes'),
        ]);
        
        return redirect()->back()->with('error', 'Komplain ditolak oleh SPV QC. Silakan perbaiki dan kirim ulang.');
    }
}
