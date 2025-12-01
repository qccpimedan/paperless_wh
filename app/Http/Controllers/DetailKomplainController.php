<?php

namespace App\Http\Controllers;

use App\Models\DetailKomplain;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DetailKomplainController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = DetailKomplain::with('user');
        
        // Role-based filtering
        if ($user->role->role !== 'SuperAdmin') {
            // Admin dan role lain hanya lihat data sesuai plant
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        
        $komplains = $query->latest()->get();
        return view('qc-sistem.detail-komplain.index', compact('komplains'));
    }

    public function create()
    {
        $user = Auth::user();
        // Semua user yang login bisa create
        
        // Filter produk berdasarkan plant
        $query = Produk::query();
        if ($user->role->role !== 'SuperAdmin') {
            // Admin dan role lain hanya lihat produk sesuai plant
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $produks = $query->latest()->get();
        return view('qc-sistem.detail-komplain.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'tanggal_kedatangan' => 'required|date',
            'no_po' => 'required|string|max:100',
            'nama_produk' => 'required|string|max:255',
            'kode_produksi' => 'required|string|max:100',
            'expired_date' => 'required|date',
            'jumlah_datang' => 'required|string|max:100',
            'jumlah_di_tolak' => 'required|string|max:100',
            'dokumentasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'keterangan' => 'nullable|string',
            'di_buat_oleh' => 'nullable|string|max:255',
            'setujui_oleh' => 'nullable|string|max:255',
        ]);

        // Upload dokumentasi jika ada
        $dokumentasiPath = null;
        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $dokumentasiPath = $file->store('komplain/dokumentasi', 'public');
        }

        // Simpan ke database
        DetailKomplain::create([
            'nama_supplier' => $request->nama_supplier,
            'tanggal_kedatangan' => $request->tanggal_kedatangan,
            'no_po' => $request->no_po,
            'nama_produk' => $request->nama_produk,
            'kode_produksi' => $request->kode_produksi,
            'expired_date' => $request->expired_date,
            'jumlah_datang' => $request->jumlah_datang,
            'jumlah_di_tolak' => $request->jumlah_di_tolak,
            'dokumentasi' => $dokumentasiPath,
            'keterangan' => $request->keterangan,
            'di_buat_oleh' => $request->di_buat_oleh,
            'setujui_oleh' => $request->setujui_oleh,
            'id_user' => Auth::id(),
        ]);

        return redirect()->route('detail-komplain.index')
                       ->with('success', 'Komplain berhasil ditambahkan');
    }

    public function show(DetailKomplain $detailKomplain)
    {
        return view('qc-sistem.detail-komplain.show', compact('detailKomplain'));
    }

    public function edit(DetailKomplain $detailKomplain)
    {
        $user = Auth::user();
        // SuperAdmin bisa edit semua, Admin hanya sesuai plant
        if ($user->role->role !== 'SuperAdmin' && $user->id_plant !== $detailKomplain->user->id_plant) {
            abort(403, 'Unauthorized');
        }
        
        // Filter produk berdasarkan plant
        $query = Produk::query();
        if ($user->role->role !== 'SuperAdmin') {
            // Admin dan role lain hanya lihat produk sesuai plant
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('id_plant', $user->id_plant);
            });
        }
        $produks = $query->latest()->get();
        return view('qc-sistem.detail-komplain.edit', compact('detailKomplain', 'produks'));
    }

    public function update(Request $request, DetailKomplain $detailKomplain)
    {
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'tanggal_kedatangan' => 'required|date',
            'no_po' => 'required|string|max:100',
            'nama_produk' => 'required|string|max:255',
            'kode_produksi' => 'required|string|max:100',
            'expired_date' => 'required|date',
            'jumlah_datang' => 'required|string|max:100',
            'jumlah_di_tolak' => 'required|string|max:100',
            'dokumentasi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'keterangan' => 'nullable|string',
            'di_buat_oleh' => 'nullable|string|max:255',
            'setujui_oleh' => 'nullable|string|max:255',
        ]);

        // Update dokumentasi jika ada file baru
        $dokumentasiPath = $detailKomplain->dokumentasi;
        if ($request->hasFile('dokumentasi')) {
            if ($detailKomplain->dokumentasi) {
                Storage::disk('public')->delete($detailKomplain->dokumentasi);
            }
            $file = $request->file('dokumentasi');
            $dokumentasiPath = $file->store('komplain/dokumentasi', 'public');
        }

        // Update data
        $detailKomplain->update([
            'nama_supplier' => $request->nama_supplier,
            'tanggal_kedatangan' => $request->tanggal_kedatangan,
            'no_po' => $request->no_po,
            'nama_produk' => $request->nama_produk,
            'kode_produksi' => $request->kode_produksi,
            'expired_date' => $request->expired_date,
            'jumlah_datang' => $request->jumlah_datang,
            'jumlah_di_tolak' => $request->jumlah_di_tolak,
            'dokumentasi' => $dokumentasiPath,
            'keterangan' => $request->keterangan,
            'di_buat_oleh' => $request->di_buat_oleh,
            'setujui_oleh' => $request->setujui_oleh,
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
}
