// app/controllers/PackageController.php

class PackageController extends Controller {

    public function __construct() {
        $this->packageModel = $this->model('Package');
    }

    // Add package action
    public function addPackage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle the form submission
            $image = $_FILES['package-image']['name'];
            $target = '/public/images/packages' . basename($image);

            // Move the uploaded file to the target directory
            move_uploaded_file($_FILES['package-image']['tmp_name'], $target);

            $data = [
                'name' => trim($_POST['package-name']),
                'description' => trim($_POST['package-description']),
                'price' => trim($_POST['package-price']),
                'image' => $image
            ];

            if ($this->packageModel->addPackage($data)) {
                // Redirect to admin page or package list
                header('Location: /travel/admin/packages');
            } else {
                die('Something went wrong');
            }
        } else {
            $this->view('admin/addPackage');
        }
    }

    // Display all packages
    public function listPackages() {
        $packages = $this->packageModel->getPackages();
        $data = ['packages' => $packages];
        $this->view('admin/package', $data);
    }
}
