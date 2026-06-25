<table>
  <tr><td><strong>Nama</strong></td><td>Rafi Maulana Firdaus</td></tr>
  <tr><td><strong>NIM</strong></td><td>312210369</td></tr>
  <tr><td><strong>Kelas</strong></td><td>I241E</td></tr>
  <tr><td><strong>Mata Kuliah</strong></td><td>Pemrograman Web 2</td></tr>
  <tr><td><strong>Dosen Pengajar</strong></td><td>Agung Nugroho, S.Kom., M.Kom.</td></tr>
</table>

# Proyek Praktikum Pemrograman Web 2

Proyek ini berisi hasil pengerjaan modul praktikum mata kuliah Pemrograman Web 2 menggunakan framework CodeIgniter 4 sebagai backend dan Vue.js sebagai frontend SPA.

## Tech Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-EE4323?style=for-the-badge&logo=codeigniter&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vue.js&logoColor=4FC08D)
![Axios](https://img.shields.io/badge/Axios-5A29E4?style=for-the-badge&logo=axios&logoColor=white)
![Postman](https://img.shields.io/badge/Postman-FF6C37?style=for-the-badge&logo=postman&logoColor=white)

---

## Ringkasan Progress Praktikum

| Praktikum | Pokok Bahasan                | Target Output                                                                        |
| --------- | ---------------------------- | ------------------------------------------------------------------------------------ |
| 1         | PHP Framework                | Instalasi CI4, konfigurasi routing, controller, view, dan layout sederhana           |
| 2         | CRUD Artikel                 | Model data, interaksi database, tambah, baca, edit, dan hapus artikel                |
| 3         | View Layout dan View Cell    | Modularisasi layout utama dan pembuatan komponen artikel terkini di sidebar          |
| 4         | Login dan Auth Filter        | Otentikasi admin, session, dan proteksi rute admin menggunakan filter                |
| 5         | Pagination dan Pencarian     | Filter data menggunakan query pencarian dan pagination data artikel                  |
| 6         | Relasi Tabel                 | Relasi tabel artikel dengan tabel kategori menggunakan Query Builder                 |
| 7         | Upload File                  | Unggah file gambar pada artikel, validasi, dan hapus berkas gambar dari storage      |
| 8         | AJAX                         | Halaman pengelolaan artikel berbasis AJAX tanpa reload halaman penuh                 |
| 10        | REST API                     | Penyediaan endpoint RESTful API format JSON untuk artikel                            |
| 11        | VueJS Frontend API           | Frontend VueJS terpisah untuk mengkonsumsi data dari REST API                        |
| 12        | VueJS Komponen & Routing SPA | Implementasi Single Page Application (SPA) menggunakan Vue Router                    |
| 13        | Autentikasi SPA              | Login frontend SPA, penyimpanan token di localStorage, dan Navigation Guards         |
| 14        | Keamanan API                 | Pembatasan akses API manipulasi data menggunakan Token Bearer dan Axios Interceptors |

---

## Detail Praktikum dan Screenshot

### Praktikum 1: PHP Framework CodeIgniter

Penyiapan awal proyek berbasis framework CodeIgniter 4 secara manual. Langkah praktikum meliputi konfigurasi server (aktivasi ekstensi PHP penting pada php.ini seperti intl, json, xml, dan mysqlnd), penyesuaian berkas env/.env untuk mengaktifkan mode development (mode debugging), penggunaan CLI Spark untuk memantau status rute aktif, pembuatan rute manual pada berkas app/Config/Routes.php, pembuatan controller Page.php dengan rute pendukung /about, /contact, /faqs, pembuatan file view dasar, serta pemisahan layout menggunakan berkas template header.php dan footer.php berbasis stylesheet style.css.

[Screenshot: Halaman Default Welcome Screen CodeIgniter 4]

[Screenshot: Tampilan Halaman About Dengan Layout CSS Header dan Footer]

---

### Praktikum 2: Framework Lanjutan CRUD

Implementasi fitur manipulasi data (Create, Read, Update, Delete) pada tabel artikel. Proses ini diawali dengan pembuatan skema database lab_ci4 dan tabel artikel di MySQL. Konfigurasi koneksi database disesuaikan pada berkas .env. Kemudian, dibuat kelas data ArtikelModel.php yang mewarisi CodeIgniter\Model untuk interaksi database secara aman. Di sisi controller Artikel.php, ditambahkan logika penanganan input, validasi data masukan, penyimpanan, pembacaan detail artikel menggunakan slug dinamis, pengeditan data, dan penghapusan data artikel di halaman admin.

[Screenshot: Halaman Daftar Artikel Publik]

[Screenshot: Halaman Admin Kelola Artikel]

---

### Praktikum 3: View Layout dan View Cell

Modularisasi tampilan web untuk meminimalkan redundansi kode HTML menggunakan fitur View Layout bawaan CodeIgniter 4. Seluruh halaman utama dipindahkan ke dalam layout induk app/Views/layout/main.php dengan menggunakan blok renderSection('content'). Selain itu, diterapkan konsep View Cell melalui pembuatan komponen reusable ArtikelTerkini di app/Cells/ArtikelTerkini.php untuk menampilkan daftar artikel terbaru secara dinamis pada bagian sidebar web publik tanpa membebani logika controller utama.

[Screenshot: Render Widget Artikel Terkini pada Sidebar Halaman Publik]

---

### Praktikum 4: Modul Login

Implementasi sistem keamanan otentikasi admin berbasis sesi (session). Dilakukan pembuatan tabel user untuk menyimpan data kredensial akun dan seeder data awal admin. Dibuat antarmuka login serta controller User.php untuk memproses verifikasi password menggunakan hashing aman. Untuk melindungi area administratif admin dari akses ilegal pihak luar, dibuat kelas filter Auth.php pada folder app/Filters yang secara otomatis mencegat request dan mengalihkan pengguna ke halaman login jika sesi otentikasi tidak ditemukan.

[Screenshot: Halaman Login Admin]

[Screenshot: Halaman Admin Kelola Artikel Terproteksi (Akses setelah Login)]

---

### Praktikum 5: Pagination dan Pencarian

Optimalisasi kinerja dan kegunaan antarmuka web dengan menambahkan fitur pencarian teks dan paginasi data artikel. Paginasi diimplementasikan menggunakan pustaka internal CI4 melalui pemanggilan fungsi paginate(10) pada model artikel guna membatasi jumlah baris data per halaman. Selain itu, ditambahkan formulir pencarian judul artikel menggunakan klausa filter like pada query SQL yang diikat ke parameter q dari request input URL.

[Screenshot: Hasil Pencarian Artikel dan Pagination Halaman Publik]

---

### Praktikum 6: Relasi Tabel dan Query Builder

Pembuatan struktur relasi data bertipe One-to-Many antara kategori dan artikel. Dilakukan pembuatan tabel kategori baru pada database dan penambahan kolom foreign key id_kategori pada tabel artikel. Model baru KategoriModel.php dibuat untuk mempermudah manipulasi data kategori. Query pengambilan artikel kemudian ditingkatkan menggunakan Query Builder join untuk menggabungkan tabel artikel dan kategori, sehingga nama kategori dapat dirender secara dinamis pada halaman publik, admin, serta pilihan dropdown formulir input artikel.

[Screenshot: Halaman Artikel Publik dengan Kolom Kategori]

[Screenshot: Struktur Relasi Tabel Artikel dan Kategori di phpMyAdmin]

---

### Praktikum 7: Upload File Gambar

Penambahan fitur pengunggahan file gambar sampul artikel. Form tambah dan edit artikel dimodifikasi dengan penambahan atribut enctype="multipart/form-data" dan komponen input file. Validasi diimplementasikan di controller untuk memeriksa ukuran maksimal file (2 MB), format ekstensi yang diizinkan (JPG, PNG, WEBP), serta validitas file. File gambar yang lolos validasi dipindahkan ke direktori public/gambar dengan nama acak (random name), sedangkan nama file disimpan di database. Saat artikel dihapus, sistem secara otomatis menghapus berkas fisik gambar terkait dari penyimpanan server.

[Screenshot: Form Tambah/Ubah Artikel dengan Input File Gambar]

[Screenshot: Gambar Artikel Tampil di Halaman Publik]

---

### Praktikum 8: AJAX

Implementasi antarmuka pengelolaan data asinkron menggunakan teknologi AJAX (Asynchronous JavaScript and XML) berbasis pustaka jQuery. Dibuat controller khusus AjaxController.php yang mengembalikan data berformat JSON menggunakan ResponseTrait. Melalui JavaScript di sisi klien, request dikirim ke backend untuk melakukan pembacaan, penambahan dengan form upload gambar (menggunakan objek FormData), pengeditan, dan penghapusan data artikel secara instan di latar belakang tanpa memicu pemuatan ulang halaman web secara menyeluruh.

[Screenshot: Halaman Dashboard Pengelolaan Artikel AJAX]

---

### Praktikum 10: REST API

Pembuatan antarmuka pemrograman aplikasi (API) RESTful untuk integrasi sistem eksternal. Dibuat kelas controller Post.php di dalam direktori app/Controllers yang mewarisi ResourceController dan memanfaatkan ResponseTrait untuk mempermudah penulisan respon HTTP. Rute didaftarkan menggunakan $routes->resource('post') untuk menangani secara otomatis pemetaan method GET (indeks & detail), POST (tambah), PUT/PATCH (ubah), dan DELETE (hapus) ke endpoint database artikel dengan format data keluaran JSON. Pengujian endpoint dilakukan menggunakan aplikasi Postman.

[Screenshot: Pengujian Endpoint REST API Artikel di Postman]

[Screenshot: Validasi Data Hasil API Tampil di Halaman Web Utama]

---

### Praktikum 11: VueJS Frontend API

Pembuatan folder aplikasi frontend terpisah menggunakan Vue.js versi 3 (via CDN) dan pustaka HTTP client Axios. Aplikasi frontend diletakkan pada folder mandiri lab8_vuejs. Kode JavaScript pada berkas assets/js/app.js dirancang untuk melakukan request asinkron ke server backend REST API CodeIgniter 4 pada rute /post guna memuat data artikel secara berkala dan merendernya secara dinamis di browser menggunakan teknik data binding reaktif.

[Screenshot: Tampilan Halaman Pengelolaan Artikel VueJS terhubung API]

---

### Praktikum 12: VueJS Komponen dan Routing SPA

Pengalihan arsitektur frontend menjadi Single Page Application (SPA) seutuhnya. Dilakukan modularisasi kode frontend dengan memisahkan halaman menjadi beberapa modul komponen JavaScript independen seperti Home.js, Artikel.js, dan About.js (berisi informasi profil mahasiswa). Ditambahkan pustaka Vue Router versi CDN untuk menangani perpindahan rute navigasi di sisi klien menggunakan elemen router-link dan router-view sehingga transisi halaman berlangsung instan tanpa reload browser.

[Screenshot: Halaman Profil Mahasiswa SPA VueJS]

---

### Praktikum 13: Autentikasi SPA dan Navigation Guards

Implementasi sistem keamanan otentikasi login terdesentralisasi pada sisi frontend SPA. Ditambahkan halaman formulir login dinamis Login.js yang mengirimkan request POST kredensial pengguna ke endpoint otentikasi API CodeIgniter (/api/login). Jika data valid, token otentikasi dan status login disimpan dalam localStorage browser. Untuk membatasi akses halaman admin, diterapkan fitur Navigation Guards (router.beforeEach) yang secara otomatis menyaring request rute dan mengalihkan pengguna ke halaman login jika mendeteksi ketiadaan token otentikasi.

[Screenshot: Halaman Login Frontend SPA VueJS]

[Screenshot: Halaman Kelola Artikel Setelah Berhasil Login SPA]

---

### Praktikum 14: Keamanan API, Token, dan Axios Interceptors

Penyelesaian lapisan pengamanan API dengan menerapkan filter otentikasi Bearer Token pada backend CodeIgniter 4 melalui pembuatan filter kelas ApiAuthFilter.php untuk memproteksi endpoint mutasi data (POST, PUT, PATCH, DELETE). Di sisi klien VueJS, dikonfigurasi fitur Axios Interceptors pada berkas assets/js/app.js untuk secara otomatis menyisipkan tajuk Authorization Bearer Token pada setiap request keluar dan menangkap error 401 Unauthorized guna mengembalikan pengguna ke halaman login jika sesi token kedaluwarsa.

[Screenshot: Response API 401 Unauthorized Saat Request Tanpa Bearer Token]

[Screenshot: Konfigurasi Axios Request dan Response Interceptor di Frontend]
