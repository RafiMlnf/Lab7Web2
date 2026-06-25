<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<small>Kontak</small>
<h1>Halaman Contact</h1>
<hr>
<p>Silakan hubungi kami melalui email untuk pertanyaan, saran, atau masukan terkait website praktikum ini.</p>

<div class="contact-email-card reveal-item">
    <div class="contact-gmail-icon" aria-hidden="true">
        <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
            <path fill="#EA4335" d="M54 18H10a4 4 0 0 0-4 4v20a4 4 0 0 0 4 4h44a4 4 0 0 0 4-4V22a4 4 0 0 0-4-4z"/>
            <path fill="#FFFFFF" d="M54 22v1.65L32 39 10 23.65V22l22 15 22-15z"/>
            <path fill="#4285F4" d="M10 22v20h8V28.2z"/>
            <path fill="#34A853" d="M54 22v20h-8V28.2z"/>
            <path fill="#FBBC04" d="M18 42h28V27.8L32 37.2 18 27.8z"/>
            <path fill="#C5221F" d="M54 18 32 34 10 18z"/>
        </svg>
    </div>

    <div class="contact-email-content">
        <span class="contact-mini-badge">Gmail Contact</span>
        <h2>Hubungi lewat Email</h2>
        <p>Email ini dapat digunakan untuk mengirim pertanyaan, saran, atau kebutuhan komunikasi terkait tugas dan website praktikum.</p>

        <div class="contact-email-row">
            <a class="contact-email-link" href="mailto:rafimaulanafirdaus@gmail.com">
                rafimaulanafirdaus@gmail.com
            </a>
            <a class="contact-email-btn" href="mailto:rafimaulanafirdaus@gmail.com">
                Kirim Email
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
