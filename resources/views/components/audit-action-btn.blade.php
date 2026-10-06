@props(['item', 'routePrefix', 'isAuditView' => false])

@php
    /**
     * Komponen: audit-action-btn
     *
     * Props:
     *   :item          => object model (harus punya uuid & is_audit)
     *   route-prefix   => string, contoh: 'pemeriksaan-kedatangan-kemasan'
     *   :is-audit-view => boolean (optional, default: false)
     *
     * Penggunaan:
     *   <x-audit-action-btn :item="$pemeriksaan" route-prefix="pemeriksaan-kedatangan-kemasan" :is-audit-view="$isAuditView ?? false" />
     *
     * Hanya tampil jika:
     *   - Role user adalah superadmin, admin, atau spv qc
     *   - Item bukan data audit sendiri (is_audit = false)
     *   - Bukan di halaman audit ($isAuditView != true)
     *
     * Controller copyToAudit() sudah menangani semua case:
     *   - Jika belum ada versi audit → buat salinan baru, redirect ke edit audit
     *   - Jika sudah ada versi audit → langsung redirect ke edit audit yang ada
     *   - Jika item sendiri adalah audit → redirect ke edit audit
     */

    $allowedRoles = ['superadmin', 'admin', 'spv qc'];
    $userRole     = auth()->user()?->role ? strtolower(auth()->user()->role->role) : null;
    $canSeeBtn    = in_array($userRole, $allowedRoles);
    $isAuditItem  = (bool) ($item->is_audit ?? false);
    $copyRoute    = $routePrefix . '.copy-to-audit';
    $routeExists  = Route::has($copyRoute);
    
    // HIDE tombol di mode audit
    $shouldShow = $canSeeBtn && !$isAuditItem && !$isAuditView && $routeExists;
@endphp

@if($shouldShow)
    <a href="{{ route($copyRoute, $item->uuid) }}"
       class="btn btn-sm"
       style="background:#7c3aed; color:#fff; border:none;"
       title="Salin / Edit Data Audit">
        <i class="bi bi-shield-plus"></i>
    </a>
@endif
