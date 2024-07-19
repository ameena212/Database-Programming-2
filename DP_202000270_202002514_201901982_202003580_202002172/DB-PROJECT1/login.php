<?php
// Including configuration file and starting session
include 'config.php';
session_start();

// Handling login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user data based on username
    $stmt = $pdo->prepare("SELECT * FROM dbProj_users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validating username and password
    if ($user && password_verify($password, $user['password'])) {
        // Setting session variables
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['email'] = $user['email'];

        // Redirecting based on user type
        if ($user['user_type'] === 'administrator') {
            header("Location: admin_dashboard.php");
        } elseif ($user['user_type'] === 'employee') {
            header("Location: employee_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid username or password."; // Error message for invalid credentials
    }
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>