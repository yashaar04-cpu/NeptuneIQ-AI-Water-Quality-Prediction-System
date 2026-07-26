<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($action === 'register') {
        $hashed_pw = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:u, :p)");
            $stmt->execute([':u' => $username, ':p' => $hashed_pw]);
            $message = "Registration successful! You can now log in.";
        } catch (Exception $e) {
            $message = "Username already taken.";
        }
    } elseif ($action === 'login') {
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :u");
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Water Analyzer</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 320px; }
        input { width: 100%; padding: 8px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; margin-top: 5px; }
        button.alt { background: #7f8c8d; }
        .msg { color: #e74c3c; font-size: 0.9rem; margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="card">
    <h2>Water Analyzer Login</h2>
    <?php if ($message): ?><p class="msg"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit" name="action" value="login">Log In</button><a href="register.php">Create a new account
</a>
    </form>
</div>
</body>
</html>