<?php
$usersFile = __DIR__ . '/users.json';
$users = [];

if (file_exists($usersFile)) {
    $json = file_get_contents($usersFile);
    $users = json_decode($json, true) ?? [];
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if ($username === '' || $password === '' || $confirmPassword === '') {
        $errorMessage = 'Please fill in all fields.';
    } elseif ($password !== $confirmPassword) {
        $errorMessage = 'Passwords do not match.';
    } else {
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $errorMessage = 'Username already exists.';
                break;
            }
        }

        if (!$errorMessage) {
            $users[] = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];
            file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
            header('Location: login.php');
            exit;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - ZouTube</title>
    <link rel="stylesheet" href="/ZouTube/styles/auth.css">
</head>
<body>
    <div class="container" style="align-items: center; justify-content: center; padding: 60px 0;">
        <div class="video-table" style="width: 100%; max-width: 400px;">
            <h2 style="margin-bottom: 16px;">Become a Creator on ZouTube</h2>
            <?php if ($errorMessage): ?>
                <p style="color: red;"><?= $errorMessage ?></p>
            <?php endif; ?>
            <?php if ($successMessage): ?>
                <p style="color: green;"><?= $successMessage ?></p>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" style="width: 100%; padding: 8px; margin-bottom: 12px;" required>
                <input type="password" name="password" placeholder="Password" style="width: 100%; padding: 8px; margin-bottom: 12px;" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" style="width: 100%; padding: 8px; margin-bottom: 12px;" required>
                <button class="visibility-toggle" type="submit" style="width: 100%;">Register</button>
            </form>

            <p style="margin-top: 12px;">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
