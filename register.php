<?php
require_once __DIR__ . '/config/functions.php';
require_once __DIR__ . '/config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$name = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'Please enter your name (up to 100 characters).';
    }
    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
        $errors[] = 'Username must be 3–30 characters and contain only letters, numbers, or underscores.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $check = $pdo->prepare('SELECT id FROM users WHERE username = :username OR email = :email LIMIT 1');
        $check->execute(['username' => $username, 'email' => $email]);

        if ($check->fetch()) {
            $errors[] = 'That username or email is already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare(
                'INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)'
            );
            $insert->execute([
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'password' => $hash
            ]);

            $_SESSION['user_id'] = (int)$pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            session_regenerate_id(true);
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="auth-card">
    <div class="brand-mark">W</div>
    <p class="eyebrow">GET STARTED</p>
    <h1>Create your account</h1>
    <p class="subtitle">Sign up to get started.</p>

    <?php if ($errors): ?>
        <div class="alert error" role="alert">
            <?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="name">Full name</label>
        <input id="name" name="name" type="text" maxlength="100" value="<?= e($name) ?>" placeholder="Your full name" required>

        <label for="username">Username</label>
        <input id="username" name="username" type="text" minlength="3" maxlength="30" value="<?= e($username) ?>" placeholder="Choose a username" required>

        <label for="email">Email address</label>
        <input id="email" name="email" type="email" maxlength="190" value="<?= e($email) ?>" placeholder="you@example.com" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" minlength="8" placeholder="At least 8 characters" required>

        <label for="confirm_password">Confirm password</label>
        <input id="confirm_password" name="confirm_password" type="password" minlength="8" placeholder="Enter your password again" required>

        <button type="submit">Create account</button>
    </form>
    <p class="switch-link">Already have an account? <a href="login.php">Sign in</a></p>
</main>
</body>
</html>
