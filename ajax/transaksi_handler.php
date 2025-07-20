<?php
include 'db.php';

$action = $_REQUEST['action'] ?? '';

if ($action == 'read') {
    $sql = "SELECT b.booking_id, u.nama AS user, s.service_name, w.name AS workshop, 
                   b.booking_date, b.booking_time, b.status
            FROM bookings b
            JOIN users u ON b.user_id = u.user_id
            JOIN services s ON b.service_id = s.service_id
            JOIN workshops w ON b.workshop_id = w.workshop_id
            ORDER BY b.created_at DESC";
    $result = $conn->query($sql);

    echo "<table border='1' cellpadding='5'>
            <tr>
                <th>ID</th><th>User</th><th>Service</th><th>Workshop</th>
                <th>Tanggal</th><th>Waktu</th><th>Status</th><th>Aksi</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['booking_id']}</td>
            <td>{$row['user']}</td>
            <td>{$row['service_name']}</td>
            <td>{$row['workshop']}</td>
            <td>{$row['booking_date']}</td>
            <td>{$row['booking_time']}</td>
            <td>{$row['status']}</td>
            <td>
                <button onclick=\"ubahStatus({$row['booking_id']}, 'confirmed')\">Confirm</button>
                <button onclick=\"ubahStatus({$row['booking_id']}, 'canceled')\">Cancel</button>
            </td>
        </tr>";
    }
    echo "</table>";
}

elseif ($action == 'create') {
    $user_id = $_POST['user_id'];
    $workshop_id = $_POST['workshop_id'];
    $service_id = $_POST['service_id'];
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];

    $sql = "INSERT INTO bookings (user_id, workshop_id, service_id, booking_date, booking_time) 
            VALUES ('$user_id', '$workshop_id', '$service_id', '$date', '$time')";
    echo $conn->query($sql) ? "Booking berhasil ditambahkan" : "Gagal: " . $conn->error;
}

elseif ($action == 'update') {
    $id = $_POST['booking_id'];
    $status = $_POST['status'];

    $sql = "UPDATE bookings SET status='$status' WHERE booking_id='$id'";
    echo $conn->query($sql) ? "Status diubah ke $status" : "Gagal: " . $conn->error;
}
?>
