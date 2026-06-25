-- SQL pendukung untuk update Praktikum 6/7/8 jika tidak memakai php spark migrate.
-- Jalankan di database lab_ci4 melalui phpMyAdmin.

CREATE TABLE IF NOT EXISTS kategori (
    id_kategori INT(11) AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    slug_kategori VARCHAR(100),
    PRIMARY KEY (id_kategori)
);

INSERT INTO kategori (nama_kategori, slug_kategori)
SELECT 'Berita Teknologi', 'berita-teknologi'
WHERE NOT EXISTS (SELECT 1 FROM kategori WHERE slug_kategori = 'berita-teknologi');

INSERT INTO kategori (nama_kategori, slug_kategori)
SELECT 'Tutorial Pemrograman', 'tutorial-pemrograman'
WHERE NOT EXISTS (SELECT 1 FROM kategori WHERE slug_kategori = 'tutorial-pemrograman');

INSERT INTO kategori (nama_kategori, slug_kategori)
SELECT 'Review Gadget', 'review-gadget'
WHERE NOT EXISTS (SELECT 1 FROM kategori WHERE slug_kategori = 'review-gadget');

-- Jika kolom berikut sudah ada, abaikan error duplicate column dari phpMyAdmin.
ALTER TABLE artikel ADD COLUMN id_kategori INT(11) NULL;
ALTER TABLE artikel ADD COLUMN gambar VARCHAR(200) NULL;
ALTER TABLE artikel ADD COLUMN status TINYINT(1) DEFAULT 0;
ALTER TABLE artikel ADD COLUMN slug VARCHAR(200) NULL;
ALTER TABLE artikel ADD COLUMN created_at DATETIME NULL;
ALTER TABLE artikel ADD COLUMN updated_at DATETIME NULL;

-- Jika constraint sudah ada, abaikan error duplicate constraint.
ALTER TABLE artikel
ADD CONSTRAINT fk_kategori_artikel
FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
ON DELETE SET NULL ON UPDATE CASCADE;

-- Update login praktikum: akun admin bisa login menggunakan ID/username/email dengan password pendek.
-- Setelah menjalankan query ini, coba login dengan ID: 1 dan Password: 1.
CREATE TABLE IF NOT EXISTS user (
    id INT(11) AUTO_INCREMENT,
    username VARCHAR(200) NOT NULL,
    useremail VARCHAR(200),
    userpassword VARCHAR(255),
    PRIMARY KEY(id)
);

INSERT INTO user (id, username, useremail, userpassword)
VALUES (1, 'admin', 'admin@email.com', '$2y$12$pQM.dkvVNCyaSI0pAhpPwu5yK2Z5DE78bJ2igoNGuQ3/ZJjUVvO8a')
ON DUPLICATE KEY UPDATE
    username = 'admin',
    useremail = 'admin@email.com',
    userpassword = '$2y$12$pQM.dkvVNCyaSI0pAhpPwu5yK2Z5DE78bJ2igoNGuQ3/ZJjUVvO8a';
