<?php

require_once './app/models/PackageModel.php';
require_once './config/db.php';

class PackageController
{
    private $packageModel;

    public function __construct()
    {
        $database = new Database();  // Create a Database instance
        $this->packageModel = new PackageModel($database);  // Pass the Database instance
    }

    // Display all packages
    public function index()
    {
        $packages = $this->packageModel->getAllPackages();

        // Set the content to be loaded dynamically
        $content = './app/views/admin/packages.php';  

        // Include the admin layout, which loads the $content dynamically
        require_once './app/views/admin/adminLayout.php';
    }

    // Add a new package
    public function addPackage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['package-name'];
            $description = $_POST['package-description'];
            $price = $_POST['package-price'];

            // Handle image upload
            $image = $_FILES['package-image']['name'];
            $imageTmpName = $_FILES['package-image']['tmp_name'];

            // Define the target directory
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/travel/public/images/packages/';

            // Make sure the directory exists
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Target file path
            $targetFilePath = $targetDir . basename($image);

            // Move the file
            if (move_uploaded_file($imageTmpName, $targetFilePath)) {
                // Call the model to save the package details, including the image name
                $this->packageModel->addPackage($name, $description, $price, $image);
            } else {
                echo "There was an error uploading the image.";
            }
        }

        // Redirect back to the packages page after adding
        header('Location: /travel/admin/packages');
        exit;
    }

    // Edit a package
    public function editPackage()
    {
        $id = $_GET['id'];
        $package = $this->packageModel->getPackageById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['package-name'];
            $description = $_POST['package-description'];
            $price = $_POST['package-price'];

            // Handle image upload if changed
            if (!empty($_FILES['package-image']['name'])) {
                $image = $_FILES['package-image']['name'];
                $targetDirectory = "../public/images/packages/";
                $targetFile = $targetDirectory . basename($image);

                // Move the uploaded file to the correct folder
                if (move_uploaded_file($_FILES['package-image']['tmp_name'], $targetFile)) {
                    echo "The file " . basename($image) . " has been uploaded.";
                } else {
                    echo "Error uploading the file.";
                }
            }

            $this->packageModel->updatePackage($id, $name, $description, $price, $image);
            
            // Redirect back to the packages page after updating
            header('Location: /travel/admin/packages');
            exit;
        }

        // Set the content to load the edit view
        $content = './app/views/admin/editPackage.php'; 

        // Include the layout, which will load the content dynamically
        require_once './app/views/admin/adminLayout.php';
    }

    // Delete a package
    public function deletePackage()
    {
        $id = $_GET['id'];
        $this->packageModel->deletePackage($id);

        // Redirect back to the packages page after deletion
        header('Location: /travel/admin/packages');
        exit;
    }
}

