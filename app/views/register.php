<?php
include './config/config.php'; // Include the configuration file to establish a database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Retrieve and sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone_no = htmlspecialchars(trim($_POST['phone_no']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm-password']));
    $userType = htmlspecialchars(trim($_POST['user_type']));

    // Validate inputs
    if (empty($username) || empty($email) || empty($phone_no) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the SQL statement
        $stmt = $mysqli->prepare("INSERT INTO users (username, email,phone_no, password, user_type) VALUES (?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param('sssss', $username, $email, $phone_no , $hashedPassword, $userType);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful!";
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
        }

        // Close the statement
        $stmt->close();
    }

    // Redirect to clear POST data
    header("Location: /travel/register");
    exit();
}
?>




<body>
<div class="registration-container">
    <div class="registration-left-column">
        <h1>Welcome</h1>
        <p>Start your adventure with us today</p>
    </div>
    <div class="registration-right-column">
        <h2>Create an Account</h2>
        
        <!-- Notification messages -->
        <div class="notification">
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php elseif (isset($_SESSION['success'])): ?>
                <p class="success"><?= htmlspecialchars($_SESSION['success']) ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
        </div>

        <form id="signup-form" method="POST">
        <div class="form-group">
            <label for="username">Full Name:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group half">
                <label for="phone_no">Phone Number:</label>
                <input type="number" id="phone_no" name="phone_no">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
        </div>

        <div class="form-group">
            <label for="user-type">Register as:</label>
            <select id="user-type" name="user_type">
                <option value="tourist">Tourist</option>
                <option value="guide">Guide</option>
            </select>
        </div>

        <button type="submit" class="form-button" name="register">Register</button>
    </form>
        
        <p class="form-footer">Already have an account? <a href="/travel/login">Sign in</a></p>
        <p class="form-footer"><a href="/travel/register/agency">Sign up for an agency?</a></p>
    </div>
</div>

</body>

