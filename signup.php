<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (empty($username) || empty($password) || empty($confirm)) {
        $_SESSION['error'] = 'All fields required';
        header('Location: index.html');
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['error'] = 'Passwords do not match';
        header('Location: index.html');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = 'Password too short (min 6)';
        header('Location: index.html');
        exit;
    }

    $db = Database::get();
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = 'Username already exists';
        header('Location: index.html');
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password, role, joined) VALUES (?, ?, 'user', ?)");
    $stmt->execute([$username, $hashed, date('Y-m-d')]);

    $_SESSION['success'] = 'Account created successfully!';
    $_SESSION['user'] = $username;
    $_SESSION['role'] = 'user';
    $_SESSION['joined'] = date('Y-m-d');
    header('Location: dashboard.php');
    exit;
}
?>