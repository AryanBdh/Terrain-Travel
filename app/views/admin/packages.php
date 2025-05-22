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

if (isset($_GET['package_id'])) {
    $packageId = $_GET['package_id'];
    $stmt = $conn->prepare("SELECT * FROM packages WHERE package_id = ?");
    $stmt->execute([$packageId]);
    $editingPackage = $stmt->fetch(PDO::FETCH_ASSOC);
    $isEditing = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['package-name'];
    $description = $_POST['package-description'];
    $price = $_POST['package-price'];
    $duration = $_POST['package-duration'];
    $category = $_POST['package-category'];
    $image = $_FILES['package-image']['name'] ? $_FILES['package-image']['name'] : $editingPackage['image'];
    $imageTmpName = $_FILES['package-image']['tmp_name'];
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/travel/public/images/packages/';
    $targetFilePath = $targetDir . basename($image);

    if (!empty($name) && !empty($description) && !empty($price) && !empty($duration)) {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if ($image && $imageTmpName) {
            move_uploaded_file($imageTmpName, $targetFilePath);
        }

        if (isset($_POST['package_id']) && $_POST['package_id']) {
            $stmt = $conn->prepare("UPDATE packages SET name = ?, description = ?, price = ?, image = ?, duration=?,category=? WHERE package_id = ?");
            $stmt->execute([$name, $description, $price, $image, $duration, $category, $_POST['package_id']]);
        } else {
            $stmt = $conn->prepare("INSERT INTO packages (name, description, price, image,duration,category) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image, $duration, $category]);
        }
        header("Location: /travel/admin/packages");
        exit;
    }
}

$stmt = $conn->query("SELECT * FROM packages");
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$packagesPerPage = 5;
$totalPages = ceil(count($packages) / $packagesPerPage);

?>

<div class="packages-section">
    <div class="package-form">
        <h2><?= $isEditing ? 'Edit Package' : 'Add New Package'; ?></h2>
        <form action="" id="packageForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="package_id" value="<?= htmlspecialchars($editingPackage['package_id'] ?? '') ?>">
            

            <label for="package-name">Name</label>
            <input type="text" id="package-name" name="package-name"
                value="<?= htmlspecialchars($editingPackage['name'] ?? '') ?>" >
                <span id="name_err" class="error" style="color:red;"></span>  

            <label for="package-description">Description</label>
            <textarea id="package-description" name="package-description"
                ><?= htmlspecialchars($editingPackage['description'] ?? '') ?></textarea>
                <span id="desc_err" class="error" style="color:red;"></span> 

            <label for="package-price">Price</label>
            <input type="number" id="package-price" name="package-price"
                value="<?= htmlspecialchars($editingPackage['price'] ?? '') ?>" >
                <span id="price_err" class="error" style="color:red;"></span> 

            <label for="package-image">Image</label>
            <input type="file" id="package-image" name="package-image">
            <?php if ($editingPackage && $editingPackage['image']): ?>
                <p>Current image: <img
                        src="/travel/public/images/packages/<?= htmlspecialchars($editingPackage['image']) ?>" width="100">
                </p>
            <?php endif; ?>
            <span id="img_err" class="error" style="color:red;"></span> 

            <label for="package-duration">Duration</label>
            <input type="number" id="package-duration" name="package-duration"
                value="<?= htmlspecialchars($editingPackage['duration'] ?? '') ?>" >
                <span id="duration_err" class="error" style="color:red;"></span> 

            <label for="package-category">Category</label>
            <select name="package-category" id="package-category" >
                <option value="" disabled selected>Select a category</option>
                <option value="Historical Tours" <?= $editingPackage && $editingPackage['category'] === 'Historical Tours' ? 'selected' : '' ?>>
                    Historical Tours</option>
                <option value="Adventure Tours" <?= $editingPackage && $editingPackage['category'] === 'Adventure Tours' ? 'selected' : '' ?>>
                    Adventure Tours</option>
                <option value="Cultural Tours" <?= $editingPackage && $editingPackage['category'] === 'Cultural Tours' ? 'selected' : '' ?>>
                    Cultural Tours</option>
                <option value="Wildlife Tours" <?= $editingPackage && $editingPackage['category'] === 'Wildlife Tours' ? 'selected' : '' ?>>
                    Wildlife Tours</option>
            </select>
            <span id="category_err" class="error" style="color:red;"></span> 

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
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $index => $package): ?>
                    <?php $pageIndex = floor($index / $packagesPerPage) + 1; ?>
                    <tr data-page="<?= $pageIndex ?>" style="display: <?= $pageIndex === 1 ? 'table-row' : 'none' ?>;">
                        <td><?= htmlspecialchars($package['name']); ?></td>
                        <td><?= limitText(htmlspecialchars($package['description']), 2); ?></td>
                        <td><?= htmlspecialchars($package['price']); ?></td>
                        <td><img src="/travel/public/images/packages/<?= htmlspecialchars($package['image']); ?>"
                                alt="<?= htmlspecialchars($package['name']); ?>" width="100" height="70px"></td>
                        <td><?= htmlspecialchars($package['duration'] . ' days and ' . ($package['duration'] - 1) . ' nights') ?></td>
                        <td>
                            <a
                                href="/travel/admin/packages?package_id=<?= htmlspecialchars($package['package_id']); ?>">Edit</a>
                            <a href="/travel/admin/deletePackage?id=<?= htmlspecialchars($package['package_id']); ?>"
                                onclick="return confirm('Are you sure you want to delete this package?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

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

                pageLinks.forEach(link => link.classList.remove('active'));
                this.classList.add('active');

                const page = parseInt(this.dataset.page);
                currentPage = page;

                showPage(page);
            });
        });

        showPage(1);

        const form = document.getElementById('packageForm');
        form.addEventListener('submit', function (e) {
            let isValid = true;

            function validateField(id, errorId, message) {
                const value = document.getElementById(id).value.trim();
                const errorSpan = document.getElementById(errorId);
                if (!value) {
                    errorSpan.textContent = message;
                    isValid = false;
                } else {
                    errorSpan.textContent = '';
                }
            }

            validateField('package-name', 'name_err', 'Package name is required');
            validateField('package-description', 'desc_err', 'Description is required');
            validateField('package-price', 'price_err', 'Price is required');
            validateField('package-image', 'img_err', 'Image is required');
            validateField('package-duration', 'duration_err', 'Duration is required');

            const category = document.getElementById('package-category').value;
            document.getElementById('category_err').textContent = category ? '' : 'Category is required';
            if (!category) isValid = false;

            if (!isValid) e.preventDefault();
        });
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