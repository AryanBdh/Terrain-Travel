<?php
include './config/config.php'; // Include the configuration file


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Retrieve and sanitize input
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validate inputs
    if (empty($email) || empty($password)) {
        $loginError = "Email and password are required.";
    } else {
        // Check credentials in the database
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                // Redirect to home page
                header("Location: /travel/index.php?page=home");
                exit;
            } else {
                $loginError = "Invalid email or password.";
            }
        } else {
            $loginError = "Invalid email or password.";
        }
        $stmt->close();
        $conn->close();
    }
}
?>


<body>

    <div class="signin-container"> <!-- Ensure there's only one signin-container -->
        <div class="left-column">
            <h1>Travelista Tours</h1>
            <p>Discover the world, one journey at a time</p>
        </div>
        <div class="right-column">
            <h2>Welcome</h2>
            <p>Login with Email</p>
            <form id="signin-form" method="POST" action="login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" >
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" >
                </div>
                <a href="#" class="forgot-password">Forgot your password?</a>
                <button type="submit" name="login">LOGIN</button>
            </form>
            <p class="register-link">Don't have an account? <a href="/travel/register">Register Now</a></p>
            <?php if (isset($loginError)): ?>
                <p class="error-message"><?php echo htmlspecialchars($loginError); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <script src="assets/js/signin.js"></script>
</body>