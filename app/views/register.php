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
        $_SESSION['error'] = "All fields are .";
    } elseif ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Start a transaction to ensure data integrity
        $mysqli->begin_transaction();

        try {
            // Insert into the users table
            $stmt = $mysqli->prepare("INSERT INTO users (username, email, phone_no, password, user_type) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $username, $email, $phone_no, $hashedPassword, $userType);

            if (!$stmt->execute()) {
                throw new Exception("Failed to insert into users table.");
            }

            // Get the newly inserted user ID
            $userId = $stmt->insert_id;

            if ($userType === 'tourist') {
                // Insert into tourists table with ON DUPLICATE and get existing tourist_id
                $touristStmt = $mysqli->prepare("
                    INSERT INTO tourists (tourist_name, tourist_email, phone_no, user_id) 
                    VALUES (?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                    tourist_name = VALUES(tourist_name), 
                    phone_no = VALUES(phone_no), 
                    user_id = VALUES(user_id)
                ");
                $touristStmt->bind_param("sssi", $username, $email, $phone_no, $userId);
                $touristStmt->execute();

                // Get tourist_id either from new insert or existing record
                $touristId = $mysqli->insert_id;
                if ($touristId == 0) {
                    $existingTouristStmt = $mysqli->prepare("SELECT tourist_id FROM tourists WHERE tourist_email = ?");
                    $existingTouristStmt->bind_param("s", $email);
                    $existingTouristStmt->execute();
                    $existingTouristStmt->bind_result($touristId);
                    $existingTouristStmt->fetch();
                    $existingTouristStmt->close();
                }
                $touristStmt->close();

                // Update users table with correct tourist_id
                $updateUserStmt = $mysqli->prepare("UPDATE users SET tourist_id = ? WHERE id = ?");
                $updateUserStmt->bind_param("ii", $touristId, $userId);
                if (!$updateUserStmt->execute()) {
                    throw new Exception("Failed to update users table with tourist_id.");
                }
                $updateUserStmt->close();
            }


            // If user type is guide, insert into the guide table
            if ($userType === 'guide') {
                $speciality = htmlspecialchars($_POST['speciality']); // Capture guide's speciality
                $status = "Available"; // Default status for a new guide

                $guideStmt = $mysqli->prepare("INSERT INTO guide (user_id, guide_name, speciality, email, guide_phone, status) VALUES (?, ?, ?, ?, ?, ?)");
                $guideStmt->bind_param("isssss", $userId, $username, $speciality, $email, $phone_no, $status);

                if (!$guideStmt->execute()) {
                    throw new Exception("Failed to insert into guide table.");
                }

                $guideStmt->close();
            }

            // Commit the transaction
            $mysqli->commit();

            $_SESSION['success'] = "Registration successful!";
        } catch (Exception $e) {
            // Rollback transaction in case of error
            $mysqli->rollback();
            $_SESSION['error'] = "Registration failed: " . $e->getMessage();
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
                    <input type="text" id="username" name="username">
                    <span id="nameError" class="error" style="color:red;"></span>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email">
                        <span id="emailError" class="error" style="color:red;"></span>

                    </div>
                    <div class="form-group half">
                        <label for="phone_no">Phone Number:</label>
                        <input type="number" id="phone_no" name="phone_no" maxlength="10" oninput="validatePhone(this)">
                        <span id="phoneError" class="error" style="color:red;"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password">
                    <span id="passwordError" class="error" style="color:red;"></span>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password">
                    <span id="confirmPasswordError" class="error" style="color:red;"></span>
                </div>

                <div class="form-group">
                    <label for="user-type">Register as:</label>
                    <select id="user-type" name="user_type" onchange="toggleSpecialityField()">
                        <option value="tourist">Tourist</option>
                        <option value="guide">Guide</option>
                    </select>
                </div>


                <div class="form-group" id="speciality-container" style="display: none;">
                    <label for="speciality">Speciality:</label>
                    <select id="speciality" name="speciality">
                        <option value="" disabled selected>Select Speciality</option>
                        <option value="Historical Tours">Historical Tours</option>
                        <option value="Adventure Tours">Adventure Tours</option>
                        <option value="Cultural Tours">Cultural Tours</option>
                        <option value="Wildlife Tours">Wildlife Tours</option>
                    </select>

                </div>
                </script>

                <button type="submit" class="form-button" name="register" id="register-submit">Register</button>
            </form>

            <p class="form-footer">Already have an account? <a href="/travel/login">Sign in</a></p>
        </div>
    </div>

</body>

<script>
    function validatePhone(input) {
        if (input.value.length > 10) {
            input.value = input.value.slice(0, 10); // Truncate to 10 digits
        }
    }

    function toggleSpecialityField() {
        var userType = document.getElementById('user-type').value;
        var specialityContainer = document.getElementById('speciality-container');

        // Show speciality field if user selects 'guide'
        if (userType === 'guide') {
            specialityContainer.style.display = 'block';
        } else {
            specialityContainer.style.display = 'none';
        }
    }

    document.getElementById('register-submit').addEventListener('click', function (e) {
        // Clear previous error messages
        document.getElementById('nameError').textContent = '';
        document.getElementById('emailError').textContent = '';
        document.getElementById('phoneError').textContent = '';
        document.getElementById('passwordError').textContent = '';
        document.getElementById('confirmPasswordError').textContent = '';

        // Form fields
        let name = document.getElementById('username').value.trim();
        let email = document.getElementById('email').value.trim();
        let phone = document.getElementById('phone_no').value.trim();
        let password = document.getElementById('password').value.trim();
        let confirmPassword = document.getElementById('confirm-password').value.trim();

        let isValid = true;

        // Validation logic
        if (!name) {
            document.getElementById('nameError').textContent = 'Name is required';
            isValid = false;
        }

        if (!email) {
            document.getElementById('emailError').textContent = 'Email is required';
            isValid = false;
        }

        if (phone < 10) {
            document.getElementById('phoneError').textContent = 'Phone number must be 10 digits';
            isValid = false;
        }

        if (!password) {
            document.getElementById('passwordError').textContent = 'Password is required';
            isValid = false;
        }

        if (!confirmPassword) {
            document.getElementById('confirmPasswordError').textContent = 'Confirm password is required';
            isValid = false;
        }

        if (password !== confirmPassword) {
            document.getElementById('confirmPasswordError').textContent = 'Passwords do not match';
            isValid = false;
        }

        // If validation fails, prevent form submission
        if (!isValid) {
            e.preventDefault(); // Prevent form submission
        } else {
            // Let the form submit naturally
            document.getElementById('signup-form').submit();
        }
    });

</script>