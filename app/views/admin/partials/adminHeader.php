<?php

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: /travel/home");
    exit;
}
?>

<!-- =============== Navigation ================ -->
<div class="navigation">
    <ul>
        <li>
        <a href="/travel/home">
                <img src="/travel/public/images/logo/travellogo.png" alt="Logo">
            </a>
        </li>

        <li>
            <a href="/travel/admin/dashboard">
                <span class="icon">
                    <ion-icon name="home-outline"></ion-icon>
                </span>
                <span class="title">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="/travel/admin/users">
                <span class="icon">
                    <ion-icon name="people-outline"></ion-icon>
                </span>
                <span class="title">Users</span>
            </a>
        </li>

        <li>
            <a href="/travel/admin/packages">
                <span class="icon">
                    <ion-icon name="briefcase-outline"></ion-icon>
                </span>
                <span class="title">Packages</span>
            </a>
        </li>

        <li>
            <a href="/travel/admin/guide">
                <span class="icon">
                    <ion-icon name="person-outline"></ion-icon>
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
