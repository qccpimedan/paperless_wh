/**
 * Auto-refresh CSRF Token dan Auto-save Form Data
 * Mencegah Page Expired (419) saat user mengisi form dalam waktu lama
 */

(function() {
    'use strict';

    // Cek apakah script sudah diinisialisasi
    if (window.csrfRefreshInitialized) {
        console.log('CSRF refresh already initialized, skipping...');
        return;
    }
    window.csrfRefreshInitialized = true;

    // Refresh CSRF token setiap 30 menit
    const CSRF_REFRESH_INTERVAL = 30 * 60 * 1000; // 30 menit
    
    // Auto-save form data setiap 2 menit
    const AUTOSAVE_INTERVAL = 2 * 60 * 1000; // 2 menit

    let csrfRefreshTimer = null;
    let autoSaveTimer = null;

    /**
     * Refresh CSRF Token dari server
     */
    function refreshCsrfToken() {
        fetch('/csrf-token', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.csrf_token) {
                // Update semua CSRF token di halaman
                document.querySelectorAll('input[name="_token"]').forEach(input => {
                    input.value = data.csrf_token;
                });
                
                // Update meta tag
                const metaTag = document.querySelector('meta[name="csrf-token"]');
                if (metaTag) {
                    metaTag.setAttribute('content', data.csrf_token);
                }
                
                console.log('CSRF token refreshed successfully');
            }
        })
        .catch(error => {
            console.error('Failed to refresh CSRF token:', error);
        });
    }

    /**
     * Auto-save form data ke localStorage
     */
    function autoSaveFormData() {
        const forms = document.querySelectorAll('form[data-autosave="true"]');
        
        forms.forEach(form => {
            const formId = form.id || form.getAttribute('action');
            if (!formId) return;
            
            const formData = {};
            const inputs = form.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                if (input.name && input.name !== '_token' && input.type !== 'file') {
                    // Skip Choices.js internal elements
                    if (input.classList.contains('choices__input') || 
                        input.classList.contains('choices__list')) {
                        return;
                    }
                    
                    if (input.type === 'checkbox') {
                        formData[input.name] = input.checked;
                    } else if (input.type === 'radio') {
                        if (input.checked) {
                            formData[input.name] = input.value;
                        }
                    } else {
                        formData[input.name] = input.value;
                    }
                }
            });
            
            localStorage.setItem('autosave_' + formId, JSON.stringify({
                data: formData,
                timestamp: Date.now()
            }));
            
            console.log('Form data auto-saved for:', formId);
        });
    }

    /**
     * Restore form data dari localStorage
     */
    function restoreFormData() {
        const forms = document.querySelectorAll('form[data-autosave="true"]');
        
        forms.forEach(form => {
            const formId = form.id || form.getAttribute('action');
            if (!formId) return;
            
            const savedData = localStorage.getItem('autosave_' + formId);
            if (!savedData) return;
            
            try {
                const { data, timestamp } = JSON.parse(savedData);
                
                // Hanya restore jika data kurang dari 8 jam
                if (Date.now() - timestamp < 8 * 60 * 60 * 1000) {
                    Object.keys(data).forEach(name => {
                        const input = form.querySelector(`[name="${name}"]`);
                        if (input) {
                            // Skip Choices.js internal elements
                            if (input.classList.contains('choices__input') || 
                                input.classList.contains('choices__list')) {
                                return;
                            }
                            
                            if (input.type === 'checkbox') {
                                input.checked = data[name];
                            } else if (input.type === 'radio') {
                                const radio = form.querySelector(`[name="${name}"][value="${data[name]}"]`);
                                if (radio) radio.checked = true;
                            } else {
                                input.value = data[name];
                                
                                // Trigger change event untuk Vue/Alpine.js dan Choices.js
                                input.dispatchEvent(new Event('input', { bubbles: true }));
                                input.dispatchEvent(new Event('change', { bubbles: true }));
                                
                                // Update Choices.js jika ada
                                if (input._choices && typeof input._choices.setChoiceByValue === 'function') {
                                    try {
                                        input._choices.setChoiceByValue(data[name]);
                                    } catch (e) {
                                        console.warn('Failed to restore Choices.js value:', e);
                                    }
                                }
                            }
                        }
                    });
                    
                    // Tampilkan notifikasi
                    showNotification('Data formulir telah dipulihkan dari penyimpanan otomatis.');
                }
            } catch (error) {
                console.error('Failed to restore form data:', error);
            }
        });
    }

    /**
     * Clear saved form data setelah berhasil submit
     */
    function clearSavedFormData(formId) {
        localStorage.removeItem('autosave_' + formId);
        console.log('Cleared saved data for:', formId);
    }

    /**
     * Show notification to user
     */
    function showNotification(message) {
        // Gunakan SweetAlert jika tersedia
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.info(message);
        } else {
            console.log(message);
        }
    }

    /**
     * Initialize
     */
    function init() {
        // Clear existing timers jika ada
        if (csrfRefreshTimer) clearInterval(csrfRefreshTimer);
        if (autoSaveTimer) clearInterval(autoSaveTimer);

        // Refresh CSRF token setiap 30 menit
        csrfRefreshTimer = setInterval(refreshCsrfToken, CSRF_REFRESH_INTERVAL);
        
        // Auto-save form data setiap 2 menit
        autoSaveTimer = setInterval(autoSaveFormData, AUTOSAVE_INTERVAL);
        
        // Restore form data saat halaman dimuat
        restoreFormData();
        
        // Clear saved data ketika form berhasil disubmit
        document.querySelectorAll('form[data-autosave="true"]').forEach(form => {
            // PENTING: Jangan gunakan cloneNode() karena akan menghancurkan Choices.js
            // Gunakan flag untuk mencegah multiple listeners
            if (form.dataset.autosaveListenerAttached === 'true') {
                return; // Skip jika listener sudah ada
            }
            
            form.dataset.autosaveListenerAttached = 'true';
            
            form.addEventListener('submit', function(e) {
                const formId = this.id || this.getAttribute('action');
                if (formId) {
                    // Clear setelah delay singkat untuk memastikan submit berhasil
                    setTimeout(() => {
                        clearSavedFormData(formId);
                    }, 1000);
                }
            }, { once: true }); // Use 'once' option to prevent multiple listeners
        });
        
        console.log('CSRF refresh and auto-save initialized');
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (csrfRefreshTimer) clearInterval(csrfRefreshTimer);
        if (autoSaveTimer) clearInterval(autoSaveTimer);
    }, { once: true });

})();
