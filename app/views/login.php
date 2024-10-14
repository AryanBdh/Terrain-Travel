<?php
include './config/config.php'; // Include the configuration file

// Hardcoded admin credentials
define('ADMIN_EMAIL', 'admin@gmail.com'); // Replace with your hardcoded admin email
define('ADMIN_PASSWORD', 'admin123'); // Replace with your hardcoded admin password

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    // Retrieve and sanitize input
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validate inputs
    if (empty($email) || empty($password)) {
        $_SESSION['loginError'] = "Email and password are required.";
    } else {
        // Check if credentials match hardcoded admin credentials
        if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
            // Admin credentials are correct, set session for admin
            $_SESSION['is_admin'] = true;
            $_SESSION['user_id'] = 'admin'; // Use a unique identifier for admin
            $_SESSION['email'] = $email;
            // Redirect to admin dashboard
            header("Location: /travel/admin/dashboard");
            exit;
        } else {
            // Check credentials in the database for regular users
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
                    // Password is correct, set session for regular user
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $email;
                    // Redirect to home page
                    header("Location: /travel/home");
                    exit;
                } else {
                    $_SESSION['loginError'] = "Invalid email or password.";
                }
            } else {
                $_SESSION['loginError'] = "Invalid email or password.";
            }
            $stmt->close();
            $conn->close();
        }
    }

    // Redirect to clear POST data and display messages only once
    header("Location: /travel/login");
    exit;
}
?>


<body>
    <div class="signin-container">
        <div class="left-column">
            <h1>    </h1>
            <p>Discover the world, one journey at a time</p>
        </div>
        <div class="right-column">
            <h2>Welcome</h2>
            <p>Login with Email</p>
            <form id="signin-form" method="POST" action="login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                </div>
                <a href="#" class="forgot-password">Forgot your password?</a>
                <button type="submit" name="login">LOGIN</button>
            </form>
            <p class="register-link">Don't have an account? <a href="/travel/register">Register Now</a></p>
            <?php if (isset($_SESSION['loginError'])): ?>
                <p class="error-message"><?php echo htmlspecialchars($_SESSION['loginError']); ?></p>
                <?php unset($_SESSION['loginError']); // Clear the error message ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="assets/js/signin.js"></script>
</body>

