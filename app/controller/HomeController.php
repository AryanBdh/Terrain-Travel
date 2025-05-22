<?php

class HomeController {
    private $packageModel;

    public function __construct() {
        $database = new Database();
        $this->packageModel = new PackageModel($database);
    }

    public function index() {
        $packages = $this->packageModel->getAllPackages();  
    
        // Debugging: Check if packages are fetched
        if (empty($packages)) {
            echo 'No packages found'; // Temporary debug line to see if packages are fetched
        }
    
        $content = './app/views/home.php';
        require_once './app/views/layout.php';  // Pass packages to the view
    }
}
