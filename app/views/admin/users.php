<?php
require_once './config/db.php';

$db = new Database();
$conn = $db->dbConnection();

$stmt = $conn->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<div class="users-section">
    <h2>Registered Users</h2>
    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Username</th>
                <th>User Type</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['username']); ?></td>
                        <td><?= htmlspecialchars($user['user_type']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['phone_no']); ?></td>
                        <td><?= htmlspecialchars($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No registered users.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
