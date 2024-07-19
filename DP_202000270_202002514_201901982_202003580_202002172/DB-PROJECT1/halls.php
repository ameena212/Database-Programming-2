<?php
// Including configuration file and starting session
include 'config.php';
session_start();

// Processing POST request to search for available halls
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $searchTerm = $_POST['search_term'];

    // Query to search for available halls based on search term, start date, and end date
    $stmt = $pdo->prepare("
        SELECT * FROM dbProj_halls 
        WHERE (hall_name LIKE :searchTerm OR description LIKE :searchTerm)
        AND id NOT IN (
            SELECT hall_id FROM dbProj_reservations 
            WHERE start_date <= :endDate AND end_date >= :startDate
        )
    ");
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bindParam(':searchTerm', $searchTerm);
    $stmt->bindParam(':startDate', $startDate);
    $stmt->bindParam(':endDate', $endDate);
    $stmt->execute();
    $availableHalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <!-- Hall reservation section -->
    <section>
        <h2>Reserve a Hall</h2>
        <form method="POST" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required><br>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required><br>
            <label for="search_term">Search (Hall Name or Description):</label>
            <input type="text" id="search_term" name="search_term" required><br>
            <button type="submit" name="search">Search</button>
        </form>
    </section>

    <!-- Display available halls if search is performed -->
    <?php if (isset($availableHalls)): ?>
        <section>
            <h2>Available Halls</h2>
            <?php if (count($availableHalls) > 0): ?>
                <table>
                    <tr>
                        <th>Hall Name</th>
                        <th>Capacity</th>
                        <th>Description</th>
                        <th>Rental Cost (per hour)</th>
                        <th>Select</th>
                    </tr>

                    <!-- Loop through available halls and display them in the table -->
                    <?php foreach ($availableHalls as $hall): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($hall['hall_name']); ?></td>
                            <td><?php echo htmlspecialchars($hall['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($hall['description']); ?></td>
                            <td><?php echo htmlspecialchars($hall['rental_cost']); ?></td>
                            <td>

                                <!-- Form to select a hall for reservation -->
                                <form method="POST" action="book_event.php">
                                    <input type="hidden" name="hall_id" value="<?php echo $hall['id']; ?>">
                                    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($_POST['start_date']); ?>">
                                    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date']); ?>">
                                    <button type="submit" name="select">Select</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>No available halls found for the given criteria. Please try different dates or search terms.</p>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
