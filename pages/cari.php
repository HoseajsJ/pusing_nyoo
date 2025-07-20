<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cari Bengkel</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/cari.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <button class="menu-toggle" onclick="toggleMenu()">&#8942;</button>
  <div class="slide-menu" id="slideMenu">
    <a href="cari.php">🏠 Home</a>
    <a href="transaksi.php">📋 Transaksi</a>
    <a href="../logout.php">🚪 Logout</a>
  </div>
  <div class="overlay" onclick="toggleMenu()" id="overlay"></div>

  <header class="header">
    <h1>🔧 Cari Bengkel</h1>
    <p>Temukan bengkel terpercaya untuk kendaraanmu</p>
  </header>

  <div class="search-wrapper">
    <form method="GET">
      <input type="text" name="keyword" placeholder="Cari layanan..." class="search-input">
      <select name="kategori" class="search-select">
        <option value="">Semua Kategori</option>
        <option value="Mobil">Mobil</option>
        <option value="Motor">Motor</option>
        <option value="Mobil & Motor">Mobil & Motor</option>
      </select>
      <button type="submit" class="search-btn">Cari</button>
    </form>
  </div>

  <div class="grid-container">
    <!-- CARD MANUAL 1 -->
    <div class="card">
      <img src="../assets/img/bengkel1.jpg" alt="Bengkel Jaya Motor" class="bengkel-img">
      <button class="btn-favorite"><i class="far fa-heart"></i></button>
      <h3>Bengkel Jaya Motor</h3>
      <p>📍 Jl. Merdeka No.12, Jakarta</p>
      <a href="booking.php?id=1" class="btn-book">💳 Booking</a>
    </div>

    <!-- CARD MANUAL 2 -->
    <div class="card">
      <img src="../assets/img/bengkel2.jpg" alt="Bengkel Andalan" class="bengkel-img">
      <button class="btn-favorite"><i class="far fa-heart"></i></button>
      <h3>Bengkel Andalan</h3>
      <p>📍 Jl. Soekarno-Hatta No.88, Bandung</p>
      <a href="booking.php?id=2" class="btn-book">💳 Booking</a>
    </div>

    <!-- CARD MANUAL 3 -->
    <div class="card">
      <img src="../assets/img/bengkel3.jpg" alt="Auto Service Center" class="bengkel-img">
      <button class="btn-favorite"><i class="far fa-heart"></i></button>
      <h3>Auto Service Center</h3>
      <p>📍 Jl. Gatot Subroto No.45, Surabaya</p>
      <a href="booking.php?id=3" class="btn-book">💳 Booking</a>
    </div>
  </div>

  <script src="../assets/js/cari.js"></script>
</body>
</html>
