<?php
/**
 * Script untuk generate placeholder images untuk semua gambar di database
 * yang belum ada file fisiknya
 */

require_once 'config/koneksi.php';

$imgDir = __DIR__ . '/assets/img/';

// Buat folder jika belum ada
if (!is_dir($imgDir)) {
    mkdir($imgDir, 0755, true);
}

// Ambil semua gambar dari tabel konten dan galeri
$images = [];

// Dari tabel konten
$stmt = $pdo->query("SELECT DISTINCT gambar FROM konten WHERE gambar IS NOT NULL AND gambar != ''");
$kontenImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
$images = array_merge($images, $kontenImages);

// Dari tabel galeri
$stmt = $pdo->query("SELECT DISTINCT gambar FROM galeri WHERE gambar IS NOT NULL AND gambar != ''");
$galeriImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
$images = array_merge($images, $galeriImages);

// Hapus duplikat
$images = array_unique($images);

$created = 0;
$existing = 0;

foreach ($images as $img) {
    $filepath = $imgDir . basename($img);
    
    if (!file_exists($filepath)) {
        // Generate placeholder image (100x70px dengan warna random)
        $width = 100;
        $height = 70;
        $image = imagecreate($width, $height);
        
        // Warna background random
        $bgColor = imagecolorallocate($image, rand(100, 200), rand(100, 200), rand(100, 200));
        
        // Warna text
        $textColor = imagecolorallocate($image, 255, 255, 255);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);
        
        // Tulis nama file di tengah gambar
        $filename = pathinfo($img, PATHINFO_FILENAME);
        $text = substr($filename, 0, 20);
        imagestring($image, 1, 5, 30, $text, $textColor);
        
        // Save
        if (strpos(strtolower($img), '.png') !== false) {
            imagepng($image, $filepath);
        } else {
            imagejpeg($image, $filepath, 80);
        }
        
        imagedestroy($image);
        $created++;
        echo "✓ Created: $img<br>";
    } else {
        $existing++;
    }
}

echo "<hr>";
echo "Selesai! $created gambar baru dibuat, $existing gambar sudah ada.<br>";
echo "File gambar tersimpan di: <code>assets/img/</code><br>";
echo "<br><a href='admin/dashboard.php'>Kembali ke Dashboard</a>";
?>
