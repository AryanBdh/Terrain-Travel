<?php
include './config/config.php'; // Include the configuration file to establish a database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Retrieve and sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm-password']));
    $userType = htmlspecialchars(trim($_POST['user_type']));

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param('ssss', $username, $email, $hashedPassword, $userType);

        // Execute the statement
        if ($stmt->execute()) {
            $success = "Registration successful!";
        } else {
            $error = "Registration failed. Please try again.";
        }

        // Close the statement
        $stmt->close();
    }
}
?>



<body>
    <div class="signin-container">
        <div class="left-column">
            <h1>Travelista Tours</h1>
            <p>Begin your journey with us today</p>
        </div>
        <div class="right-column">
            <h2>Create an Account</h2>
            <p>Join Travelista Tours for amazing travel experiences</p>

            <!-- Display error or success message -->
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php elseif (isset($success)): ?>
                <p class="success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>

            <form id="signup-form" method="POST">
                <div class="form-group">
                    <label for="username">Full Name</label>
                    <input type="text" id="username" name="username">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                </div>
                <div class="form-group">
                    <label for="user_type">Register As:</label>
                    <select id="user_type" name="user_type">
                        <option value="tourist">Tourist</option>
                        <option value="guide">Guide</option>
                    </select>
                </div>
                
                <button type="submit" name="register">SIGN UP</button>
            </form>
            <p class="login-link">Already have an account? <a href="/travel/register">Sign In</a></p>
        </div>
    </div>
</body>
</html>
