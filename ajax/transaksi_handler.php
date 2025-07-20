<?php
header('Content-Type: application/json');
include '../includes/db.php';

$workshopId = intval($_GET['workshop_id'] ?? 0);

$services = [];
if($workshopId > 0){
    $stmt = $conn->prepare("SELECT service_id, service_name, price FROM services WHERE workshop_id = ?");
    $stmt->bind_param("i", $workshopId);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $services[] = $row;
    }
    $stmt->close();
}

// Kirim JSON ke frontend
echo json_encode($services);
