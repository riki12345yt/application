const express = require('express');
const session = require('express-session');
const path = require('path');
const { initDB } = require('./database');
const authRoutes = require('./routes/auth');
const usersRoutes = require('./routes/users');

const app = express();
const PORT = 3000;

// Middleware
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));
app.use(session({
    secret: 'your-secret-key-123',
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false }
}));

// Initialize Database
initDB();

// Routes
app.use('/api/auth', authRoutes);
app.use('/api/users', usersRoutes);

// Serve HTML pages
app.get('/', (req, res) => res.sendFile(path.join(__dirname, 'public/index.html')));
app.get('/dashboard', (req, res) => {
    if (!req.session.user) return res.redirect('/');
    if (req.session.role === 'admin') return res.redirect('/admin');
    res.sendFile(path.join(__dirname, 'public/dashboard.html'));
});
app.get('/admin', (req, res) => {
    if (!req.session.user || req.session.role !== 'admin') return res.redirect('/');
    res.sendFile(path.join(__dirname, 'public/admin.html'));
});

app.listen(PORT, () => {
    console.log(`🚀 Server running at http://localhost:${PORT}`);
});