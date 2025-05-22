<?php

class AdminPackageController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->dbConnection();
    }

    public function listPackages($page = 1, $limit = 3)
    {
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->conn->prepare("SELECT * FROM packages LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $this->conn->query("SELECT COUNT(*) FROM packages");
        $totalPackages = $countStmt->fetchColumn();
        
        include './app/views/admin/packages.php';
    }

}
