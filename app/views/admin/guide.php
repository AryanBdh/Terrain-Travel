<?php
include './config/config.php';
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

// Fetch all guides
$guideQuery = "SELECT guide_id, guide_name AS name, speciality, email, guide_phone AS phone_no FROM guide WHERE status = 'Available'";
$guideStmt = $conn->prepare($guideQuery);
$guideStmt->execute();
$guides = $guideStmt->fetchAll(PDO::FETCH_ASSOC);

$assignedQuery = "SELECT g.guide_id, g.guide_name AS name, g.speciality, g.email, g.guide_phone AS phone_no, p.name AS package_name, p.package_id 
                  FROM guide g
                  INNER JOIN packages p ON g.guide_id = p.guide_id";
$assignedStmt = $conn->prepare($assignedQuery);
$assignedStmt->execute();
$assignedGuides = $assignedStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch packages
$packageQuery = "SELECT package_id, name, category FROM packages WHERE guide_id IS NULL";
$packageStmt = $conn->prepare($packageQuery);
$packageStmt->execute();
$packages = $packageStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign_guide'])) {
        $guide_id = $_POST['guide_id'];
        $package_id = $_POST['package_id'];

        // Assign guide to the package
        $assignQuery = "UPDATE packages SET guide_id = ? WHERE package_id = ?";
        $assignStmt = $conn->prepare($assignQuery);
        $assignStmt->execute([$guide_id, $package_id]);

        // Update guide status
        $updateGuideQuery = "UPDATE guide SET status = 'Assigned' WHERE guide_id = ?";
        $updateGuideStmt = $conn->prepare($updateGuideQuery);
        $updateGuideStmt->execute([$guide_id]);

        // Set success message
        $_SESSION['message'] = "Guide successfully assigned to the package.";
        header("Location: /travel/admin/guide");
        exit;
    } elseif (isset($_POST['unassign_guide'])) {
        $guide_id = $_POST['guide_id'];
        $package_id = $_POST['package_id'];

        // Unassign guide from the package
        $unassignQuery = "UPDATE packages SET guide_id = NULL WHERE package_id = ?";
        $unassignStmt = $conn->prepare($unassignQuery);
        $unassignStmt->execute([$package_id]);

        // Update guide status to available
        $updateGuideQuery = "UPDATE guide SET status = 'Available' WHERE guide_id = ?";
        $updateGuideStmt = $conn->prepare($updateGuideQuery);
        $updateGuideStmt->execute([$guide_id]);

        // Set success message
        $_SESSION['message'] = "Guide successfully unassigned from the package.";
        header("Location: /travel/admin/guide");
        exit;
    }
}
?>

<div class="guide-section">
    <h1>Guide Management</h1>

    <!-- Display success/error messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <p class="success-message" style="color:green"><?= $_SESSION['message'];
        unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <p class="error-message"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <div class="guide-management">
        <h2>Available Guides</h2>
        <?php if (!empty($guides)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Speciality</th>
                        <th>Contact</th>
                        <th>Assign to Package</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($guides as $guide): ?>
                        <tr>
                            <td><?= htmlspecialchars($guide['name']); ?></td>
                            <td><?= htmlspecialchars($guide['speciality']); ?></td>
                            <td><?= htmlspecialchars($guide['email']); ?> / <?= htmlspecialchars($guide['phone_no']); ?></td>
                            <td>
                                <form method="POST" action="/travel/admin/guide" class="assign-guide-form">
                                    <input type="hidden" name="guide_id" value="<?= $guide['guide_id']; ?>">
                                    <select name="package_id" required class="package-select" style="padding: 5px; width: 135px" >
                                        <option value="">Select Package</option>
                                        <?php foreach ($packages as $package): ?>
                                            <?php if (stripos($package['category'], $guide['speciality']) !== false): ?>
                                                <option value="<?= $package['package_id']; ?>">
                                                    <?= htmlspecialchars($package['name']); ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="assign_guide" style="padding: 5px; color:white; background-color:rgb(5, 98, 119);" >Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No available guides.</p>
        <?php endif; ?>
    </div>

    <div class="guide-management">
        <h2>Assigned Guides</h2>
        <?php if (!empty($assignedGuides)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Speciality</th>
                        <th>Contact</th>
                        <th>Package</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignedGuides as $guide): ?>
                        <tr>
                            <td><?= htmlspecialchars($guide['name']); ?></td>
                            <td><?= htmlspecialchars($guide['speciality']); ?></td>
                            <td><?= htmlspecialchars($guide['email']); ?> / <?= htmlspecialchars($guide['phone_no']); ?></td>
                            <td><?= htmlspecialchars($guide['package_name']); ?></td>
                            <td>
                                <form method="POST" action="/travel/admin/guide" class="unassign-guide-form">
                                    <input type="hidden" name="guide_id" value="<?= $guide['guide_id']; ?>">
                                    <input type="hidden" name="package_id" value="<?= $guide['package_id']; ?>">
                                    <button type="submit" name="unassign_guide" style="padding: 5px; color:white; background-color:rgb(5, 98, 119);">Unassign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No guides assigned to packages.</p>
        <?php endif; ?>
    </div>
</div>
