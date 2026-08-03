<?php
require_once '../config.php';

$error = '';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan Password wajib diisi.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];
            header("Location: index.php");
            exit;
        } else {
            $error = 'Username atau Password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator | Little Blossom Baby Spa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-body">

    <div class="login-card">
        <div style="font-size: 3rem; color: var(--admin-primary); margin-bottom: 10px;">
            <i class="fa-solid fa-baby"></i>
        </div>
        <h2 class="login-title">Admin Login</h2>
        <p class="login-desc">Masuk untuk mengelola reservasi & layanan baby spa</p>

        <?php if (!empty($error)): ?>
            <div style="background: #FEE2E2; color: #991B1B; padding: 10px 15px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px;">
                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" style="text-align: left;">
            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Username</label>
                <input type="text" name="username" class="admin-input" style="width: 100%; padding: 12px 16px; border: 1px solid var(--admin-border); border-radius: 8px;" placeholder="admin" required value="admin">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Password</label>
                <input type="password" name="password" class="admin-input" style="width: 100%; padding: 12px 16px; border: 1px solid var(--admin-border); border-radius: 8px;" placeholder="••••••••" required value="admin123">
            </div>

            <button type="submit" style="width: 100%; padding: 14px; background: var(--admin-primary); color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk Sekarang
            </button>
        </form>

        <div style="margin-top: 25px; padding-top: 15px; border-top: 1px solid var(--admin-border); font-size: 0.8rem; color: var(--admin-muted);">
            Default Login: <strong>admin</strong> / <strong>admin123</strong>
        </div>
    </div>

</body>
</html>
