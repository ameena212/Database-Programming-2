<?php
include 'config.php';
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle reservation deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    $sql = $pdo->prepare("DELETE FROM dbProj_reservations WHERE id = :reservation_id");
    $sql->bindParam(':reservation_id', $reservation_id);
    $sql->execute();

    // Send JSON response
    echo json_encode(['success' => true]);
    exit();
}

$sql = $pdo->prepare("
    SELECT r.*, h.hall_name
    FROM dbProj_reservations r 
    JOIN dbProj_halls h ON r.hall_id = h.id 
    WHERE client_id = :user_id
");
$sql->bindParam(':user_id', $user_id);
$sql->execute();
$reservations = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>View Reservations</h2>
        <?php if (empty($reservations)): ?>
            <p>No reservations found for your user specifically.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Reservation ID</th>
                    <th>Hall</th>
                    <th>Event Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Number of Audiences</th>
                    <th>Total Cost</th>
                    <th>Actions</th> <!-- Added Actions column -->
                </tr>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?php echo $reservation['id']; ?></td>
                    <td><?php echo $reservation['hall_name']; ?></td>
                    <td><?php echo $reservation['event_name']; ?></td>
                    <td><?php echo $reservation['start_date']; ?></td>
                    <td><?php echo $reservation['end_date']; ?></td>
                    <td><?php echo $reservation['number_of_audiences']; ?></td>
                    <td><?php echo $reservation['total_cost']; ?></td>
                    <td>
                        <!-- AJAX delete button -->
                        <button class="delete-btn" data-reservation-id="<?php echo $reservation['id']; ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
    // Add click event listener to delete buttons
    $('.delete-btn').click(function() {
        // Get the reservation ID
        var reservationId = $(this).data('reservation-id');
        
        // Send AJAX request to delete reservation
        $.ajax({
            url: 'view_reservations.php',
            method: 'POST',
            data: { delete_reservation: true, reservation_id: reservationId },
            success: function(response) {
                // Reload the page after successful deletion
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Error deleting reservation. Please try again.');
            }
        });
    });
});
</script>
