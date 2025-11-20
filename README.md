# web-sma

Dokumentasi singkat untuk proyek website sekolah sederhana (web-sma).

## Ringkasan
- **Nama proyek:** web-sma
- **Deskripsi:** Website sederhana untuk manajemen artikel/berita, galeri, dan halaman admin redaksi.
- **Teknologi:** PHP (kode procedural), MySQL, HTML, CSS, JavaScript.

## Prasyarat
- Windows (XAMPP direkomendasikan)
- PHP (disediakan oleh XAMPP)
- MySQL / MariaDB (disediakan oleh XAMPP)

## Instalasi dan Setup (Windows + XAMPP)
1. Salin atau letakkan folder proyek `web-sma` ke direktori `htdocs` XAMPP (mis. `C:\xampp\htdocs\web-sma`).
2. Jalankan XAMPP Control Panel, mulai modul **Apache** dan **MySQL**.
3. Import database:
   - Buka `http://localhost/phpmyadmin` dan buat database baru (mis. `web_sma`), lalu impor file `database/website.sql`.
   - Atau gunakan command line (ganti `root`/password bila perlu):
     ```cmd
     mysql -u root -p web_sma < "C:\xampp\htdocs\web-sma\database\website.sql"
     ```
4. Konfigurasi koneksi database:
   - Buka file `config/koneksi.php` dan sesuaikan parameter host, username, password, dan nama database.

## Menjalankan Aplikasi
1. Akses aplikasi di browser: `http://localhost/web-sma/`
2. Halaman login ada di `login.php`. Halaman administrasi biasanya di `admin/dashboard.php` atau `redaksi/dashbord.php` (periksa file untuk kredensial default jika ada).
3. **Generate placeholder gambar (opsional):**
   - Jika gambar belum muncul pada dashboard/kategori, jalankan script pembuat placeholder di browser: `http://localhost/web-sma/generate-placeholders.php`
   - Script ini akan membuat placeholder PNG untuk semua gambar di database yang belum ada file fisiknya.
   - Placeholder hanya untuk demo; ganti dengan gambar asli dengan upload melalui form "Tambah Artikel".

## Struktur File (ringkasan)
- `index.php` — Halaman depan
- `login.php`, `logout.php` — Autentikasi
- `admin/` — Halaman admin (tambah/edit/hapus artikel)
- `redaksi/` — Halaman redaksi
- `assets/` — CSS dan JS (ada `css/style.css` dan `js/script.js`)
- `config/koneksi.php` — Konfigurasi koneksi database
- `database/website.sql` — Dump database untuk impor
- `galeri/`, `kategori/`, `img/` — Koleksi media dan kategori

## Penggunaan Singkat
- Tambah artikel: `admin/tambah_artikel.php` atau `redaksi/tambah_artikel.php`.
- Edit/hapus artikel: `admin/edit_artikel.php`, `admin/hapus_artikel.php`.
- Kelola galeri: buka `galeri/galeri.php`.

## Penanganan Gambar

**Lokasi penyimpanan:** `assets/img/`

**Cara menampilkan gambar:**
1. Upload gambar melalui form "Tambah Artikel" atau "Tambah Galeri"
2. Gambar otomatis disimpan ke folder `assets/img/`
3. Dashboard, kategori, dan galeri akan menampilkan gambar secara otomatis
4. Jika gambar belum ada, jalankan `http://localhost/web-sma/generate-placeholders.php` untuk membuat placeholder demo

**Helper functions** untuk upload gambar tersedia di `config/image-helper.php`

Lihat file `SETUP_GAMBAR.md` untuk dokumentasi lengkap penanganan gambar.

## Troubleshooting
- Jika muncul error koneksi database, pastikan `config/koneksi.php` berisi host, username, password dan nama database yang benar.
- Jika halaman kosong / error PHP, aktifkan `display_errors` di `php.ini` (untuk development) atau periksa log Apache di XAMPP Control Panel.

## Kontribusi
- Untuk perubahan kecil, fork/clone proyek lalu ajukan patch atau kirim file yang diperbarui.

## Kontak
- Untuk pertanyaan, hubungi pemelihara email:ramaraditya4371@gmail.com.

---
