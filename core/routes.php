<?php


// Route definitions for admin package management
$router->get('/travel/admin/packages', 'PackageController@index');
$router->post('/travel/admin/packages', 'PackageController@addPackage');
$router->get('/travel/admin/editPackage', 'PackageController@editPackage');
$router->post('/travel/admin/editPackage', 'PackageController@editPackage');
$router->get('/travel/admin/deletePackage', 'PackageController@deletePackage');
// Admin package management routes
$router->get('/travel/admin/packages', function() {
    $controller = new AdminPackageController();
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $controller->listPackages($page);
});

// Home route
$router->get('/', 'HomeController@index');

// Admin dashboard route (or use a DashboardController if desired)
$router->get('/travel/admin/users', 'UserController@index');
$router->get('/travel/packages/package-detail', 'PackageController@show');

?>