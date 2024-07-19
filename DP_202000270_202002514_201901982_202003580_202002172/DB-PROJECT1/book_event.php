<?php
// Include configuration file and start session
include 'config.php';
session_start();

// Handle form submission to select a hall
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select'])) {
    // Retrieve selected hall information
    $hallId = $_POST['hall_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Query to get hall details
    $sql = $pdo->prepare("SELECT * FROM dbProj_halls WHERE id = :hallId");
    $sql->bindParam(':hallId', $hallId);
    $sql->execute();
    $hall = $sql->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission to book an event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book'])) {
    // Retrieve form data
    $clientId = $_SESSION['user_id'];
    $hallId = $_POST['hall_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $eventName = $_POST['event_name'];
    $numberOfAudiences = $_POST['number_of_audiences'];

    // Calculate total cost based on rental duration
    $startDateTime = new DateTime($startDate);
    $endDateTime = new DateTime($endDate);
    $interval = $startDateTime->diff($endDateTime);
    $days = $interval->days + 1;
    $totalCost = $days * 24 * $_POST['rental_cost'];

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Insert reservation details into database
        $sql = $pdo->prepare("
            INSERT INTO dbProj_reservations (client_id, hall_id, start_date, end_date, number_of_audiences, total_cost)
            VALUES (:clientId, :hallId, :startDate, :endDate, :numberOfAudiences, :totalCost)
        ");
        $sql->bindParam(':clientId', $clientId);
        $sql->bindParam(':hallId', $hallId);
        $sql->bindParam(':startDate', $startDate);
        $sql->bindParam(':endDate', $endDate);
        $sql->bindParam(':numberOfAudiences', $numberOfAudiences);
        $sql->bindParam(':totalCost', $totalCost);
        $sql->execute();
        $reservationId = $pdo->lastInsertId();
        // Insert event details into database
        $eventDate = $startDate;
        $sql = $pdo->prepare("
            INSERT INTO dbProj_events (event_name, event_date, hall_id, client_id)
            VALUES (:eventName, :eventDate, :hallId, :clientId)
        ");
        $sql->bindParam(':eventName', $eventName);
        $sql->bindParam(':eventDate', $eventDate);
        $sql->bindParam(':hallId', $hallId);
        $sql->bindParam(':clientId', $clientId);
        $sql->execute();

        // Commit transaction
        $pdo->commit();

        // Redirect to payment page
        header("Location: select_services.php?reservation_id=" . $reservationId);
        exit();
    } catch (Exception $e) {
        // rollback transaction on error
        $pdo->rollBack();
        echo "Error: Could not book the event.";
    }
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <?php if (isset($hall)): ?>
        <section>
            <h2>Book Event at <?php echo $hall['hall_name']; ?></h2>
            <?php
            $capacity = $hall['capacity'];
            $image = '';
            if ($capacity >= 1 && $capacity <= 35) {
                $image = 'hallsmall.png';
            } elseif ($capacity >= 36 && $capacity <= 79) {
                $image = 'hallmedium.png';
            } elseif ($capacity >= 80) {
                $image = 'hallbig.png';
            }
            ?>
            <img src="images/<?php echo $image; ?>" alt="Image of <?php echo $hall['hall_name']; ?>" style="width:300px;">
            <p>Description: <?php echo $hall['description']; ?></p>
            <p>Rental Cost: $<?php echo $hall['rental_cost']; ?> per hour</p>
            <p>Start Date: <?php echo $startDate; ?></p>
            <p>End Date: <?php echo $endDate; ?></p>

            <form method="POST" action="">
                <input type="hidden" name="hall_id" value="<?php echo $hallId; ?>">
                <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">
                <input type="hidden" name="rental_cost" value="<?php echo $hall['rental_cost']; ?>">

                <label for="event_name">Event Name:</label>
                <input type="text" id="event_name" name="event_name" required><br>

                <label for="number_of_audiences">Number of Audiences:</label>
                <input type="number" id="number_of_audiences" name="number_of_audiences" required><br>

                <button type="submit" name="book">Proceed to Catering</button>

                <!--<button type="submit" name="book">Proceed to Client Details</button>-->
            </form>
            <form method="POST" action="update_event.php" style="display:inline;">
                <input type="hidden" name="hall_id" value="<?php echo $hallId; ?>">
                <input type="hidden" name="start_date" value="<?php echo $startDate; ?>">
                <input type="hidden" name="end_date" value="<?php echo $endDate; ?>">
                <button type="submit" name="update">Update Details</button>
            </form>
            <form method="GET" action="index.php" style="display:inline;">
                <button type="submit">Cancel</button>
            </form>
        </section>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
