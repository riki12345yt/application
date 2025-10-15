const sqlite3 = require('sqlite3').verbose();
const bcrypt = require('bcrypt');

const db = new sqlite3.Database('./users.db');

function initDB() {
    db.serialize(() => {
        db.run(`CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            joined DATE NOT NULL
        )`);

        // Default admin
        db.get("SELECT COUNT(*) as count FROM users WHERE username = 'admin'", (err, row) => {
            if (row.count === 0) {
                const hashed = bcrypt.hashSync('admin123', 10);
                db.run("INSERT INTO users (username, password, role, joined) VALUES (?, ?, 'admin', ?)",
                    ['admin', hashed, new Date().toISOString().split('T')[0]]);
                console.log('✅ Default admin created: admin/admin123');
            }
        });
    });
}

module.exports = { db, initDB };