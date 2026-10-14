<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait HasAuditController
{
    /**
     * Abstract methods yang HARUS diimplementasikan di controller
     */
    abstract protected function auditModel(): string;
    abstract protected function auditRoutePrefix(): string;
    abstract protected function auditViewPrefix(): string;

    /**
     * Optional methods dengan default values
     */
    protected function auditRelations(): array 
    { 
        return ['shift', 'user', 'plant']; 
    }

    protected function auditSearchColumns(): array 
    { 
        return ['kode_produksi', 'keterangan']; 
    }

    protected function auditDateColumn(): string 
    { 
        return 'tanggal'; 
    }

    protected function auditTimeColumn(): string 
    { 
        return 'jam'; 
    }

    protected function auditShiftRelation(): string 
    { 
        return 'shift'; 
    }

    protected function auditShiftColumn(): string 
    { 
        return 'shift_id'; 
    }

    protected function auditPlanColumn(): string 
    { 
        return 'id_plan'; 
    }

    /**
     * Smart Copy & Edit Audit: Auto copy saat edit jika belum ada versi audit
     * 
     * Flow:
     * 1. Jika user klik "Edit Data Audit" pada data operasional
     * 2. Cek apakah sudah pernah di-copy ke audit
     * 3. Jika belum: Auto-copy + redirect ke edit audit
     * 4. Jika sudah: Langsung redirect ke edit audit yang sudah ada
     */
    public function copyToAudit($uuid)
    {
        $model = $this->auditModel();
        $item = $model::where('uuid', $uuid)->firstOrFail();

        // Check role: hanya superadmin, admin, spv qc yang boleh copy/edit data audit
        $allowedRoles = ['superadmin', 'admin', 'spv qc'];
        $userRole = strtolower(Auth::user()->role?->role ?? '');
        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit data audit.');
        }

        // Jika sudah dalam mode audit, langsung edit
        if ($item->is_audit) {
            return redirect()->route("{$this->auditRoutePrefix()}.edit", [$item->uuid, 'is_audit' => 1]);
        }

        // Cek apakah sudah pernah di-copy ke audit
        if ($item->auditVersion) {
            return redirect()->route("{$this->auditRoutePrefix()}.edit", [$item->auditVersion->uuid, 'is_audit' => 1])
                ->with('info', 'Data audit sudah ada sebelumnya. Silakan lakukan penyesuaian.');
        }

        // Belum pernah di-copy: Lakukan copy + auto-correction
        $clone = $item->copyToAudit();

        return redirect()->route("{$this->auditRoutePrefix()}.edit", [$clone->uuid, 'is_audit' => 1])
            ->with('success', 'Salinan data audit berhasil dibuat dengan koreksi otomatis. Silakan lakukan penyesuaian jika diperlukan.');
    }

    /**
     * Index Audit dengan Audit-First Smart Query Filter & Collation-Safe Logic
     */
    public function auditIndex(Request $request)
    {
        $model = $this->auditModel();
        $user = Auth::user();
        $search = $request->get('search');
        $perPage = $request->get('per_page', 10);

        // Ambil daftar source_uuid yang sudah di-copy ke audit (Bebas Error Collation)
        $auditedSourceUuids = $model::where('is_audit', true)
            ->whereNotNull('source_uuid')
            ->pluck('source_uuid')
            ->toArray();

        // Builder factory agar bisa dipakai dua kali (grouped vs flat)
        $buildQuery = function () use ($model, $user, $auditedSourceUuids, $search) {
            $q = $model::with($this->auditRelations())
                ->where(function ($q) use ($auditedSourceUuids) {
                    $q->where('is_audit', true)
                      ->orWhere(function ($subQ) use ($auditedSourceUuids) {
                          $subQ->where('is_audit', false);
                          if (!empty($auditedSourceUuids)) {
                              $subQ->whereNotIn('uuid', $auditedSourceUuids);
                          }
                      });
                });

            // Filter plant (hanya untuk non-superadmin)
            if (!($user->role && strtolower($user->role->role) === 'superadmin')) {
                $q->whereHas('user', function ($userQuery) use ($user) {
                    $userQuery->where('id_plant', $user->getEffectivePlantId());
                });
            }

            if ($search) {
                $q->where(function ($sq) use ($search) {
                    foreach ($this->auditSearchColumns() as $column) {
                        $sq->orWhere($column, 'LIKE', '%' . $search . '%');
                    }
                });
            }

            return $q;
        };

        $dateColumn = $this->auditDateColumn();
        $timeColumn = $this->auditTimeColumn();

        // ── Cek apakah view index_audit terpisah tersedia ──────────────────
        $auditView = "{$this->auditViewPrefix()}.index_audit";
        if (view()->exists($auditView)) {
            // View terpisah: kirim data grouped (dikelompokkan per tanggal+shift)
            $records = $buildQuery()
                ->orderBy($dateColumn, 'desc')
                ->orderBy($timeColumn, 'desc')
                ->paginate($perPage)
                ->appends($request->all());

            $groupedData = $records->getCollection()->groupBy(function ($item) use ($dateColumn) {
                $dateStr = $item->{$dateColumn}
                    ? (is_string($item->{$dateColumn})
                        ? \Carbon\Carbon::parse($item->{$dateColumn})->format('Y-m-d')
                        : $item->{$dateColumn}->format('Y-m-d'))
                    : 'no_date';
                $shiftId = $item->{$this->auditShiftColumn()} ?? 'no_shift';
                return $dateStr . '_' . $shiftId;
            });

            $records->setCollection($groupedData);

            return view($auditView, compact('records', 'search', 'perPage'));
        }

        // ── FALLBACK: Reuse view index utama ──────────────────────────────
        // Tidak perlu buat file view baru. Kirim $isAuditView=true dan
        // $auditRecords ke view yang sama. View tinggal cek variabel ini.
        $auditRecords = $buildQuery()
            ->orderBy($dateColumn, 'desc')
            ->orderBy($timeColumn, 'desc')
            ->paginate($perPage)
            ->appends($request->all());

        $mainView = "{$this->auditViewPrefix()}.index";
        $isAuditView = true;
        $auditRoutePrefix = $this->auditRoutePrefix();

        // Deteksi nama variabel yang dipakai view berdasarkan auditViewPrefix
        // agar view yang sudah ada ($pemeriksaans, $loadingProduks, dll) tetap bekerja
        $viewVarName = $this->auditViewVariableName();

        return view($mainView, array_merge(
            compact('isAuditView', 'auditRecords', 'auditRoutePrefix', 'search', 'perPage'),
            [$viewVarName => $auditRecords],
            $this->auditExtraViewData($auditRecords)
        ));
    }

    /**
     * Hook untuk menyediakan variabel tambahan ke view index jika dibutuhkan.
     */
    protected function auditExtraViewData($records): array
    {
        return [];
    }

    /**
     * Nama variabel yang digunakan oleh view index masing-masing modul.
     * Override di controller jika nama variabel berbeda dari default 'pemeriksaans'.
     */
    protected function auditViewVariableName(): string
    {
        return 'pemeriksaans';
    }

    /**
     * AJAX Search untuk Halaman Audit Datatable
     * Digunakan untuk live search tanpa reload halaman
     */
    public function auditSearchAjax(Request $request)
    {
        try {
            $model = $this->auditModel();
            $user = Auth::user();
            $search = $request->get('search');
            $perPage = (int) $request->get('per_page', 10);
            $page = (int) $request->get('page', 1);

            // Smart Filter Query (sama dengan auditIndex)
            $auditedSourceUuids = $model::where('is_audit', true)
                ->whereNotNull('source_uuid')
                ->pluck('source_uuid')
                ->toArray();

            $query = $model::with($this->auditRelations())
                ->where(function ($q) use ($auditedSourceUuids) {
                    $q->where('is_audit', true)
                      ->orWhere(function ($subQ) use ($auditedSourceUuids) {
                          $subQ->where('is_audit', false);
                          if (!empty($auditedSourceUuids)) {
                              $subQ->whereNotIn('uuid', $auditedSourceUuids);
                          }
                      });
                })
                ->when($user->role !== 'superadmin', function ($q) use ($user) {
                    $q->where($this->auditPlanColumn(), $user->id_plan);
                });

            // Search
            if ($search) {
                $query->where(function ($q) use ($search) {
                    foreach ($this->auditSearchColumns() as $column) {
                        $q->orWhere($column, 'LIKE', '%' . $search . '%');
                    }
                });
            }

            $dateColumn = $this->auditDateColumn();
            $timeColumn = $this->auditTimeColumn();

            $records = $query->orderBy($dateColumn, 'desc')
                ->orderBy($timeColumn, 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $total = $records->total();

            // Group by date + shift
            $groupedData = $records->getCollection()->groupBy(function ($item) use ($dateColumn) {
                $dateStr = $item->{$dateColumn}
                    ? (is_string($item->{$dateColumn})
                        ? \Carbon\Carbon::parse($item->{$dateColumn})->format('Y-m-d')
                        : $item->{$dateColumn}->format('Y-m-d'))
                    : 'no_date';

                $shiftId = $item->{$this->auditShiftColumn()} ?? 'no_shift';
                
                return $dateStr . '_' . $shiftId;
            });

            $records->setCollection($groupedData);

            return response()->json([
                'success' => true,
                'html' => view("{$this->auditViewPrefix()}._table", [
                    'records' => $records,
                    'isAuditView' => true
                ])->render(),
                'pagination' => [
                    'current_page' => $page,
                    'last_page' => (int) ceil($total / $perPage),
                    'per_page' => $perPage,
                    'total' => $total,
                    'from' => $records->firstItem(),
                    'to' => $records->lastItem(),
                    'has_pages' => $total > $perPage,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper untuk menerapkan Audit-First Smart Query pada Query Export PDF
     * Digunakan di method exportPdf() controller
     */
    protected function applyAuditPdfScope($query, Request $request)
    {
        if ($request->boolean('is_audit')) {
            $model = $this->auditModel();
            
            $auditedSourceUuids = $model::where('is_audit', true)
                ->whereNotNull('source_uuid')
                ->pluck('source_uuid')
                ->toArray();

            return $query->where(function ($q) use ($auditedSourceUuids) {
                $q->where('is_audit', true)
                  ->orWhere(function ($subQ) use ($auditedSourceUuids) {
                      $subQ->where('is_audit', false);
                      if (!empty($auditedSourceUuids)) {
                          $subQ->whereNotIn('uuid', $auditedSourceUuids);
                      }
                  });
            });
        }

        return $query->where('is_audit', false);
    }
}
