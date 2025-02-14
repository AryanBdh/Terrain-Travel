<?php

// Include database connection
include './config/config.php'; // Include the configuration file

// Hardcoded admin credentials
$adminEmail = 'admin@gmail.com'; // Replace with your hardcoded admin email
$adminUsername = 'Admin'; // Replace with your hardcoded admin username


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /travel/login'); // Redirect to login if not logged in
    exit();
}

// Check if the logged-in user is the admin
if ($_SESSION['email'] === $adminEmail) {
    // Manually set user details for the admin account
    $user = [
        'username' => $adminUsername,
        'email' => $adminEmail
    ];
    // Assign a unique identifier for the admin
    $userId = 'admin';
    $userType = 'admin';
} else {
    // Fetch user details from the database
    $userId = $_SESSION['user_id'];

    // Ensure the connection is established
    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    $query = "SELECT * FROM users WHERE id = '$userId'";
    $result = mysqli_query($mysqli, $query);

    // Check for query errors
    if (!$result) {
        die("Error fetching user data: " . mysqli_error($mysqli));
    }

    $user = mysqli_fetch_assoc($result);

    // Ensure user data is fetched successfully
    if (!$user) {
        die("User not found.");
    }

    $userType = $user['user_type'];
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    // Only allow non-admin users to update their profile details
    if ($_SESSION['email'] !== $adminEmail) {
        // Handle profile picture update
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            // Directory to store profile images
            $targetDir = "/travel/public/images/profile_images/";
            $targetFile = $targetDir . $userId . '.png'; // Save as user_id.png

            // Ensure directory exists and is writable
            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $targetDir)) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . $targetDir, 0777, true);
            }

            // Move uploaded file to the destination folder
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $targetFile)) {
                // Update the profile image path in the database (store the relative path)
                $updateQuery = "UPDATE users SET profile_image='$targetFile' WHERE id='$userId'";

                if (mysqli_query($mysqli, $updateQuery)) {
                    $_SESSION['success'] = "Profile picture updated successfully.";
                    $_SESSION['profile_image'] = $targetFile; // Update session with the new profile image path

                    // Redirect after updating session to reload the page and reflect new image
                    header('Location: /travel/profile');
                    exit();
                } else {
                    $_SESSION['error'] = "Failed to update profile picture in the database.";
                }
            } else {
                $_SESSION['error'] = "Failed to upload profile picture.";
            }
        } elseif (isset($_FILES['profile_image'])) {
            // Handle any file upload errors
            $_SESSION['error'] = "Error uploading file: " . $_FILES['profile_image']['error'];
        }

        // Handle user details update
        if (isset($_POST['update_details'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));
            $userType = $_SESSION['user_type'];
            $userId = $_SESSION['user_id'];

            // Update user details in the users table
            $updateUserQuery = "UPDATE users SET username='$name', email='$email' WHERE id='$userId'";
            if (mysqli_query($mysqli, $updateUserQuery)) {
                $_SESSION['success'] = "Details updated successfully.";
                $_SESSION['email'] = $email; // Update session email

                // Handle guide-specific updates
                if ($userType === 'guide') {
                    $speciality = htmlspecialchars(trim($_POST['speciality']));
                    $status = htmlspecialchars(trim($_POST['status']));

                    // Check if the guide exists in the guide table
                    $checkGuideQuery = "SELECT * FROM guide WHERE user_id='$userId'";
                    $guideResult = mysqli_query($mysqli, $checkGuideQuery);

                    if (!$guideResult) {
                        $_SESSION['error'] = "Error checking guide details: " . mysqli_error($mysqli);
                        error_log("Guide Check Error: " . mysqli_error($mysqli)); // Debugging
                    } else if (mysqli_num_rows($guideResult) > 0) {
                        // Update existing guide details
                        $updateGuideQuery = "
                            UPDATE guide 
                            SET guide_name='$name', email='$email', speciality='$speciality', status='$status' 
                            WHERE user_id='$userId'";
                        if (!mysqli_query($mysqli, $updateGuideQuery)) {
                            $_SESSION['error'] = "Failed to update guide-specific details.";
                            error_log("Guide Update Error: " . mysqli_error($mysqli)); // Debugging
                        }
                    } else {
                        // Insert new guide details if not existing
                        $insertGuideQuery = "
                            INSERT INTO guide (user_id, guide_name, email, speciality, status) 
                            VALUES ('$userId', '$name', '$email', '$speciality', '$status')";
                        if (!mysqli_query($mysqli, $insertGuideQuery)) {
                            $_SESSION['error'] = "Failed to add guide-specific details.";
                            error_log("Guide Insert Error: " . mysqli_error($mysqli)); // Debugging
                        }
                    }
                }
            } else {
                $_SESSION['error'] = "Failed to update user details.";
                error_log("User Update Error: " . mysqli_error($mysqli)); // Debugging
            }

            // Redirect to profile page after processing
            header('Location: /travel/profile');
            exit();
        }

    } else {
        $_SESSION['error'] = "Admin details cannot be updated.";
    }
}
?>

