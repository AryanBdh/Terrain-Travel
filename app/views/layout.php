<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Nepal</title>
    <!-- CSS files -->
    <link rel="stylesheet" href="/travel/public/css/style.css">
</head>
<body>
    <?php
    // Include the regular header for public-facing pages
    include 'partials/header.php'; 
    ?>

    <main>
        <?php
        // Determine the current page
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        // Set the file path for public-facing pages only
        $filePath = 'app/views/' . $page . '.php';

        // Check if the file exists and include it
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            // If file doesn't exist, show an error message
            echo '<p>Page not found. Please check the URL or return to the home page.</p>';
        }
        ?>
    </main>

    <?php
    // Include the footer for public-facing pages
    include 'partials/footer.php';
    ?>

    <!-- JS files -->
    <script src="/travel/public/js/header.js"></script>
</body>
</html>
