<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit();
}

// 🧾 Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id   = intval($_POST['booking_id']);
    $service_id   = intval($_POST['service_id']);
    $booking_date = $_POST['booking_date'];

    $stmt = $conn->prepare("UPDATE bookings SET booking_date = ?, service_id = ? WHERE booking_id = ?");
    $stmt->bind_param("sii", $booking_date, $service_id, $booking_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Booking berhasil diupdate.";
    } else {
        $_SESSION['message'] = "❌ Gagal mengupdate booking.";
    }

    header("Location: riwayat.php");
    exit;
}

// ✏️ Ambil data transaksi
if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    // Ambil data booking + layanan terkait
    $result = mysqli_query($conn, "
        SELECT b.*, s.workshop_id 
        FROM bookings b 
        JOIN services s ON b.service_id = s.service_id
        WHERE booking_id = $booking_id
    ");

    if ($result && mysqli_num_rows($result) > 0) {
        $transaksi = mysqli_fetch_assoc($result);

        // Ambil semua layanan dari bengkel yang sama
        $services = [];
        $workshop_id = $transaksi['workshop_id'];
        $res = mysqli_query($conn, "SELECT * FROM services WHERE workshop_id = $workshop_id");
        while ($row = mysqli_fetch_assoc($res)) {
            $services[] = $row;
        }
    } else {
        $_SESSION['message'] = "❌ Booking tidak ditemukan.";
        header("Location: riwayat.php");
        exit;
    }
} else {
    $_SESSION['message'] = "❌ Booking ID tidak tersedia.";
    header("Location: riwayat.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Transaksi</title>
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/transaksi.css">
</head>
<body>
  <div class="container">
    <h1>Edit Transaksi Booking</h1>

    <form method="post">
      <input type="hidden" name="booking_id" value="<?= $transaksi['booking_id'] ?>">

      <label for="service_id">Layanan:</label><br>
      <select name="service_id" required>
        <?php foreach ($services as $s): ?>
          <option value="<?= $s['service_id'] ?>" <?= $s['service_id'] == $transaksi['service_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['service_name']) ?> - Rp<?= number_format($s['price'], 0, ',', '.') ?>
          </option>
        <?php endforeach; ?>
      </select><br><br>

      <label for="booking_date">Tanggal:</label><br>
      <input type="date" name="booking_date" value="<?= $transaksi['booking_date'] ?>" required><br><br>

      <button type="submit">💾 Simpan Perubahan</button>
      <a href="riwayat.php">← Batal</a>
    </form>
  </div>
</body>
</html>
