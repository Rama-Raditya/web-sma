<?php
require_once '../config/koneksi.php';

$contentId = isset($_GET['id']) ? $_GET['id'] : null;

if ($contentId) {
    // Show single content
    $stmt = $pdo->prepare("SELECT * FROM konten WHERE id = ? AND kategori = 'artikel'");
    $stmt->execute([$contentId]);
    $content = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$content) {
        redirect('artikel.php');
    }
    
    // Get related content
    $stmt = $pdo->prepare("SELECT * FROM konten WHERE kategori = 'artikel' AND id != ? ORDER BY created_at DESC LIMIT 3");
    $stmt->execute([$contentId]);
    $relatedContent = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Show content list
    $stmt = $pdo->query("SELECT * FROM konten WHERE kategori = 'artikel' ORDER BY created_at DESC");
    $contentList = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel - SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/footer.css" rel="stylesheet">
</head>
<body>
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
                        <a class="nav-link active" href="artikel.php">Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kegiatan.php">Kegiatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../galeri/galeri.php">Galeri</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isAdmin() ? '../admin/dashboard.php' : '../redaksi/dashboard.php'; ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <?php if ($contentId && $content): ?>
            <!-- Single Content View -->
            <div class="row">
                <div class="col-lg-8">
                    <article>
                        <img src="../assets/img/<?php echo htmlspecialchars($content['gambar']); ?>" class="img-fluid rounded mb-4" alt="<?php echo htmlspecialchars($content['judul']); ?>">
                        <h1 class="display-6 mb-3"><?php echo htmlspecialchars($content['judul']); ?></h1>
                        <div class="content-meta mb-4">
                            <span class="badge bg-success"><?php echo ucfirst($content['kategori']); ?></span>
                            <span class="ms-3"><i class="fas fa-user"></i> <?php echo htmlspecialchars($content['penulis']); ?></span>
                            <span class="ms-3"><i class="fas fa-calendar"></i> <?php echo date('d F Y', strtotime($content['created_at'])); ?></span>
                        </div>
                        <div class="content-body">
                            <?php echo nl2br(htmlspecialchars($content['konten'])); ?>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Artikel Terkait</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($relatedContent as $related): ?>
                                <div class="mb-3">
                                    <h6><a href="artikel.php?id=<?php echo $related['id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($related['judul']); ?></a></h6>
                                    <small class="text-muted"><?php echo date('d M Y', strtotime($related['created_at'])); ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Content List View -->
            <div class="row">
                <div class="col-12">
                    <h1 class="display-6 mb-4">Artikel</h1>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="row">
                <?php foreach ($contentList as $content): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="../assets/img/<?php echo htmlspecialchars($content['gambar']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($content['judul']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($content['judul']); ?></h5>
                                <p class="card-text"><?php echo substr(htmlspecialchars($content['konten']), 0, 150) . '...'; ?></p>
                                <div class="content-meta">
                                    <small><i class="fas fa-user"></i> <?php echo htmlspecialchars($content['penulis']); ?></small>
                                    <small class="ms-3"><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($content['created_at'])); ?></small>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="artikel.php?id=<?php echo $content['id']; ?>" class="btn btn-success">Baca Selengkapnya</a>
                                <?php if (isLoggedIn()): ?>
                                    <a href="<?php echo isAdmin() ? '../admin/edit_artikel.php?id=' . $content['id'] : '../redaksi/edit_artikel.php?id=' . $content['id']; ?>" class="btn btn-outline-success">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>