const express = require('express');
const bcrypt = require('bcrypt');
const { db } = require('../database');
const router = express.Router();

// SIGNUP
router.post('/signup', (req, res) => {
    const { username, password, confirm } = req.body;

    if (!username || !password || !confirm) {
        return res.json({ success: false, message: 'All fields required' });
    }
    if (password !== confirm) {
        return res.json({ success: false, message: 'Passwords do not match' });
    }
    if (password.length < 6) {
        return res.json({ success: false, message: 'Password too short (min 6)' });
    }

    db.get("SELECT * FROM users WHERE username = ?", [username], (err, user) => {
        if (user) {
            return res.json({ success: false, message: 'Username already exists' });
        }

        const hashed = bcrypt.hashSync(password, 10);
        db.run("INSERT INTO users (username, password, role, joined) VALUES (?, ?, 'user', ?)",
            [username, hashed, new Date().toISOString().split('T')[0]],
            function(err) {
                if (err) return res.json({ success: false, message: 'Error creating account' });
                req.session.user = username;
                req.session.role = 'user';
                req.session.joined = new Date().toISOString().split('T')[0];
                res.json({ success: true, message: 'Account created!', redirect: '/dashboard' });
            }
        );
    });
});

// LOGIN
router.post('/login', (req, res) => {
    const { username, password } = req.body;

    if (!username || !password) {
        return res.json({ success: false, message: 'Credentials required' });
    }

    db.get("SELECT * FROM users WHERE username = ?", [username], (err, user) => {
        if (!user || !bcrypt.compareSync(password, user.password)) {
            return res.json({ success: false, message: 'Invalid credentials' });
        }

        req.session.user = username;
        req.session.role = user.role;
        req.session.joined = user.joined;
        const redirect = user.role === 'admin' ? '/admin' : '/dashboard';
        res.json({ success: true, message: 'Login successful!', redirect });
    });
});

// LOGOUT
router.post('/logout', (req, res) => {
    req.session.destroy();
    res.json({ success: true, redirect: '/' });
});

module.exports = router;