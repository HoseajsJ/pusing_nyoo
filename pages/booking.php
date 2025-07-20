<?php
include '../includes/db.php';

// Ambil ID bengkel dari URL
$id = $_GET['id'] ?? 0;

// Query ambil detail bengkel
$sql = "SELECT name, address, image FROM bengkel WHERE workshop_id = '$id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row    = $result->fetch_assoc();
    $bengkel = $row['name'];
    $alamat  = $row['address'];
    $gambar  = "../assets/img/" . $row['image']; // path ke folder gambar
} else {
    // fallback kalau bengkel tidak ditemukan
    $bengkel = "Bengkel Tidak Ditemukan";
    $alamat  = "-";
    $gambar  = "../assets/img/default.jpg"; 
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Booking</title>

  <!-- CSS -->
  <link rel="stylesheet" href="../assets/css/booking.css">
  <link rel="stylesheet" href="../assets/css/main.css">
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI&display=swap" rel="stylesheet">
</head>
<body>
  <div class="booking-container">
    <div class="booking-card">

      <!-- ✅ Kolom Kiri: Gambar -->
      <div class="booking-image">
        <img src="<?= $gambar; ?>" alt="Foto Bengkel">
      </div>

      <!-- ✅ Kolom Kanan: Detail -->
      <div class="booking-detail">
        <h2>Yakin Deck?</h2>
        <p><strong>Nama Bengkel:</strong> <?= $bengkel; ?></p>
        <p><strong>Alamat:</strong> <?= $alamat; ?></p>

        <div class="btn-group">
          <a href="transaksi.php" class="btn">Ke Halaman Transaksi</a>
          <a href="hapus_booking.php?id=123" class="btn danger">Batalkan Booking</a>
        </div>
      </div>

    </div>
  </div>
</body>
</html>
