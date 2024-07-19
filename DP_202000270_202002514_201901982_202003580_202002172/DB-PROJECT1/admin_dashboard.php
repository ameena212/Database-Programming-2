<?php
// Include configuration file and start session
include 'config.php';
session_start();

// Redirect to login page if user is not an administrator
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'administrator') {
    header("Location: login.php");
    exit();
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Admin Dashboard</h2>
        <p>Welcome to the admin dashboard.</p>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
