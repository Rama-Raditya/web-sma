<?php
require_once '../config/koneksi.php';

if (!isLoggedIn() || !isRedaksi()) {
    redirect('../login.php');
}

// Get statistics
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM konten WHERE kategori = 'artikel' AND penulis = ?");
$stmt->execute([$_SESSION['username']]);
$artikelCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM konten WHERE kategori = 'berita' AND penulis = ?");
$stmt->execute([$_SESSION['username']]);
$beritaCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM konten WHERE kategori = 'kegiatan' AND penulis = ?");
$stmt->execute([$_SESSION['username']]);
$kegiatanCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get recent content
$stmt = $pdo->prepare("SELECT * FROM konten WHERE penulis = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['username']]);
$recentContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Redaksi - SMA Negeri 72 Jakarta</title>
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
                            <li><a class="dropdown-item" href="../kategori/artikel.php">Kelola Artikel</a></li>
                        </ul>
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
                <h1 class="display-6 mb-4">Dashboard Redaksi</h1>
                <p class="lead">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Artikel</h6>
                                <h3 class="text-success"><?php echo $artikelCount; ?></h3>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Berita</h6>
                                <h3 class="text-primary"><?php echo $beritaCount; ?></h3>
                            </div>
                            <i class="fas fa-newspaper fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Kegiatan</h6>
                                <h3 class="text-warning"><?php echo $kegiatanCount; ?></h3>
                            </div>
                            <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="tambah_artikel.php?cat=artikel" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Tambah Artikel
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="tambah_artikel.php?cat=berita" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i> Tambah Berita
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="tambah_artikel.php?cat=kegiatan" class="btn btn-warning w-100">
                                    <i class="fas fa-plus"></i> Tambah Kegiatan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Konten Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentContent as $content): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($content['judul']); ?></td>
                                            <td><span class="badge bg-primary"><?php echo ucfirst($content['kategori']); ?></span></td>
                                            <td><?php echo date('d M Y', strtotime($content['created_at'])); ?></td>
                                            <td>
                                                <a href="edit_artikel.php?id=<?php echo $content['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>