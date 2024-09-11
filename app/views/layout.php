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
