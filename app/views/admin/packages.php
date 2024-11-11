<?php

// Function to limit text to a certain word count
function limitText($text, $limit)
{
    $words = explode(' ', $text);
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
    return $text;
}

require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

$editingPackage = null;
$isEditing = false;

// Check if an edit request was made
if (isset($_GET['package_id'])) {
    $packageId = $_GET['package_id'];
    $stmt = $conn->prepare("SELECT * FROM packages WHERE package_id = ?");
    $stmt->execute([$packageId]);
    $editingPackage = $stmt->fetch(PDO::FETCH_ASSOC);
    $isEditing = true;
}

// Handle form submission for add/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['package-name'];
    $description = $_POST['package-description'];
    $price = $_POST['package-price'];
    $image = $_FILES['package-image']['name'] ? $_FILES['package-image']['name'] : $editingPackage['image'];
    $imageTmpName = $_FILES['package-image']['tmp_name'];
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/travel/public/images/packages/';
    $targetFilePath = $targetDir . basename($image);

    if (!empty($name) && !empty($description) && !empty($price)) {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Check if a new image is uploaded
        if ($image && $imageTmpName) {
            move_uploaded_file($imageTmpName, $targetFilePath);
        }

        if (isset($_POST['package_id']) && $_POST['package_id']) {
            // Update existing package
            $stmt = $conn->prepare("UPDATE packages SET name = ?, description = ?, price = ?, image = ? WHERE package_id = ?");
            $stmt->execute([$name, $description, $price, $image, $_POST['package_id']]);
        } else {
            // Insert new package
            $stmt = $conn->prepare("INSERT INTO packages (name, description, price, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image]);
        }
        header("Location: /travel/admin/packages");
        exit;
    }
}

// Fetch all packages to display in the table
$stmt = $conn->query("SELECT * FROM packages");
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$packagesPerPage = 3;
$totalPages = ceil(count($packages) / $packagesPerPage);

?>

<div class="packages-section">
    <div class="package-form">
        <h2><?= $isEditing ? 'Edit Package' : 'Add New Package'; ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="package_id" value="<?= htmlspecialchars($editingPackage['package_id'] ?? '') ?>">

            <label for="package-name">Name</label>
            <input type="text" id="package-name" name="package-name"
                   value="<?= htmlspecialchars($editingPackage['name'] ?? '') ?>" required>

            <label for="package-description">Description</label>
            <textarea id="package-description" name="package-description" required><?= htmlspecialchars($editingPackage['description'] ?? '') ?></textarea>

            <label for="package-price">Price</label>
            <input type="number" id="package-price" name="package-price"
                   value="<?= htmlspecialchars($editingPackage['price'] ?? '') ?>" required>

            <label for="package-image">Image</label>
            <input type="file" id="package-image" name="package-image">
            <?php if ($editingPackage && $editingPackage['image']): ?>
                <p>Current image: <img src="/travel/public/images/packages/<?= htmlspecialchars($editingPackage['image']) ?>" width="100"></p>
            <?php endif; ?>

            <button type="submit"><?= $isEditing ? 'Update Package' : 'Add Package'; ?></button>
        </form>
    </div>

    <div class="package-list">
        <h2>Available Packages</h2>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($packages as $index => $package): ?>
                <?php $pageIndex = floor($index / $packagesPerPage) + 1; ?>
                <tr data-page="<?= $pageIndex ?>" style="display: <?= $pageIndex === 1 ? 'table-row' : 'none' ?>;">
                    <td><?= htmlspecialchars($package['name']); ?></td>
                    <td><?= limitText(htmlspecialchars($package['description']), 5); ?></td>
                    <td><?= htmlspecialchars($package['price']); ?></td>
                    <td><img src="/travel/public/images/packages/<?= htmlspecialchars($package['image']); ?>" alt="<?= htmlspecialchars($package['name']); ?>" width="100" height="70px"></td>
                    <td>
                        <a href="/travel/admin/packages?package_id=<?= htmlspecialchars($package['package_id']); ?>">Edit</a>
                        <a href="/travel/admin/deletePackage?id=<?= htmlspecialchars($package['package_id']); ?>" onclick="return confirm('Are you sure you want to delete this package?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination Links at the Bottom of the Table -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="#" class="page-link <?= $i == 1 ? 'active' : '' ?>" data-page="<?= $i ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pageLinks = document.querySelectorAll('.page-link');
        const rows = document.querySelectorAll('tbody tr');
        let currentPage = 1;

        function showPage(page) {
            rows.forEach(row => {
                row.style.display = row.dataset.page == page ? 'table-row' : 'none';
            });
        }

        pageLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                // Remove active class from all pagination links
                pageLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');

                const page = parseInt(this.dataset.page);
                currentPage = page;

                // Show only rows for the current page
                showPage(page);
            });
        });

        // Show page 1 by default on load
        showPage(1);
    });
</script>

<style>
    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination .active {
        font-weight: bold;
        color: #000;
    }
</style>
