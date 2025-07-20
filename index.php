<?php
session_start();
require_once __DIR__ . '/includes/db.php'; 

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // HARUS 1 hasil
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($pass, $user['password'])) {
            $_SESSION['user'] = $email;
            header("Location: ./pages/cari.php");
            exit();
        }
    }

    $error = "Login gagal. Cek email/password.";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Bengkel Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
<div class="form-wrapper">
    <div class="login-box-kip">
        <h2 class="form-title">Login Bengkel Online</h2>
        <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>
        <form method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="user@example.com" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Masukkan password" required>

            <button type="submit" name="login">Login</button>
            <p class="note">Belum punya akun? <a href="pages/daftar.php">Daftar di sini</a></p>
        </form>
    </div>
</div>
</body>
</html>
