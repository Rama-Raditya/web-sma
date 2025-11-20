<?php
require_once '../config/koneksi.php';

// Validasi dan sanitasi ID konten
 $contentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($contentId > 0) {
    // Tampilkan konten tunggal
    try {
        $stmt = $pdo->prepare("SELECT * FROM konten WHERE id = ? AND kategori = 'kegiatan'");
        $stmt->execute([$contentId]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$content) {
            // Jika konten tidak ditemukan, redirect ke halaman kegiatan
            header('Location: kegiatan.php');
            exit;
        }
        
        // Ambil konten terkait
        $stmt = $pdo->prepare("SELECT * FROM konten WHERE kategori = 'kegiatan' AND id != ? ORDER BY created_at DESC LIMIT 3");
        $stmt->execute([$contentId]);
        $relatedContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Tangani error database
        $error = "Terjadi kesalahan saat memuat data: " . $e->getMessage();
        $content = null;
        $relatedContent = [];
    }
} else {
    // Tampilkan daftar konten
    try {
        $stmt = $pdo->query("SELECT * FROM konten WHERE kategori = 'kegiatan' ORDER BY created_at DESC");
        $contentList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Tangani error database
        $error = "Terjadi kesalahan saat memuat data: " . $e->getMessage();
        $contentList = [];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan - SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .content-body {
            line-height: 1.6;
        }
        .content-meta {
            color: #6c757d;
        }
        .article-image {
            max-height: 500px;
            object-fit: cover;
        }
        .related-activity {
            max-height: 80px;
            object-fit: cover;
        }
        .social-links a {
            transition: transform 0.2s ease;
        }
        .social-links a:hover {
            transform: translateY(-3px);
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-warning {
            transition: all 0.3s ease;
        }
        .btn-warning:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fas fa-school"></i> SMA Negeri 72 Jakarta
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="berita.php">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artikel.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="kegiatan.php">Kegiatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../galeri/galeri.php">Galeri</a>
                    </li>
                    <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo (function_exists('isAdmin') && isAdmin()) ? '../admin/dashboard.php' : '../redaksi/dashboard.php'; ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5 flex-grow-1">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($contentId > 0 && $content): ?>
            <!-- Single Content View -->
            <div class="row">
                <div class="col-lg-8">
                    <article class="card shadow-sm">
                        <img src="../assets/img/<?php echo htmlspecialchars($content['gambar']); ?>" class="card-img-top article-image" alt="<?php echo htmlspecialchars($content['judul']); ?>">
                        <div class="card-body">
                            <h1 class="display-6 mb-3"><?php echo htmlspecialchars($content['judul']); ?></h1>
                            <div class="content-meta mb-4">
                                <span class="badge bg-warning text-dark"><?php echo ucfirst(htmlspecialchars($content['kategori'])); ?></span>
                                <span class="ms-3"><i class="fas fa-user"></i> <?php echo htmlspecialchars($content['penulis']); ?></span>
                                <span class="ms-3"><i class="fas fa-calendar"></i> <?php echo date('d F Y', strtotime($content['created_at'])); ?></span>
                            </div>
                            <div class="content-body">
                                <?php echo nl2br(htmlspecialchars($content['konten'])); ?>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>Kegiatan Terkait
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($relatedContent)): ?>
                                <p class="text-muted">Tidak ada kegiatan terkait.</p>
                            <?php else: ?>
                                <?php foreach ($relatedContent as $related): ?>
                                    <div class="mb-3 pb-3 border-bottom">
                                        <h6><a href="kegiatan.php?id=<?php echo $related['id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($related['judul']); ?></a></h6>
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i> <?php echo date('d M Y', strtotime($related['created_at'])); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Content List View -->
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="display-6 mb-0">
                            <i class="fas fa-calendar-alt text-warning me-2"></i>Kegiatan
                        </h1>
                        <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                            <a href="<?php echo (function_exists('isAdmin') && isAdmin()) ? '../admin/tambah_artikel.php?kategori=kegiatan' : '../redaksi/tambah_artikel.php?kategori=kegiatan'; ?>" class="btn btn-warning">
                                <i class="fas fa-plus me-1"></i> Tambah Kegiatan
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (empty($contentList)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i> Belum ada kegiatan yang tersedia.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($contentList as $content): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="../assets/img/<?php echo htmlspecialchars($content['gambar']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($content['judul']); ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($content['judul']); ?></h5>
                                    <p class="card-text flex-grow-1"><?php echo substr(htmlspecialchars($content['konten']), 0, 150) . '...'; ?></p>
                                    <div class="content-meta mb-3">
                                        <small><i class="fas fa-user"></i> <?php echo htmlspecialchars($content['penulis']); ?></small>
                                        <small class="ms-3"><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($content['created_at'])); ?></small>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <a href="kegiatan.php?id=<?php echo $content['id']; ?>" class="btn btn-warning">
                                            <i class="fas fa-book-reader me-1"></i> Baca Selengkapnya
                                        </a>
                                        <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                                            <a href="<?php echo (function_exists('isAdmin') && isAdmin()) ? '../admin/edit_artikel.php?id=' . $content['id'] : '../redaksi/edit_artikel.php?id=' . $content['id']; ?>" class="btn btn-outline-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>