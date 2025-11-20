<?php
require_once 'config/koneksi.php';

// Get latest content from all categories
try {
    $stmt = $pdo->query("SELECT * FROM konten ORDER BY created_at DESC LIMIT 6");
    $latestContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $latestContent = [];
    $errorMessage = "Terjadi kesalahan saat memuat konten: " . $e->getMessage();
}

// Get gallery images
try {
    $stmt = $pdo->query("SELECT * FROM galeri ORDER BY created_at DESC LIMIT 6");
    $galleryImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $galleryImages = [];
    $errorMessage = "Terjadi kesalahan saat memuat galeri: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1e3c72;
            --secondary-blue: #2a5cdb;
            --light-blue: #e3f2fd;
            --accent-blue: #0d47a1;
            --white: #ffffff;
            --gray-light: #f8f9fa;
            --gray-dark: #343a40;
            --text-muted: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--gray-dark);
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            box-shadow: 0 2px 15px rgba(30, 60, 114, 0.15);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 0.5px;
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 0.5rem;
        }

        .nav-link:hover {
            color: #c2e0ff !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #c2e0ff;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: var(--white);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(20px); }
        }

        .hero-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
            position: relative;
            z-index: 1;
        }

        .hero-text {
            flex: 1;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .hero-text .lead {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #c2e0ff;
        }

        .hero-text p {
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .hero-image {
            flex: 0 0 40%;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .btn-light {
            background-color: var(--white);
            color: var(--primary-blue);
            font-weight: 600;
            padding: 12px 35px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-light:hover {
            background-color: #f0f0f0;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            color: var(--secondary-blue);
        }

        .btn-primary {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(42, 92, 219, 0.3);
        }

        /* Main Content */
        .main-content {
            padding: 60px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-blue), var(--primary-blue));
            border-radius: 2px;
        }

        .section-header .lead {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-top: 1.5rem;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(42, 92, 219, 0.15);
        }

        .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.08);
        }

        .card-body {
            padding: 1.5rem;
        }

        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
            color: var(--white);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            letter-spacing: 0.5px;
        }

        .card-title {
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            line-height: 1.4;
        }

        .card-text {
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .content-meta {
            color: var(--text-muted);
            font-size: 0.85rem;
            border-top: 1px solid var(--light-blue);
            padding-top: 1rem;
        }

        .content-meta small {
            display: inline-block;
            margin-right: 1.5rem;
        }

        /* About Section */
        .about-section {
            background: var(--light-blue);
            padding: 60px 0;
        }

        .about-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--secondary-blue);
        }

        /* Full-width About Section styles */
        .about-section-full {
            position: relative;
            padding: 80px 0;
            overflow: hidden;
            background: linear-gradient(90deg, rgba(14,44,99,0.95) 0%, rgba(42,92,219,0.95) 100%);
            color: #fff;
        }

        .about-section-full .about-card {
            background: rgba(255,255,255,0.98);
            color: var(--gray-dark);
            border-left: none;
            border-radius: 12px;
            padding: 2rem;
            max-width: 520px;
        }

        .about-section-full .about-image-container {
            height: 360px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            overflow: hidden;
            position: relative;
        }

        .about-section-full .about-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .about-section-full .about-image:hover {
            transform: scale(1.05);
        }

        .about-section-full .about-bg-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(14,44,99,0.85) 0%, rgba(42,92,219,0.75) 100%);
            pointer-events: none;
            z-index: 0;
        }

        .about-section-full .container-fluid,
        .about-section-full .row,
        .about-section-full .col-md-6 {
            position: relative;
            z-index: 1;
        }

        @media (max-width: 767px) {
            .about-section-full {
                padding: 40px 0;
            }
            .about-section-full .about-image-container {
                display: none;
            }
            .about-section-full .about-card {
                margin: 0 auto;
                max-width: 100%;
            }
        }

        .about-card .card-title {
            color: var(--primary-blue);
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
        }

        .about-card i {
            color: var(--secondary-blue);
        }

        /* Contact Section */
        .contact-section {
            background: var(--gray-light);
            padding: 60px 0;
        }

        .contact-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border-top: 4px solid var(--secondary-blue);
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: var(--light-blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-icon i {
            font-size: 1.5rem;
            color: var(--secondary-blue);
        }

        .contact-info strong {
            color: var(--primary-blue);
            display: block;
            margin-bottom: 0.5rem;
        }

        .contact-info p {
            color: var(--text-muted);
            margin: 0;
            line-height: 1.6;
        }

        /* Gallery Section */
        .gallery-section {
            background: var(--white);
            padding: 60px 0;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            height: 280px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            box-shadow: 0 15px 40px rgba(42, 92, 219, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.15) rotate(1deg);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 60, 114, 0.85), rgba(42, 92, 219, 0.85));
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.4s ease;
            padding: 1.5rem;
            text-align: center;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay h5 {
            color: var(--white);
            font-weight: 700;
            margin-bottom: 0.8rem;
            font-size: 1.2rem;
        }

        .gallery-overlay p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Features Section */
        .features-section {
            background: var(--gray-light);
            padding: 60px 0;
        }

        .feature-box {
            text-align: center;
            padding: 2.5rem 1.5rem;
            background: var(--white);
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-bottom: 3px solid var(--light-blue);
        }

        .feature-box:hover {
            transform: translateY(-10px);
            border-bottom-color: var(--secondary-blue);
            box-shadow: 0 15px 40px rgba(42, 92, 219, 0.15);
        }

        .feature-box i {
            font-size: 3rem;
            color: var(--secondary-blue);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-box:hover i {
            transform: scale(1.1);
            color: var(--primary-blue);
        }

        .feature-box h4 {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .feature-box p {
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            color: var(--white);
            padding: 50px 0 20px;
        }

        footer h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        footer p {
            line-height: 1.8;
            opacity: 0.9;
            margin-bottom: 0.8rem;
        }

        footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 0.5rem;
        }

        footer a:hover {
            color: var(--white);
            padding-left: 5px;
        }

        .social-links a {
            display: inline-block;
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--white);
            color: var(--primary-blue);
            transform: translateY(-5px);
        }

        footer hr {
            background: rgba(255, 255, 255, 0.2);
            margin: 2rem 0;
        }

        footer .text-center {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
            opacity: 0;
        }

        .fade-in:nth-child(1) { animation-delay: 0.1s; }
        .fade-in:nth-child(2) { animation-delay: 0.2s; }
        .fade-in:nth-child(3) { animation-delay: 0.3s; }
        .fade-in:nth-child(4) { animation-delay: 0.4s; }
        .fade-in:nth-child(5) { animation-delay: 0.5s; }
        .fade-in:nth-child(6) { animation-delay: 0.6s; }

        /* Alert Styling */
        .alert-info {
            background-color: var(--light-blue);
            border: 1px solid var(--secondary-blue);
            color: var(--primary-blue);
            border-radius: 8px;
            padding: 1.5rem;
        }

        .alert-danger {
            background-color: #ffebee;
            border: 1px solid #ef5350;
            color: #c62828;
            border-radius: 8px;
            padding: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-text h1 {
                font-size: 2.2rem;
            }

            .hero-text .lead {
                font-size: 1.2rem;
            }

            .hero-inner {
                flex-direction: column;
                gap: 30px;
            }

            .section-header h2 {
                font-size: 1.8rem;
            }

            .feature-box, .about-card, .contact-card {
                padding: 1.8rem;
            }

            footer .row > div {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-school"></i> SMAN 72 JAKARTA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kategori/berita.php">Berita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kategori/artikel.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kategori/kegiatan.php">Kegiatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="galeri/galeri.php">Galeri</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isAdmin() ? 'admin/dashboard.php' : 'redaksi/dashboard.php'; ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-text fade-in">
                    <h1>Selamat Datang di SMAN 72 JAKARTA</h1>
                    <p class="lead">Mencetak Generasi Unggul, Berkarakter, dan Berprestasi</p>
                    <p>SMAN 72 JAKARTA berkomitmen membentuk peserta didik yang cerdas, disiplin, kreatif, dan berakhlak mulia. Dukungan guru profesional dan fasilitas modern siap mendukung prestasi anak didik.</p>
                    <a href="kategori/berita.php" class="btn btn-light btn-lg mt-3">
                        <i class="fas fa-newspaper"></i> Lihat Berita Terkini
                    </a>
                </div>
                <div class="hero-image d-none d-md-block fade-in">
                    <img src="img/lab1.jpg" alt="Sekolah" onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Error Message (if any) -->
            <?php if (isset($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
            <!-- Section Header -->
            <div class="section-header fade-in">
                <h2>Konten Terbaru</h2>
                <p class="lead">Berita, artikel, dan kegiatan terkini dari sekolah kami</p>
            </div>

            <!-- Latest Content -->
            <div class="row">
                <?php if (empty($latestContent)): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Belum ada konten yang tersedia.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($latestContent as $content): ?>
                        <div class="col-md-6 col-lg-4 mb-4 fade-in">
                            <div class="card">
                                <img src="assets/img/<?php echo htmlspecialchars($content['gambar']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($content['judul']); ?>">
                                <div class="card-body">
                                    <span class="category-badge">
                                        <i class="fas fa-tag"></i> <?php echo ucfirst(htmlspecialchars($content['kategori'])); ?>
                                    </span>
                                    <h5 class="card-title"><?php echo htmlspecialchars($content['judul']); ?></h5>
                                    <p class="card-text"><?php echo substr(htmlspecialchars($content['konten']), 0, 100) . '...'; ?></p>
                                    <div class="content-meta">
                                        <small><i class="fas fa-user"></i> <?php echo htmlspecialchars($content['penulis']); ?></small>
                                        <small><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($content['created_at'])); ?></small>
                                    </div>
                                    <a href="kategori/<?php echo htmlspecialchars($content['kategori']); ?>.php?id=<?php echo $content['id']; ?>" class="btn btn-primary btn-sm mt-3">
                                        <i class="fas fa-book-reader"></i> Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <div class="section-header fade-in">
                <h2>Galeri Foto</h2>
                <p class="lead">Dokumentasi kegiatan dan fasilitas sekolah</p>
            </div>

            <div class="row">
                <?php if (empty($galleryImages)): ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Belum ada foto di galeri.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($galleryImages as $image): ?>
                        <div class="col-md-4 mb-4 fade-in">
                            <div class="gallery-item">
                                <img src="assets/img/<?php echo htmlspecialchars($image['gambar']); ?>" alt="<?php echo htmlspecialchars($image['judul']); ?>">
                                <div class="gallery-overlay">
                                    <div>
                                        <h5><?php echo htmlspecialchars($image['judul']); ?></h5>
                                        <p><?php echo htmlspecialchars($image['deskripsi']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5 fade-in">
                <a href="galeri/galeri.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-images"></i> Lihat Semua Galeri
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 fade-in">
                    <div class="feature-box">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>Pendidikan Berkualitas</h4>
                        <p>Kurikulum terkini dengan metode pembelajaran modern dan inovatif</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="feature-box">
                        <i class="fas fa-users"></i>
                        <h4>Guru Profesional</h4>
                        <p>Tim pengajar yang berkualitas dan berpengalaman dalam bidangnya</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in">
                    <div class="feature-box">
                        <i class="fas fa-trophy"></i>
                        <h4>Prestasi Membanggakan</h4>
                        <p>Berbagai prestasi di bidang akademik dan non-akademik</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section - Full Width -->
    <section class="about-section-full">
        <div class="about-bg-overlay"></div>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 ms-5 d-none d-md-block">
                    <!-- Gambar sekolah menggunakan dummy photo URL -->
                    <div class="about-image-container">
                        <img src="https://www.smadwiwarna.sch.id/wp-content/uploads/2023/12/sma-terbaik-di-indonesia-scaled.jpg" alt="Gedung Sekolah SMAN 72 Jakarta" class="about-image">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="p-4 p-md-6">
                        <div class="about-card float-md-end fade-in">
                            <h5 class="card-title">
                                <i class="fas fa-info-circle"></i> Tentang Sekolah
                            </h5>
                            <p class="card-text">SMAN 72 JAKARTA berkomitmen untuk memberikan pendidikan berkualitas tinggi dengan fokus pada pengembangan kompetensi akademik dan pembentukan karakter siswa melalui program unggulan dan kegiatan ekstrakurikuler yang beragam.</p>
                            <a href="#" class="btn btn-primary btn-sm">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>SMAN 72 JAKARTA</h5>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Pendidikan No. 1, Jakarta Pusat</p>
                    <p><i class="fas fa-phone"></i> (021) 1234567</p>
                    <p><i class="fas fa-envelope"></i> info@sman72jakarta.sch.id</p>
                </div>
                <div class="col-md-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="kategori/berita.php"><i class="fas fa-newspaper"></i> Berita</a></li>
                        <li><a href="kategori/artikel.php"><i class="fas fa-file-alt"></i> Artikel</a></li>
                        <li><a href="kategori/kegiatan.php"><i class="fas fa-calendar-alt"></i> Kegiatan</a></li>
                        <li><a href="galeri/galeri.php"><i class="fas fa-images"></i> Galeri</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Ikuti Kami</h5>
                    <!-- Mengganti div.social-links dengan div.social-info untuk menampilkan informasi lebih detail -->
                    <div class="social-info">
                        <a href="https://www.facebook.com/sman72jakarta" target="_blank" class="social-item" title="Kunjungi Facebook kami">
                            <i class="fab fa-facebook"></i>
                            <span>SMAN 72 Jakarta</span>
                        </a>
                        <a href="https://www.twitter.com/sman72jkt" target="_blank" class="social-item" title="Kunjungi Twitter kami">
                            <i class="fab fa-twitter"></i>
                            <span>@sman72jkt</span>
                        </a>
                        <a href="https://www.instagram.com/sman72jakarta" target="_blank" class="social-item" title="Kunjungi Instagram kami">
                            <i class="fab fa-instagram"></i>
                            <span>@sman72jakarta</span>
                        </a>
                        <a href="https://www.youtube.com/@sman72jakarta" target="_blank" class="social-item" title="Kunjungi YouTube kami">
                            <i class="fab fa-youtube"></i>
                            <span>SMAN 72 Jakarta</span>
                        </a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> SMAN 72 JAKARTA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.fade-in').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>