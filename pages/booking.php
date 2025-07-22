<?php
include '../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT name, address, image FROM bengkel WHERE workshop_id = '$id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row     = $result->fetch_assoc();
    $bengkel = $row['name'];
    $alamat  = $row['address'];
    $gambar  = "../assets/img/" . $row['image'];

    if (!file_exists($gambar)) {
        $gambar = "../assets/img/default.jpg";
    }
} else {
    $bengkel = "Bengkel Tidak Ditemukan";
    $alamat  = "-";
    $gambar  = "../assets/img/default.jpg"; 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Bengkel</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/booking.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

  <div class="booking-container">
    <div class="booking-card">

      <div class="booking-image">
        <img src="<?= $gambar; ?>" alt="Foto Bengkel">
      </div>

      <div class="booking-detail">
        <h2>Konfirmasi Booking</h2>
        <p><strong>Nama Bengkel:</strong> <?= htmlspecialchars($bengkel); ?></p>
        <p><strong>Alamat:</strong> <?= htmlspecialchars($alamat); ?></p>

        <div class="btn-group">
          <a href="transaksi.php?workshop_id=<?= $id; ?>" class="btn">➡ Ke Halaman Transaksi</a>
          <a href="cari.php" class="btn danger">❌ Batal</a>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
