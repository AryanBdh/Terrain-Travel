<body>

    <div class="package">
        <h2>Popular Packages</h2>

        <!-- <button class="carousel-btn prev-btn">&lt;</button>
        <button class="carousel-btn next-btn">&gt;</button> -->
        <div class="package-container">

            <?php
            foreach ($packages as $package): ?>
                <div class="package-card">
                    <img src="/uploads/<?php echo $package['image']; ?>" alt="Package Image" class="package-image">
                    <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                    <p><?php echo htmlspecialchars($package['description']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($package['price']); ?></p>
                    <a href="/travel/package?id=<?php echo $package['id']; ?>" class="btn">View Details</a>
                </div>
                
               <?php endforeach; ?>
            }
            ?>
        </div>
        <!-- Carousel Navigation Buttons -->

    </div>

</body>