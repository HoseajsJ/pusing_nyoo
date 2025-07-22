<?php
session_start();
require_once '../includes/db.php';

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

// Cek parameter ID
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "❌ ID booking tidak ditemukan.";
    header("Location: riwayat.php");
    exit;
}

$booking_id = intval($_GET['id']);

// Eksekusi hapus
$query = "DELETE FROM bookings WHERE booking_id = $booking_id";

if (mysqli_query($conn, $query)) {
    $_SESSION['message'] = "✅ Booking berhasil dihapus.";
} else {
    $_SESSION['message'] = "❌ Gagal menghapus booking.";
}

header("Location: riwayat.php");
exit;
