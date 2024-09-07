<?php
session_start();
// Include the configuration file
include './config/config.php';

// Include the Router class
include './core/Router.php';

// Create a new Router instance
$router = new Router();

// Dispatch the request
$router->dispatch();

?>
