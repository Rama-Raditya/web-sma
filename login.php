<?php
// ... (Kode PHP untuk proses login tetap sama) ...
require_once 'config/koneksi.php';

if (isLoggedIn()) {
    redirect(isAdmin() ? 'admin/dashboard.php' : 'redaksi/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        redirect($user['role'] === 'admin' ? 'admin/dashboard.php' : 'redaksi/dashboard.php');
    } else {
        $error = "Username atau password salah!";
    }
}

// Tampilkan notifikasi sukses jika ada dari halaman reset
if (isset($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMA Negeri 72 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ... (Gunakan CSS yang sama dari jawaban sebelumnya) ... */
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .brand-logo i {
            font-size: 4rem;
            color: var(--primary-color);
        }
        
        .brand-logo h2 {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .brand-logo p {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-header {
            background: var(--gradient-bg);
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 500;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark-color);
        }
        
        .form-control {
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background-color: white;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 42px;
            color: #6c757d;
        }
        
        .btn-login {
            background: var(--gradient-bg);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .alert {
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: none;
        }
        
        .alert-danger {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--accent-color);
        }
        
        .alert-success {
            background-color: rgba(6, 255, 165, 0.1);
            color: #00a854;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 0.85rem;
        }
        
        .forgot-password-link {
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        
        .forgot-password-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        
        .forgot-password-link a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">
            <i class="fas fa-school"></i>
            <h2>SMA Negeri 72 Jakarta</h2>
            <p>Portal Administrasi</p>
        </div>
        
        <div class="login-card">
            <div class="card-header">
                <h4>Login Admin</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><?php echo $error; ?></div>
                    </div>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div><?php echo $success_message; ?></div>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                    
                    <button type="submit" class="btn btn-login">Login</button>
                </form>
                
                <div class="forgot-password-link">
                    <a href="lupa_password.php">Lupa Password?</a>
                </div>
            </div>
        </div>
        
        <div class="footer-text">
            <p>&copy; <?php echo date('Y'); ?> SMA Negeri 72 Jakarta. All rights reserved.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>