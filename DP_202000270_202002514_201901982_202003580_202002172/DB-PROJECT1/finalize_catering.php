<?php

// Including configuration file and starting session
include 'config.php';
session_start();

// Processing POST request to handle catering reservations
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservationId = $_POST['reservation_id'];
    $selectedOptions = isset($_POST['catering']) ? $_POST['catering'] : [];
    $totalCost = 0;

    // Loop through selected catering options to calculate total cost
    foreach ($selectedOptions as $optionId) {
        $servicesSql = $pdo->prepare("SELECT price FROM dbProj_catering WHERE id = :optionId");
        $servicesSql->bindParam(':optionId', $optionId);
        $servicesSql->execute();
        $price = $servicesSql->fetchColumn();
        $totalCost += $price;

        // Insert selected catering options into database
        $insertStmt = $pdo->prepare("
            INSERT INTO dbProj_catering_reservations (reservation_id, catering_id)
            VALUES (:reservationId, :optionId)
        ");
        $insertStmt->bindParam(':reservationId', $reservationId);
        $insertStmt->bindParam(':optionId', $optionId);
        $insertStmt->execute();
    }

    // Update total cost for the reservation
    $servicesSql = $pdo->prepare("UPDATE dbProj_reservations SET total_cost = total_cost + :totalCost WHERE id = :reservationId");
    $servicesSql->bindParam(':totalCost', $totalCost);
    $servicesSql->bindParam(':reservationId', $reservationId);
    $servicesSql->execute();

    // Return success response
    echo json_encode(['status' => 'success']);
} else {
    // Return error response for invalid request
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
