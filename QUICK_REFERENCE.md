# Dokumentasi Gambar - Quick Reference

## File & Folder yang Ditambahkan/Diubah

### ✅ File Baru Dibuat:
1. **`generate-placeholders.php`** - Script untuk generate placeholder gambar dari database
2. **`quick-check.php`** - Halaman untuk verifikasi setup gambar
3. **`SETUP_GAMBAR.md`** - Dokumentasi lengkap penanganan gambar
4. **`config/image-helper.php`** - Helper functions untuk upload dan penampilan gambar
5. **`assets/img/`** - Folder untuk penyimpanan gambar (auto-created)

### 🔄 File yang Dimodifikasi:
1. **`admin/dashboard.php`** - Ditambahkan tampilan thumbnail gambar dengan fallback SVG
2. **`admin/tambah_artikel.php`** - Diperbaiki upload handler dengan validasi
3. **`redaksi/tambah_artikel.php`** - Diperbaiki upload handler dengan validasi
4. **`README.md`** - Ditambahkan instruksi penanganan gambar

## Alur Kerja Gambar

```
┌─────────────────────────────────────────────────────────────┐
│ 1. User Upload Gambar melalui Form                         │
│    admin/tambah_artikel.php atau redaksi/tambah_artikel.php │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│ 2. File Gambar Disimpan ke assets/img/                     │
│    Nama file disimpan di Database (tabel konten/galeri)     │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│ 3. Tampilkan Gambar di View (kategori/berita.php, dll)    │
│    Path: ../assets/img/[nama_file]                         │
└─────────────────────────────────────────────────────────────┘
```

## Quick Start - Testing Gambar

### Langkah 1: Verifikasi Setup
Buka di browser:
```
http://localhost/web-sma/quick-check.php
```
Akan menampilkan status: DB, folder, file gambar, dll.

### Langkah 2: Generate Placeholder (Opsional)
Jika gambar belum ada, buat placeholder:
```
http://localhost/web-sma/generate-placeholders.php
```
Script ini membuat gambar placeholder 100x70px untuk semua gambar di database.

### Langkah 3: Lihat di Dashboard
Buka admin dashboard untuk verifikasi:
```
http://localhost/web-sma/admin/dashboard.php
```
Tabel "Konten Terbaru" akan menampilkan thumbnail gambar.

### Langkah 4: Upload Gambar Asli
- Klik tombol "Tambah Berita/Artikel/Kegiatan" di dashboard
- Pilih file gambar di form
- Submit
- Gambar otomatis tersimpan di `assets/img/` dan langsung terlihat

## Path Referensi untuk Pengembang

### Upload Handler (di form action):
```php
$targetDir = '../assets/img/';
if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
$gambar = basename($_FILES['gambar']['name']);
move_uploaded_file($_FILES['gambar']['tmp_name'], $targetDir . $gambar);
```

### Display Image (di view):
```php
<img src="../assets/img/<?php echo htmlspecialchars($content['gambar']); ?>" 
     alt="thumbnail"
     onerror="this.src='placeholder.svg'">
```

### Helper Functions (new):
```php
require_once 'config/image-helper.php';

// Get image URL
$url = getImageUrl($filename);

// Generate img tag dengan fallback
echo getImageTag($filename, 'Alt text', ['class' => 'img-fluid']);

// Handle upload
$result = handleImageUpload($_FILES['gambar'], '../assets/img/');
if ($result['success']) {
    echo "Upload OK: " . $result['filename'];
} else {
    echo "Error: " . $result['error'];
}

// Check image exists
if (imageExists($filename)) {
    echo "Image found!";
}

// Delete image file
deleteImageFile($filename);
```

## Troubleshooting Checklist

- [ ] Folder `assets/img/` ada dan writable (chmod 755)
- [ ] Database `sekolah` terhubung
- [ ] Tabel `konten` dan `galeri` ada
- [ ] File gambar di database sesuai dengan file di `assets/img/`
- [ ] Run `quick-check.php` untuk verifikasi otomatis
- [ ] Jika gambar hilang, run `generate-placeholders.php`
- [ ] Check browser console (F12) untuk error jaringan
- [ ] Pastikan extension `php-gd` terinstall untuk placeholder generator

## Support Files

- **Dokumentasi lengkap:** `SETUP_GAMBAR.md`
- **Verifikasi setup:** `quick-check.php`
- **Generator placeholder:** `generate-placeholders.php`
- **Helper library:** `config/image-helper.php`

---
Dibuat: November 2025
Untuk: web-sma project
