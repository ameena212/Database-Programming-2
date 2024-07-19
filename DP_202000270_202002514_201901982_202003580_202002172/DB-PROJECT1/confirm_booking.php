<?php
include 'config.php';
session_start();

// Check if reservation ID is set
if (!isset($_GET['reservation_id'])) {
    // Redirect to index page if reservation ID is not set
    header("Location: index.php");
    exit();
}

$reservationId = $_GET['reservation_id'];

// Query to retrieve reservation details
$stmt = $pdo->prepare("
    SELECT r.*, h.hall_name, h.description, h.rental_cost 
    FROM dbProj_reservations r 
    JOIN dbProj_halls h ON r.hall_id = h.id 
    WHERE r.id = :reservationId
");
$stmt->bindParam(':reservationId', $reservationId);
$stmt->execute();
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

// Get catering options for this reservation
$cateringStmt = $pdo->prepare("
    SELECT c.name, c.price 
    FROM dbProj_catering_reservations cr
    JOIN dbProj_catering c ON cr.catering_id = c.id
	
    WHERE cr.reservation_id = :reservationId
");
$cateringStmt->bindParam(':reservationId', $reservationId);
$cateringStmt->execute();
$cateringOptions = $cateringStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Booking Confirmation</h2>
         <!--<p>Event Name: <?php echo htmlspecialchars($reservation['event_name']); ?></p>-->
        <p>Hall Name: <?php echo htmlspecialchars($reservation['hall_name']); ?></p>
        <p>Description: <?php echo htmlspecialchars($reservation['description']); ?></p>
        <p>Rental Cost: $<?php echo htmlspecialchars($reservation['rental_cost']); ?> per hour</p>
        <p>Total Cost: $<?php echo htmlspecialchars($reservation['total_cost']); ?></p>
        <p>Start Date: <?php echo htmlspecialchars($reservation['start_date']); ?></p>
        <p>End Date: <?php echo htmlspecialchars($reservation['end_date']); ?></p>
        <p>Number of Audiences: <?php echo htmlspecialchars($reservation['number_of_audiences']); ?></p>

        <!-- Display catering options if available -->
        <?php if (count($cateringOptions) > 0): ?>
            <h3>Catering Services</h3>
            <ul>
                <?php foreach ($cateringOptions as $option): ?>
                    <li><?php echo htmlspecialchars($option['name']); ?> - $<?php echo number_format($option['price'], 2); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <!-- Form to proceed to client details -->
        <form method="GET" action="client_details.php">
            <input type="hidden" name="reservation_id" value="<?php echo htmlspecialchars($reservationId); ?>">
            <button type="submit">Proceed to Client Details</button>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
