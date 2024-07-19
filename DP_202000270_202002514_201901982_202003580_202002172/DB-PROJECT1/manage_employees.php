<?php
include 'config.php';
session_start();

// Check if the user is logged in as an administrator
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'administrator') {
    header("Location: login.php");
    exit();
}

// Add a new employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $user_type = 'employee';

    // Insert new employee into database
    $stmt = $pdo->prepare("
        INSERT INTO dbProj_users (username, password, email, user_type)
        VALUES (:username, :password, :email, :user_type)
    ");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->execute();

    // Redirect to manage employees page
    header("Location: manage_employees.php");
    exit();
}

// Update employee information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_employee'])) {
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

    header("Location: manage_employees.php");
    exit();
}

// Delete employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_employee'])) {
    $user_id = $_POST['user_id'];

    $stmt = $pdo->prepare("DELETE FROM dbProj_users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    header("Location: manage_employees.php");
    exit();
}

// Retrieve list of employees
$stmt = $pdo->prepare("SELECT * FROM dbProj_users WHERE user_type = 'employee'");
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <div class="employees-heading">
            <i class="fa-solid fa-user"></i>
        </div>
        <h2>Manage Employees</h2>
        <h3>Add Employee</h3>
        <!-- Form to add a new employee -->
        <form method="POST" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <button type="submit" name="add_employee">Add Employee</button>
        </form>

        <h3>Employee List</h3>
        <!-- Table to display list of employees -->
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo $employee['username']; ?></td>
                    <td><?php echo $employee['email']; ?></td>
                    <td>
                        <!-- Form to edit employee information -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $employee['id']; ?>">
                            <input type="text" name="username" value="<?php echo $employee['username']; ?>">
                            <input type="email" name="email" value="<?php echo $employee['email']; ?>">
                            <button type="submit" name="edit_employee">Edit</button>
                        </form>
                        <!-- Form to delete an employee -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?php echo $employee['id']; ?>">
                            <button type="submit" name="delete_employee">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
