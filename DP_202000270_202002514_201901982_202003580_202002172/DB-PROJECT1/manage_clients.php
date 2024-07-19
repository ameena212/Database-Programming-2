<?php
include 'config.php';
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the user is an administrator or employee
if ($_SESSION['user_type'] != 'administrator' && $_SESSION['user_type'] != 'employee') {
    header("Location: login.php"); // Redirect to login page if not an admin or employee
    exit();
}

// Add a new client
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_client'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $user_type = 'client';

    $stmt = $pdo->prepare("
        INSERT INTO dbProj_users (username, password, email, user_type)
        VALUES (:username, :password, :email, :user_type)
    ");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->execute();

    header("Location: manage_clients.php");
    exit();
}

// Edit a client
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_client'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $stmt = $pdo->prepare("
        UPDATE dbProj_users
        SET username = :username, email = :email
        WHERE id = :user_id
    ");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    header("Location: manage_clients.php");
    exit();
}

// Delete a client
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_client'])) {
    $user_id = $_POST['user_id'];

    $stmt = $pdo->prepare("DELETE FROM dbProj_users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    header("Location: manage_clients.php");
    exit();
}

// Fetch all clients
$stmt = $pdo->prepare("SELECT * FROM dbProj_users WHERE user_type = 'client'");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Manage Clients</h2>
        <!-- Form to add a new client -->
        <h3>Add Client</h3>
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <button type="submit" name="add_client">Add Client</button>
        </form>

        <!-- Displaying the client list -->
        <h3>Client List</h3>
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo $client['username']; ?></td>
                    <td><?php echo $client['email']; ?></td>
                    <td>
                        <!-- Form to edit a client -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $client['id']; ?>">
                            <input type="text" name="username" value="<?php echo $client['username']; ?>">
                            <input type="email" name="email" value="<?php echo $client['email']; ?>">
                            <button type="submit" name="edit_client">Edit</button>
                        </form>
                        <!-- Form to delete a client -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $client['id']; ?>">
                            <button type="submit" name="delete_client">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
