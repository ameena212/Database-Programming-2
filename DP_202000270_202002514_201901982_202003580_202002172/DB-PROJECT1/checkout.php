<?php
// Include configuration file and start session
include 'config.php';
session_start();

// Check if reservation ID is set
if (!isset($_GET['reservation_id'])) {
    // Redirect to index page if reservation ID is not set
    header("Location: index.php");
    exit();
}

// Get reservation ID from GET parameter
$reservationId = $_GET['reservation_id'];

// Query to retrieve reservation details
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

// Check if reservation exists
if (!$reservation) {
    // Handle the case where no reservation is found
    echo "<p>Reservation not found.</p>";
    exit();
}

// Calculate discount based on client status
$discount = 0;
if ($reservation['client_status'] == 'Gold') {
    $discount = 0.20;
} elseif ($reservation['client_status'] == 'Silver') {
    $discount = 0.10;
} elseif ($reservation['client_status'] == 'Bronze') {
    $discount = 0.05;
}
// Calculate total cost after discount
$totalCost = $reservation['total_cost'] * (1 - $discount);

// Handle confirmation of checkout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_checkout'])) {
    $_SESSION['total_cost'] = $totalCost;
    header("Location: payment.php?reservation_id=" . $reservationId);
    exit();
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Checkout</h2>
         <!--<p>Event Name: <?php echo htmlspecialchars($reservation['event_name']); ?></p>-->
        <p>Hall Name: <?php echo htmlspecialchars($reservation['hall_name']); ?></p>
        <p>Description: <?php echo htmlspecialchars($reservation['description']); ?></p>
        <p>Rental Cost: $<?php echo htmlspecialchars($reservation['rental_cost']); ?> per hour</p>
        <p>Total Cost: $<?php echo number_format($totalCost, 2); ?></p>
        <p>Start Date: <?php echo htmlspecialchars($reservation['start_date']); ?></p>
        <p>End Date: <?php echo htmlspecialchars($reservation['end_date']); ?></p>
        <p>Number of Audiences: <?php echo htmlspecialchars($reservation['number_of_audiences']); ?></p>

        <!-- Form to confirm checkout -->
        <form method="POST" action="">
            <button type="submit" name="confirm_checkout">Confirm and Proceed to Payment</button>
        </form>
        <!-- Button to cancel and go back to index page -->
        <form method="GET" action="index.php" style="display:inline;">
            <button type="submit">Cancel</button>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
