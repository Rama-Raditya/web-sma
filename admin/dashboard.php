<?php
require_once '../config/koneksi.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get statistics
 $stmt = $pdo->query("SELECT COUNT(*) as total FROM konten WHERE kategori = 'berita'");
 $beritaCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

 $stmt = $pdo->query("SELECT COUNT(*) as total FROM konten WHERE kategori = 'artikel'");
 $artikelCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

 $stmt = $pdo->query("SELECT COUNT(*) as total FROM konten WHERE kategori = 'kegiatan'");
 $kegiatanCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

 $stmt = $pdo->query("SELECT COUNT(*) as total FROM galeri");
 $galeriCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get recent content
 $stmt = $pdo->query("SELECT * FROM konten ORDER BY created_at DESC LIMIT 5");
 $recentContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }
        
        .sidebar-header h3 {
            color: white;
            font-size: 1.25rem;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .sidebar-menu a i {
            width: 25px;
            text-align: center;
            margin-right: 10px;
        }
        
        .sidebar-submenu {
            list-style: none;
            padding-left: 45px;
            display: none;
        }
        
        .sidebar-submenu.show {
            display: block;
        }
        
        .sidebar-submenu a {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .top-navbar {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            border: none;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: none;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
        
        .btn-toggle {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            width: 100%;
            text-align: left;
        }
        
        .btn-toggle:hover, .btn-toggle.active {
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        
        .mobile-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background-color: #343a40;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-school me-2"></i> SMA Negeri 72</h3>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <button class="btn-toggle" id="contentToggle">
                    <i class="fas fa-file-alt"></i> Konten <i class="fas fa-chevron-down ms-auto"></i>
                </button>
                <ul class="sidebar-submenu" id="contentSubmenu">
                    <li><a href="tambah_artikel.php"><i class="fas fa-plus"></i> Tambah Artikel</a></li>
                    <li><a href="../kategori/berita.php"><i class="fas fa-newspaper"></i> Kelola Berita</a></li>
                    <li><a href="../kategori/artikel.php"><i class="fas fa-file-alt"></i> Kelola Artikel</a></li>
                    <li><a href="../kategori/kegiatan.php"><i class="fas fa-calendar-alt"></i> Kelola Kegiatan</a></li>
                    <li><a href="kelola_galeri.php"><i class="fas fa-images"></i> Kelola Galeri</a></li>
                </ul>
            </li>
            <li>
                <a href="../index.php" target="_blank">
                    <i class="fas fa-eye"></i> Lihat Website
                </a>
            </li>
            <li>
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <h4 class="mb-0">Dashboard Admin</h4>
            <div class="user-info">
                <span>Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                <div class="user-avatar ms-2">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                </div>
            </div>
        </div>
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Berita</h6>
                                <h3 class="text-primary mb-0"><?php echo $beritaCount; ?></h3>
                            </div>
                            <i class="fas fa-newspaper fa-2x text-primary opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Artikel</h6>
                                <h3 class="text-success mb-0"><?php echo $artikelCount; ?></h3>
                            </div>
                            <i class="fas fa-file-alt fa-2x text-success opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Kegiatan</h6>
                                <h3 class="text-warning mb-0"><?php echo $kegiatanCount; ?></h3>
                            </div>
                            <i class="fas fa-calendar-alt fa-2x text-warning opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Galeri</h6>
                                <h3 class="text-info mb-0"><?php echo $galeriCount; ?></h3>
                            </div>
                            <i class="fas fa-images fa-2x text-info opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="tambah_artikel.php?cat=berita" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i> Tambah Berita
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="tambah_artikel.php?cat=artikel" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i> Tambah Artikel
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="tambah_artikel.php?cat=kegiatan" class="btn btn-warning w-100">
                                    <i class="fas fa-plus me-2"></i> Tambah Kegiatan
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="kelola_galeri.php" class="btn btn-info w-100">
                                    <i class="fas fa-images me-2"></i> Kelola Galeri
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
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Gambar</th>
                                        <th>Kategori</th>
                                        <th>Penulis</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentContent as $content): ?>
                                        <?php
                                            // Tentukan field gambar yang mungkin digunakan di DB
                                            $imgHtml = '<span class="text-muted text-center d-block">Tidak ada gambar</span>';
                                            $imgField = null;
                                            foreach (['gambar','image','foto','thumbnail','img','gambar_path'] as $f) {
                                                if (!empty($content[$f])) { $imgField = $f; break; }
                                            }
                                            if ($imgField) {
                                                $src = $content[$imgField];
                                                // Path default ke assets/img (tempat upload file)
                                                $webSrc = '../assets/img/' . basename($src);
                                                
                                                // Buat tag img dengan fallback
                                                $imgHtml = '
                                                <img src="'.htmlspecialchars($webSrc).'" 
                                                     alt="thumbnail" 
                                                     style="max-width:100px; max-height:70px; object-fit:cover; border-radius:4px; display:block; margin:0 auto;"
                                                     onerror="this.src=\'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%2270%22%3E%3Crect fill=%22%23ccc%22 width=%22100%22 height=%2270%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 fill=%22%23999%22 text-anchor=%22middle%22 dy=%22.3em%22%3ENo Image%3C/text%3E%3C/svg%3E\'">
                                                ';
                                            }
                                        ?>
                                        <tr>
                                            <td style="vertical-align:middle"><?php echo htmlspecialchars($content['judul']); ?></td>
                                            <td style="vertical-align:middle"><?php echo $imgHtml; ?></td>
                                            <td style="vertical-align:middle"><span class="badge bg-primary"><?php echo ucfirst($content['kategori']); ?></span></td>
                                            <td style="vertical-align:middle"><?php echo htmlspecialchars($content['penulis']); ?></td>
                                            <td style="vertical-align:middle"><?php echo date('d M Y', strtotime($content['created_at'])); ?></td>
                                            <td style="vertical-align:middle">
                                                <a href="edit_artikel.php?id=<?php echo $content['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="hapus_artikel.php?id=<?php echo $content['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin?')">
                                                    <i class="fas fa-trash"></i> Hapus
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar on mobile
            const mobileToggle = document.getElementById('mobileToggle');
            const sidebar = document.getElementById('sidebar');
            
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
            
            // Toggle content submenu
            const contentToggle = document.getElementById('contentToggle');
            const contentSubmenu = document.getElementById('contentSubmenu');
            
            contentToggle.addEventListener('click', function() {
                contentSubmenu.classList.toggle('show');
                const icon = this.querySelector('.fa-chevron-down');
                if (contentSubmenu.classList.contains('show')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
            
            // Show content submenu by default if on a content page
            const currentPath = window.location.pathname;
            if (currentPath.includes('tambah_artikel.php') || 
                currentPath.includes('edit_artikel.php') || 
                currentPath.includes('hapus_artikel.php') || 
                currentPath.includes('kelola_galeri.php')) {
                contentSubmenu.classList.add('show');
                const icon = contentToggle.querySelector('.fa-chevron-down');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        });
    </script>
</body>
</html>