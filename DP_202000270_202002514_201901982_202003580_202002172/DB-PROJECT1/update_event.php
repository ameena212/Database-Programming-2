<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $hallId = $_POST['hall_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    $stmt = $pdo->prepare("SELECT * FROM dbProj_halls WHERE id = :hallId");
    $stmt->bindParam(':hallId', $hallId);
    $stmt->execute();
    $hall = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save'])) {
    $hallId = $_POST['hall_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    header("Location: book_event.php?hall_id=$hallId&start_date=$startDate&end_date=$endDate");
    exit();
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <?php if (isset($hall)): ?>
        <section>
            <h2>Update Event Details for <?php echo htmlspecialchars($hall['hall_name']); ?></h2>
            <form method="POST" action="">
                <input type="hidden" name="hall_id" value="<?php echo $hallId; ?>">

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required><br>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required><br>

                <button type="submit" name="save">Save Changes</button>
            </form>
        </section>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
