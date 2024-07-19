<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM dbProj_users WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$discount = 0;
if ($user['client_status'] == 'Gold') {
    $discount = 20;
} else if ($user['client_status'] == 'Silver') {
    $discount = 10;
} else if ($user['client_status'] == 'Bronze') {
    $discount = 5;
}

// Determine the heading based on the user type
$heading = 'Profile';
switch ($user['user_type']) {
    case 'administrator':
        $heading = 'Administrator Profile';
        break;
    case 'employee':
        $heading = 'Employee Profile';
        break;
    default:
        $heading = 'Client Profile';
        break;
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2><?php echo $heading; ?></h2>
        <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Booked Events: <?php echo htmlspecialchars($user['booked_events']); ?></p>
        <p>Client Status: <?php echo htmlspecialchars($user['client_status']); ?></p>
        <p>Discount: <?php echo $discount; ?>%</p>
    </section>
</main>

<?php include 'includes/footer.php'; ?>