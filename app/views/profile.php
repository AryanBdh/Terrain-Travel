<?php

// Include database connection
include './config/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /travel/login'); // Redirect to login if not logged in
    exit();
}

// Fetch user details from the database
$userId = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$userId'";
$result = mysqli_query($mysqli, $query);
$user = mysqli_fetch_assoc($result);

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle profile picture update
    if (isset($_FILES['profile_image'])) {
        $targetDir = "/public/images/profile_images/";
        $targetFile = $targetDir . $userId . '.png'; // Save as user_id.png

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $success = "Profile picture updated successfully.";
        } else {
            $error = "Failed to upload profile picture.";
        }
    }

    // Handle user details update
    if (isset($_POST['update_details'])) {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));

        $updateQuery = "UPDATE users SET username='$name', email='$email' WHERE id='$userId'";
        if (mysqli_query($mysqli, $updateQuery)) {
            $success = "Details updated successfully.";
            $_SESSION['email'] = $email; // Update session email
        } else {
            $error = "Failed to update details.";
        }
    }
}
?>


<body>
    <div class="profile-container">
        <div class="profile-picture-section">
            <?php
            $profileImagePath = '/public/images/profile_images/' . $userId . '.png';
            $defaultImage = '/public/images/default.png';
            $profileImage = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath) ? $profileImagePath : $defaultImage;
            ?>
            <div class="pic-img">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Picture" class="profile-pic-large">

            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_image" accept="image/*">
                <button type="submit" name="update_image">Update Picture</button>
            </form>
        </div>

        <div class="profile-details-section">
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>

            <form method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                </div>
                <button type="submit" name="update_details">Update Details</button>
            </form>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
    <?php elseif (isset($error)): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>

