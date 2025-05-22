<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Travel Nepal</title>
    <link rel="stylesheet" href="/travel/public/css/admin.css">
</head>

<body>
    <div class="admin-container">

        <?php include 'partials/adminHeader.php'; ?>

        <main class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-outline"></ion-icon>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- User is logged in -->
                    <li class="user-options">
                        <?php
                        $defaultImagePath = '/travel/public/images/default.png'; 
                        $profileImagePath = '/travel/public/images/profile_images/' . $_SESSION['user_id'] . '.png';

                        $profileImage = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath) ? $profileImagePath . '?' . time() : $defaultImagePath;
                        ?>
                        <a href="#" class="user-profile" id="userProfile">
                            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile" class="profile-pic">
                        </a>
                        <ul class="dropdown" id="userDropdown">
                            <li><a href="/travel/profile">View Profile</a></li>

                            <?php if ($_SESSION['email'] === 'admin@gmail.com' ): ?>
                                <li><a href="/travel/admin/dashboard">Dashboard</a></li>
                            <?php endif; ?>

                            <li><a href="/travel/logout">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li style="list-style:none"><a href="/travel/login" class="sign-in-btn">Sign in</a></li>
                <?php endif; ?>



            </div>
            <div class="main-content">
             <?php if (isset($content)) include $content; ?>
            </div>
        </main>
    </div> 

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var userProfile = document.getElementById('userProfile');
        var userDropdown = document.getElementById('userDropdown');

        userDropdown.style.display = 'none'; 

        userProfile.addEventListener('click', function (event) {
            userDropdown.style.display = (userDropdown.style.display === 'none') ? 'block' : 'none';
            event.preventDefault();
        });

        document.addEventListener('click', function (event) {
            if (!userProfile.contains(event.target)) {
                userDropdown.style.display = 'none';
            }
        });

        let navList = document.querySelectorAll(".navigation li");

        function activeLink() {
            navList.forEach((item) => {
                item.classList.remove("hovered");
            });
            this.classList.add("hovered");
        }

        navList.forEach((item) => item.addEventListener("mouseover", activeLink));

        // Menu Toggle
        let toggle = document.querySelector(".toggle");
        let navigation = document.querySelector(".navigation");
        let main = document.querySelector(".main");

        toggle.onclick = function () {
            navigation.classList.toggle("active");
            main.classList.toggle("active");
        };
    });
</script>
    

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>


</html>