<?php
session_start();

// Include the configuration file and Router class
include './config/config.php';
include './core/Router.php';

// Create a new Router instance
$router = new Router();

// Include routes
include './core/routes.php';

// Dispatch the request
$router->dispatch();
?>  