<?php
require_once '../config/koneksi.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get all gallery items
$stmt = $pdo->query("SELECT * FROM galeri ORDER BY created_at DESC");
$galleryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri - SMA Negeri 72 Jakarta</title>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Konten
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="tambah_artikel.php">Tambah Artikel</a></li>
                            <li><a class="dropdown-item" href="../kategori/berita.php">Kelola Berita</a></li>
                            <li><a class="dropdown-item" href="../kategori/artikel.php">Kelola Artikel</a></li>
                            <li><a class="dropdown-item" href="../kategori/kegiatan.php">Kelola Kegiatan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kelola_galeri.php">Galeri</a>
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
                <h1 class="display-6 mb-4">Kelola Galeri</h1>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <a href="tambah_galeri.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Foto Galeri
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Foto Galeri</h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($galleryItems) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Gambar</th>
                                            <th>Judul</th>
                                            <th>Deskripsi</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($galleryItems as $item): ?>
                                            <tr>
                                                <td style="width: 120px;">
                                                    <img src="../assets/img/<?php echo htmlspecialchars($item['gambar']); ?>" 
                                                         alt="<?php echo htmlspecialchars($item['judul']); ?>"
                                                         style="width: 100px; height: 70px; object-fit: cover; border-radius: 4px;">
                                                </td>
                                                <td><?php echo htmlspecialchars($item['judul']); ?></td>
                                                <td><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 60)) . (strlen($item['deskripsi']) > 60 ? '...' : ''); ?></td>
                                                <td><?php echo date('d M Y', strtotime($item['created_at'])); ?></td>
                                                <td>
                                                    <a href="edit_galeri.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="hapus_galeri.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus galeri ini?')">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Belum ada foto galeri. 
                                <a href="tambah_galeri.php" class="alert-link">Tambah foto sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
