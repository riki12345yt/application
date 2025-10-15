<?php
session_start();
class Database {
    private static $db = null;

    public static function get() {
        if (self::$db === null) {
            self::$db = new PDO('sqlite:users.db');
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::init();
        }
        return self::$db;
    }

    private static function init() {
        $db = self::$db;
        $db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            joined DATE NOT NULL
        )");

        // Default admin
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $hashed = password_hash('admin123', PASSWORD_DEFAULT);
            $db->prepare("INSERT INTO users (username, password, role, joined) VALUES (?, ?, 'admin', ?)")
               ->execute(['admin', $hashed, date('Y-m-d')]);
        }
    }
}
?>