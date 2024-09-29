// app/models/Package.php

class Package {
    private $db;

    public function __construct() {
        // Assuming you have a Database class for managing DB connections
        $this->db = new Database;
    }

    // Add a new package
    public function addPackage($data) {
        $this->db->query('INSERT INTO packages (name, description, price, image) VALUES (:name, :description, :price, :image)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':image', $data['image']); // Image field
        return $this->db->execute();
    }

    // Get all packages
    public function getPackages() {
        $this->db->query('SELECT * FROM packages');
        return $this->db->resultSet();
    }

    // Edit a package (Not fully implemented)
    public function editPackage($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['package-name'];
        $description = $_POST['package-description'];
        $price = $_POST['package-price'];

        // Update the package in the database
        $sql = "UPDATE packages SET name = :name, description = :description, price = :price WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':description', $description);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':id', $id);
        
        $stmt->execute();
        header("Location: /travel/admin/package");
    } else {
        // Fetch package data
        $sql = "SELECT * FROM packages WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $package = $stmt->fetch(PDO::FETCH_ASSOC);

        // Load the edit view and pass package details
        include 'app/views/admin/editPackage.php';
    }
}


    // Delete a package
    public function deletePackage($id) {
        $this->db->query('DELETE FROM packages WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
