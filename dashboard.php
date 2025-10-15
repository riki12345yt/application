<?php
require 'db.php';
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'user') {
    header('Location: index.html'); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard active">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>! 🎉</h2>
            <div class="user-info">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['user']); ?></p>
                <p><strong>Member Since:</strong> <?php echo htmlspecialchars($_SESSION['joined']); ?></p>
                <p><strong>Role:</strong> User</p>
            </div>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>