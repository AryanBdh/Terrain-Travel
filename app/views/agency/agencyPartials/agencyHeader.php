<?php
// Check if the user type is agency otherwise redirect to home page
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'agency') {
    header("Location: /travel/home");
    exit;
}


?>

<!-- =============== Navigation ================ -->
<div class="navigation">
    <ul>
        <li>
            <a href="#">
                
                    <img src="/travel/public/images/logo/travellogo.png" alt="">
            </a>
        </li>

        <li>
            <a href="/travel/agency/dashboard">
                <span class="icon">
                    <ion-icon name="home-outline"></ion-icon>
                </span>
                <span class="title">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="/travel/agency/users">
                <span class="icon">
                    <ion-icon name="people-outline"></ion-icon>
                </span>
                <span class="title">Users</span>
            </a>
        </li>

        <li>
            <a href="/travel/agency/packages">
                <span class="icon">
                    <ion-icon name="briefcase-outline"></ion-icon>
                </span>
                <span class="title">Packages</span>
            </a>
        </li>

        <li>
            <a href="/travel/agency/guide">
                <span class="icon">
                    <ion-icon name="trail-sign-outline"></ion-icon>
                </span>
                <span class="title">Guides</span>
            </a>
        </li>

        <!-- a logout button that will destroy the session and redirect the user to the home page -->

        <li>
            <a href="/travel/logout">
                <span class="icon">
                    <ion-icon name="log-out-outline"></ion-icon>
                </span>
                <span class="title">Logout</span>
            </a>
        </li>
        

    </ul>
</div>
