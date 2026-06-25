<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(17, 24, 39, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --accent-color: #6366f1;
            --accent-hover: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.1) 0%, transparent 40%);
            background-attachment: fixed;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        header {
            text-align: center;
            margin-bottom: 2.5rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.5rem;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(to right, #818cf8, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
            animation: fadeIn 0.4s ease;
        }

        .grid {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 2.5rem;
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #818cf8;
        }

        .modes-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mode-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.02);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .mode-card:hover {
            background: rgba(99, 102, 241, 0.08);
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-2px);
        }

        .mode-card.active {
            background: rgba(99, 102, 241, 0.15);
            border-color: var(--accent-color);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
        }

        .mode-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .mode-name {
            font-weight: 600;
            font-size: 1.05rem;
        }

        .mode-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.6rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-active {
            background: var(--success-color);
            color: #0b0f19;
        }

        .badge-inactive {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-secondary);
        }

        .mode-desc {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.4;
        }

        .links-panel {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 1.5rem;
        }

        .url-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .url-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .url-item:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .url-info {
            display: flex;
            flex-direction: column;
        }

        .url-path {
            font-family: monospace;
            color: #34d399;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .url-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.2rem;
        }

        .btn-visit {
            background: var(--accent-color);
            color: white;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .btn-visit:hover {
            background: var(--accent-hover);
        }

        .notes-box {
            margin-top: 2rem;
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.2);
            padding: 1.25rem;
            border-radius: 16px;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .notes-box h4 {
            color: var(--warning-color);
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .notes-box ul {
            padding-left: 1.25rem;
        }

        .notes-box li {
            margin-bottom: 0.25rem;
            color: var(--text-secondary);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Modul 1 Screenshot Helper</h1>
            <p class="subtitle">Kelola dan aktifkan mode tampilan yang sesuai untuk kebutuhan screenshot laporan praktikum</p>
        </header>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success">
                <?= esc(session()->getFlashdata('success')); ?>
            </div>
        <?php endif; ?>

        <div class="grid">
            <div>
                <h3 class="section-title">
                    <span class="dot"></span> Pilih Mode Tampilan
                </h3>
                <div class="modes-list">
                    <?php foreach ($modes as $key => $name): ?>
                        <?php 
                            $isActive = ($currentMode === $key);
                            $description = '';
                            if ($key === 'normal') {
                                $description = 'Menampilkan seluruh fungsionalitas web akhir yang telah diselesaikan (Ajax, Artikel, Auth, dll).';
                            } elseif ($key === 'welcome') {
                                $description = 'Menonaktifkan controller home Anda dan mengembalikan tampilan awal bawaan CodeIgniter 4.';
                            } elseif ($key === 'plain') {
                                $description = 'Mengembalikan respon rute berupa teks polos tanpa kerangka HTML sama sekali (Langkah awal praktikum 1).';
                            } elseif ($key === 'simple') {
                                $description = 'Menampilkan struktur HTML sederhana untuk halaman About/Contact tanpa memuat berkas header/footer dari layout.';
                            } elseif ($key === 'layout') {
                                $description = 'Menggunakan struktur template header/footer lawas berbasis tabel/layout dasar (CSS Layout Praktikum 1).';
                            }
                        ?>
                        <a href="<?= base_url('/modul1-helper/set/' . $key); ?>" class="mode-card <?= $isActive ? 'active' : ''; ?>">
                            <div class="mode-header">
                                <span class="mode-name"><?= esc($name); ?></span>
                                <span class="mode-badge <?= $isActive ? 'badge-active' : 'badge-inactive'; ?>">
                                    <?= $isActive ? 'Aktif' : 'Pilih'; ?>
                                </span>
                            </div>
                            <div class="mode-desc"><?= esc($description); ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <h3 class="section-title">Tautan Pengujian</h3>
                <div class="links-panel">
                    <div class="url-list">
                        <div class="url-item">
                            <div class="url-info">
                                <span class="url-path">/</span>
                                <span class="url-label">Halaman Utama (Home)</span>
                            </div>
                            <a href="<?= base_url('/'); ?>" class="btn-visit" target="_blank">Buka</a>
                        </div>
                        <div class="url-item">
                            <div class="url-info">
                                <span class="url-path">/about</span>
                                <span class="url-label">Halaman Tentang Kami</span>
                            </div>
                            <a href="<?= base_url('/about'); ?>" class="btn-visit" target="_blank">Buka</a>
                        </div>
                        <div class="url-item">
                            <div class="url-info">
                                <span class="url-path">/contact</span>
                                <span class="url-label">Halaman Kontak</span>
                            </div>
                            <a href="<?= base_url('/contact'); ?>" class="btn-visit" target="_blank">Buka</a>
                        </div>
                        <div class="url-item">
                            <div class="url-info">
                                <span class="url-path">/faqs</span>
                                <span class="url-label">Pertanyaan Umum</span>
                            </div>
                            <a href="<?= base_url('/faqs'); ?>" class="btn-visit" target="_blank">Buka</a>
                        </div>
                        <div class="url-item">
                            <div class="url-info">
                                <span class="url-path">/page/tos</span>
                                <span class="url-label">Term of Services (Auto-Route)</span>
                            </div>
                            <a href="<?= base_url('/page/tos'); ?>" class="btn-visit" target="_blank">Buka</a>
                        </div>
                    </div>
                </div>

                <div class="notes-box">
                    <h4>💡 Tips Pengambilan Screenshot</h4>
                    <ul>
                        <li>Gunakan **Welcome Mode** untuk mengambil gambar halaman default bawaan CI4.</li>
                        <li>Gunakan **Plain Text Mode** untuk rute awal `/about`, `/contact`, `/faqs` agar tampil polos tanpa CSS/HTML.</li>
                        <li>Gunakan **Simple View Mode** untuk screenshot halaman about sebelum ditambahkan layout CSS.</li>
                        <li>Gunakan **Layout Template Mode** untuk screenshot halaman about dan kontak yang sudah dihias dengan header & footer CSS.</li>
                        <li>Kembalikan ke **Normal Mode** setelah selesai melakukan screenshot agar web berjalan seperti semula.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
