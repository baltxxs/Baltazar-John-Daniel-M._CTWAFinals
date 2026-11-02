<?php
require_once __DIR__ . '/config/functions.php';
require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$identity = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $identity = trim($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($identity === '' || $password === '') {
        $error = 'Please enter your username/email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, username, email, password FROM users WHERE username = :identity OR email = :identity LIMIT 1');
        $stmt->execute(['identity' => $identity]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Incorrect username/email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="auth-card">
    <div class="brand-mark">W</div>
    <p class="eyebrow">WELCOME BACK</p>
    <h1>Sign in to your account</h1>
    <p class="subtitle">Enter your details to continue.</p>

    <?php if ($error): ?>
        <div class="alert error" role="alert"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="identity">Username or email</label>
        <input id="identity" name="identity" type="text" value="<?= e($identity) ?>" placeholder="Enter username or email" autocomplete="username" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required>

        <button type="submit">Sign in</button>
    </form>
    <p class="switch-link">Don't have an account? <a href="register.php">Create one</a></p>
</main>
</body>
</html>
