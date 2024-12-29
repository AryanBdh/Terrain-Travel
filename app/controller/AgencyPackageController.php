<?php

class AgencyPackageController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->dbConnection();
    }

    // List packages with pagination
    public function listPackages($page = 1, $limit = 3)
    {
        $offset = ($page - 1) * $limit;
        
        // Fetch packages with limit and offset for pagination
        $stmt = $this->conn->prepare("SELECT * FROM packages LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count total packages for pagination
        $countStmt = $this->conn->query("SELECT COUNT(*) FROM packages");
        $totalPackages = $countStmt->fetchColumn();
        
        // Pass packages and pagination info to the view
        include './app/views/agency/packages.php';
    }

}