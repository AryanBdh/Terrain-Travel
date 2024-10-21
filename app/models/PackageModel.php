<?php

class PackageModel
{
    private $db;

    public function __construct($database)
    {
        $this->db = $database->dbConnection();
    }

    // Fetch all packages
    public function getAllPackages()
    {
        $sql = "SELECT * FROM packages";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $result;
    }

    // Add a new package
    public function addPackage($name, $description, $price, $image)
    {
        $sql = "INSERT INTO packages (name, description, price, image) VALUES (:name, :description, :price, :image)";
        $this->db->query($sql);
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':price', $price);
        $this->db->bind(':image', $image);
        return $this->db->execute();
    }

    // Get package by ID
    public function getPackageById($id)
    {
        $this->db->query("SELECT * FROM packages WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Update a package
    public function updatePackage($id, $name, $description, $price, $image = null)
    {
        $sql = $image
            ? "UPDATE packages SET name = :name, description = :description, price = :price, image = :image WHERE id = :id"
            : "UPDATE packages SET name = :name, description = :description, price = :price WHERE id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':price', $price);
        if ($image) {
            $this->db->bind(':image', $image);
        }
        return $this->db->execute();
    }

    // Delete a package
    public function deletePackage($id)
    {
        $this->db->query("DELETE FROM packages WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
