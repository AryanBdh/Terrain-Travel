<?php
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();



// Retrieve the package ID from the query string
$packageId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['travel_date'], $_POST['no_of_people'], $_POST['package_id'])) {
    // Sanitize and validate input
    $packageId = intval($_POST['package_id']);
    $travelDate = $_POST['travel_date'];
    $numPeople = intval($_POST['no_of_people']);

    if (empty($travelDate) || $numPeople <= 0) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: /travel/package/package-detail?id=$packageId");
        exit;
    }

    // Retrieve the logged-in user's tourist ID
    $touristIdQuery = "SELECT tourist_id FROM users WHERE id = ?";
    $touristIdStmt = $conn->prepare($touristIdQuery);
    $touristIdStmt->execute([$_SESSION['user_id']]);
    $touristId = $touristIdStmt->fetchColumn();

    if (!$touristId) {
        $_SESSION['error'] = "User is not registered as a tourist.";
        header("Location: /travel/login");
        exit;
    }

    $userId = $_SESSION['user_id'];

    $bookingCheckQuery = "SELECT * FROM booking WHERE user_id = ? AND booking_date = ?";
    $bookingCheckStmt = $conn->prepare($bookingCheckQuery);
    $bookingCheckStmt->execute([$userId, $travelDate]);

    if ($bookingCheckStmt->rowCount() > 0) {
        $_SESSION['error'] = "You already have a booking on the selected date.";
        header("Location: /travel/package/package-detail?id=$packageId");
        exit;
    }

    // Check if the user has already booked the same package on the selected date
    $packageCheckQuery = "SELECT * FROM booking WHERE user_id = ? AND package_id = ? AND booking_date = ?";
    $packageCheckStmt = $conn->prepare($packageCheckQuery);
    $packageCheckStmt->execute([$userId, $packageId, $travelDate]);

    if ($packageCheckStmt->rowCount() > 0) {
        $_SESSION['error'] = "You have already booked this package on the selected date.";
        header("Location: /travel/package/package-detail?id=$packageId");
        exit;
    }

    // Get the package details to calculate total cost
    $packageQuery = "SELECT * FROM packages WHERE package_id = ?";
    $packageStmt = $conn->prepare($packageQuery);
    $packageStmt->execute([$packageId]);
    $package = $packageStmt->fetch(PDO::FETCH_ASSOC);

    if (!$package) {
        $_SESSION['error'] = "Package not found.";
        header("Location: /travel/packages");
        exit;
    }

    // Calculate the total cost
    $packagePrice = $package['price'];
    $totalCost = $packagePrice * $numPeople;

    // Get the guide assigned to the package
    $guideQuery = "SELECT guide_id FROM packages WHERE package_id = ?";
    $guideStmt = $conn->prepare($guideQuery);
    $guideStmt->execute([$packageId]);
    $guide = $guideStmt->fetch(PDO::FETCH_ASSOC);
    
    if ($guide && !empty($guide['guide_id'])) {
        $guideId = $guide['guide_id'];
    } else {
        $_SESSION['error'] = "No guide assigned to this package.";
        header("Location: /travel/package/package-detail?id=$packageId");
        exit;
    }

    // Insert booking details into the booking table
    $bookingQuery = "INSERT INTO booking (tourist_id, package_id, guide_id, booking_date,no_of_people, total_cost, user_id) VALUES (?, ?, ?, ?, ?,?, ?)";
    $bookingStmt = $conn->prepare($bookingQuery);
    $bookingStmt->execute([$touristId, $packageId, $guideId, $travelDate, $numPeople, $totalCost, $userId]);

    $_SESSION['message'] = "Booking successfully confirmed.";
    header("Location: /travel/package/package-detail?id=$packageId");  // Redirect to the package page with success message
    exit;
}

// Fetch package details to display
if ($packageId > 0) {
    $stmt = $conn->prepare("SELECT * FROM packages WHERE package_id = ?");
    $stmt->execute([$packageId]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$package) {
        $_SESSION['error'] = "Invalid package ID.";
        header("Location: /travel/packages");
        exit;
    }
} else {
    $_SESSION['error'] = "Invalid package ID.";
    header("Location: /travel/packages");
    exit;
}
?>




<?php if ($package): ?>

    <div class="package-detail-container">
        <div class="package-image">
            <img src="/travel/public/images/packages/<?= htmlspecialchars($package['image']); ?>"
                alt="<?= htmlspecialchars($package['name']); ?>">
        </div>
        <div class="newSection">
            <div class="package-info">
                <h1 class="package-title"><?= htmlspecialchars($package['name']); ?></h1>
                <p class="package-description"><?= nl2br(htmlspecialchars($package['description'])); ?></p>
                <p class="package-price">Price: Rs. <?= htmlspecialchars($package['price']) . ' per person'; ?></p>
            </div>

        </div>

        <!-- Booking Button -->
        <div class="package-booking" style="text-align: center;">
            <h2>Book Now</h2>
            <button id="bookNowButton" class="btn-book-now">Book Now</button>
        </div>
        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-message"><?= $_SESSION['error'];
            unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION['message'])): ?>
            <p class="success-message" style="text-align:center;"><?= $_SESSION['message'];
            unset($_SESSION['message']); ?></p>
        <?php endif; ?>
        <!-- Booking Form -->
        <div id="bookingFormContainer" class="booking-form-container" style="display: none;">
            <form id="booking-form" method="POST" action="" class="bookForm">
                <input type="hidden" id="package_id" name="package_id" value="<?= htmlspecialchars($packageId); ?>">
                <div class="form-section right">
                    <h3>Travel Information</h3>
                    <div class="form-group">
                        <label for="travel_date">Travel Date:</label>
                        <input type="date" id="travel_date" name="travel_date" min="<?= date('Y-m-d'); ?>">
                        <span id="dateError" class="error" style="color: red;"></span>
                    </div>
                    <div class="form-group">
                        <label for="no_of_people">Number of People:</label>
                        <input type="number" id="no_of_people" name="no_of_people" min="1">
                        <span id="peopleError" class="error" style="color: red;"></span>
                    </div>
                </div>

                <div class="form-group submit">
                    <button type="submit" class="btn-submit">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bookNowButton = document.getElementById('bookNowButton');
            const bookingFormPanel = document.getElementById('bookingFormContainer');
            const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;

            bookNowButton.addEventListener('click', function () {
                if (isLoggedIn) {
                    bookingFormPanel.style.display = 'block';
                } else {
                    window.location.href = '/travel/login';
                }
            });

            // Form validation (client-side)
            document.getElementById('booking-form').addEventListener('submit', function (e) {
                e.preventDefault();

                document.getElementById('dateError').textContent = '';
                document.getElementById('peopleError').textContent = '';


                let date = document.getElementById('travel_date').value.trim();
                let people = document.getElementById('no_of_people').value.trim();

                let isValid = true;

                let today = new Date();
                today.setHours(0, 0, 0, 0);
                let selectedDate = new Date(date);

                if (!date) {
                    document.getElementById('dateError').textContent = 'Travel date is required';
                    isValid = false;
                } else if (selectedDate < today) {
                    document.getElementById('dateError').textContent = 'Travel date cannot be in the past';
                    isValid = false;
                }

                if (!people || parseInt(people) < 1) {
                    document.getElementById('peopleError').textContent = 'Number of people is required';
                    isValid = false;
                }

                if (isValid) {
                    document.getElementById('booking-form').submit();
                }

            })
        });
    </script>
<?php else: ?>
    <p>Package not found.</p>
<?php endif;

