<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Lupa Password') ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">
</head>
<body class="auth-page">
    <div class="auth-card">
        <h1>Ubah Password</h1>
        <p>Masukkan ID, username, atau email yang terdaftar lalu buat password baru. Password boleh pendek untuk pembelajaran lokal.</p>

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
                <label for="new_password">Password Baru</label>
                <input id="new_password" type="password" name="new_password" placeholder="Boleh 1 huruf/angka, contoh: 1">
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password Baru</label>
                <input id="confirm_password" type="password" name="confirm_password" placeholder="Ulangi password baru">
            </div>
            <button type="submit" class="btn">Perbarui Password</button>
        </form>

        <div class="auth-links">
            <a href="<?= base_url('/user/login'); ?>">Kembali ke login</a>
            <a href="<?= base_url('/user/register'); ?>">Buat akun baru</a>
        </div>
    </div>
</body>
</html>
