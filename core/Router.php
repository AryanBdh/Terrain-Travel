<?php

class Router {

    private $getRoutes = [];
    private $postRoutes = [];

    // Register a GET route
    public function get($route, $action) {
        $this->getRoutes[$route] = $action;
    }

    // Register a POST route
    public function post($route, $action) {
        $this->postRoutes[$route] = $action;
    }
    public function dispatch() {
        // Get the requested URL and set default to 'home'
        $url = isset($_GET['page']) ? rtrim($_GET['page'], '/') : 'home';


        

        if (strpos($url, 'admin') === 0) {
            $this->handleAdminRequest($url);
        } else {
            $this->handlePublicRequest($url);
        }
    }

    // Function to handle admin requests
    private function handleAdminRequest($url) {

        include 'routes.php';        // Strip 'admin/' from the URL and determine the content path
        $filePath = 'app/views/admin/' . str_replace('admin/', '', $url) . '.php';

        // Check if the requested admin content file exists
        if (file_exists($filePath)) {
            $content = $filePath;  // Dynamically set content
            include './app/views/admin/adminLayout.php';  // Load the admin layout
        } else {
            echo "<h1>Admin 404 Error</h1>";
        }
    }

    private function handlePublicRequest($url) {
        $filePath = 'app/views/' . $url . '.php';

        if (file_exists($filePath)) {
            $content = $filePath;  
            include './app/views/layout.php'; 
        } else {
            echo "<h1>Public 404 Error</h1>";
        }
    }
}
