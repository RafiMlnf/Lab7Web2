<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<h1><?= esc($title); ?></h1>
<hr>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($users): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= esc($user['id']); ?></td>
                    <td><?= esc($user['username']); ?></td>
                    <td><?= esc($user['useremail']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Belum ada user.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?= $this->endSection(); ?>
