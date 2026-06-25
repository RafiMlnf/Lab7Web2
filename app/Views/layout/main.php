<?php
$currentPath = trim(uri_string(), '/');
$parts = $currentPath === '' ? [] : explode('/', $currentPath);

$segment1 = $parts[0] ?? '';
$segment2 = $parts[1] ?? '';

$navActive = static function (string $page) use ($segment1, $segment2, $currentPath): string {
    return match ($page) {
        'home'      => ($currentPath === '' || $currentPath === 'index.php') ? 'active' : '',
        'artikel'   => ($segment1 === 'artikel' || ($segment1 === 'admin' && $segment2 === 'artikel')) ? 'active' : '',
        'dashboard' => in_array($segment1, ['dashboard', 'ajax'], true) ? 'active' : '',
        'about'     => $segment1 === 'about' ? 'active' : '',
        'contact'   => $segment1 === 'contact' ? 'active' : '',
        default     => '',
    };
};

$spaVueUrl = 'http://localhost/lab11_ci/Lab7Web-2/lab8_vuejs/#/';
$modeBetaActive = (bool) session()->get('mode_beta');
$modeToggleUrl = $modeBetaActive ? base_url('/mode-normal') : base_url('/mode-beta');
$modeToggleLabel = $modeBetaActive ? 'Mode Normal' : 'Mode Beta';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Layout Sederhana') ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body class="page-preload">
    <div id="container">
        <header class="site-header">
            <div class="site-title-block">
                <h1>Layout Sederhana</h1>
                <p>Pemrograman Web 2 - CodeIgniter 4</p>
            </div>

            <div class="header-actions">
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= base_url('/admin/artikel'); ?>" class="header-admin-btn">Admin Panel</a>
                    <a href="<?= esc($modeToggleUrl); ?>" class="header-admin-btn secondary"><?= esc($modeToggleLabel); ?></a>
                    <a href="<?= base_url('/dashboard'); ?>" class="header-admin-btn secondary">Dashboard</a>
                    <a href="<?= esc($spaVueUrl); ?>" class="header-admin-btn secondary" title="Buka frontend VueJS SPA">SPA VueJS</a>
                    <a href="<?= base_url('/user/logout'); ?>" class="header-admin-btn secondary">Logout</a>
                <?php else: ?>
                    <a href="<?= base_url('/user/login'); ?>" class="header-admin-btn">Login Admin</a>
                    <a href="<?= esc($modeToggleUrl); ?>" class="header-admin-btn secondary"><?= esc($modeToggleLabel); ?></a>
                <?php endif; ?>
            </div>
        </header>

        <nav class="main-nav" aria-label="Navigasi utama">
            <?php if ($modeBetaActive): ?>
                <a href="<?= base_url('/artikel'); ?>" class="nav-link active">Mode Beta</a>
                <a href="<?= base_url('/artikel#materi-kuliah-web'); ?>" class="nav-link">Materi</a>
                <a href="<?= base_url('/artikel#daftar-artikel-web'); ?>" class="nav-link">Artikel</a>
                <a href="<?= base_url('/mode-normal'); ?>" class="nav-link">Mode Normal</a>
            <?php else: ?>
                <a href="<?= base_url('/'); ?>" class="nav-link <?= $navActive('home') ?>">Home</a>
                <a href="<?= base_url('/artikel'); ?>" class="nav-link <?= $navActive('artikel') ?>">Artikel</a>
                <a href="<?= base_url('/about'); ?>" class="nav-link <?= $navActive('about') ?>">About</a>
                <a href="<?= base_url('/contact'); ?>" class="nav-link <?= $navActive('contact') ?>">Kontak</a>
                <?php if (session()->get('logged_in')): ?>
                    <a href="<?= base_url('/dashboard'); ?>" class="nav-link <?= $navActive('dashboard') ?>">Dashboard</a>
                    <a href="<?= esc($spaVueUrl); ?>" class="nav-link">SPA VueJS</a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>

        <section id="wrapper" class="app-shell">
            <main id="main">
                <?= $this->renderSection('content') ?>
            </main>

            <aside id="sidebar" class="site-sidebar">
                <div class="widget-box reveal-item">
                    <?= view_cell('App\\Cells\\ArtikelTerkini::render') ?>
                </div>

                <div class="widget-box reveal-item">
                    <h3 class="title">Widget Header</h3>
                    <ul>
                        <?php if (session()->get('logged_in')): ?>
                            <li><a href="<?= base_url('/admin/artikel'); ?>">Admin Artikel</a></li>
                            <li><a href="<?= base_url('/admin/artikel/add'); ?>">Tambah Artikel</a></li>
                            <li><a href="<?= base_url('/dashboard'); ?>">Dashboard</a></li>
                            <li><a href="<?= esc($spaVueUrl); ?>">SPA VueJS</a></li>
                        <?php else: ?>
                            <li><a href="<?= base_url('/user/login'); ?>">Login Admin</a></li>
                            <li><a href="<?= base_url('/user/register'); ?>">Daftar Akun</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="widget-box reveal-item">
                    <h3 class="title">Widget Text</h3>
                    <p>Website ini memuat artikel, materi kuliah, modul praktikum, dan pengelolaan konten berbasis CodeIgniter 4.</p>
                </div>
            </aside>
        </section>

        <footer class="site-footer">
            <p>&copy; 2026 Rafi Maulana Firdaus</p>
        </footer>
    </div>

    <script>
    (function () {
        const body = document.body;
        const navLinks = document.querySelectorAll('.main-nav .nav-link');

        window.addEventListener('load', function () {
            window.requestAnimationFrame(function () {
                body.classList.remove('page-preload');
                body.classList.add('page-ready');
            });
        });

        navLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                const href = link.getAttribute('href');

                if (!href) return;
                if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
                if (link.target === '_blank') return;
                if (href.startsWith('#')) return;
                if (href === window.location.href) return;

                event.preventDefault();
                body.classList.remove('page-ready');
                body.classList.add('page-leave');

                setTimeout(function () {
                    window.location.href = href;
                }, 180);
            });
        });
    })();
    </script>
</body>
</html>
