<?php
include 'config.php';

// Fetch all halls from the database
$stmt = $pdo->query("SELECT * FROM dbProj_halls");
$halls = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'includes/language.php'; ?>
<?php include 'includes/header.php'; ?>
<main>
    <section>
        <h2>All Halls</h2>
        <table>
            <tr>
                <th>Hall Name</th>
                <th>Capacity</th>
                <th>Description</th>
                <th>Rental Cost (per hour)</th>
            </tr>
            <?php foreach ($halls as $hall): ?>
                <tr>
                    <td><?php echo htmlspecialchars($hall['hall_name']); ?></td>
                    <td><?php echo htmlspecialchars($hall['capacity']); ?></td>
                    <td><?php echo htmlspecialchars($hall['description']); ?></td>
                    <td><?php echo htmlspecialchars($hall['rental_cost']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <button onclick="location.href='halls.php'" type="button">Reserve a Hall</button>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
