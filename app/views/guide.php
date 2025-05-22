<?php


include './config/config.php';
require_once './config/db.php';


$db = new Database();
$conn = $db->dbConnection();

$stmt = $conn->query("SELECT g.*, u.id, u.profile_image FROM guide g INNER JOIN users u ON g.user_id = u.id");
$guides = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="guide-container">

        <div class="guide-img">
            <img src="/travel/public/images/guide-img.jpg" alt="" srcset="">
            <div class="guide-banner-content">
                <h1>Meet out Expert Guides</h1>
            </div>
        </div>

        <div class="guide-content">
        <?php foreach ($guides as $guide): 
                $profileImagePath = '/travel/public/images/profile_images/' . $guide['id'] . '.png';
                $defaultImage = '/travel/public/images/default.png';

                $guideImage = file_exists($_SERVER['DOCUMENT_ROOT'] . $profileImagePath) ? $profileImagePath . '?' . time() : $defaultImage;
            ?>
                <div class="guide-card">
                    <div class="guide-image">
                        <img src="<?= htmlspecialchars($guideImage) ?>" alt="Guide Image">
                    </div>
                    <div class="guide-details">
                        <h3 class="guide-name"><?= htmlspecialchars($guide['guide_name']); ?></h3>
                        <p class="guide-speciality">Specialises in <?= htmlspecialchars($guide['speciality']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>