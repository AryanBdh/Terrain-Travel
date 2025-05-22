<header>
    <div class="container">
        <div class="logo">
            <a href="/travel/home">
                <img src="/travel/public/images/logo/travellogo.png" alt="Logo">
            </a>
        </div>
        <nav>
            <ul id="nav">
                <li><a href="/travel/home">Home</a></li>
                <li><a href="/travel/about">About</a></li>
                <li><a href="/travel/packages">Packages</a></li>
                <li><a href="/travel/guide">Guide</a></li>
                <li><a href="/travel/contact">Contact</a></li>
            </ul>
        </nav>
        
        <?php if (isset($_SESSION['user_id'])): ?>
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

                    <?php if ($_SESSION['email'] === 'admin@gmail.com'): ?>
                        <li><a href="/travel/admin/dashboard">Dashboard</a></li>
                    <?php endif; ?>

                    <li><a href="/travel/logout">Logout</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li style="list-style:none"><a href="/travel/login" class="sign-in-btn">Sign in</a></li>
        <?php endif; ?>
    </div>
</header>

<script>
    
    document.addEventListener('DOMContentLoaded', function () {
        var userProfile = document.getElementById('userProfile');
        var userDropdown = document.getElementById('userDropdown');

        if (userProfile && userDropdown) {
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
        }
    });
</script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>