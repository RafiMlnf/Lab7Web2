<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
  .article-detail-card {
    background: #fff;
    border: 1px solid #dfe5ec;
    border-radius: 10px;
    padding: 22px;
    box-shadow: 0 10px 30px rgba(16, 24, 40, 0.05);
  }

  .article-detail-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 12px;
    color: #1d3557;
  }

  .article-detail-meta {
    margin-bottom: 16px;
    color: #5f6b7a;
    font-size: 0.95rem;
  }

  .article-detail-meta strong {
    color: #2b4f77;
  }

  .detail-image-frame {
    margin: 18px 0 10px;
    padding: 18px;
    background: #f8fbff;
    border: 1px solid #d7e5f5;
    border-radius: 12px;
    text-align: center;
    overflow: hidden;
  }

  .detail-image-frame img {
    display: block;
    margin: 0 auto;
    width: auto;
    height: auto;
    max-width: min(100%, 920px);
    max-height: 70vh;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.10);
  }

  .detail-image-note {
    margin: 8px 0 18px;
    font-size: 0.9rem;
    color: #637184;
  }

  .article-detail-body {
    font-size: 1rem;
    line-height: 1.75;
    color: #202938;
    margin-top: 12px;
    white-space: normal;
    word-wrap: break-word;
  }

  .article-detail-actions {
    margin-top: 24px;
  }

  .article-detail-actions a {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    background: #6c757d;
    color: #fff;
    font-weight: 600;
    transition: transform .18s ease, opacity .18s ease;
  }

  .article-detail-actions a:hover {
    transform: translateY(-1px);
    opacity: .92;
  }

  @media (max-width: 768px) {
    .article-detail-card {
      padding: 16px;
    }

    .article-detail-title {
      font-size: 1.6rem;
    }

    .detail-image-frame {
      padding: 12px;
    }

    .detail-image-frame img {
      max-width: 100%;
      max-height: 55vh;
    }
  }
</style>

<?php
  $modeBeta = (bool) ($modeBeta ?? false);
  $backUrl = $modeBeta ? base_url('/artikel#daftar-artikel-web') : base_url('artikel');
  $gambarUrl = null;
  if (!empty($artikel['gambar'])) {
      $gambarUrl = base_url('gambar/' . $artikel['gambar']);
  }
?>

<div class="article-detail-card">
  <h1 class="article-detail-title"><?= esc($artikel['judul'] ?? 'Detail Artikel'); ?></h1>

  <div class="article-detail-meta">
    <?php if (!empty($artikel['nama_kategori'])): ?>
      Kategori: <strong><?= esc($artikel['nama_kategori']); ?></strong>
    <?php elseif (!empty($artikel['kategori'])): ?>
      Kategori: <strong><?= esc($artikel['kategori']); ?></strong>
    <?php else: ?>
      Kategori: <strong>Tidak ada kategori</strong>
    <?php endif; ?>
  </div>

  <?php if ($gambarUrl): ?>
    <div class="detail-image-frame">
      <img src="<?= $gambarUrl; ?>" alt="<?= esc($artikel['judul'] ?? 'Gambar Artikel'); ?>">
    </div>
    <p class="detail-image-note">
      Gambar akan menyesuaikan otomatis dengan area artikel agar tidak terlalu besar maupun terlalu kecil.
    </p>
  <?php endif; ?>

  <div class="article-detail-body">
    <?= nl2br(esc($artikel['isi'] ?? '')); ?>
  </div>

  <div class="article-detail-actions">
    <a href="<?= esc($backUrl); ?>">Kembali ke Artikel</a>
  </div>
</div>

<?= $this->endSection() ?>
