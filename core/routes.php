<?php
// Include the database file
require_once './config/db.php';

require_once './app/models/PackageModel.php'; 

require_once './app/controller/PackageController.php';

// Create a new Database instance
$database = new Database();

// Create a new PackageModel instance
$packageModel = new PackageModel($database);

// Handle Admin Packages List - GET Request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] === 'admin/packages') {
    require_once './app/controller/PackageController.php';
    $controller = new PackageController($packageModel);
    $controller->index();
}

// Handle Add New Package - POST Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['page'] === 'admin/packages') {
    require_once './app/controller/PackageController.php';
    $controller = new PackageController($packageModel);
    $controller->addPackage();
}

// Handle Edit Package - GET and POST Requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] === 'admin/editPackage') {
    require_once './app/controller/PackageController.php';
    $controller = new PackageController($packageModel);
    $controller->editPackage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['page'] === 'admin/editPackage') {
    require_once './app/controller/PackageController.php';
    $controller = new PackageController($packageModel);
    $controller->editPackage();
}

// Handle Delete Package - GET Request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['page'] === 'admin/deletePackage') {
    require_once './app/controller/PackageController.php';
    $controller = new PackageController($packageModel);
    $controller->deletePackage();
}
