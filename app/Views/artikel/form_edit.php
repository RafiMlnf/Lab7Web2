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
        <input id="judul" type="text" name="judul" value="<?= old('judul', $data['judul']) ?>" required>
    </div>
    <div class="form-group">
        <label for="isi">Isi Artikel</label>
        <textarea id="isi" name="isi" cols="50" rows="10" required><?= old('isi', $data['isi']) ?></textarea>
    </div>
    <div class="form-group">
        <label for="id_kategori">Kategori</label>
        <select id="id_kategori" name="id_kategori" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= esc((string) $k['id_kategori']); ?>" <?= (string) old('id_kategori', $data['id_kategori'] ?? '') === (string) $k['id_kategori'] ? 'selected' : ''; ?>>
                    <?= esc($k['nama_kategori']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="gambar">Ganti Gambar Artikel</label>
        <?php if (! empty($data['gambar'])): ?>
            <div class="current-image-preview">
                <img src="<?= base_url('/gambar/' . $data['gambar']); ?>" alt="<?= esc($data['judul']); ?>">
                <p>Gambar saat ini: <?= esc($data['gambar']); ?></p>
            </div>
        <?php else: ?>
            <p class="form-help">Artikel ini belum memiliki gambar.</p>
        <?php endif; ?>
        <input id="gambar" type="file" name="gambar" accept="image/png,image/jpeg,image/gif,image/webp">
        <p class="form-help">Kosongkan jika tidak ingin mengganti gambar. Maksimal 2 MB.</p>
    </div>
    <p><button type="submit" class="btn btn-primary">Kirim</button></p>
</form>
<?= $this->endSection() ?>
