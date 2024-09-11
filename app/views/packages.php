<body>

    <div class="package">
        <h2>Popular Packages</h2>

        <!-- <button class="carousel-btn prev-btn">&lt;</button>
        <button class="carousel-btn next-btn">&gt;</button> -->
        <div class="package-container">

            <?php
            $query = "SELECT * FROM packages";
            $result = mysqli_query($mysqli, $query);
            while ($package = mysqli_fetch_assoc($result)) {
                ?>
                <div class="package-card">
                    <div class="package-image">
                        <img src="./public/images/packages/<?= $package['image'] ?>"
                            alt="<?= htmlspecialchars($package['name']) ?>">
                        <div class="overlay">
                            <h3><?= htmlspecialchars($package['name']) ?></h3>
                            <a href="/travel/packages/<?= $package['id'] ?>" class="btn">See More</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <!-- Carousel Navigation Buttons -->

    </div>

</body>