<?php
include './config/config.php';
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

// Get the package ID from the URL
$packageId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($packageId > 0) {
    $stmt = $conn->prepare("SELECT * FROM packages WHERE package_id = ?");
    $stmt->execute([$packageId]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($package): ?>
        <div class="package-detail-container">
            <div class="package-image">
                <img src="/travel/public/images/packages/<?= htmlspecialchars($package['image']); ?>" alt="<?= htmlspecialchars($package['name']); ?>">
            </div>
            <div class="package-info">
                <h1 class="package-title"><?= htmlspecialchars($package['name']); ?></h1>
                <p class="package-description"><?= htmlspecialchars($package['description']); ?></p>
                <p class="package-price">Price: Rs. <?= htmlspecialchars($package['price']); ?></p>
            </div>
        </div>
    <?php else: ?>
        <p>Package not found.</p>
    <?php endif;
} else {
    echo "<p>Invalid package ID.</p>";
}
?>
