<?php
require_once '../config/koneksi.php';

if (!isLoggedIn() || !isRedaksi()) {
    redirect('../login.php');
}

$category = isset($_GET['cat']) ? $_GET['cat'] : 'artikel';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $konten = $_POST['konten'];
    $kategori = $_POST['kategori'];
    $penulis = $_POST['penulis'];
    
    // Handle image upload
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $targetDir = '../assets/img/';
        // Buat folder jika belum ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $gambar = basename($_FILES['gambar']['name']);
        $targetPath = $targetDir . $gambar;
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetPath)) {
            // Upload berhasil
        } else {
            $_SESSION['error'] = 'Gagal upload gambar!';
            $gambar = '';
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO konten (judul, konten, kategori, gambar, penulis) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$judul, $konten, $kategori, $gambar, $penulis]);
    
    $_SESSION['success'] = 'Konten berhasil ditambahkan!';
    redirect('../kategori/' . $kategori . '.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Artikel - SMA Negeri 1 Jakarta</title>
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
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="display-6 mb-4">Tambah <?php echo ucfirst($category); ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="judul" name="judul" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="berita" <?php echo ($category === 'berita') ? 'selected' : ''; ?>>Berita</option>
                                    <option value="artikel" <?php echo ($category === 'artikel') ? 'selected' : ''; ?>>Artikel</option>
                                    <option value="kegiatan" <?php echo ($category === 'kegiatan') ? 'selected' : ''; ?>>Kegiatan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="penulis" class="form-label">Penulis</label>
                                <input type="text" class="form-control" id="penulis" name="penulis" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Gambar</label>
                                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="konten" class="form-label">Konten</label>
                                <textarea class="form-control" id="konten" name="konten" rows="10" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
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