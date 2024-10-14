<?php
session_start();
// Include the configuration file
include './config/config.php';

// Include the Router class
include './core/Router.php';

// Create a new Router instance
$router = new Router();

$router->get('/admin/packages', 'PackageController@index');
$router->post('/admin/packages', 'PackageController@addPackage');
$router->get('/admin/editPackage', 'PackageController@editPackage');
$router->post('/admin/editPackage', 'PackageController@editPackage');
$router->get('/admin/deletePackage', 'PackageController@deletePackage');

// Dispatch the request
$router->dispatch();

?>
