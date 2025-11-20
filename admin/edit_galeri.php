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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $gambar = $gallery['gambar']; // Keep old image by default
    
    // Handle image upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $targetDir = '../assets/img/';
        $newGambar = basename($_FILES['gambar']['name']);
        $targetPath = $targetDir . $newGambar;
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
            $gambar = $newGambar;
        }
    }
    
    $stmt = $pdo->prepare("UPDATE galeri SET judul = ?, gambar = ?, deskripsi = ? WHERE id = ?");
    $stmt->execute([$judul, $gambar, $deskripsi, $id]);
    
    $_SESSION['success'] = 'Foto galeri berhasil diperbarui!';
    redirect('kelola_galeri.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galeri - SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-school"></i> SMA Negeri 72 Jakarta
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kelola_galeri.php">Kelola Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="display-6 mb-4">Edit Foto Galeri</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Foto</label>
                                <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($gallery['judul']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Gambar</label>
                                <div class="mb-2">
                                    <img src="../assets/img/<?php echo htmlspecialchars($gallery['gambar']); ?>" alt="<?php echo htmlspecialchars($gallery['judul']); ?>" style="max-width: 200px; max-height: 150px; border-radius: 4px;">
                                </div>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"><?php echo htmlspecialchars($gallery['deskripsi']); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="kelola_galeri.php" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
