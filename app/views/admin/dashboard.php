<?php
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

$userQuery = "SELECT COUNT(*) AS user_count FROM users";
$userResult = $conn->query($userQuery);
$userCount = $userResult->fetch(PDO::FETCH_ASSOC)['user_count'];

$packageQuery = "SELECT COUNT(*) AS package_count FROM packages";
$packageResult = $conn->query($packageQuery);
$packageCount = $packageResult->fetch(PDO::FETCH_ASSOC)['package_count'];

$guideQuery = "SELECT COUNT(*) AS guide_count FROM guide";
$guideResult = $conn->query($guideQuery);
$guideCount = $guideResult->fetch(PDO::FETCH_ASSOC)['guide_count'];

$touristQuery="SELECT COUNT(*) AS tourist_count FROM tourists";
$touristResult = $conn->query($touristQuery);
$touristCount = $touristResult->fetch(PDO::FETCH_ASSOC)['tourist_count'];

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

   <div class="card">
        <div>
            <div class="numbers"><?php echo $guideCount; ?></div>
            <div class="cardName">Guides</div>
        </div>

        <div class="iconBx">
            <ion-icon name="people-outline"></ion-icon>
        </div>
   </div>

   <div class="card">
        <div>
            <div class="numbers"><?php echo $touristCount; ?></div>
            <div class="cardName">Tourists</div>
        </div>

        <div class="iconBx">
            <ion-icon name="people-outline"></ion-icon>
        </div>
   </div>

</div>