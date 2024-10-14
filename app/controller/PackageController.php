<?php
// app/controllers/AdminPackageController.php

class PackageController {
    private $packageModel;

    public function __construct($packageModel) {
        require_once './config/db.php'; // Ensure db.php is included
        $this->packageModel = $packageModel;
    }

    // Display the packages page with form and list
    public function index() {
        $packages = $this->packageModel->getAllPackages();
        $content = 'admin/packages.php'; // View for admin package
        require_once 'views/admin/adminLayout.php';
    }

    // Add a new package
    public function addPackage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['package-name'];
            $description = $_POST['package-description'];
            $price = $_POST['package-price'];

            // Handle image upload
            if (!empty($_FILES['package-image']['name'])) {
                $imageName = time() . '_' . $_FILES['package-image']['name'];
                move_uploaded_file($_FILES['package-image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/public/images/packages' . $imageName);
            }

            $this->packageModel->addPackage($name, $description, $price, $imageName);
            header('Location: /travel/admin/packages');
            exit;
        }
    }

    // Edit a package
    public function editPackage() {
        $id = $_GET['id'];
        $package = $this->packageModel->getPackageById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['package-name'];
            $description = $_POST['package-description'];
            $price = $_POST['package-price'];

            // Handle image upload if changed
            if (!empty($_FILES['package-image']['name'])) {
                $imageName = time() . '_' . $_FILES['package-image']['name'];
                move_uploaded_file($_FILES['package-image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/public/images/packages' . $imageName);
            } else {
                $imageName = $package['image']; // Keep the current image if no new image is uploaded
            }

            $this->packageModel->updatePackage($id, $name, $description, $price, $imageName);
            header('Location: /travel/admin/packages');
            exit;
        }

        // Load the edit view
        $content = 'admin/editPackage.php'; // View for editing the package
        require_once 'views/admin/adminLayout.php';
    }

    // Delete a package
    public function deletePackage() {
        $id = $_GET['id'];
        $this->packageModel->deletePackage($id);
        header('Location: /travel/admin/packages');
        exit;
    }
}
