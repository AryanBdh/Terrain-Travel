<?php

// Check if the user is logged in and if they have admin privileges
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // User is not an admin or not logged in, redirect to the login page
    header("Location: /travel/home");
    exit;
}
?>

<body>
    <!-- =============== Navigation ================ -->
    <div class="admin-container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="logo-apple"></ion-icon>
                        </span>
                        <span class="title">Brand Name</span>
                    </a>
                </li>

                <li>
                    <a href="dashboardCard">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Users</span>
                    </a>
                </li>

                <li>
                    <a href="#" id="dashboardLink">
                        <span class="icon">
                            <ion-icon name="chatbubble-outline"></ion-icon>
                        </span>
                        <span class="title">Destination</span>
                    </a>
                </li>

                <li>
                    <a href="package" id="packageLink">
                        <span class="icon">
                            <ion-icon name="help-outline"></ion-icon>
                        </span>
                        <span class="title">Packages</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>



                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User is logged in -->
                    <li style="right:20px" class="user-options">
                        <?php
                        // Determine profile image path
                        $defaultImagePath = '/travel/public/images/default.png'; // Path to default image
                        $profileImagePath = '/travel/public/images/profile_images/' . $_SESSION['user_id'] . '.png';

                        // Check if the user's profile image exists; if not, use the default image
                        $profileImage = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath) ? $profileImagePath : $defaultImagePath;
                        ?>
                        <a href="#" class="user-profile" id="userProfile">
                            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile" class="profile-pic">
                        </a>
                        <ul class="dropdown" id="userDropdown">
                            <li><a href="/travel/profile">View Profile</a></li>
                            <li><a href="/travel/logout">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- User is not logged in -->
                    <li style="list-style:none"><a href="/travel/login" class="sign-in-btn">Sign in</a></li>
                <?php endif; ?>
            </div>

            <!-- ======================= Cards ================== -->
            <!-- <div class="cardBox">
                <div class="card">
                    <div>
                        <div class="numbers">1,504</div>
                        <div class="cardName">Daily Views</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="eye-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">80</div>
                        <div class="cardName">Packages</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">284</div>
                        <div class="cardName">Reviews</div>
                    </div>

                    <div class="iconBx">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                    </div>
                </div>

            </div>  -->

        </div>
        <!-- ================ Order Details List ================= -->

    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>

    <script>
        // JavaScript to handle dropdown visibility
        document.addEventListener('DOMContentLoaded', function () {
            var userProfile = document.getElementById('userProfile');
            var userDropdown = document.getElementById('userDropdown');

            userDropdown.style.display = 'none'; // Initially hide the dropdown

            userProfile.addEventListener('click', function (event) {
                userDropdown.style.display = (userDropdown.style.display === 'none') ? 'block' : 'none';
                event.preventDefault();
            });

            document.addEventListener('click', function (event) {
                if (!userProfile.contains(event.target)) {
                    userDropdown.style.display = 'none';
                }
            });
        });

        let list = document.querySelectorAll(".navigation li");

        function activeLink() {
            list.forEach((item) => {
                item.classList.remove("hovered");
            });
            this.classList.add("hovered");
        }

        list.forEach((item) => item.addEventListener("mouseover", activeLink));

        // Menu Toggle
        let toggle = document.querySelector(".toggle");
        let navigation = document.querySelector(".navigation");
        let main = document.querySelector(".main");

        toggle.onclick = function () {
            navigation.classList.toggle("active");
            main.classList.toggle("active");
        };
    </script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>