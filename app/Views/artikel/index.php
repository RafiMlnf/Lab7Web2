<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$materiTipeAktif = $materiTipeAktif ?? '';
$materiKategoriAktif = $materiKategoriAktif ?? '';
$materiKategoriLabelAktif = $materiKategoriLabelAktif ?? '';
$modeBeta = (bool) ($modeBeta ?? false);
$artikelBasePath = '/artikel';
$materiBasePath = '/artikel';

$materiFilterUrl = static function (array $params = []) use ($materiBasePath): string {
    $params = array_filter($params, static fn ($value) => $value !== '' && $value !== null);
    $query = http_build_query($params);

    return base_url($materiBasePath . ($query !== '' ? '?' . $query : '')) . '#materi-kuliah-web';
};

$judulMateriSection = 'Materi belajar versi web';
$deskripsiMateriSection = 'Kumpulan materi kuliah dan praktikum yang dapat dibaca langsung melalui halaman web.';

if ($materiTipeAktif === 'pertemuan') {
    $judulMateriSection = 'Materi pertemuan kuliah';
    $deskripsiMateriSection = 'Daftar materi pertemuan diurutkan dari pertemuan awal sampai materi terbaru.';
} elseif ($materiTipeAktif === 'praktikum') {
    $judulMateriSection = 'Materi praktikum';
    $deskripsiMateriSection = 'Daftar modul praktikum diurutkan dari praktikum awal sampai praktikum terbaru.';
}
?>

<h1><?= $modeBeta ? 'Mode Beta Materi' : 'Daftar Artikel'; ?></h1>
<hr class="divider">

<section class="intro-box reveal-item">
    <?php if ($modeBeta): ?>
        <h2>Anda sedang berada di Mode Beta</h2>
        <p>Mode Beta menampilkan katalog materi PDF, shortcut pertemuan, filter materi, dan daftar artikel seperti tampilan improvisasi. Mode ini akan tetap aktif saat berpindah halaman sampai Anda menekan tombol Mode Normal.</p>
        <p>
            <a href="<?= base_url('/mode-normal'); ?>" class="btn btn-secondary">Mode Normal</a>
            <a href="#materi-kuliah-web" class="btn btn-primary">Lihat Materi</a>
            <a href="#daftar-artikel-web" class="btn btn-secondary">Lihat Artikel</a>
        </p>
    <?php else: ?>
        <h2>Artikel pembelajaran</h2>
        <p>Halaman normal menampilkan daftar artikel dari database sesuai alur praktikum. Katalog materi PDF tambahan dipisahkan ke Mode Beta agar tidak mengganggu tampilan utama.</p>
        <p>
            <a href="#daftar-artikel-web" class="btn btn-primary">Lihat Artikel</a>
            <a href="<?= base_url('/mode-beta'); ?>" class="btn btn-secondary beta-mode-btn">Mode Beta</a>
        </p>
    <?php endif; ?>
</section>

<section class="search-box reveal-item" aria-label="Pencarian cepat">
    <label for="contentSearch"><strong>Cari cepat</strong></label>
    <div class="search-row">
        <input type="search" id="contentSearch" placeholder="Cari judul, kategori, atau topik..." autocomplete="off">
        <button type="button" id="clearContentSearch" class="btn btn-secondary">Reset</button>
    </div>
</section>

<?php if ($modeBeta): ?>
<section class="content-section reveal-item" id="materi-kuliah-web">
        <h2><?= esc($judulMateriSection); ?><?= $materiKategoriLabelAktif !== '' ? ' - ' . esc($materiKategoriLabelAktif) : ''; ?></h2>
        <p><?= esc($deskripsiMateriSection); ?> Jika file PDF sudah dipindahkan ke folder <code>file</code>, tombol unduh akan aktif.</p>

        <div class="filter-box" aria-label="Filter jenis materi">
            <div class="filter-row filter-row-primary">
                <a href="<?= $materiFilterUrl(); ?>" class="filter-chip <?= empty($materiTipeAktif) && empty($materiKategoriAktif) ? 'active' : ''; ?>">Semua Materi</a>
                <a href="<?= $materiFilterUrl(['materi_tipe' => 'pertemuan']); ?>" class="filter-chip <?= $materiTipeAktif === 'pertemuan' ? 'active' : ''; ?>">Materi Pertemuan</a>
                <a href="<?= $materiFilterUrl(['materi_tipe' => 'praktikum']); ?>" class="filter-chip <?= $materiTipeAktif === 'praktikum' ? 'active' : ''; ?>">Materi Praktikum</a>
            </div>

            <?php if (! empty($materiKategoriList)): ?>
                <div class="filter-row filter-row-secondary">
                    <span class="filter-caption">Shortcut:</span>
                    <a href="<?= $materiFilterUrl(['materi_tipe' => $materiTipeAktif]); ?>" class="filter-chip small <?= empty($materiKategoriAktif) ? 'active' : ''; ?>">
                        <?= $materiTipeAktif === 'pertemuan' ? 'Semua Pertemuan' : ($materiTipeAktif === 'praktikum' ? 'Semua Praktikum' : 'Semua Label'); ?>
                    </a>
                    <?php foreach ($materiKategoriList as $slug => $label): ?>
                        <a href="<?= $materiFilterUrl(['materi_tipe' => $materiTipeAktif, 'materi_kategori' => $slug]); ?>" class="filter-chip small <?= ($materiKategoriAktif === $slug) ? 'active' : ''; ?>">
                            <?= esc($label); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="materi-grid" data-search-group="content">
            <?php if (! empty($materi)): ?>
            <?php foreach ($materi as $item): ?>
                <?php
                $searchText = strtolower(($item['label'] ?? '') . ' ' . ($item['judul'] ?? '') . ' ' . ($item['deskripsi'] ?? '') . ' ' . ($item['filename'] ?? ''));
                ?>
                <article class="materi-card searchable-card" data-search-text="<?= esc($searchText); ?>">
                    <div class="materi-card-head">
                        <span class="materi-chip"><?= esc($item['label']); ?></span>
                        <?= ! empty($item['available']) ? '<span class="file-status ok">PDF tersedia</span>' : '<span class="file-status missing">PDF belum ada</span>'; ?>
                    </div>
                    <h3><a href="<?= base_url('/artikel/materi/' . $item['slug']); ?>"><?= esc($item['judul']); ?></a></h3>
                    <p class="materi-desc"><?= esc($item['deskripsi']); ?></p>
                    <p class="materi-file">File: <strong><?= esc($item['filename']); ?></strong></p>
                    <p class="materi-actions">
                        <a href="<?= base_url('/artikel/materi/' . $item['slug']); ?>" class="btn btn-primary">Buka Materi</a>
                        <?php if (! empty($item['available'])): ?>
                            <a href="<?= base_url('/artikel/download/' . $item['slug']); ?>" class="btn btn-secondary">Unduh PDF</a>
                        <?php else: ?>
                            <span class="btn btn-disabled">PDF belum ada</span>
                        <?php endif; ?>
                    </p>
                </article>
            <?php endforeach; ?>
            <?php else: ?>
                <article class="materi-card empty-state-card">
                    <div class="materi-card-head">
                        <span class="materi-chip"><?= esc($materiKategoriLabelAktif !== '' ? $materiKategoriLabelAktif : 'Materi'); ?></span>
                        <span class="file-status missing">Kosong</span>
                    </div>
                    <h3>Belum ada materi pada bagian ini</h3>
                    <p class="materi-desc">Shortcut ini tetap ditampilkan agar urutan pertemuan tidak loncat. Jika nanti file materi tersedia, data dapat ditambahkan ke daftar materi pada controller.</p>
                </article>
            <?php endif; ?>
        </div>
