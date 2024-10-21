<?php


include './config/config.php';
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
                    <?php foreach ($packages as $package): ?>
                        <div class="package-card">
                            <?php
                            $imagePath = "/travel/public/images/packages/" . htmlspecialchars($package->image);
                            echo "<p>Image path: $imagePath</p>";  // Debug: Print the image path
                            ?>
                            <img src="<?php echo $imagePath; ?>" alt="Package Image">
                            <h3><?php echo htmlspecialchars($package->name); ?></h3>
                            <p><?php echo htmlspecialchars($package->description); ?></p>
                            <p><strong>Price:</strong> $<?php echo htmlspecialchars($package->price); ?></p>
                            <a href="/travel/packages/details?id=<?php echo $package->id; ?>" class="btn">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

    </div>
</body>