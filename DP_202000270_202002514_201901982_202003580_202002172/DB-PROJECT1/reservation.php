<?php
include 'config.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $numberOfAudiences = $_POST['number_of_audiences'];
    $searchTerm = $_POST['search_term'];

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
    <section>
        <h2>Reserve a Hall</h2>
        <form method="POST" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required><br>
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required><br>
            <label for="number_of_audiences">Number of Audiences:</label>
            <input type="number" id="number_of_audiences" name="number_of_audiences" required><br>
            <label for="search_term">Search (Hall Name or Description):</label>
            <input type="text" id="search_term" name="search_term" required><br>
            <button type="submit">Search</button>
        </form>
    </section>

    <?php if (isset($availableHalls)): ?>
        <section>
            <h2>Available Halls</h2>
            <?php if (count($availableHalls) > 0): ?>
                <table>
                    <tr>
                        <th>Hall Name</th>
                        <th>Capacity</th>
                        <th>Availability</th>
                        <th>Description</th>
                        <th>Rental Cost (per hour)</th>
                        <th>Total Cost</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($availableHalls as $hall): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($hall['hall_name']); ?></td>
                            <td><?php echo htmlspecialchars($hall['capacity']); ?></td>
                            <td><?php echo $hall['available'] ? 'Available' : 'Not Available'; ?></td>
                            <td><?php echo htmlspecialchars($hall['description']); ?></td>
                            <td><?php echo htmlspecialchars($hall['rental_cost']); ?></td>
                            <td>
                                <?php
                                $startDate = new DateTime($_POST['start_date']);
                                $endDate = new DateTime($_POST['end_date']);
                                $interval = $startDate->diff($endDate);
                                $days = $interval->days + 1;
                                $totalCost = $days * 24 * $hall['rental_cost'];
                                echo number_format($totalCost, 2);
                                ?>
                            </td>
                            <td>
                                <form method="POST" action="book_event.php">
                                    <input type="hidden" name="hall_id" value="<?php echo $hall['id']; ?>">
                                    <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($_POST['start_date']); ?>">
                                    <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($_POST['end_date']); ?>">
                                    <input type="hidden" name="number_of_audiences" value="<?php echo htmlspecialchars($_POST['number_of_audiences']); ?>">
                                    <input type="hidden" name="total_cost" value="<?php echo $totalCost; ?>">
                                    <button type="submit">Book</button>
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
