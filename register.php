<?php
session_start();
require 'db.php';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = "Please enter username and password.";
    } else {
        $hashed_pw = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $db->prepare(
                "INSERT INTO users (username, password) 
                 VALUES (:u, :p)"
            );
            $stmt->execute([
                ':u' => $username,
                ':p' => $hashed_pw
            ]);
            $message = "Registration successful! You can now login.";
        } catch (Exception $e) {
    $message = $e->getMessage();}
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - NeptuneIQ</title>
<style>

body {
    font-family: Arial, sans-serif;
    background: #ADD8E6;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.card {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 320px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}


input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 10px;
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #219150;
}

a {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #3498db;
    text-decoration: none;
}

.message {
    color: #e74c3c;
    text-align: center;
}
</style>
</head>

<body>
<div class="card">
<h2>Create Account</h2>

<?php if ($message): ?>
<p class="message">
<?= htmlspecialchars($message) ?>
</p>

<?php endif; ?>

<form method="POST">
<label>Username</label>

<input type="text" name="username" required>

<label>Password</label>
<input type="password" name="password" required>
<button type="submit">Register</button>
</form>
<a href="login.php">
Already have an account? Login
</a>

</div>

</body>
</html>