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
            <a href="#">
                <span class="icon">
                    <ion-icon name="logo-apple"></ion-icon>
                </span>
                <span class="title">Brand Name</span>
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
            <a href="/travel/admin/destination">
                <span class="icon">
                    <ion-icon name="map-outline"></ion-icon>
                </span>
                <span class="title">Destination</span>
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

    </ul>
</div>
