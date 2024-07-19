<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <!-- Display links for unauthenticated users -->
            <li><a href="signup.php">Sign Up</a></li>
            <li><a href="login.php">Login</a></li>
        <?php } else { ?>
            <!-- Display links based on user type -->
            <?php if ($_SESSION['user_type'] == 'administrator') { ?>
                <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                <li><a href="manage_employees.php">Manage Employees</a></li>
                <li><a href="manage_halls.php">Manage Halls</a></li>
                <li><a href="manage_clients.php">Manage Clients</a></li>
                <li><a href="manage_catering.php">Manage Catering</a></li>
                <li><a href="manage_reservations.php">Manage Reservations</a></li>
            <?php } elseif ($_SESSION['user_type'] == 'employee') {
                ?>
                <li><a href = "employee_dashboard.php">Employee Dashboard</a></li>
                <li><a href = "manage_halls.php">Manage Halls</a></li>
                <li><a href = "manage_clients.php">Manage Clients</a></li>
                <li><a href = "manage_catering.php">Manage Catering</a></li>
                <li><a href = "manage_reservations.php">Manage Reservations</a></li>
            <?php } ?>
            <!-- Common links for all users -->
            <li><a href="view_halls.php">Halls</a></li>
            <li><a href="view_reservations.php">Reservations</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php } ?>
    </ul>
</nav>
