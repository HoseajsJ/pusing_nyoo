<?php
session_start();
require_once '../includes/db.php';

if (isset($_POST['id']) && isset($_POST['booking_date'])) {
    $id = $_POST['id'];
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);

    $query = "UPDATE booking SET booking_date = '$booking_date' WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo "Tanggal booking berhasil diubah.";
    } else {
        echo "Gagal mengubah tanggal booking.";
    }
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
        <h2>Edit Transaksi</h2>
        <form method="post">

            <label>Tanggal:</label><br>
            <input type="date" name="tanggal" value="<?= $transaksi['booking_date'] ?>" required><br><br>

            <button type="submit">Simpan Perubahan</button>
            <a href="riwayat.php">Batal</a>
        </form>
    </div>
</body>
</html>
