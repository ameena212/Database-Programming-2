<?php
include 'config.php';

// Process the registration form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];

        // Check if the username already exists
    $registerStmt = $pdo->prepare("SELECT * FROM dbProj_users WHERE username = :username");
    $registerStmt->bindParam(':username', $username);
    $registerStmt->execute();

    if ($registerStmt->rowCount() > 0) {
        echo "Username already exists.";
    } else {
        $registerStmt = $pdo->prepare("INSERT INTO dbProj_users (username, password, email, user_type) VALUES (:username, :password, :email, 'client')");
        $registerStmt->bindParam(':username', $username);
        $registerStmt->bindParam(':password', $password);
        $registerStmt->bindParam(':email', $email);
        
        if ($registerStmt->execute()) {
            echo "Registration successful.";
        } else {
            echo "Error: Could not complete the registration.";
        }
    }
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Sign Up</h2>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <button type="submit">Sign Up</button>
        </form>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
