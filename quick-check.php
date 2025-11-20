<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Check - Image Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .status-ok { color: #28a745; }
        .status-warning { color: #ffc107; }
        .status-error { color: #dc3545; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Quick Check - Setup Gambar web-sma</h1>
        
        <?php
        $checks = [];
        
        // 1. Cek koneksi database
        require_once 'config/koneksi.php';
        try {
            $stmt = $pdo->query("SELECT 1");
            $checks['Database Connection'] = ['status' => 'ok', 'message' => 'Database berhasil terhubung'];
        } catch (Exception $e) {
            $checks['Database Connection'] = ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
        
        // 2. Cek tabel konten
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM konten");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $result['total'] ?? 0;
            $checks['Tabel konten'] = ['status' => 'ok', 'message' => "Total data: $total baris"];
        } catch (Exception $e) {
            $checks['Tabel konten'] = ['status' => 'error', 'message' => $e->getMessage()];
        }
        
        // 3. Cek tabel galeri
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM galeri");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $result['total'] ?? 0;
            $checks['Tabel galeri'] = ['status' => 'ok', 'message' => "Total data: $total baris"];
        } catch (Exception $e) {
            $checks['Tabel galeri'] = ['status' => 'error', 'message' => $e->getMessage()];
        }
        
        // 4. Cek folder assets/img
        $imgDir = __DIR__ . '/assets/img/';
        if (is_dir($imgDir)) {
            $count = count(array_diff(scandir($imgDir), array('..', '.')));
            $writable = is_writable($imgDir) ? 'writable' : 'read-only';
            $checks['Folder assets/img'] = ['status' => 'ok', 'message' => "Ditemukan, $count file, $writable"];
        } else {
            $checks['Folder assets/img'] = ['status' => 'warning', 'message' => 'Folder tidak ditemukan (akan dibuat saat upload)'];
        }
        
        // 5. Cek file gambar di database vs file fisik
        try {
            $stmt = $pdo->query("SELECT DISTINCT gambar FROM konten WHERE gambar IS NOT NULL AND gambar != '' UNION SELECT DISTINCT gambar FROM galeri WHERE gambar IS NOT NULL AND gambar != ''");
            $allImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            $missing = [];
            $exists = [];
            
            foreach ($allImages as $img) {
                if (file_exists($imgDir . basename($img))) {
                    $exists[] = $img;
                } else {
                    $missing[] = $img;
                }
            }
            
            if (empty($missing)) {
                $checks['Gambar di Database'] = ['status' => 'ok', 'message' => count($exists) . " gambar ditemukan semuanya"];
            } else {
                $status = count($missing) < count($exists) ? 'warning' : 'error';
                $checks['Gambar di Database'] = ['status' => $status, 'message' => count($exists) . " ada, " . count($missing) . " hilang"];
            }
        } catch (Exception $e) {
            $checks['Gambar di Database'] = ['status' => 'error', 'message' => $e->getMessage()];
        }
        
        // 6. Cek config/image-helper.php
        if (file_exists(__DIR__ . '/config/image-helper.php')) {
            $checks['Helper Functions'] = ['status' => 'ok', 'message' => 'config/image-helper.php tersedia'];
        } else {
            $checks['Helper Functions'] = ['status' => 'warning', 'message' => 'config/image-helper.php tidak ditemukan'];
        }
        
        // 7. Cek generate-placeholders.php
        if (file_exists(__DIR__ . '/generate-placeholders.php')) {
            $checks['Placeholder Generator'] = ['status' => 'ok', 'message' => 'generate-placeholders.php tersedia'];
        } else {
            $checks['Placeholder Generator'] = ['status' => 'warning', 'message' => 'generate-placeholders.php tidak ditemukan'];
        }
        
        // Render checks
        foreach ($checks as $name => $check) {
            $statusClass = 'status-' . $check['status'];
            echo "<div class='row mb-3'>";
            echo "<div class='col-md-6'>";
            echo "<strong>$name</strong>";
            echo "</div>";
            echo "<div class='col-md-6'>";
            echo "<span class='$statusClass'>";
            echo strtoupper($check['status']) . ": " . $check['message'];
            echo "</span>";
            echo "</div>";
            echo "</div>";
        }
        ?>
        
        <hr class="my-4">
        
        <h3>Rekomendasi Next Steps</h3>
        <ul>
            <?php if (!is_dir($imgDir)): ?>
                <li>Folder <code>assets/img/</code> belum ada. Buat terlebih dahulu atau jalankan upload untuk auto-create.</li>
            <?php endif; ?>
            
            <?php if (!empty($missing) && !empty($missing)): ?>
                <li>Ada <strong><?php echo count($missing); ?> gambar hilang</strong> di database:
                    <pre><?php echo implode("\n", $missing); ?></pre>
                    Jalankan script <code>generate-placeholders.php</code> untuk membuat placeholder.
                </li>
            <?php endif; ?>
            
            <li>Untuk testing, jalankan: <a href="generate-placeholders.php" class="btn btn-sm btn-primary">Generate Placeholder Images</a></li>
            <li>Buka dashboard: <a href="admin/dashboard.php" class="btn btn-sm btn-success">Admin Dashboard</a></li>
            <li>Lihat galeri: <a href="galeri/galeri.php" class="btn btn-sm btn-info">Galeri</a></li>
        </ul>
        
        <hr class="my-4">
        
        <h3>Info Sistem</h3>
        <table class="table table-sm">
            <tr><td><strong>PHP Version:</strong></td><td><?php echo phpversion(); ?></td></tr>
            <tr><td><strong>Web Root:</strong></td><td><?php echo __DIR__; ?></td></tr>
            <tr><td><strong>Image Directory:</strong></td><td><?php echo $imgDir; ?></td></tr>
            <tr><td><strong>GD Library:</strong></td><td><?php echo extension_loaded('gd') ? 'Available ✓' : 'Not Available ✗'; ?></td></tr>
        </table>
    </div>
</body>
</html>
