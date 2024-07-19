<?php
include 'config.php';
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an administrator or employee
if ($_SESSION['user_type'] != 'administrator' && $_SESSION['user_type'] != 'employee') {
    header("Location: login.php"); // Redirect to login page if not an admin or employee
    exit();
}

// Process the form submission to edit a reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $event_name = $_POST['event_name'];
    $number_of_audiences = $_POST['number_of_audiences'];

    // Calculate charge
    $sql = $pdo->prepare("SELECT total_cost FROM dbProj_reservations WHERE id = :reservation_id");
    $sql->bindParam(':reservation_id', $reservation_id);
    $sql->execute();
    $total_cost = $sql->fetchColumn();
    $amendment_charge = $total_cost * 0.05;
    $new_total_cost = $total_cost + $amendment_charge;

    // Update reservation details
    $sql = $pdo->prepare("
        UPDATE dbProj_reservations
        SET start_date = :start_date, end_date = :end_date, event_name = :event_name, number_of_audiences = :number_of_audiences, total_cost = :new_total_cost
        WHERE id = :reservation_id
    ");
    $sql->bindParam(':start_date', $start_date);
    $sql->bindParam(':end_date', $end_date);
    $sql->bindParam(':event_name', $event_name);
    $sql->bindParam(':number_of_audiences', $number_of_audiences);
    $sql->bindParam(':new_total_cost', $new_total_cost);
    $sql->bindParam(':reservation_id', $reservation_id);
    $sql->execute();

    // Redirect to manage reservations page after editing
    header("Location: manage_reservations.php");
    exit();
}

// Process the form submission to cancel a reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    // Delete reservation from database
    $sql = $pdo->prepare("DELETE FROM dbProj_reservations WHERE id = :reservation_id");
    $sql->bindParam(':reservation_id', $reservation_id);
    $sql->execute();

    // Redirect to manage reservations page after cancellation
    header("Location: manage_reservations.php");
    exit();
}

// Fetch all reservations from the database
$sql = $pdo->prepare("
    SELECT r.*, h.hall_name, u.username 
    FROM dbProj_reservations r 
    JOIN dbProj_halls h ON r.hall_id = h.id 
    JOIN dbProj_users u ON r.client_id = u.id
");
$sql->execute();
$reservations = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Manage Reservations</h2>
        <!-- Table to display reservations -->
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>Client</th>
                <th>Hall</th>
                <th>Event Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Number of Audiences</th>
                <th>Total Cost</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <!-- Display reservation details -->
                    <td><?php echo $reservation['id']; ?></td>
                    <td><?php echo $reservation['username']; ?></td>
                    <td><?php echo $reservation['hall_name']; ?></td>
                    <td><?php echo $reservation['event_name']; ?></td>
                    <td><?php echo $reservation['start_date']; ?></td>
                    <td><?php echo $reservation['end_date']; ?></td>
                    <td><?php echo $reservation['number_of_audiences']; ?></td>
                    <td><?php echo $reservation['total_cost']; ?></td>
                    <td>
                        <!-- Form to edit reservation -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                            <input type="date" name="start_date" value="<?php echo $reservation['start_date']; ?>">
                            <input type="date" name="end_date" value="<?php echo $reservation['end_date']; ?>">
                            <input type="text" name="event_name" value="<?php echo $reservation['event_name']; ?>">
                            <input type="number" name="number_of_audiences" value="<?php echo $reservation['number_of_audiences']; ?>">
                            <button type="submit" name="edit_reservation">Edit</button>
                        </form>
                        <!-- Form to cancel reservation -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="reservation_id" value="<?php echo $reservation['id']; ?>">
                            <button type="submit" name="cancel_reservation">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
