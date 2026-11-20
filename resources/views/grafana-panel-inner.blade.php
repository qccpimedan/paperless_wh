{{-- resources/views/grafana-panel-inner.blade.php --}}
@php
    // Paksa tema terang di Grafana (hapus parameter theme lama bila ada, lalu tambahkan theme=light)
    $src = rtrim(preg_replace('/([?&])theme=[^&]*(&|$)/', '$1', $panelUrls[$panelId]), '&?');
    $src .= (str_contains($src, '?') ? '&' : '?') . 'theme=light';
@endphp
<div class="qc-grafana-loader" aria-live="polite">
    <img src="{{ asset('dist/images/logo/cpi-logo.png') }}" alt="CP">
    <span class="spinner-border text-primary" role="status" aria-label="Memuat"></span>
    <small>Memuat dashboard…</small>
</div>
<div class="qc-grafana-error">
    <span>Panel belum berhasil dimuat.</span>
    <button type="button" class="btn btn-outline-primary btn-sm qc-grafana-retry">Coba lagi</button>
</div>
@if(!empty($showLabel))
    <div class="qc-grafana-label">{{ $panelNames[$panelId] }}</div>
@endif
<div class="qc-grafana-frame">
    <iframe
        data-src="{{ $src }}"
        title="{{ $panelNames[$panelId] }}"
        loading="lazy"
        referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen>
    </iframe>
</div>