<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
        if (mysqli_query($conn, $query)) {
          echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='../index.php';</script>";
        } else {
            $error = "Gagal daftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun</title>
    <link href="https://fonits.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/daftar.css">
</head>
<body>
<div class="form-container">
    <h2>Daftar Akun</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Daftar</button>
        <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </form>
</div>
</body>
</html>
