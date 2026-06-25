<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$modeBeta = (bool) ($modeBeta ?? false);
$materiBackUrl = $modeBeta ? base_url('/artikel#materi-kuliah-web') : base_url('/artikel');
$materiDownloadUrl = base_url('/artikel/download/' . $materi['slug']);
?>
<div class="materi-reader-header">
    <span class="materi-badge"><?= esc($materi['label']); ?></span>
    <h1><?= esc($materi['judul']); ?></h1>
    <p class="materi-reader-desc"><?= esc($materi['deskripsi']); ?></p>
</div>

<div class="materi-reader-actions">
    <a href="<?= esc($materiBackUrl); ?>" class="btn btn-secondary">Kembali ke Daftar Materi</a>
    <?php if (! empty($materi['available'])): ?>
        <a href="<?= esc($materiDownloadUrl); ?>" class="btn">Download PDF</a>
    <?php else: ?>
        <span class="btn btn-disabled">PDF belum tersedia di folder /file</span>
    <?php endif; ?>
</div>

<section class="materi-reader-card">
    <h2>Ringkasan Materi</h2>
    <p><?= esc($materi['ringkasan']); ?></p>
</section>

<?php if (! empty($materi['sections'])): ?>
    <?php foreach ($materi['sections'] as $section): ?>
        <section class="materi-reader-card">
            <h2><?= esc($section['heading']); ?></h2>

            <?php if (! empty($section['paragraphs'])): ?>
                <?php foreach ($section['paragraphs'] as $paragraph): ?>
                    <p><?= esc($paragraph); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (! empty($section['points'])): ?>
                <ul class="materi-reader-list">
                    <?php foreach ($section['points'] as $point): ?>
                        <li><?= esc($point); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<section class="materi-reader-card materi-reader-note">
    <h2>Catatan</h2>
    <p>Versi ini disediakan agar materi dapat dibaca langsung melalui web tanpa harus membuka PDF. Jika ingin memakai dokumen aslinya, pindahkan file <strong><?= esc($materi['filename']); ?></strong> ke folder <code>file</code> pada root project.</p>
</section>
<?= $this->endSection() ?>
