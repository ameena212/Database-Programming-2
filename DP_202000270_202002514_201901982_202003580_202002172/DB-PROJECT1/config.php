<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=db202002172';
$username = 'u202002172';
$password = 'u202002172';

try {
    // Create PDO instance
    $pdo = new PDO($dsn, $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    die('Connection failed: ' . $e->getMessage());
}

// Function to update client status based on booked events
function updateClientStatus($clientId, $pdo) {
    $stmt = $pdo->prepare("SELECT booked_events FROM dbProj_users WHERE id = :clientId");
    $stmt->bindParam(':clientId', $clientId);
    $stmt->execute();
    $bookedEvents = $stmt->fetchColumn();

    if ($bookedEvents > 15) {
        $status = 'Gold';
    } elseif ($bookedEvents > 10) {
        $status = 'Silver';
    } elseif ($bookedEvents > 5) {
        $status = 'Bronze';
    } else {
        $status = 'None';
    }

    $stmt = $pdo->prepare("UPDATE dbProj_users SET client_status = :status WHERE id = :clientId");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':clientId', $clientId);
    $stmt->execute();
}

?>
