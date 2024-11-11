<?php
include './config/config.php';
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

$stmt = $conn->query("SELECT * FROM packages");
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="home-container">
    <div class="package">
            <h2>Our Packages</h2>
            <div class="package-container">
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $package): ?>
                        <?php
                        $packageId = isset($package['package_id']) ? htmlspecialchars($package['package_id']) : null;
                        $packageImage = isset($package['image']) ? htmlspecialchars($package['image']) : 'default.jpg';
                        $packageName = htmlspecialchars($package['name']);
                        ?>
                        <?php if ($packageId): ?>
                            <div class="package-card">
                                <a href="/travel/package/package-detail?id=<?= $packageId; ?>">
                                    <img src="/travel/public/images/packages/<?= $packageImage; ?>" alt="<?= $packageName; ?>">
                                </a>
                                <div class="package-detail">
                                    <h3><?= $packageName; ?></h3>
                                </div>
                            </div>
                        <?php else: ?>
                            <p>Package ID is missing.</p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>