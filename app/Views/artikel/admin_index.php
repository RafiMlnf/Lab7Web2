<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<h1><?= esc($title); ?></h1>
<hr>
<p>Selamat datang, <strong><?= esc((string) session()->get('user_name')); ?></strong> | <a href="<?= base_url('/user/logout'); ?>">Logout</a></p>
<p>
    <a class="btn btn-primary" href="<?= base_url('/admin/artikel/add'); ?>">Tambah Artikel</a>
    <a class="btn btn-secondary" href="<?= base_url('/ajax'); ?>">Kelola Artikel via AJAX</a>
</p>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<form method="get" class="form-search form-search-grid">
    <input type="text" name="q" value="<?= esc($q ?? ''); ?>" placeholder="Cari data berdasarkan judul">
    <select name="kategori_id">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategori as $k): ?>
            <option value="<?= esc((string) $k['id_kategori']); ?>" <?= ((string) ($kategori_id ?? '') === (string) $k['id_kategori']) ? 'selected' : ''; ?>>
                <?= esc($k['nama_kategori']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Cari" class="btn btn-primary">
    <?php if (! empty($q) || ! empty($kategori_id)): ?>
        <a href="<?= base_url('/admin/artikel'); ?>" class="btn btn-secondary">Reset</a>
    <?php endif; ?>
</form>

<div class="table-responsive admin-table-wrap">
<table class="table admin-article-table">
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
        <?php if ($artikel): ?>
            <?php foreach ($artikel as $row): ?>
                <tr>
                    <td><?= esc((string) $row['id']); ?></td>
                    <td class="image-cell">
                        <?php if (! empty($row['gambar'])): ?>
                            <img class="table-thumb" src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= esc($row['judul']); ?>">
                        <?php else: ?>
                            <span class="muted-text">Tidak ada</span>
                        <?php endif; ?>
                    </td>
                    <td class="title-cell">
                        <b><?= esc($row['judul']); ?></b>
                        <p><small><?= esc(strlen((string) $row['isi']) > 90 ? substr((string) $row['isi'], 0, 90) . '...' : (string) $row['isi']); ?></small></p>
                    </td>
                    <td><?= esc($row['nama_kategori'] ?: 'Tanpa Kategori'); ?></td>
                    <td><?= ((int) ($row['status'] ?? 0) === 1) ? 'Aktif' : 'Draft'; ?></td>
                    <td>
                        <div class="action-inline">
                            <a class="btn btn-secondary" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
                            <form action="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>" method="post" onsubmit="return confirm('Yakin menghapus data?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Data artikel tidak ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

<?php if (isset($pager)): ?>
    <div class="pagination-wrap">
        <?= $pager->only(['q', 'kategori_id'])->links(); ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
