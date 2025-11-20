<?php
require_once '../config/koneksi.php';

// Query untuk mengambil data galeri
try {
    $stmt = $pdo->query("SELECT * FROM galeri ORDER BY created_at DESC");
    $galleryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle error jika query gagal
    $galleryItems = [];
    $errorMessage = "Terjadi kesalahan saat memuat data galeri: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
    <style>
        /* Custom styles untuk galeri */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            height: 250px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.03);
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-overlay h6 {
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .gallery-overlay small {
            display: block;
            max-height: 100px;
            overflow-y: auto;
        }
        
        .social-links a {
            transition: transform 0.2s ease;
        }
        
        .social-links a:hover {
            transform: translateY(-3px);
        }
        
        /* Animasi untuk navbar */
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Animasi untuk footer */
        footer {
            margin-top: auto;
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
                        <a class="nav-link" href="../kategori/berita.php">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../kategori/artikel.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../kategori/kegiatan.php">Kegiatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="galeri.php">Galeri</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isAdmin() ? '../admin/dashboard.php' : '../redaksi/dashboard.php'; ?>">
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
        <div class="row">
            <div class="col-12">
                <h1 class="display-6 mb-4">
                    <i class="fas fa-images text-primary"></i> Galeri Foto
                </h1>
                <p class="lead">Koleksi foto dokumentasi kegiatan dan fasilitas SMA Negeri 72 Jakarta</p>
            </div>
        </div>

        <!-- Error Message (jika ada) -->
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <!-- Gallery Items -->
        <div class="row">
            <?php if (empty($galleryItems)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle"></i> Belum ada foto di galeri.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($galleryItems as $item): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="gallery-item">
                            <img src="../assets/img/<?php echo htmlspecialchars($item['gambar']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['judul']); ?>">
                            <div class="gallery-overlay">
                                <div class="text-center text-white p-3">
                                    <h6><?php echo htmlspecialchars($item['judul']); ?></h6>
                                    <small><?php echo htmlspecialchars($item['deskripsi']); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>