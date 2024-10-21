<div class="packages-section">
    <div class="package-form">
        <h2>Add New Package</h2>
        <form action="/travel/admin/packages" method="POST" enctype="multipart/form-data">
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
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $package): ?>
                        <tr>
                    <td><?= htmlspecialchars($package->name); ?></td>
                    <td><?= htmlspecialchars($package->description); ?></td>
                    <td><?= htmlspecialchars($package->price); ?></td>
                    <td><img src="/travel/public/images/packages/<?= htmlspecialchars($package->image); ?>" alt="<?= htmlspecialchars($package->name); ?>" width="100"></td>
                    <td>
                        <a href="/travel/admin/editPackage?id=<?= $package->id; ?>">Edit</a>
                        <a href="/travel/admin/deletePackage?id=<?= $package->id; ?>">Delete</a>
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