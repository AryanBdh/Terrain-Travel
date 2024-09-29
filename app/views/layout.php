<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Nepal</title>
    <link rel="stylesheet" href="/travel/public/css/style.css">
    <link rel="stylesheet" href="/travel/public/css/admin.css">
</head>
<body>
    <?php
    // Determine the current page
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    
    // Include the header only if it's not an admin page
    if ($page !== 'admin/dashboard') {
        include 'partials/header.php';
    }
    ?>

    <main>
        <?php

     // Check if the current page is the admin dashboard
     $requestUri = strtok($_SERVER['REQUEST_URI'], '?');  // Remove the query string
     $requestUri = strtok($requestUri, '#');  // Remove the fragment


     $adminPath = 'app/views/admin/' . $page . '.php';
     // If the user is on the admin dashboard page, include dashboardCard.php
     if ($requestUri === '/travel/admin/dashboard') {
        if (file_exists($adminPath)) {
            include $adminPath;
        }
     }

        // Determine the file path for the page
        $filePath = 'app/views/' . $page . '.php';
        
        // Check if the file exists and include it; otherwise, show an error message
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo '<p>Page not found.</p>';
        }
        ?>
    </main>

    <?php
    // Include the footer only if it's not an admin page
    if ($page !== 'admin/dashboard') {
        include 'partials/footer.php';
    }
    ?>

    <script src="/public/js/header.js"></script>
</body>
</html>
