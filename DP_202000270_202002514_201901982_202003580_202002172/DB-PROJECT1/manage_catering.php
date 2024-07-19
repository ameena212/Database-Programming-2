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

// Pagination variables
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 2; // Number of records to display per page
// Calculate the starting record for the current page
$start_from = ($page - 1) * $records_per_page;

// Fetch total number of records
$total_records_query = "SELECT COUNT(*) AS total FROM dbProj_catering";
$total_records_stmt = $pdo->query($total_records_query);
$total_records = $total_records_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Calculate total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Ensure page is within valid range
$page = max(min($page, $total_pages), 1);

// Fetch data for the current page
$query = "SELECT * FROM dbProj_catering LIMIT $start_from, $records_per_page";
$stmt = $pdo->query($query);
$cateringItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>Manage Catering Menu</h2>
        <!-- Form to add a new catering item -->
        <h3>Add Catering Item</h3>
        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea><br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required><br>
            <button type="submit" name="add_catering">Add Item</button>
        </form>

        <!-- Displaying the catering menu -->
        <h3>Catering Menu</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($cateringItems as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['description']; ?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td>
                        <!-- Form to edit a catering item -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="catering_id" value="<?php echo $item['id']; ?>">
                            <input type="text" name="name" value="<?php echo $item['name']; ?>">
                            <textarea name="description"><?php echo $item['description']; ?></textarea>
                            <input type="number" name="price" value="<?php echo $item['price']; ?>">
                            <button type="submit" name="edit_catering">Edit</button>
                        </form>
                        <!-- Form to delete a catering item -->
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="catering_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="delete_catering">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination links -->
        <div class='pagination'>
            <?php if ($page > 1) : ?>
                <a href='?page=<?php echo ($page - 1); ?>'>Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <a href='?page=<?php echo $i; ?>'><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($page < $total_pages) : ?>
                <a href='?page=<?php echo ($page + 1); ?>'>Next</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
include 'includes/footer';
?>