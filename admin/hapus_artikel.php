<?php
require_once '../config/koneksi.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM konten WHERE id = ?");
    $stmt->execute([$id]);
    
    $_SESSION['success'] = 'Konten berhasil dihapus!';
}

redirect('dashboard.php');
?>