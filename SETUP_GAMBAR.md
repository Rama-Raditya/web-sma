# Setup Gambar - web-sma

## Lokasi Penyimpanan Gambar

Semua gambar untuk artikel, berita, kegiatan, dan galeri disimpan di folder:

```
web-sma/assets/img/
```

## Cara Kerja Sistem Gambar

### 1. **Upload Gambar Manual**
   - Buka halaman Admin/Redaksi: `admin/dashboard.php` atau `redaksi/dashboard.php`
   - Klik **Tambah Artikel/Berita/Kegiatan** atau **Tambah Galeri**
   - Pilih file gambar di form "Gambar"
   - Submit form
   - Gambar otomatis tersimpan ke `assets/img/`

### 2. **Generate Placeholder Gambar (untuk Demo)**
   - Jalankan script: `http://localhost/web-sma/generate-placeholders.php`
   - Script ini membaca semua nama gambar dari database (tabel `konten` dan `galeri`)
   - Membuat placeholder PNG 100x70px jika file belum ada
   - Berguna untuk testing sebelum upload gambar asli

### 3. **Database Schema**

**Tabel konten:**
```sql
CREATE TABLE konten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    konten TEXT NOT NULL,
    kategori ENUM('berita', 'artikel', 'kegiatan') NOT NULL,
    gambar VARCHAR(255),  -- Menyimpan nama file: "berita1.jpg"
    penulis VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Tabel galeri:**
```sql
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    gambar VARCHAR(255) NOT NULL,  -- Menyimpan nama file: "gedung1.jpg"
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Path Resolusi Gambar di View

Semua file view (`.php`) menggunakan path:

```php
../assets/img/<?php echo $gambar; ?>
```

Contoh dari `kategori/berita.php` (menampilkan berita):
```php
<img src="../assets/img/<?php echo htmlspecialchars($content['gambar']); ?>" 
     class="img-fluid rounded mb-4" 
     alt="...">
```

Dashboard (`admin/dashboard.php`) juga menggunakan path yang sama dengan fallback SVG placeholder jika gambar tidak ditemukan.

## Struktur Folder yang Benar

```
web-sma/
├── admin/
│   ├── dashboard.php          (menampilkan gambar dari ../assets/img/)
│   ├── tambah_artikel.php     (upload ke ../assets/img/)
│   ├── edit_artikel.php
│   └── hapus_artikel.php
├── redaksi/
│   ├── tambah_artikel.php     (upload ke ../assets/img/)
│   ├── edit_artikel.php
│   └── dashbord.php
├── assets/
│   └── img/                   ← FOLDER UTAMA GAMBAR
│       ├── berita1.jpg
│       ├── artikel1.jpg
│       ├── kegiatan1.jpg
│       ├── gedung1.jpg
│       └── ...
├── kategori/
│   ├── berita.php             (tampilkan dari ../assets/img/)
│   ├── artikel.php
│   └── kegiatan.php
├── galeri/
│   └── galeri.php             (tampilkan dari ../assets/img/)
├── config/
│   └── koneksi.php
├── database/
│   └── website.sql
├── generate-placeholders.php  (buat placeholder gambar)
└── README.md
```

## Troubleshooting

### Gambar tidak muncul di dashboard
1. Pastikan folder `assets/img/` ada dan readable
2. Pastikan nama file gambar di database sesuai dengan file fisik yang ada
3. Jalankan `http://localhost/web-sma/generate-placeholders.php` untuk buat placeholder
4. Check permission folder (chmod 755)

### Upload gambar gagal
1. Pastikan folder `assets/img/` writable (chmod 755 atau 777)
2. Check error di `$_SESSION['error']` pada halaman form

### Gambar masih tidak keluar setelah placeholder
1. Buka DevTools (F12), tab "Network" → periksa response gambar
2. Buka tab "Console" → lihat error messages
3. Manual copy gambar ke `assets/img/` folder menggunakan FTP/File Manager

## Contoh Struktur Data di Database

```sql
-- Data yang disimpan di kolom 'gambar'
INSERT INTO konten (judul, konten, kategori, gambar, penulis) VALUES
('Berita 1', 'Konten berita...', 'berita', 'berita1.jpg', 'Admin'),
('Artikel 1', 'Konten artikel...', 'artikel', 'artikel1.jpg', 'Redaksi');

INSERT INTO galeri (judul, gambar, deskripsi) VALUES
('Gedung Sekolah', 'gedung1.jpg', 'Tampak depan gedung');
```

File `berita1.jpg` harus ada di `web-sma/assets/img/berita1.jpg`

## Tips Penting

- Gunakan nama file **tanpa spasi** (ganti spasi dengan `-` atau `_`)
- Format gambar yang didukung: JPG, PNG, GIF, WebP
- Ukuran rekomendasi: max 5MB per gambar
- Untuk performa, resize gambar ke max 1000x800px sebelum upload
- Jangan hapus folder `assets/img/` saat deploy live

---

Dokumentasi ini dibuat untuk panduan teknis implementasi gambar di web-sma.
