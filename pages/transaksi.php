<?php
session_start();
require_once '../includes/db.php';

// ✅ Pastikan user login
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

// ✅ Ambil workshop_id dari URL
$workshop_id = isset($_GET['workshop_id']) ? intval($_GET['workshop_id']) : 0;
if ($workshop_id <= 0) {
    header("Location: cari.php");
    exit;
}

// ✅ Ambil detail bengkel
$queryBengkel = "SELECT name, address FROM bengkel WHERE workshop_id = $workshop_id";
$resultBengkel = mysqli_query($conn, $queryBengkel);
$bengkel = mysqli_fetch_assoc($resultBengkel);

// // ✅ Kalau bengkel tidak ditemukan
// if (!$bengkel) {
//     echo "<h2 style='color:red;'>Bengkel tidak ditemukan. <a href='cari.php'>Kembali</a></h2>";
//     exit;
// }

// ✅ Ambil semua layanan di bengkel ini
$queryService = "SELECT service_id, service_name, price FROM services WHERE workshop_id = $workshop_id";
$resultService = mysqli_query($conn, $queryService);
$services = [];
if ($resultService && mysqli_num_rows($resultService) > 0) {
    while ($row = mysqli_fetch_assoc($resultService)) {
        $services[] = $row;
    }
}

// ✅ Jika form booking disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id     = intval($_POST['service_id']);
    $booking_date   = $_POST['booking_date'];
    $booking_time   = $_POST['booking_time'];
    $customer_name  = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);

    // ✅ Ambil user_id dari session (langsung atau lewat email)
    if (is_array($_SESSION['user']) && isset($_SESSION['user']['user_id'])) {
        $user_id = $_SESSION['user']['user_id'];
    } else {
        // Jika session hanya email → cari user_id dari tabel users
        $email = mysqli_real_escape_string($conn, $_SESSION['user']);
        $res = $conn->query("SELECT user_id FROM users WHERE email='$email' LIMIT 1");
        if ($res && $res->num_rows > 0) {
            $user_id = $res->fetch_assoc()['user_id'];
        } else {
            die("❌ User tidak ditemukan di tabel users!");
        }
    }

    // ✅ Insert ke bookings jika semua data lengkap
    if ($workshop_id && $service_id && $booking_date && $booking_time) {
        $stmt = $conn->prepare("
            INSERT INTO bookings (user_id, workshop_id, service_id, booking_date, booking_time, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("iiiss", $user_id, $workshop_id, $service_id, $booking_date, $booking_time);
        $stmt->execute();

        // ✅ Setelah sukses booking → redirect ke Riwayat Booking
        header("Location: riwayat.php");
        exit();
    } else {
        echo "<p style='color:red;'>❌ Data booking tidak lengkap!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Booking</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/transaksi.css">
</head>
<body>
  <div class="form-container">
    <h1>Booking di <?= htmlspecialchars($bengkel['name']); ?></h1>
    <p>📍 <?= htmlspecialchars($bengkel['address']); ?></p>

    <!-- ✅ Form submit ke halaman ini sendiri -->
    <form action="" method="POST" class="booking-form">
      <input type="hidden" name="workshop_id" value="<?= $workshop_id; ?>">

      <div class="form-group">
        <label for="service_id">Pilih Layanan</label>
        <select name="service_id" id="service_id" required>
          <option value="">-- Pilih Layanan --</option>
          <?php if (!empty($services)): ?>
            <?php foreach ($services as $s): ?>
              <option value="<?= $s['service_id']; ?>">
                <?= htmlspecialchars($s['service_name']); ?> - Rp <?= number_format($s['price'], 0, ',', '.'); ?>
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value="" disabled>Tidak ada layanan tersedia</option>
          <?php endif; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="booking_date">Tanggal Booking</label>
        <input type="date" name="booking_date" id="booking_date" required min="<?= date('Y-m-d'); ?>">
      </div>

      <div class="form-group">
        <label for="booking_time">Waktu Booking</label>
        <input type="time" name="booking_time" id="booking_time" required min="08:00" max="17:00">
      </div>

      <div class="form-group">
        <label for="customer_name">Nama Pelanggan</label>
        <input type="text" name="customer_name" id="customer_name" required>
      </div>

      <div class="form-group">
        <label for="customer_phone">Nomor Telepon</label>
        <input type="tel" name="customer_phone" id="customer_phone" required>
      </div>

      <button type="submit" class="btn-submit">✅ Konfirmasi Booking</button>
      <p><a href="riwayat.php" class="btn-submit">📋 Lihat Riwayat Booking</a></p>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function(){
      document.getElementById('booking_date').valueAsDate = new Date();
    });
  </script>
</body>
</html>