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

// ✅ Ambil semua bengkel dari database
$sql = "SELECT workshop_id, name, address, image FROM bengkel";
$result = $conn->query($sql);
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
  <!-- MENU SLIDE -->
  <button class="menu-toggle" onclick="toggleMenu()">&#8942;</button>
  <div class="slide-menu" id="slideMenu">
    <a href="cari.php">🏠 Home</a>
    <a href="transaksi.php">📋 Transaksi</a>
    <a href="../logout.php">🚪 Logout</a>
  </div>
  <div class="overlay" onclick="toggleMenu()" id="overlay"></div>

  <!-- HEADER -->
  <header class="header">
    <h1>🔧 Cari Bengkel</h1>
    <p>Temukan bengkel terpercaya untuk kendaraanmu</p>
  </header>

  <!-- SEARCH -->
  <div class="search-wrapper">
    <form method="GET">
      <input type="text" name="keyword" placeholder="Cari bengkel..." class="search-input">
      <select name="kategori" class="search-select">
        <option value="">Semua Kategori</option>
        <option value="Mobil">Mobil</option>
        <option value="Motor">Motor</option>
        <option value="Mobil & Motor">Mobil & Motor</option>
      </select>
      <button type="submit" class="search-btn">Cari</button>
    </form>
  </div>

  <!-- GRID CARD -->
  <div class="grid-container">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id     = $row['workshop_id'];
            $nama   = $row['name'];
            $alamat = $row['address'];

            // ✅ tentukan nama file gambar
            $gambarFile = !empty($row['image']) ? $row['image'] : 'default.jpg';
            $gambarPath = "../assets/img/" . $gambarFile;

            // ✅ jika file tidak ada, fallback ke default
            if (!file_exists($gambarPath)) {
                $gambarPath = "../assets/img/default.jpg";
            }

            echo '
            <div class="card">
              <img src="'.$gambarPath.'" alt="'.$nama.'" class="bengkel-img">
              <button class="btn-favorite"><i class="far fa-heart"></i></button>
              <h3>'.$nama.'</h3>
              <p>📍 '.$alamat.'</p>
              <a href="booking.php?id='.$id.'" class="btn-book">💳 Booking</a>
            </div>
            ';
        }
    } else {
        echo '<p>Tidak ada bengkel tersedia.</p>';
    }
    ?>
  </div>

  <script src="../assets/js/cari.js"></script>
</body>
</html>
