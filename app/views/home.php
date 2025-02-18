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
        <div class="banner">

            <img src="./public/images/banner.jpg" alt="" srcset="">
            <div class="banner-content">
                <h1>TRAVEL NEPAL</h1>
            </div>
        </div>


        <div class="package">
            <h2>Popular Packages</h2>
            <div class="package-container">
                <?php if (!empty($packages)): ?>
                    <?php foreach (array_slice($packages, 0, 4) as $package): ?>
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

        <section class="why-choose-us">
            <h2>Why choose Us?</h2>
            <p class="subheading">Our services have been trusted by world travelers.</p>
            <div class="card-container">
                <div class="card">
                    <div class="icon">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                    <h3>Best Service</h3>
                    <p>Our service is reliable and convenient, our service is quality.</p>
                </div>
                <div class="card">
                    <div class="icon">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
                    <h3>Price Guarantee</h3>
                    <p>Enjoy competitive rates with no hidden fees. We ensure you get the best value for your money on every trip.</p>
                </div>
                <div class="card">
                    <div class="icon">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                    <h3>Experienced Guide</h3>
                    <p>Our professional guides have extensive local knowledge, ensuring a safe, informative, and unforgettable journey.</p>
                </div>
            </div>
        </section>

        
    </div>
</body>