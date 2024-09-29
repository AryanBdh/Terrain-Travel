public function home() {
    $packages = $this->getPackages(); // Use the same method to fetch packages
    include 'app/views/home.php'; // Pass $packages to the home view
}


