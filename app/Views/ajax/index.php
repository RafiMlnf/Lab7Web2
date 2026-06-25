<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="ajax-page-header">
    <span class="materi-badge">Dashboard Admin</span>
    <h1><?= esc($title); ?></h1>
    <p>Kelola konten website dari satu tempat: tulis artikel baru, unggah gambar, perbarui isi, atur kategori, ubah status publikasi, dan hapus artikel yang sudah tidak diperlukan.</p>
</div>

<div id="ajaxAlert" class="ajax-alert" hidden></div>

<section class="ajax-form-card">
    <h2 id="formTitle">Tulis Artikel Baru</h2>
    <form id="artikelForm" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" id="artikel_id" name="artikel_id" value="">

        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" id="judul" name="judul" placeholder="Judul artikel" required>
        </div>

        <div class="form-group">
            <label for="id_kategori">Kategori</label>
            <select id="id_kategori" name="id_kategori" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= esc((string) $k['id_kategori']); ?>"><?= esc($k['nama_kategori']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (empty($kategori)): ?>
                <p class="form-help danger">Kategori masih kosong. Jalankan migration/seed terlebih dahulu.</p>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="0">Draft</option>
                <option value="1">Aktif</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gambar">Gambar Artikel</label>
            <input type="file" id="gambar" name="gambar" accept="image/png,image/jpeg,image/gif,image/webp">
            <p class="form-help">Opsional. Format JPG, JPEG, PNG, GIF, atau WEBP. Maksimal 2 MB.</p>
            <div id="currentImageInfo" class="form-help"></div>
        </div>

        <div class="form-group">
            <label for="isi">Isi</label>
            <textarea id="isi" name="isi" rows="7" placeholder="Isi artikel" required></textarea>
        </div>

        <div class="ajax-actions">
            <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan Artikel</button>
            <button type="button" class="btn btn-secondary" id="btnReset">Bersihkan Form</button>
            <a class="btn btn-secondary" href="<?= base_url('/admin/artikel'); ?>">Kembali ke Admin</a>
        </div>
    </form>
</section>

<section class="ajax-table-card">
    <h2>Daftar Artikel Saya</h2>
    <table class="table table-data" id="artikelTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="6">Loading data...</td></tr>
        </tbody>
    </table>
</section>

<script src="<?= base_url('assets/js/jquery-3.7.1.min.js') ?>"></script>
<script>
$(document).ready(function () {
    const ajaxBase = <?= json_encode(base_url('dashboard')) ?>;
    const imageBase = <?= json_encode(base_url('gambar')) ?>;
    let artikelRows = [];

    function htmlEscape(value) {
        return $('<div>').text(value ?? '').html();
    }

    function imageUrl(filename) {
        return imageBase + '/' + encodeURIComponent(filename || '');
    }

    function showAlert(type, message) {
        const alert = $('#ajaxAlert');
        alert.removeClass('alert-success alert-danger').addClass(type === 'success' ? 'alert-success' : 'alert-danger');
        alert.text(message).prop('hidden', false);
        setTimeout(function () {
            alert.prop('hidden', true);
        }, 4500);
    }

    function resetForm() {
        $('#artikel_id').val('');
        $('#judul').val('');
        $('#isi').val('');
        $('#id_kategori').val('');
        $('#status').val('0');
        $('#gambar').val('');
        $('#currentImageInfo').html('');
        $('#formTitle').text('Tulis Artikel Baru');
        $('#btnSubmit').text('Simpan Artikel');
    }

    function showLoadingMessage() {
        $('#artikelTable tbody').html('<tr><td colspan="6">Loading data...</td></tr>');
    }

    function loadData() {
        showLoadingMessage();
        $.ajax({
            url: ajaxBase + '/getData',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                artikelRows = response.data || [];
                let tableBody = '';

                if (artikelRows.length === 0) {
                    $('#artikelTable tbody').html('<tr><td colspan="6">Belum ada data artikel.</td></tr>');
                    return;
                }

                for (let i = 0; i < artikelRows.length; i++) {
                    const row = artikelRows[i];
                    const gambarCell = row.gambar
                        ? '<img src="' + imageUrl(row.gambar) + '" alt="' + htmlEscape(row.judul) + '" style="width:72px;max-height:54px;object-fit:cover;border-radius:6px;">'
                        : '<small>Tidak ada</small>';

                    tableBody += '<tr>';
                    tableBody += '<td>' + htmlEscape(row.id) + '</td>';
                    tableBody += '<td>' + gambarCell + '</td>';
                    tableBody += '<td><strong>' + htmlEscape(row.judul) + '</strong><br><small>' + htmlEscape((row.isi || '').substring(0, 70)) + '</small></td>';
                    tableBody += '<td>' + htmlEscape(row.nama_kategori || 'Tanpa Kategori') + '</td>';
                    tableBody += '<td>' + (parseInt(row.status || 0, 10) === 1 ? 'Aktif' : 'Draft') + '</td>';
                    tableBody += '<td><button type="button" class="btn btn-secondary btn-edit-ajax" data-id="' + htmlEscape(row.id) + '">Ubah</button> ';
                    tableBody += '<button type="button" class="btn btn-danger btn-delete-ajax" data-id="' + htmlEscape(row.id) + '">Hapus</button></td>';
                    tableBody += '</tr>';
                }

                $('#artikelTable tbody').html(tableBody);
            },
            error: function () {
                $('#artikelTable tbody').html('<tr><td colspan="6">Gagal memuat data.</td></tr>');
            }
        });
    }

    $('#artikelForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#artikel_id').val();
        const url = id ? ajaxBase + '/update/' + id : ajaxBase + '/create';
        const formData = new FormData(this);

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                showAlert('success', response.message || 'Data berhasil disimpan.');
                resetForm();
                loadData();
            },
            error: function (xhr) {
                let message = 'Gagal menyimpan data.';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.errors) {
                        message = Object.values(xhr.responseJSON.errors).join(' ');
                    } else if (xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                }
                showAlert('error', message);
            }
        });
    });

    $(document).on('click', '.btn-edit-ajax', function () {
        const id = String($(this).data('id'));
        const row = artikelRows.find(function (item) {
            return String(item.id) === id;
        });

        if (! row) {
            showAlert('error', 'Data artikel tidak ditemukan di tabel.');
            return;
        }

        $('#artikel_id').val(row.id);
        $('#judul').val(row.judul);
        $('#isi').val(row.isi);
        $('#id_kategori').val(row.id_kategori);
        $('#status').val(String(row.status || 0));
        $('#gambar').val('');
        $('#currentImageInfo').html(row.gambar
            ? 'Gambar saat ini: <a href="' + imageUrl(row.gambar) + '" target="_blank" rel="noopener">' + htmlEscape(row.gambar) + '</a>. Pilih file baru jika ingin mengganti gambar.'
            : 'Artikel ini belum memiliki gambar.');
        $('#formTitle').text('Ubah Artikel');
        $('#btnSubmit').text('Perbarui Artikel');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    $(document).on('click', '.btn-delete-ajax', function () {
        const id = $(this).data('id');

        if (! confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
            return;
        }

        $.ajax({
            url: ajaxBase + '/delete/' + id,
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                showAlert('success', response.message || 'Data berhasil dihapus.');
                resetForm();
                loadData();
            },
            error: function () {
                showAlert('error', 'Gagal menghapus artikel.');
            }
        });
    });

    $('#btnReset').on('click', resetForm);
    loadData();
});
</script>
<?= $this->endSection() ?>
