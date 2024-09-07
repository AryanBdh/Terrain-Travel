<?php

class Router {
    public function dispatch() {
        // Get the requested URL and set default to 'home'
        $url = isset($_GET['page']) ? rtrim($_GET['page'], '/') : 'home';

        // Determine the path of the content file
        $filePath = 'app/views/' . $url . '.php';

        // Check if the requested content file exists
        if (file_exists($filePath)) {
            // Include the main layout file that dynamically loads the content
            include './app/views/layout.php';
        } else {
            // Handle 404 error or page not found scenario
            echo '<p>Page not found.</p>';
        }
    }
}
?>
