<?php
session_start();
require_once '../includes/db.php';

// Proses update saat form disubmit
if (isset($_POST['booking_id']) && isset($_POST['booking_date'])) {
    $booking_id = intval($_POST['booking_id']);
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);

    $query = "UPDATE bookings SET booking_date = '$booking_date' WHERE booking_id = $booking_id";

    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "✅ Booking berhasil diupdate.";
    } else {
        $_SESSION['message'] = "❌ Gagal mengupdate booking.";
    }

    header("Location: riwayat.php");
    exit;
}

// Ambil data transaksi berdasarkan ID dari URL
if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);
    $result = mysqli_query($conn, "SELECT * FROM bookings WHERE booking_id = $booking_id");

    if (mysqli_num_rows($result) > 0) {
        $transaksi = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['message'] = "❌ ID booking tidak ditemukan.";
        header("Location: riwayat.php");
        exit;
    }
} else {
    $_SESSION['message'] = "❌ ID booking tidak ditemukan di URL.";
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
    <h1>Riwayat Booking</h1>

    <div class="container">
        <h2>Edit Transaksi</h2>
        <form method="post">
            <input type="hidden" name="booking_id" value="<?= $transaksi['booking_id'] ?>">

            <label>Tanggal:</label><br>
            <input type="date" name="booking_date" value="<?= $transaksi['booking_date'] ?>" required><br><br>

            <button type="submit">Simpan Perubahan</button>
            <a href="riwayat.php">Batal</a>
        </form>
    </div>
</body>
</html>
