/**
 * Validasi Frontend untuk Pemeriksaan Loading Produk
 * 
 * Field yang WAJIB diisi (except Keterangan):
 * - Tanggal
 * - Minimal 1 detail produk dengan: Kode Produksi, Best Before, Jumlah Kemasan, Jumlah Sampling, Berat per Karung
 * 
 * Validasi dilakukan saat SUBMIT (bukan real-time)
 */

document.addEventListener('DOMContentLoaded', function() {
    const formLoadingProduk = document.getElementById('form-pemeriksaan-loading-produk');
    
    if (!formLoadingProduk) {
        console.warn('Form pemeriksaan-loading-produk tidak ditemukan');
        return;
    }

    // Attach validasi pada form submit
    formLoadingProduk.addEventListener('submit', function(e) {
        if (!validateFormLoadingProduk()) {
            e.preventDefault();
            console.log('Form validation failed');
        }
    });

    /**
     * Main validation function
     */
    function validateFormLoadingProduk() {
        // Clear previous error alerts
        clearPreviousErrors();

        const errors = [];

        // 1. Validasi Tanggal
        const tanggalInput = document.getElementById('tanggal');
        if (!tanggalInput.value.trim()) {
            errors.push('❌ <strong>Tanggal</strong> harus diisi');
        }

        // 2. Validasi produk_data (minimal 1 detail lengkap)
        const produkDataErrors = validateProdukData();
        if (produkDataErrors.length > 0) {
            errors.push(...produkDataErrors);
        }

        // 3. Jika ada error, tampilkan alert dan return false
        if (errors.length > 0) {
            showValidationAlert(errors);
            return false;
        }

        return true;
    }

    /**
     * Validasi struktur produk_data[]
     */
    function validateProdukData() {
        const errors = [];
        const produkGroups = document.querySelectorAll('.produk-group');

        if (produkGroups.length === 0) {
            errors.push('❌ <strong>Data Produk</strong> minimal harus ada 1 grup produk');
            return errors;
        }

        let hasProdukValid = false;

        produkGroups.forEach((group, groupIdx) => {
            const groupNumber = groupIdx + 1;
            const produkRows = group.querySelectorAll('.produk-row');

            if (produkRows.length === 0) {
                errors.push(`⚠️ <strong>Produk #${groupNumber}</strong>: minimal harus ada 1 detail produk`);
                return;
            }

            let hasDetailValid = false;

            produkRows.forEach((row, detailIdx) => {
                const detailNumber = detailIdx + 1;
                const kodeProduks = row.querySelector('input[name*="kode_produksi"]');
                const bestBefore = row.querySelector('input[name*="best_before"]');
                const jumlahKemasan = row.querySelector('input[name*="jumlah_kemasan"]');
                const jumlahSampling = row.querySelector('input[name*="jumlah_sampling"]');
                const beratPerkarung = row.querySelector('input[name*="berat_perkarung"]');

                // Keterangan tidak wajib diisi (dilewati dari validasi)

                const fields = [
                    { el: kodeProduks, nama: 'Kode Produksi' },
                    { el: bestBefore, nama: 'Best Before' },
                    { el: jumlahKemasan, nama: 'Jumlah Kemasan' },
                    { el: jumlahSampling, nama: 'Jumlah Sampling' },
                    { el: beratPerkarung, nama: 'Berat per Karung/Box' }
                ];

                let detailError = [];

                fields.forEach(field => {
                    if (field.el && (!field.el.value || !field.el.value.trim())) {
                        detailError.push(field.nama);
                    }
                });

                if (detailError.length > 0) {
                    errors.push(
                        `⚠️ <strong>Produk #${groupNumber} - Detail #${detailNumber}</strong>: ` +
                        `<em>${detailError.join(', ')}</em> harus diisi`
                    );
                } else {
                    hasDetailValid = true;
                }
            });

            if (hasDetailValid) {
                hasProdukValid = true;
            }
        });

        if (!hasProdukValid) {
            errors.push('❌ <strong>Data Produk</strong>: minimal harus ada 1 detail produk yang lengkap');
        }

        return errors;
    }

    /**
     * Clear previous validation alerts
     */
    function clearPreviousErrors() {
        const existingAlert = document.querySelector('.alert-validation-loading-produk');
        if (existingAlert) {
            existingAlert.remove();
        }
    }

    /**
     * Show validation error alert
     */
    function showValidationAlert(errors) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger alert-validation-loading-produk';
        alertDiv.style.marginBottom = '20px';
        alertDiv.innerHTML = `
            <div class="d-flex align-items-start gap-3">
                <div style="flex-shrink: 0;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem;"></i>
                </div>
                <div style="flex-grow: 1;">
                    <h5 class="alert-heading mb-2">⚠️ Validasi Gagal</h5>
                    <p class="mb-0">Mohon lengkapi data berikut sebelum menyimpan:</p>
                    <ul class="mb-0 mt-2 ps-3">
                        ${errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;

        // Insert alert at top of form body
        const formBody = document.querySelector('.card-body');
        if (formBody) {
            formBody.insertBefore(alertDiv, formBody.firstChild);
            // Scroll to alert
            alertDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});
