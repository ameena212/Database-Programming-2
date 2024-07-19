<?php
include 'config.php';
session_start();

// Check if user is logged in as an employee, if not, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'employee') {
    header("Location: login.php");
    exit();
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Employee Dashboard</h2>
        <p>Welcome to the employee dashboard.</p>
    </section>
</main>

<?php include 'includes/footer.php'; ?>