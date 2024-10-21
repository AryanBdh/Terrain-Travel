<body>

<div class="package">
            <h2>Popular Packages</h2>
            <div class="package-container">
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $package): ?>
                        <div class="package-card">
                            <img src="/travel/public/images/packages/<?php echo htmlspecialchars($package->image); ?>"
                                alt="Package Image">
                            <h3><?php echo htmlspecialchars($package->name); ?></h3>
                            <p><?php echo htmlspecialchars($package->description); ?></p>
                            <p><strong>Price:</strong> $<?php echo htmlspecialchars($package->price); ?></p>
                            <a href="/travel/packages/details?id=<?php echo $package->id; ?>" class="btn">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

</body>