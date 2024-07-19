<?php
include 'config.php';
session_start();

// Redirect if reservation_id is not provided
if (!isset($_GET['reservation_id'])) {
    header("Location: index.php");
    exit();
}

$reservationId = $_GET['reservation_id'];

// Fetch catering options from the database
$servicesSql = $pdo->prepare("SELECT * FROM dbProj_catering");
$servicesSql->execute();
$cateringOptions = $servicesSql->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Select Catering Services</h2>
        <!-- Form to select catering services -->
        <form id="cateringForm">
            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
            <?php foreach ($cateringOptions as $option): ?>
                <div>
                    <!-- Checkbox for each catering option -->
                    <input type="checkbox" id="catering_<?php echo $option['id']; ?>" name="catering[]" value="<?php echo $option['id']; ?>">
                    <label for="catering_<?php echo $option['id']; ?>"><?php echo $option['name']; ?> - $<?php echo number_format($option['price'], 2); ?></label>
                    <p><?php echo $option['description']; ?></p>
                </div>
            <?php endforeach; ?>
            <button type="submit">Finalize Services</button>
        </form>
        <!-- Form to skip and go to the main page -->
        <form method="GET" action="index.php" style="display:inline;">
            <button type="submit">Skip and Go to Main Page</button>
        </form>
    </section>
</main>

<!-- JavaScript to handle form submission via AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#cateringForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally
            $.ajax({
                url: 'finalize_catering.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    window.location.href = "confirm_booking.php?reservation_id=<?php echo $reservationId; ?>";
                },
                error: function (xhr, status, error) {
                    alert('An error occurred while finalizing the services. Please try again.');
                }
            });
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
