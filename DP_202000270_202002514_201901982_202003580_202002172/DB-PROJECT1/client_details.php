<?php
// Include configuration file and start session
include 'config.php';
session_start();

// Check if user is not logged in and form is submitted
if (!isset($_SESSION['user_id'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_details'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $company = $_POST['company'];

        // Store form data in session
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['company'] = $company;

        // Redirect to checkout page with reservation ID
        header("Location: checkout.php?reservation_id=" . $_POST['reservation_id']);
        exit();
    }
} else {
    // If user is logged in, redirect to checkout page with reservation ID
    header("Location: checkout.php?reservation_id=" . $_GET['reservation_id']);
    exit();
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <!-- Form to enter personal/business details -->
        <h2>Enter Personal/Business Details to Confirm Reservation</h2>
        <form method="POST" action="">
            <input type="hidden" name="reservation_id" value="<?php echo $_GET['reservation_id']; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="company">Company (optional):</label>
            <input type="text" id="company" name="company"><br>
            <button type="submit" name="submit_details">Proceed to Checkout</button>
        </form>
        <!-- Button to cancel and go back to index page -->
        <form method="GET" action="index.php" style="display:inline;">
            <button type="submit">Cancel</button>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
