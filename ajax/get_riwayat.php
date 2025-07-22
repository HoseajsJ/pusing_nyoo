<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode([]);
    exit;
}

// Ambil user_id
if (is_array($_SESSION['user']) && isset($_SESSION['user']['user_id'])) {
    $user_id = $_SESSION['user']['user_id'];
} else {
    $email = mysqli_real_escape_string($conn, $_SESSION['user']);
    $res = $conn->query("SELECT user_id FROM users WHERE email='$email' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $user_id = $res->fetch_assoc()['user_id'];
    } else {
        echo json_encode([]);
        exit;
    }
}

// Ambil data booking + layanan
$query = "
SELECT 
    b.booking_id,
    s.service_name,
    s.price,
    b.booking_date,
    b.booking_time,
    b.status
FROM bookings b
JOIN services s ON b.service_id = s.service_id
WHERE b.user_id = $user_id
ORDER BY b.booking_date DESC, b.booking_time DESC
";

$result = mysqli_query($conn, $query);
$data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
}

echo json_encode($data);