<body>
    <div class="profile-container">
        <div class="profile-picture-section">
            <?php
            // Use the userId variable to construct the profile image path
            $profileImagePath = '/travel/public/images/profile_images/' . $userId . '.png';
            $defaultImage = '/travel/public/images/default.png';

            // Force image refresh by appending the current timestamp
            $profileImage = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath) ? $profileImagePath . '?' . time() : $defaultImage;
            ?>
            <div class="pic-img">
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Picture"
                    class="profile-pic-large">

                <form method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_image" accept="image/*" <?php if ($_SESSION['email'] === $adminEmail)
                        echo 'disabled'; ?>>
                    <button type="submit" name="update_image" <?php if ($_SESSION['email'] === $adminEmail)
                        echo 'disabled'; ?>>Update Picture</button>
                </form>
            </div>
        </div>

        <div class="profile-details-section">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

            <?php if (isset($userType) && $userType === 'tourist'): ?>
                <h3>Your Bookings</h3>
                <?php
                // Fetch the tourist_id for the logged-in user
                $userId = $_SESSION['user_id'];
                $touristIdQuery = "SELECT tourist_id FROM tourists WHERE user_id = ?";
                $touristIdStmt = $mysqli->prepare($touristIdQuery);
                $touristIdStmt->bind_param("i", $userId);
                $touristIdStmt->execute();
                $touristIdResult = $touristIdStmt->get_result();

                if ($touristIdResult && $touristIdResult->num_rows > 0) {
                    $touristData = $touristIdResult->fetch_assoc();
                    $touristId = $touristData['tourist_id'];

                    // Fetch the bookings for the tourist
                    $bookingQuery = "
    SELECT b.booking_id, p.name AS package_name, b.booking_date, b.no_of_people, b.total_cost, g.guide_name, g.guide_phone
    FROM booking b
    INNER JOIN packages p ON b.package_id = p.package_id
    INNER JOIN guide g ON b.guide_id = g.guide_id
    WHERE b.tourist_id = ?";
                    $bookingsStmt = $mysqli->prepare($bookingQuery);
                    if ($bookingsStmt) {
                        $bookingsStmt->bind_param("i", $touristId);
                        $bookingsStmt->execute();
                        $bookings = $bookingsStmt->get_result();
                    } else {
                        die("Booking Query Error: " . $mysqli->error);
                    }


                    if ($bookings && $bookings->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Date</th>
                                    <th>People</th>
                                    <th>Total Cost</th>
                                    <th>Guide Name</th>
                                    <th>Guide Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($booking = $bookings->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($booking['package_name']); ?></td>
                                        <td><?= htmlspecialchars($booking['booking_date']); ?></td>
                                        <td><?= htmlspecialchars($booking['no_of_people']); ?></td>
                                        <td><?= htmlspecialchars($booking['total_cost']); ?></td>
                                        <td><?= htmlspecialchars($booking['guide_name']); ?></td>
                                        <td><?= htmlspecialchars($booking['guide_phone']); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="booking_id"
                                                    value="<?= htmlspecialchars($booking['booking_id']); ?>">
                                                <button type="submit" name="cancel_booking" onclick="return confirm('Do you want to cancel your booking?');">Cancel</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No bookings found for this user.</p>
                    <?php endif; ?>
                <?php } else {
                    echo "<p>Tourist details not found for this user.</p>";
                }
                ?>
            <?php elseif (isset($userType) && $userType === 'guide'): ?>
                <h3>Your Assigned Packages</h3>
                <?php
                $guideQuery = "SELECT guide_id FROM guide WHERE user_id = ?";
                $guideStmt = $mysqli->prepare($guideQuery);
                $guideStmt->bind_param("i", $userId);
                $guideStmt->execute();
                $guideResult = $guideStmt->get_result();

                if ($guideResult && $guideResult->num_rows > 0) {
                    $guideData = $guideResult->fetch_assoc();
                    $guideId = $guideData['guide_id'];

                    // Fetch the assigned packages for the guide
                    $assignedPackagesQuery = "
                SELECT p.name AS package_name,
                b.booking_date,
                t.tourist_name,
                t.phone_no AS tourist_phone,
                 b.no_of_people,
                  p.category,
                   p.duration
                FROM booking b
                JOIN packages p ON b.package_id = p.package_id
                JOIN tourists t ON b.tourist_id = t.tourist_id
                WHERE b.guide_id = ?
            ";
                    $assignedPackagesStmt = $mysqli->prepare($assignedPackagesQuery);
                    $assignedPackagesStmt->bind_param("i", $guideId);
                    $assignedPackagesStmt->execute();
                    $assignedPackages = $assignedPackagesStmt->get_result();

                    if ($assignedPackages && $assignedPackages->num_rows > 0): ?>
                         <table>
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Date</th>
                                    <th>Tourist name</th>
                                    <th>Phone</th>
                                    <th>No of People</th>
                                    <th>Category</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($assignedPackage = $assignedPackages->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($assignedPackage['package_name']); ?></td>
                                        <td><?= htmlspecialchars($assignedPackage['booking_date']); ?></td>
                                        <td><?= htmlspecialchars($assignedPackage['tourist_name']); ?></td>
                                        <td><?= htmlspecialchars($assignedPackage['tourist_phone']); ?></td>
                                        <td><?= htmlspecialchars($assignedPackage['no_of_people']); ?></td>
                                        <td><?= htmlspecialchars($assignedPackage['category']); ?></td>
                                        <td><?= htmlspecialchars($assignedPackage['duration']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No bookings assigned.</p>
                    <?php endif; ?>
                <?php } else {
                    echo "<p>Package details not found for this user.</p>";
                }
                ?>
            <?php endif; ?>
        </div>


        <?php if (isset($_SESSION['success'])): ?>
            <p class="success-message"><?php echo htmlspecialchars($_SESSION['success']); ?></p>
            <?php unset($_SESSION['success']); // Clear the message after displaying it ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
            <?php unset($_SESSION['error']); // Clear the message after displaying it ?>
        <?php endif; ?>
    </div>
</body>