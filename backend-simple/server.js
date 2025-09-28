const express = require('express');
const cors = require('cors');
const mysql = require('mysql2/promise');

const app = express();
const PORT = process.env.PORT || 8080;

// Middleware
app.use(cors());
app.use(express.json());

// Database connection
const dbConfig = {
  host: process.env.MYSQL_HOST || 'mysql.railway.internal',
  port: process.env.MYSQL_PORT || 3306,
  user: process.env.MYSQL_USER || 'root',
  password: process.env.MYSQL_PASSWORD || 'wtWYuztNPZkFwJXTayCFeYXiSgrBIbLg',
  database: process.env.MYSQL_DATABASE || 'railway'
};

// Health check endpoint
app.get('/health', (req, res) => {
  res.json({ status: 'healthy', timestamp: new Date().toISOString() });
});

// Test endpoint
app.get('/api/test', (req, res) => {
  res.json({ message: 'Backend is working!', timestamp: new Date().toISOString() });
});

// Login endpoint
app.post('/api/auth/login', async (req, res) => {
  try {
    const { email, password } = req.body;

    // Hardcoded users for testing
    const users = [
      { email: 'admin@uniportal.com', password: 'admin123', role: 'Admin', firstName: 'Admin', lastName: 'User' },
      { email: 'john@student.uniportal.com', password: 'admin123', role: 'Student', firstName: 'John', lastName: 'Doe' },
      { email: 'jane@student.uniportal.com', password: 'admin123', role: 'Student', firstName: 'Jane', lastName: 'Smith' },
      { email: 'k.stefanovska@univ.mk', password: 'admin123', role: 'Professor', firstName: 'Kristina', lastName: 'Stefanovska' }
    ];

    const user = users.find(u => u.email === email && u.password === password);

    if (!user) {
      return res.status(400).json({ message: 'Invalid email or password' });
    }

    res.json({
      token: 'fake-jwt-token-for-testing',
      user: {
        email: user.email,
        role: user.role,
        firstName: user.firstName,
        lastName: user.lastName
      }
    });
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ message: 'Internal server error' });
  }
});

// Get all students
app.get('/api/students', async (req, res) => {
  try {
    const connection = await mysql.createConnection(dbConfig);
    const [rows] = await connection.execute('SELECT * FROM Students');
    await connection.end();
    res.json(rows);
  } catch (error) {
    console.error('Error fetching students:', error);
    res.status(500).json({ message: 'Error fetching students' });
  }
});

// Get all professors
app.get('/api/professors', async (req, res) => {
  try {
    const connection = await mysql.createConnection(dbConfig);
    const [rows] = await connection.execute('SELECT * FROM Professors');
    await connection.end();
    res.json(rows);
  } catch (error) {
    console.error('Error fetching professors:', error);
    res.status(500).json({ message: 'Error fetching professors' });
  }
});

// Get all courses
app.get('/api/courses', async (req, res) => {
  try {
    const connection = await mysql.createConnection(dbConfig);
    const [rows] = await connection.execute('SELECT * FROM Courses');
    await connection.end();
    res.json(rows);
  } catch (error) {
    console.error('Error fetching courses:', error);
    res.status(500).json({ message: 'Error fetching courses' });
  }
});

// Get all grades
app.get('/api/grades', async (req, res) => {
  try {
    const connection = await mysql.createConnection(dbConfig);
    const [rows] = await connection.execute('SELECT * FROM Enrollments');
    await connection.end();
    res.json(rows);
  } catch (error) {
    console.error('Error fetching grades:', error);
    res.status(500).json({ message: 'Error fetching grades' });
  }
});

app.listen(PORT, '0.0.0.0', () => {
  console.log(`Server is running on port ${PORT}`);
  console.log(`Health check: http://localhost:${PORT}/health`);
});
