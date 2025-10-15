const express = require('express');
const { db } = require('../database');
const router = express.Router();

router.get('/', (req, res) => {
    if (!req.session.user || req.session.role !== 'admin') {
        return res.status(403).json({ error: 'Access denied' });
    }

    db.all("SELECT * FROM users ORDER BY joined DESC", (err, users) => {
        res.json(users);
    });
});

module.exports = router;