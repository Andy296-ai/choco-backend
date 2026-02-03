/* resources/js/panel-crud.js */

document.addEventListener('DOMContentLoaded', function () {
    // Modal Toggle Logic
    const modalButtons = document.querySelectorAll('[data-modal]');
    const overlays = document.querySelectorAll('.modal-overlay');

    modalButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const modalId = btn.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');

                // If it's an edit button, populate form
                if (btn.hasAttribute('data-edit')) {
                    const data = JSON.parse(btn.getAttribute('data-edit'));
                    const form = modal.querySelector('form');
                    Object.keys(data).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) input.value = data[key];
                    });
                    // Set method to PATCH/PUT if needed (Laravel workaround)
                    if (!form.querySelector('input[name="_method"]')) {
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PATCH';
                        form.appendChild(methodInput);
                    }
                    form.action = btn.getAttribute('data-action');
                }
            }
        });
    });

    const closeButtons = document.querySelectorAll('.close-modal, .btn-cancel');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.target.closest('.modal-overlay').classList.remove('active');
        });
    });

    // Close on overlay click
    overlays.forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) overlay.classList.remove('active');
        });
    });

    // AJAX Form Handling
    const ajaxForms = document.querySelectorAll('.ajax-form');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            submitBtn.innerText = 'Сохранение...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST', // Always POST for FormData, Laravel uses _method
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    location.reload(); // Refresh to show changes
                } else {
                    alert('Ошибка: ' + (result.message || 'Что-то пошло не так'));
                }
            } catch (error) {
                console.error('Submit error:', error);
                alert('Сетевая ошибка');
            } finally {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        });
    });
});
