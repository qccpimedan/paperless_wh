/**
 * Auto-refresh CSRF Token, Pre-submit Guard, dan Auto-save Form Data
 * Mencegah 419 Page Expired saat user menginput form dalam waktu lama / setelah tablet sleep.
 */

(function () {
    'use strict';

    if (window.csrfRefreshInitialized) {
        console.log('[CSRF Guard] Already initialized.');
        return;
    }
    window.csrfRefreshInitialized = true;

    const CSRF_REFRESH_INTERVAL = 2 * 60 * 1000; // Refresh tiap 2 menit di background (Keep-Alive)
    const AUTOSAVE_INTERVAL = 1 * 60 * 1000;     // Auto-save draft tiap 1 menit

    let csrfRefreshTimer = null;
    let autoSaveTimer = null;
    let isRefreshingToken = false;

    /**
     * Refresh CSRF Token dari server (Async)
     */
    async function refreshCsrfToken() {
        if (isRefreshingToken) return null;
        isRefreshingToken = true;

        try {
            const response = await fetch('/csrf-token', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            if (data.csrf_token) {
                updateCsrfTokensInDOM(data.csrf_token);
                console.log('[CSRF Guard] Token refreshed successfully.');
                return data.csrf_token;
            }
        } catch (error) {
            console.warn('[CSRF Guard] Refresh failed:', error);
        } finally {
            isRefreshingToken = false;
        }
        return null;
    }

    /**
     * Update semua input _token dan meta tag di DOM
     */
    function updateCsrfTokensInDOM(token) {
        if (!token) return;

        // Update semua <input name="_token">
        document.querySelectorAll('input[name="_token"]').forEach(input => {
            input.value = token;
        });

        // Update <meta name="csrf-token">
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', token);
        }

        // Update jQuery AJAX setup jika ada
        if (window.jQuery && window.jQuery.ajaxSetup) {
            window.jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
        }
    }

    /**
     * Save Form Data ke LocalStorage
     */
    function saveFormDataToStorage(form) {
        if (!form) return;
        const formId = form.id || form.getAttribute('action') || window.location.pathname;
        const formData = {};

        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (!input.name || input.name === '_token' || input.type === 'file' || input.type === 'password') return;

            if (input.classList.contains('choices__input') || input.classList.contains('choices__list')) return;

            if (input.type === 'checkbox') {
                formData[input.name] = input.checked;
            } else if (input.type === 'radio') {
                if (input.checked) formData[input.name] = input.value;
            } else {
                formData[input.name] = input.value;
            }
        });

        try {
            localStorage.setItem('draft_' + formId, JSON.stringify({
                data: formData,
                timestamp: Date.now()
            }));
            console.log('[Draft Guard] Saved draft for:', formId);
        } catch (e) {
            console.warn('[Draft Guard] Storage limit reached:', e);
        }
    }

    /**
     * Restore Form Data dari LocalStorage jika ada
     */
    function restoreFormDataFromStorage() {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            const formId = form.id || form.getAttribute('action') || window.location.pathname;
            const savedRaw = localStorage.getItem('draft_' + formId);
            if (!savedRaw) return;

            try {
                const { data, timestamp } = JSON.parse(savedRaw);
                // Restore jika draft berusia kurang dari 24 jam
                if (Date.now() - timestamp < 24 * 60 * 60 * 1000) {
                    let restoredCount = 0;

                    Object.keys(data).forEach(name => {
                        const input = form.querySelector(`[name="${name}"]`);
                        if (input && !input.value) {
                            if (input.type === 'checkbox') {
                                input.checked = data[name];
                            } else if (input.type === 'radio') {
                                const radio = form.querySelector(`[name="${name}"][value="${data[name]}"]`);
                                if (radio) radio.checked = true;
                            } else {
                                input.value = data[name];
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                            restoredCount++;
                        }
                    });

                    if (restoredCount > 0) {
                        showToastInfo('Draft data formulir sebelumnya berhasil dipulihkan!');
                    }
                }
            } catch (e) {
                console.error('[Draft Guard] Restore error:', e);
            }
        });
    }

    /**
     * Clear draft setelah form berhasil disubmit
     */
    function clearFormDraft(form) {
        if (!form) return;
        const formId = form.id || form.getAttribute('action') || window.location.pathname;
        localStorage.removeItem('draft_' + formId);
    }

    /**
     * Toast info sederhana
     */
    function showToastInfo(msg) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Draft Dipulihkan',
                text: msg,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });
        }
    }

    /**
     * Global Pre-Submit Interceptor: Refresh CSRF token tepat sebelum submit disetujui!
     */
    function setupPreSubmitGuard() {
        document.addEventListener('submit', async function (event) {
            const form = event.target;
            if (!form || form.tagName !== 'FORM') return;

            const method = (form.getAttribute('method') || 'GET').toUpperCase();
            if (method === 'GET') return; // Tidak perlu CSRF untuk GET

            // Jika submit sudah diproses oleh guard ini, biarkan lewat
            if (form.dataset.csrfGuardPassed === 'true') {
                delete form.dataset.csrfGuardPassed;
                clearFormDraft(form);
                return;
            }

            // Hentikan submit sementara untuk merefresh token CSRF secara kilat
            event.preventDefault();
            event.stopPropagation();

            // Simpan draft sebagai perlindungan cadangan
            saveFormDataToStorage(form);

            // Tampilkan loading / disable tombol submit jika perlu
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
            }

            console.log('[CSRF Guard] Pre-submit refreshing CSRF token...');

            try {
                // Refresh token dengan timeout 3 detik
                const newToken = await Promise.race([
                    refreshCsrfToken(),
                    new Promise(resolve => setTimeout(() => resolve(null), 3000))
                ]);

                if (newToken) {
                    const tokenInput = form.querySelector('input[name="_token"]');
                    if (tokenInput) {
                        tokenInput.value = newToken;
                    }
                }
            } catch (err) {
                console.warn('[CSRF Guard] Pre-submit token fetch failed, submitting with current token.', err);
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }

                // Lanjutkan submit form secara resmi
                form.dataset.csrfGuardPassed = 'true';
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            }
        }, true);
    }

    /**
     * Event Listener saat tab/device aktif kembali (Habis Sleep / Pindah Tab)
     */
    function setupVisibilityAndFocusListeners() {
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                console.log('[CSRF Guard] Tab became visible. Refreshing token...');
                refreshCsrfToken();
            }
        });

        window.addEventListener('focus', function () {
            console.log('[CSRF Guard] Window focused. Refreshing token...');
            refreshCsrfToken();
        });
    }

    /**
     * Inisialisasi Utama
     */
    function init() {
        if (csrfRefreshTimer) clearInterval(csrfRefreshTimer);
        if (autoSaveTimer) clearInterval(autoSaveTimer);

        // 1. Periodik background refresh & auto save
        csrfRefreshTimer = setInterval(refreshCsrfToken, CSRF_REFRESH_INTERVAL);
        autoSaveTimer = setInterval(() => {
            document.querySelectorAll('form').forEach(saveFormDataToStorage);
        }, AUTOSAVE_INTERVAL);

        // 2. Pre-submit CSRF Guard (Anti-419 saat tombol Simpan diklik)
        setupPreSubmitGuard();

        // 3. Tab Visibility & Focus Listener (Anti-419 setelah tablet sleep)
        setupVisibilityAndFocusListeners();

        // 4. Restore draft jika ada
        setTimeout(restoreFormDataFromStorage, 500);

        // 5. Initial token refresh saat load
        refreshCsrfToken();

        console.log('[CSRF Guard & Draft Protection] Fully Activated.');
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }

    window.addEventListener('beforeunload', function () {
        if (csrfRefreshTimer) clearInterval(csrfRefreshTimer);
        if (autoSaveTimer) clearInterval(autoSaveTimer);
    }, { once: true });

})();
