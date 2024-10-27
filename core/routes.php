<?php

require_once './app/controller/PackageController.php';
// Route definitions for admin package management
$router->get('/admin/packages', 'PackageController@index');
$router->post('/admin/packages', 'PackageController@addPackage');
$router->get('/admin/editPackage', 'PackageController@editPackage');
$router->post('/admin/editPackage', 'PackageController@editPackage');
$router->get('/admin/deletePackage', 'PackageController@deletePackage');

// Home route
$router->get('/', 'HomeController@index');

// Admin dashboard route (or use a DashboardController if desired)


?>