<header>
    <div class="container">
        <div class="logo">
            <a href="/travel/home">
                <img src="/travel/assets/images/logo.png" alt="Logo">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="/travel/home">Home</a></li>
                <li><a href="/travel/about">About</a></li>
                <li><a href="/travel/packages">Packages</a></li>
                <li><a href="/travel/guide">Guide</a></li>
                <li><a href="/travel/contact">Contact</a></li>
            </ul>
        </nav>
        <?php if (isset($_SESSION['user_id'])): ?>
    <!-- User is logged in -->
    <li class="user-options">
        <?php
        // Determine profile image path
        $defaultImagePath = '/travel/public/images/default.png'; // Path to default image
        $profileImagePath = '/travel/public/images/profile_images/' . $_SESSION['user_id'] . '.png';

        // Check if the user's profile image exists; if not, use the default image
        $profileImage = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath) ? $profileImagePath . '?' . time() : $defaultImagePath;
        ?>
        <a href="#" class="user-profile" id="userProfile">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile" class="profile-pic">
        </a>
        <ul class="dropdown" id="userDropdown">
            <li><a href="/travel/profile">View Profile</a></li>
            
            <!-- Only show Dashboard if the user is admin -->
            <?php if ($_SESSION['email'] === 'admin@gmail.com'): ?>
                <li><a href="/travel/admin/dashboard">Dashboard</a></li>
            <?php endif; ?>

            <li><a href="/travel/logout">Logout</a></li>
        </ul>
    </li>
<?php else: ?>
            <!-- User is not logged in -->
            <li style="list-style:none"><a href="/travel/login" class="sign-in-btn">Sign in</a></li>
        <?php endif; ?>
    </div>
</header>

<script>
    // JavaScript to handle dropdown visibility
    document.addEventListener('DOMContentLoaded', function () {
        var userProfile = document.getElementById('userProfile');
        var userDropdown = document.getElementById('userDropdown');

        // Only proceed if both elements exist
        if (userProfile && userDropdown) {
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
        }
    });
</script>
