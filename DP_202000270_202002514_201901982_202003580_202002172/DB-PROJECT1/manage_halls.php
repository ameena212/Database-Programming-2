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

// Process the form submission to add a new hall
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_hall'])) {
    $hall_name = $_POST['hall_name'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    $rental_cost = $_POST['rental_cost'];

    $stmt = $pdo->prepare("
        INSERT INTO dbProj_halls (hall_name, capacity, description, rental_cost)
        VALUES (:hall_name, :capacity, :description, :rental_cost)
    ");
    $stmt->bindParam(':hall_name', $hall_name);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':rental_cost', $rental_cost);
    $stmt->execute();

    // Redirect to the manage halls page after adding the hall
    header("Location: manage_halls.php");
    exit();
}

// Process the form submission to edit a hall
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_hall'])) {
    $hall_id = $_POST['hall_id'];
    $hall_name = $_POST['hall_name'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];
    $rental_cost = $_POST['rental_cost'];

    // Prepare SQL statement to update hall information
    $stmt = $pdo->prepare("
        UPDATE dbProj_halls
        SET hall_name = :hall_name, capacity = :capacity, description = :description, rental_cost = :rental_cost
        WHERE id = :hall_id
    ");
    $stmt->bindParam(':hall_name', $hall_name);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':rental_cost', $rental_cost);
    $stmt->bindParam(':hall_id', $hall_id);
    $stmt->execute();

    // Redirect to the manage halls page after editing the hall
    header("Location: manage_halls.php");
    exit();
}

// Process the form submission to delete a hall
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_hall'])) {
    $hall_id = $_POST['hall_id'];

    // Prepare SQL statement to delete a hall
    $stmt = $pdo->prepare("DELETE FROM dbProj_halls WHERE id = :hall_id");
    $stmt->bindParam(':hall_id', $hall_id);
    $stmt->execute();

    // Redirect to the manage halls page after deleting the hall
    header("Location: manage_halls.php");
    exit();
}

// Fetch all halls from the database
$stmt = $pdo->prepare("SELECT * FROM dbProj_halls");
$stmt->execute();
$halls = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Manage Halls</h2>
        <h3>Add Hall</h3>
        <!-- Form to add a new hall -->
        <form method="POST" action="">
            <label for="hall_name">Hall Name:</label>
            <input type="text" id="hall_name" name="hall_name" required><br>
            <label for="capacity">Capacity:</label>
            <input type="number" id="capacity" name="capacity" required><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br>
            <label for="rental_cost">Rental Cost:</label>
            <input type="number" id="rental_cost" name="rental_cost" step="0.01" required><br>
            <button type="submit" name="add_hall">Add Hall</button>
        </form>

        <h3>Hall List</h3>
        <table>
            <tr>
                <th>Hall Name</th>
                <th>Capacity</th>
                <th>Description</th>
                <th>Rental Cost</th>
                <th>Actions</th>
            </tr>
            <!-- Loop through halls and display them in a table -->
            <?php foreach ($halls as $hall): ?>
                <tr>
                    <td><?php echo $hall['hall_name']; ?></td>
                    <td><?php echo $hall['capacity']; ?></td>
                    <td><?php echo $hall['description']; ?></td>
                    <td><?php echo $hall['rental_cost']; ?></td>
                    <td>
                        <!-- Form to edit hall details -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="hall_id" value="<?php echo $hall['id']; ?>">
                            <input type="text" name="hall_name" value="<?php echo $hall['hall_name']; ?>">
                            <input type="number" name="capacity" value="<?php echo $hall['capacity']; ?>">
                            <textarea name="description"><?php echo $hall['description']; ?></textarea>
                            <input type="number" name="rental_cost" value="<?php echo $hall['rental_cost']; ?>">
                            <button type="submit" name="edit_hall">Edit</button>
                        </form>
                        <!-- Form to delete a hall -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="hall_id" value="<?php echo $hall['id']; ?>">
                            <button type="submit" name="delete_hall">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
