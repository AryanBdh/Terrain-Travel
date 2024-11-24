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
            <!-- Package Details -->
            <div class="package-image">
                <img src="/travel/public/images/packages/<?= htmlspecialchars($package['image']); ?>"
                    alt="<?= htmlspecialchars($package['name']); ?>">
            </div>
            <div class="package-info">
                <h1 class="package-title"><?= htmlspecialchars($package['name']); ?></h1>
                <p class="package-description"><?= htmlspecialchars($package['description']); ?></p>
                <p class="package-price">Price: Rs. <?= htmlspecialchars($package['price']); ?></p>
            </div>

            <!-- Booking Button -->
            <div class="package-booking">
                <h2>Book Now</h2>
                <button id="bookNowButton" class="btn-book-now">Book Now</button>
            </div>

            <!-- Booking Form -->
            <div id="bookingFormContainer" class="booking-form-container" style="display: none;">
                <form id="booking-form" method="POST" action="/travel/book-package">
                    <!-- Left Section: User Information -->
                    <div class="form-section left">
                        <h3>User Information</h3>
                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Phone Number:</label>
                            <input type="number" id="phone_no" name="phone_no" required>
                        </div>
                    </div>

                    <!-- Right Section: Travel Information -->
                    <div class="form-section right">
                        <h3>Travel Information</h3>
                        <div class="form-group">
                            <label for="travel_date">Travel Date:</label>
                            <input type="date" id="travel_date" name="travel_date" required>
                        </div>
                        <div class="form-group">
                            <label for="num_people">Number of People:</label>
                            <input type="number" id="num_people" name="num_people" required>
                        </div>
                        <div class="form-group">
                            <label for="special_requests">Special Requests (optional):</label>
                            <textarea id="special_requests" name="special_requests" rows="4"></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group submit">
                        <button type="submit" class="btn-submit">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- JavaScript for toggling the form -->
        <script>
            document.getElementById('bookNowButton').addEventListener('click', function () {
                const formContainer = document.getElementById('bookingFormContainer');
                if (formContainer.style.display === 'none' || formContainer.style.display === '') {
                    formContainer.style.display = 'block';
                } else {
                    formContainer.style.display = 'none';
                }
            });
        </script>
    <?php else: ?>
        <p>Package not found.</p>
    <?php endif;
} else {
    echo "<p>Invalid package ID.</p>";
}
?>
