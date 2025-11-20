<?php
session_start();
require_once 'config/koneksi.php';

 $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    // Cari user berdasarkan username
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Jika user ditemukan, arahkan ke halaman reset password dengan membawa ID user
        header('Location: reset_password.php?id=' . $user['id']);
        exit;
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SMA Negeri 72 Jakarta</title>
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
            <i class="fas fa-key"></i>
            <h2>Lupa Password</h2>
        </div>
        
        <div class="login-card">
            <div class="card-header">
                <h4>Masukkan Username</h4>
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
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    
                    <button type="submit" class="btn btn-login">Cari Akun</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>