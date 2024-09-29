<!-- app/views/admin/package.php -->

<div class="admin-container">
    <div class="main-content">
        <div class="packages-section">
            <!-- Packages Form -->
            <div class="package-form">
                <h2>Add New Package</h2>
                <form action="/travel/admin/package" method="POST" enctype="multipart/form-data">
                    <label for="package-name">Package Name</label>
                    <input type="text" id="package-name" name="package-name" required>

                    <label for="package-description">Description</label>
                    <textarea id="package-description" name="package-description" required></textarea>

                    <label for="package-price">Price</label>
                    <input type="number" id="package-price" name="package-price" required>

                    <!-- New Image Upload Field -->
                    <label for="package-image">Image</label>
                    <input type="file" id="package-image" name="package-image" accept="image/*" required>

                    <button type="submit">Add Package</button>
                </form>
            </div>

            <!-- List of Packages -->
            <div class="package-list">
                <h2>Available Packages</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Package Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($packages)): ?>
                            <?php foreach ($packages as $package): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($package['name']); ?></td>
                                    <td><?php echo htmlspecialchars($package['description']); ?></td>
                                    <td><?php echo htmlspecialchars($package['price']); ?></td>
                                    <td><img src="/uploads/<?php echo htmlspecialchars($package['image']); ?>" width="50"></td>
                                    <td>
                                        <a href="/travel/admin/editPackage?id=<?php echo $package['id']; ?>">Edit</a>
                                        <a href="/travel/admin/deletePackage?id=<?php echo $package['id']; ?>"
                                            onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No packages available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>