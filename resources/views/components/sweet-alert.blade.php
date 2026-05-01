@php
    $statusMessages = [
        'profile-updated' => 'Profil berhasil diperbarui',
        'password-updated' => 'Password berhasil diperbarui',
        'verification-link-sent' => 'Link verifikasi baru sudah dikirim ke email Anda',
    ];

    $status = session('status');
    $statusMessage = is_string($status) ? ($statusMessages[$status] ?? $status) : null;
    $swalFlash = [
        'success' => session('success') ?? $statusMessage,
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
        'errors' => $errors->any() ? $errors->all() : [],
    ];
@endphp

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const swalFlash = @json($swalFlash);

    const defaultOptions = {
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
    };

    const escapeHtml = function(value) {
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    };

    window.ArsantaraSwal = {
        fire(options) {
            return Swal.fire(Object.assign({}, defaultOptions, options));
        },
        toast(icon, title) {
            return Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 2800,
                timerProgressBar: true,
            });
        },
        confirm(message, options = {}) {
            return Swal.fire(Object.assign({}, defaultOptions, {
                title: options.title || 'Apakah Anda yakin?',
                text: message || 'Aksi ini akan diproses.',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonText: options.confirmButtonText || 'Ya, lanjutkan',
                cancelButtonText: options.cancelButtonText || 'Batal',
            }, options));
        }
    };

    if (swalFlash.success) {
        window.ArsantaraSwal.toast('success', swalFlash.success);
    }

    if (swalFlash.error) {
        window.ArsantaraSwal.fire({
            icon: 'error',
            title: 'Gagal',
            text: swalFlash.error,
        });
    }

    if (swalFlash.warning) {
        window.ArsantaraSwal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: swalFlash.warning,
        });
    }

    if (swalFlash.info) {
        window.ArsantaraSwal.fire({
            icon: 'info',
            title: 'Informasi',
            text: swalFlash.info,
        });
    }

    if (swalFlash.errors.length) {
        window.ArsantaraSwal.fire({
            icon: 'error',
            title: 'Periksa kembali input Anda',
            html: '<ul class="text-left">' + swalFlash.errors.map(function(error) {
                return '<li>' + escapeHtml(error) + '</li>';
            }).join('') + '</ul>',
        });
    }

    document.addEventListener('click', function(event) {
        const link = event.target.closest('a[data-swal-confirm]');
        if (!link) return;

        event.preventDefault();
        window.ArsantaraSwal.confirm(link.dataset.swalConfirm, {
            confirmButtonText: link.dataset.swalConfirmButton || 'Ya, lanjutkan',
            icon: link.dataset.swalIcon || 'warning',
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = link.href;
            }
        });
    });

    document.addEventListener('submit', function(event) {
        const form = event.target;
        if (form.dataset.swalConfirmed === 'true') return;

        const submitter = event.submitter;
        const spoofedMethod = form.querySelector('input[name="_method"]');
        const method = (spoofedMethod ? spoofedMethod.value : form.method || 'GET').toUpperCase();
        const message = form.dataset.swalConfirm
            || (submitter && submitter.dataset ? submitter.dataset.swalConfirm : '')
            || (method === 'DELETE' ? 'Data yang dihapus tidak dapat dikembalikan.' : '');

        if (!message) return;

        event.preventDefault();
        window.ArsantaraSwal.confirm(message, {
            confirmButtonText: (submitter && submitter.dataset ? submitter.dataset.swalConfirmButton : '') || (method === 'DELETE' ? 'Ya, hapus' : 'Ya, lanjutkan'),
            icon: (submitter && submitter.dataset ? submitter.dataset.swalIcon : '') || (method === 'DELETE' ? 'warning' : 'question'),
        }).then(function(result) {
            if (!result.isConfirmed) return;

            form.dataset.swalConfirmed = 'true';
            if (submitter && typeof form.requestSubmit === 'function') {
                form.requestSubmit(submitter);
                return;
            }
            form.submit();
        });
    });
});
</script>
