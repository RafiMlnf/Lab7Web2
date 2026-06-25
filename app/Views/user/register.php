<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Buat Akun') ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Buat Akun</h1>
        <p>Silakan isi data berikut untuk membuat akun baru. Password boleh pendek untuk kebutuhan pembelajaran lokal.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" value="<?= old('username') ?>" placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= old('email') ?>" placeholder="contoh@email.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Boleh 1 huruf/angka, contoh: 1">
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input id="confirm_password" type="password" name="confirm_password" placeholder="Ulangi password">
            </div>
            <button type="submit" class="btn">Buat Akun</button>
        </form>

        <div class="auth-links">
            <a href="<?= base_url('/user/login'); ?>">Sudah punya akun? Login</a>
            <a href="<?= base_url('/user/forgot-password'); ?>">Lupa password?</a>
        </div>
    </div>
</body>
</html>
