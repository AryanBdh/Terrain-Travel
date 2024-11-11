<?php

class PackageController
{
    public function show()
    {
        // Get the package ID from the URL query string
        if (!isset($_GET['id'])) {
            echo "Invalid package ID.";
            return;
        }

        $packageId = $_GET['id'];

        // Connect to the database
        $db = new Database();
        $conn = $db->dbConnection();

        // Fetch the package details from the database
        $stmt = $conn->prepare("SELECT * FROM packages WHERE id = :id");
        $stmt->bindParam(':id', $packageId, PDO::PARAM_INT);
        $stmt->execute();
        $package = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if package was found
        if (!$package) {
            echo "Package not found.";
            return;
        }

        // Render the package-detail view and pass the package data to it
        include './app/views/package-detail.php';
    }
}
