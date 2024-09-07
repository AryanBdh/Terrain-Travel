<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Nepal</title>
    <link rel="stylesheet" href="/travel/public/css/style.css">
</head>
<body>
    <?php include 'partials/header.php'; ?>
    
    <main>
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        $filePath = 'app/views/' . $page . '.php';
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo '<p>Page not found.</p>';
        }
        ?>
    </main>

    <?php include 'partials/footer.php'; ?>

    <script src="/public/js/header.js"></script>
</body>
</html>
