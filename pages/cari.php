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

// Ambil semua bengkel dari database
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
  <style>
    body.custom-bg {
      background: url('../assets/img/4045rlys.png') no-repeat center center fixed;
      background-size: cover;
    }
  </style>
</head>
<body class="custom-bg">
  <!-- MENU SLIDE -->
  <button class="menu-toggle" onclick="toggleMenu()">⋮</button>
  <div class="slide-menu" id="slideMenu">
    <a href="cari.php">🏠 Home</a>
    <a href="../pages/logout.php">🚪 Logout</a>
  </div>
  <div class="overlay" onclick="toggleMenu()" id="overlay"></div>

  <!-- HEADER -->
  <header class="header">
    <h1>🔧 Cari Bengkel</h1>
    <p>Temukan bengkel terpercaya untuk kendaraanmu</p>
  </header>

  <!-- GRID CARD -->
  <div class="grid-container">
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id     = $row['workshop_id'];
            $nama   = $row['name'];
            $alamat = $row['address'];

            $gambarFile = !empty($row['image']) ? $row['image'] : 'default.jpg';
            $gambarPath = "../assets/img/" . $gambarFile;

            if (!file_exists($gambarPath)) {
                $gambarPath = "../assets/img/default.jpg";
            }

            echo '
            <div class="card">
              <img src="'.$gambarPath.'" alt="'.$nama.'" class="bengkel-img">
              <div class="card-content">
                <h3>'.$nama.'</h3>
                <p><i class="fa fa-map-marker-alt"></i> '.$alamat.'</p>
                <p><i class="fa fa-clock"></i> Buka: 08.00 - 17.00</p>
                <div class="rating">⭐⭐⭐⭐☆</div>
                <div class="tags">
                  <span class="tag">🔧 Servis</span>
                  <span class="tag">🛠️ Tambal Ban</span>
                  <span class="tag">🚗 Mobil</span>
                  <span class="tag">🧽 Cuci Mobil</span>
                  <span class="tag">🔋 Aki</span>
                </div>
                <a href="booking.php?id='.$id.'" class="btn-book">💳 Booking</a>
              </div>
              <button class="btn-favorite"><i class="far fa-heart"></i></button>
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
