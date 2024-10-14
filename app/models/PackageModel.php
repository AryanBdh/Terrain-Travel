<?php
// app/models/PackageModel.php

class PackageModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Add a new package
    public function addPackage($name, $description, $price, $image) {
        $stmt = $this->db->prepare("INSERT INTO packages (name, description, price, image) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $price, $image]);
    }

    // Get all packages
    public function getAllPackages() {
        $stmt = $this->db->query("SELECT * FROM packages");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a package by ID
    public function getPackageById($id) {
        $stmt = $this->db->prepare("SELECT * FROM packages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update a package
    public function updatePackage($id, $name, $description, $price, $image = null) {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE packages SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $price, $image, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE packages SET name = ?, description = ?, price = ? WHERE id = ?");
            return $stmt->execute([$name, $description, $price, $id]);
        }
    }

    // Delete a package
    public function deletePackage($id) {
        $stmt = $this->db->prepare("DELETE FROM packages WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
