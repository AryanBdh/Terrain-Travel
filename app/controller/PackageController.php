<?php

class PackageController
{
    public function show()
    {
        if (!isset($_GET['id'])) {
            echo "Invalid package ID.";
            return;
        }

        $packageId = $_GET['id'];

        $db = new Database();
        $conn = $db->dbConnection();

        $stmt = $conn->prepare("SELECT * FROM packages WHERE id = :id");
        $stmt->bindParam(':id', $packageId, PDO::PARAM_INT);
        $stmt->execute();
        $package = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$package) {
            echo "Package not found.";
            return;
        }

        include './app/views/package-detail.php';
    }
}
