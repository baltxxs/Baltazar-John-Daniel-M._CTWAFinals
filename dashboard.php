<?php
require_once __DIR__ . '/config/functions.php';
require_once __DIR__ . '/config/db.php';
require_login();

$stmt = $pdo->prepare('SELECT name, username, email, created_at FROM users WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="auth-card dashboard">
    <div class="brand-mark">W</div>
    <p class="eyebrow">YOUR ACCOUNT</p>
    <h1>Welcome, <?= e($user['name']) ?>!</h1>
    <p class="subtitle">You have successfully signed in.</p>
    <div class="profile-details">
        <p><strong>Name</strong><span><?= e($user['name']) ?></span></p>
        <p><strong>Username</strong><span><?= e($user['username']) ?></span></p>
        <p><strong>Email</strong><span><?= e($user['email']) ?></span></p>
        <p><strong>Member since</strong><span><?= e($user['created_at']) ?></span></p>
    </div>
    <form method="POST" action="logout.php">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <button class="secondary" type="submit">Sign out</button>
    </form>
</main>
</body>
</html>
