// ===========================
// VALIDASI FORM KATALOG
// ===========================
document.addEventListener('DOMContentLoaded', function () {

    // Validasi form tambah/edit katalog
    const formKatalog = document.getElementById('formKatalog');
    if (formKatalog) {
        formKatalog.addEventListener('submit', function (e) {
            let valid = true;

            // Validasi judul
            const judul = document.getElementById('judul');
            const judulError = document.getElementById('judulError');
            if (judul.value.trim() === '') {
                judulError.textContent = 'Judul katalog wajib diisi.';
                judulError.classList.remove('d-none');
                judul.classList.add('is-invalid');
                valid = false;
            } else {
                judulError.classList.add('d-none');
                judul.classList.remove('is-invalid');
            }

            // Validasi tanggal event
            const tanggal = document.getElementById('tanggal_event');
            const tanggalError = document.getElementById('tanggalError');
            if (tanggal.value === '') {
                tanggalError.textContent = 'Tanggal event wajib diisi.';
                tanggalError.classList.remove('d-none');
                tanggal.classList.add('is-invalid');
                valid = false;
            } else {
                tanggalError.classList.add('d-none');
                tanggal.classList.remove('is-invalid');
            }

            if (!valid) e.preventDefault();
        });
    }

    // ===========================
    // VALIDASI FORM UPLOAD FOTO
    // ===========================
    const formFoto = document.getElementById('formFoto');
    if (formFoto) {
        formFoto.addEventListener('submit', function (e) {
            let valid = true;

            // Validasi judul foto
            const judulFoto = document.getElementById('judul_foto');
            const judulFotoError = document.getElementById('judulFotoError');
            if (judulFoto && judulFoto.value.trim() === '') {
                judulFotoError.textContent = 'Judul foto wajib diisi.';
                judulFotoError.classList.remove('d-none');
                judulFoto.classList.add('is-invalid');
                valid = false;
            } else if (judulFoto) {
                judulFotoError.classList.add('d-none');
                judulFoto.classList.remove('is-invalid');
            }

            // Validasi file foto
            const fileFoto = document.getElementById('file_foto');
            const fileFotoError = document.getElementById('fileFotoError');
            if (fileFoto && fileFoto.files.length === 0) {
                fileFotoError.textContent = 'File foto wajib dipilih.';
                fileFotoError.classList.remove('d-none');
                fileFoto.classList.add('is-invalid');
                valid = false;
            } else if (fileFoto && fileFoto.files.length > 0) {
                // Validasi tipe file
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedTypes.includes(fileFoto.files[0].type)) {
                    fileFotoError.textContent = 'Hanya file JPG, PNG, atau WEBP yang diizinkan.';
                    fileFotoError.classList.remove('d-none');
                    fileFoto.classList.add('is-invalid');
                    valid = false;
                } else {
                    fileFotoError.classList.add('d-none');
                    fileFoto.classList.remove('is-invalid');
                }
            }

            if (!valid) e.preventDefault();
        });
    }

    // ===========================
    // KONFIRMASI HAPUS
    // ===========================
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const nama = this.dataset.nama || 'item ini';
            if (!confirm('Yakin ingin menghapus ' + nama + '?\nData tidak dapat dikembalikan.')) {
                e.preventDefault();
            }
        });
    });

    // ===========================
    // PREVIEW THUMBNAIL
    // ===========================
    const inputThumbnail = document.getElementById('thumbnail');
    const previewThumbnail = document.getElementById('previewThumbnail');
    if (inputThumbnail && previewThumbnail) {
        inputThumbnail.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewThumbnail.src = e.target.result;
                    previewThumbnail.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    }

});