</section>

<?php endif; ?>

<section class="content-section reveal-item" id="daftar-artikel-web">
    <h2>Daftar Artikel</h2>
    <p>Bagian ini menampilkan artikel dari database beserta kategori yang berelasi.</p>

    <?php if (! empty($kategoriList)): ?>
        <div class="kategori-filter-list">
            <a href="<?= base_url($artikelBasePath); ?>#daftar-artikel-web" class="filter-chip <?= empty($kategoriAktif) ? 'active' : ''; ?>">Semua Artikel</a>
            <?php foreach ($kategoriList as $kategori): ?>
                <a href="<?= base_url($artikelBasePath . '?kategori=' . $kategori['slug_kategori']); ?>#daftar-artikel-web" class="filter-chip <?= ($kategoriAktif === $kategori['slug_kategori']) ? 'active' : ''; ?>">
                    <?= esc($kategori['nama_kategori']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($artikel): ?>
        <div class="article-list" data-search-group="content">
            <?php foreach ($artikel as $row): ?>
                <?php
                $kategoriArtikel = $row['nama_kategori'] ?: 'Tanpa Kategori';
                $searchText = strtolower(($row['judul'] ?? '') . ' ' . ($row['isi'] ?? '') . ' ' . $kategoriArtikel);
                $ringkasan = trim(strip_tags((string) ($row['isi'] ?? '')));
                if (strlen($ringkasan) > 190) {
                    $ringkasan = substr($ringkasan, 0, 190) . '...';
                }
                ?>
                <article class="entry searchable-card" data-search-text="<?= esc($searchText); ?>">
                    <?php if (! empty($row['gambar'])): ?>
                        <img class="entry-thumb" src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= esc($row['judul']); ?>">
                    <?php endif; ?>
                    <h2><a href="<?= base_url('/artikel/' . $row['slug']); ?>"><?= esc($row['judul']); ?></a></h2>
                    <p class="article-meta">Kategori: <strong><?= esc($kategoriArtikel); ?></strong></p>
                    <p><?= esc($ringkasan !== '' ? $ringkasan : 'Belum ada ringkasan artikel.'); ?></p>
                    <p><a href="<?= base_url('/artikel/' . $row['slug']); ?>" class="read-more-link">Baca Selengkapnya</a></p>
                </article>
                <hr class="divider" />
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <article class="entry empty-state-card">
            <h2>Belum ada data.</h2>
            <p>Silakan tambahkan artikel melalui halaman admin.</p>
        </article>
    <?php endif; ?>
</section>

<div id="noSearchResult" class="empty-state-card" hidden>
    <h3>Konten tidak ditemukan</h3>
    <p>Coba gunakan kata kunci lain atau reset pencarian.</p>
</div>

<script>
(function () {
    const input = document.getElementById('contentSearch');
    const reset = document.getElementById('clearContentSearch');
    const cards = Array.from(document.querySelectorAll('.searchable-card'));
    const empty = document.getElementById('noSearchResult');

    function filterCards() {
        const keyword = (input.value || '').toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(function (card) {
            const text = (card.getAttribute('data-search-text') || '').toLowerCase();
            const match = keyword === '' || text.includes(keyword);
            card.hidden = !match;
            if (match) visibleCount++;
        });

        if (empty) {
            empty.hidden = keyword === '' || visibleCount > 0;
        }
    }

    if (input) {
        input.addEventListener('input', filterCards);
    }

    if (reset) {
        reset.addEventListener('click', function () {
            input.value = '';
            filterCards();
            input.focus();
        });
    }
})();
</script>
<?= $this->endSection() ?>
