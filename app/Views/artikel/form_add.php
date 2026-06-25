<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h2><?= esc($title); ?></h2>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<?php if (! empty($uploadError)): ?>
    <div class="alert alert-danger"><?= esc($uploadError) ?></div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-group">
        <label for="judul">Judul Artikel</label>
        <input id="judul" type="text" name="judul" value="<?= old('judul') ?>" placeholder="Ketik Judul Artikel" required>
    </div>
    <div class="form-group">
        <label for="isi">Isi Artikel</label>
        <textarea id="isi" name="isi" cols="50" rows="10" placeholder="Ketik isi artikel di sini..." required><?= old('isi') ?></textarea>
    </div>
    <div class="form-group">
        <label for="id_kategori">Kategori</label>
        <select id="id_kategori" name="id_kategori" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= esc((string) $k['id_kategori']); ?>" <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : ''; ?>>
                    <?= esc($k['nama_kategori']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (empty($kategori)): ?>
            <p class="form-help danger">Data kategori masih kosong. Jalankan migration/seed atau tambah data kategori terlebih dahulu.</p>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="gambar">Gambar Artikel</label>
        <input id="gambar" type="file" name="gambar" accept="image/png,image/jpeg,image/gif,image/webp">
        <p class="form-help">Opsional. Format: JPG, JPEG, PNG, GIF, atau WEBP. Maksimal 2 MB.</p>
    </div>
    <p><button type="submit" class="btn btn-primary">Kirim</button></p>
</form>
<?= $this->endSection() ?>
