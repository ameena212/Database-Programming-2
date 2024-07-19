<?php
include 'config.php';
session_start();

// Redirect if reservation_id is not provided
if (!isset($_GET['reservation_id'])) {
    header("Location: index.php");
    exit();
}

$reservationId = $_GET['reservation_id'];

// Process the form submission to confirm payment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_payment'])) {
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $securityNumber = $_POST['security_number'];
    $cardholderName = $_POST['cardholder_name'];
    $billingAddress = $_POST['billing_address'];

    // Generate reservation code
    $reservationCode = uniqid('RES-', true);

    // Update reservation with reservation code
    $stmt = $pdo->prepare("
        UPDATE dbProj_reservations
        SET reservation_code = :reservationCode
        WHERE id = :reservationId
    ");
    $stmt->bindParam(':reservationCode', $reservationCode);
    $stmt->bindParam(':reservationId', $reservationId);
    $stmt->execute();

    // Send confirmation email
    mail($_SESSION['email'], "Reservation Confirmation", "Your reservation code is: " . $reservationCode);

    // Redirect to confirmation page
    header("Location: confirmation.php?reservation_id=" . $reservationId);
    exit();
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Payment Details</h2>
        <form method="POST" action="">
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required><br>
            <label for="expiry_date">Expiry Date:</label>
            <input type="text" id="expiry_date" name="expiry_date" required><br>
            <label for="security_number">Security Number:</label>
            <input type="text" id="security_number" name="security_number" required><br>
            <label for="cardholder_name">Cardholder Name:</label>
            <input type="text" id="cardholder_name" name="cardholder_name" required><br>
            <label for="billing_address">Billing Address:</label>
            <input type="text" id="billing_address" name="billing_address" required><br>
            <button type="submit" name="confirm_payment">Confirm Payment</button>
            <button onclick="printPaymentDetails()">Print</button>
            <a href="index.php"><button type="button">Cancel</button></a>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<script>
    function printPaymentDetails() {
        window.print();
    }
</script>
