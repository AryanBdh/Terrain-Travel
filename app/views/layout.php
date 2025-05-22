<?php


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Nepal</title>
    <link rel="stylesheet" href="/travel/public/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    
</head>
<body>
    <?php
    include 'partials/header.php'; 
    ?>

    <main>
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        $filePath = 'app/views/' . $page . '.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo '<p>Page not found. Please check the URL or return to the home page.</p>';
        }
        ?>
    </main>

    <?php
    if ($page !== 'login' && $page !== 'register')
    {
        include 'partials/footer.php';
    }

    
    ?>


<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
