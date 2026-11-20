{{-- resources/views/partials/grafana-panel-inner.blade.php --}}
<div class="qc-grafana-loader" aria-live="polite">
    <img src="{{ asset('dist/images/logo/cpi-logo.png') }}" alt="CP">
    <span class="spinner-border text-primary" role="status" aria-label="Memuat"></span>
    <small>Memuat dashboard…</small>
</div>
<div class="qc-grafana-error">
    <span>Panel belum berhasil dimuat.</span>
    <button type="button" class="btn btn-outline-primary btn-sm qc-grafana-retry">Coba lagi</button>
</div>
<div class="qc-grafana-brand-mask" aria-hidden="true"></div>
<iframe
    data-src="{{ $panelUrls[$panelId] }}"
    title="{{ $panelNames[$panelId] }}"
    loading="lazy"
    referrerpolicy="strict-origin-when-cross-origin"
    allowfullscreen>
</iframe>