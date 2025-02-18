<?php
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

$userQuery = "SELECT COUNT(*) AS user_count FROM users";
$userResult = $conn->query($userQuery);
$userCount = $userResult->fetch(PDO::FETCH_ASSOC)['user_count'];

// Fetch the number of packages
$packageQuery = "SELECT COUNT(*) AS package_count FROM packages";
$packageResult = $conn->query($packageQuery);
$packageCount = $packageResult->fetch(PDO::FETCH_ASSOC)['package_count'];
?>

<div class="cardBox">
    <div class="card">
        <div>
            <div class="numbers"><?php echo $userCount; ?></div>
            <div class="cardName">Users</div>
        </div>

        <div class="iconBx">
            <ion-icon name="people-outline"></ion-icon>
        </div>
    </div>

    <div class="card">
        <div>
            <div class="numbers"><?php echo $packageCount; ?></div>
            <div class="cardName">Packages</div>
        </div>

        <div class="iconBx">
            <ion-icon name="briefcase-outline"></ion-icon>
        </div>
    </div>

   

</div>