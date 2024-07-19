<?php
// Include configuration file and start session
include 'config.php';
session_start();

// Check if reservation ID is set in the URL
if (!isset($_GET['reservation_id'])) {
    // Redirect to index page if reservation ID is not set
    header("Location: index.php");
    exit();
}

// Retrieve reservation ID from the URL
$reservationId = $_GET['reservation_id'];

// Query to retrieve reservation details along with client status
$sql = $pdo->prepare("
    SELECT r.*, h.hall_name, h.description, h.rental_cost, u.client_status 
    FROM dbProj_reservations r 
    JOIN dbProj_halls h ON r.hall_id = h.id 
    JOIN dbProj_users u ON r.client_id = u.id 
    WHERE r.id = :reservationId
");
$sql->bindParam(':reservationId', $reservationId);
$sql->execute();
$reservation = $sql->fetch(PDO::FETCH_ASSOC);
?>

<?php include 'includes/header.php'; ?>

<main>
    <section>
        <!-- Reservation confirmation details -->
        <h2>Reservation Confirmation</h2>
        <p>Reservation Code: <?php echo $reservation['reservation_code']; ?></p>
        <p>Event Name: <?php echo $reservation['event_name']; ?></p>
        <p>Hall Name: <?php echo $reservation['hall_name']; ?></p>
        <p>Description: <?php echo $reservation['description']; ?></p>
        <p>Rental Cost: $<?php echo $reservation['rental_cost']; ?> per hour</p>
        <p>Total Cost: $<?php echo $reservation['total_cost']; ?></p>
        <p>Start Date: <?php echo $reservation['start_date']; ?></p>
        <p>End Date: <?php echo $reservation['end_date']; ?></p>
        <p>Number of Audiences: <?php echo $reservation['number_of_audiences']; ?></p>
        <p>Client Status: <?php echo $reservation['client_status']; ?></p>
        <br/>
        <button onclick="printPaymentDetails()">Print</button>

    </section>
</main>

<?php include 'includes/language.php'; ?>