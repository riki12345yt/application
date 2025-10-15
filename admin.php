<?php
require 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.html'); exit;
}
$db = Database::get();
$stmt = $db->query("SELECT * FROM users ORDER BY joined DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard active">
            <h2>👑 Admin Panel - All Users (<?php echo count($users); ?>)</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><strong><?php echo $user['role']; ?></strong></td>
                        <td><?php echo $user['joined']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>