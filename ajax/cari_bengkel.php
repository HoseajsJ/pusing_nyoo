<?php
require_once '../includes/db.php';

if ($_POST['action'] == 'cari_bengkel') {
    $lat_user = $_POST['latitude'];
    $lon_user = $_POST['longitude'];

    $sql = "SELECT * FROM bengkel";
    $result = $conn->query($sql);

    function hitungJarak($lat1, $lon1, $lat2, $lon2) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1.609344; // km
    }

    $output = "<h3>Daftar Bengkel Terdekat:</h3>";
    while ($row = $result->fetch_assoc()) {
        $jarak = hitungJarak($lat_user, $lon_user, $row['latitude'], $row['longitude']);
        $output .= "<div class='result-card'>
            <strong>{$row['nama']}</strong><br>
            Alamat: {$row['alamat']}<br>
            Jarak: " . round($jarak, 2) . " km<br>
            <form method='POST' action='success.php'>
                <input type='hidden' name='id_bengkel' value='{$row['id']}'>
                <button type='submit' class='btn'>Booking</button>
            </form>
        </div>";
    }

    echo $output;
}
