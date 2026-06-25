<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login') ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Login Admin</h1>
        <p>Masuk menggunakan ID, username, atau email. Untuk akun bawaan, bisa pakai ID <strong>1</strong> dengan password <strong>1</strong>.</p>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="login">ID / Username / Email</label>
                <input id="login" type="text" name="login" value="<?= old('login') ?>" placeholder="Contoh: 1, admin, atau admin@email.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Contoh: 1">
            </div>
            <button type="submit" class="btn">Masuk</button>
        </form>

        <div class="auth-links">
            <a href="<?= base_url('/user/register'); ?>">Coba daftar akun</a>
            <a href="<?= base_url('/user/forgot-password'); ?>">Lupa password? Ubah di sini</a>
            <a href="<?= base_url('/'); ?>">Kembali ke beranda</a>
        </div>
    </div>
</body>
</html>
