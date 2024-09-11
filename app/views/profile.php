<?php
// Start the session

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
}

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Only allow non-admin users to update their profile details
    if ($_SESSION['email'] !== $adminEmail) {
        // Handle profile picture update
        if (isset($_FILES['profile_image'])) {
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/travel/public/images/profile_images/";
            $targetFile = $targetDir . $userId . '.png'; // Save as user_id.png

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                $_SESSION['success'] = "Profile picture updated successfully.";
                
            } else {
                $_SESSION['error'] = "Failed to upload profile picture.";
            }
        }

        // Handle user details update
        if (isset($_POST['update_details'])) {
            $name = htmlspecialchars(trim($_POST['name']));
            $email = htmlspecialchars(trim($_POST['email']));

            $updateQuery = "UPDATE users SET username='$name', email='$email' WHERE id='$userId'";
            if (mysqli_query($mysqli, $updateQuery)) {
                $_SESSION['success'] = "Details updated successfully.";
                $_SESSION['email'] = $email; // Update session email
            } else {
                $_SESSION['error'] = "Failed to update details.";
            }
            header('Location: /travel/profile'); // Redirect to profile page
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
            $profileImage = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath) ? $profileImagePath : $defaultImage;
            ?>
            <div class="pic-img">
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Picture" class="profile-pic-large">

                <form method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_image" accept="image/*" <?php if ($_SESSION['email'] === $adminEmail) echo 'disabled'; ?>>
                    <button type="submit" name="update_image" <?php if ($_SESSION['email'] === $adminEmail) echo 'disabled'; ?>>Update Picture</button>
                </form>
            </div>
        </div>

        <div class="profile-details-section">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

            <form method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>" <?php if ($_SESSION['email'] === $adminEmail) echo 'disabled'; ?>>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" <?php if ($_SESSION['email'] === $adminEmail) echo 'disabled'; ?>>
                </div>
                <button type="submit" name="update_details" <?php if ($_SESSION['email'] === $adminEmail) echo 'disabled'; ?>>Update Details</button>
            </form>
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
