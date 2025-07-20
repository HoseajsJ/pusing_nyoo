<?php include '../includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transaksi Booking</title>
  <link rel="stylesheet" href="../assets/css/transaksi.css">
</head>
<body>
  <div class="form-container">
    <h2>Form Transaksi</h2>
    <form action="proses_booking.php" method="POST">
      
      <!-- Pilih Bengkel -->
      <div class="form-group">
        <select name="workshop_id" required>
          <option value="">Pilih Bengkel</option>
          <option value="1">Bengkel Sejahtera</option>
          <option value="2">Bengkel Motor Jaya</option>
        </select>
      </div>

      <!-- Pilih Service -->
      <div class="form-group">
        <select name="service_id" required>
          <option value="">Pilih Layanan</option>
          <option value="1">Ganti Oli - Rp 100.000</option>
          <option value="2">Tune Up - Rp 250.000</option>
        </select>
      </div>

      <!-- Tanggal -->
      <div class="form-group">
        <input type="date" name="booking_date" required>
      </div>

      <!-- Waktu -->
      <div class="form-group">
        <input type="time" name="booking_time" required>
      </div>

      <!-- Tombol -->
      <button type="submit">Buat Booking</button>

      <p class="info">Lihat <a href="riwayat.php">Riwayat Transaksi</a></p>
    </form>
  </div>
</body>
</html>
