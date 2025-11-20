<?php
require_once '../config/koneksi.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    redirect('kelola_galeri.php');
}

$stmt = $pdo->prepare("SELECT * FROM galeri WHERE id = ?");
$stmt->execute([$id]);
$gallery = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$gallery) {
    redirect('kelola_galeri.php');
}

// Delete image file
if (!empty($gallery['gambar'])) {
    $imagePath = '../assets/img/' . $gallery['gambar'];
    if (file_exists($imagePath)) {
        @unlink($imagePath);
    }
}

// Delete from database
$stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['success'] = 'Foto galeri berhasil dihapus!';
redirect('kelola_galeri.php');
