<?php
$usersFile = __DIR__ . '/users.json';
$users = [];

if (file_exists($usersFile)) {
    $json = file_get_contents($usersFile);
    $users = json_decode($json, true) ?? [];
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    foreach ($users as $user) {
        if ($user['username'] === $username && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        }
    }

    $errorMessage = 'Invalid username or password.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - ZouTube</title>
    <link rel="stylesheet" href="/ZouTube/styles/auth.css">
</head>
<body>
    <div class="container" style="align-items: center; justify-content: center; padding: 60px 0;">
        <div class="video-table" style="width: 100%; max-width: 400px;">
            <h2 style="margin-bottom: 16px;">Login as a creator</h2>
            <?php if ($errorMessage): ?>
                <p style="color: red;"><?= $errorMessage ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" style="width: 100%; padding: 8px; margin-bottom: 12px;" required>
                <input type="password" name="password" placeholder="Password" style="width: 100%; padding: 8px; margin-bottom: 12px;" required>
                <button class="visibility-toggle" type="submit" style="width: 100%;">Login</button>
            </form>
            <p style="margin-top: 12px;">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>
