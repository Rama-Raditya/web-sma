<?php
session_start();
require_once 'config/koneksi.php';

 $error = '';
 $success = '';

// Ambil ID user dari URL
 $user_id = isset($_GET['id']) ? $_GET['id'] : '';

// Validasi: pastikan ID ada dan user tersebut benar-benar ada di database
if (empty($user_id)) {
    die('Akses tidak valid: ID pengguna tidak disediakan.');
}

 $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
 $stmt->execute([$user_id]);
 $user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Akses tidak valid: Pengguna tidak ditemukan.');
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_baru = $_POST['password_baru'];
    $password_konfirmasi = $_POST['password_konfirmasi'];

    // Validasi password
    if (empty($password_baru) || empty($password_konfirmasi)) {
        $error = "Semua field harus diisi.";
    } elseif ($password_baru !== $password_konfirmasi) {
        $error = "Password baru dan konfirmasi tidak cocok.";
    } elseif (strlen($password_baru) < 6) { // Contoh validasi panjang password
        $error = "Password minimal 6 karakter.";
    } else {
        // Hash password baru
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);

        // Update password di database
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$hashed_password, $user_id])) {
            // Set pesan sukses dan redirect ke halaman login
            $_SESSION['success'] = "Password untuk username '<strong>" . htmlspecialchars($user['username']) . "</strong>' berhasil diubah. Silakan login.";
            header('Location: login.php');
            exit;
        } else {
            $error = "Terjadi kesalahan, gagal mengubah password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Gunakan CSS yang sama dengan login.php untuk konsistensi */
        body { background-color: #f5f7fb; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .login-container { width: 100%; max-width: 420px; padding: 20px; }
        .brand-logo { text-align: center; margin-bottom: 30px; }
        .brand-logo i { font-size: 4rem; color: #4361ee; }
        .brand-logo h2 { font-weight: 600; color: #212529; }
        .login-card { background: white; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; text-align: center; border-radius: 12px 12px 0 0;}
        .card-body { padding: 30px; }
        .form-group { margin-bottom: 20px; position: relative; }
        .form-group label { font-weight: 500; margin-bottom: 8px; color: #212529; }
        .form-control { border: 1px solid #e0e6ed; border-radius: 8px; padding: 12px 15px; background-color: #f8fafc; }
        .form-control:focus { border-color: #4361ee; box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1); }
        .input-icon { position: absolute; right: 15px; top: 42px; color: #6c757d; }
        .btn-login { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px; padding: 12px; font-weight: 500; color: white; width: 100%; transition: all 0.3s ease; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3); }
        .alert-danger { background-color: rgba(247, 37, 133, 0.1); color: #f72585; border-radius: 8px; padding: 12px 15px; margin-bottom: 20px; border: none; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">
            <i class="fas fa-sync-alt"></i>
            <h2>Reset Password</h2>
            <p>Username: <strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
        </div>
        
        <div class="login-card">
            <div class="card-header">
                <h4>Masukkan Password Baru</h4>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><?php echo $error; ?></div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="password_baru">Password Baru</label>
                        <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="password_konfirmasi">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    
                    <button type="submit" class="btn btn-login">Ubah Password</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>