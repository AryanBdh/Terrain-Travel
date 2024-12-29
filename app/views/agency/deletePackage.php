<?php
include './config/config.php';
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $packageId = intval($_GET['id']);

    // Delete the package from the database
    $stmt = $conn->prepare("DELETE FROM packages WHERE package_id = ?");
    if ($stmt->execute([$packageId])) {
        header("Location: /travel/agency/packages");  // Redirect back to the packages page after deletion
        exit;
    } else {
        echo "Error deleting package.";
    }
} else {
    echo "Invalid package ID.";
}
?>
