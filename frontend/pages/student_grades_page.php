<?php
declare(strict_types=1);

// Show PHP errors while developing
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Server-side DB calls removed. The page fetches data via get_student_id.php and get_student_grades.php.
$grades = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>My Grades - Uni Portal</title>
    <style>
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-dark: #5a5f9e;
            --background: #fff;
            --sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-text: #fff;
            --table-header: #f5f5f5;
            --table-border: #e0e0e0;
        }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background: var(--primary);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            padding: 32px 0 0 0;
        }
        .sidebar .logo {
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 8px;
            padding-left: 30px;
        }
        
        .portal-text {
            font-size: 0.9rem;
        }
        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .sidebar nav a {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 12px 32px;
            font-size: 1rem;
            border-left: 4px solid transparent;
            transition: background 0.2s, border-color 0.2s;
            display: block;
        }
        .sidebar nav a.active, .sidebar nav a:hover {
            background: rgba(255,255,255,0.08);
            border-left: 4px solid #fff;
        }
        
        .language-link {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .language-link:hover {
            opacity: 1;
        }
        .main-content {
            flex: 1;
            background: var(--background);
            border-radius: 0 8px 8px 0;
            padding: 40px 40px 100px 40px;
            min-height: 100vh;
        }
        .main-content h1 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 24px;
            color: #222;
        }
        .table-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            padding: 24px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
        }
        th {
            background: var(--table-header);
            font-size: 0.98rem;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid var(--table-border);
        }
        td {
            border-bottom: 1px solid var(--table-border);
            font-size: 0.97rem;
            color: #444;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .grade-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
            min-width: 40px;
        }
        .grade-a {
            background: #e8f5e8;
            color: #2e7d32;
        }
        .grade-b {
            background: #e3f2fd;
            color: #1976d2;
        }
        .grade-c {
            background: #fff3e0;
            color: #f57c00;
        }
        .grade-d {
            background: #ffebee;
            color: #d32f2f;
        }
        .grade-f {
            background: #fce4ec;
            color: #c2185b;
        }
        .course-tag {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        @media (max-width: 900px) {
            .main-content {
                padding: 24px 8px;
            }
            .sidebar {
                width: 60px;
                padding: 16px 0 0 0;
            }
            .sidebar .logo {
                font-size: 0.9rem;
                padding-left: 20px;
            }
            
            .portal-text {
                font-size: 0.8rem;
            }
            .sidebar nav a {
                padding: 10px 10px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="logo">
            <span>🎓</span> <span class="portal-text">Uni Portal</span>
        </div>
        <nav>
            <a href="home.php" class="nav-home">Home</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='student_page.php' : Auth.showLoginPopup(); return false;" class="nav-students">Students</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='courses_page.php' : Auth.showLoginPopup(); return false;" class="nav-courses">Courses</a>
            <a href="#" class="active nav-grades">My Grades</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='professor_page.php' : Auth.showLoginPopup(); return false;" class="nav-professor">Professor</a>
            <a href="contact_page.php" class="nav-contact">Contact</a>
            <div class="language-flags" style="display: flex; justify-content: flex-start; gap: 15px; padding: 12px 32px;">
                <button onclick="changeLanguage('mk')" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); cursor: pointer; padding: 4px 8px; border-radius: 4px; color: white; font-size: 12px;">
                    MK
                </button>
                <button onclick="changeLanguage('en')" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); cursor: pointer; padding: 4px 8px; border-radius: 4px; color: white; font-size: 12px;">
                    EN
                </button>
                <button onclick="changeLanguage('al')" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3); cursor: pointer; padding: 4px 8px; border-radius: 4px; color: white; font-size: 12px;">
                    AL
                </button>
            </div>
        </nav>
    </aside>
    <main class="main-content">
        <h1 class="page-title">My Grades</h1>

        <?php if (!empty($grades)): ?>
            <div class="table-container">
                <table>
                    <thead>
                    <tr>
                        <th>Course</th>
                        <th>Grade</th>
                        <th>Letter Grade</th>
                        <th>Professor</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td>
                                <span class="course-tag"><?= htmlspecialchars((string)$grade['course_name']) ?></span>
                            </td>
                            <td>
                                <span class="grade-badge grade-<?= strtolower($grade['letter_grade']) ?>">
                                    <?= htmlspecialchars((string)$grade['grade']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="grade-badge grade-<?= strtolower($grade['letter_grade']) ?>">
                                    <?= htmlspecialchars((string)$grade['letter_grade']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars((string)$grade['professor_name'] ?? 'Not assigned') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No grades available. Please log in to view your grades.</p>
        <?php endif; ?>
    </main>
</div>

<script src="../assets/js/auth.js"></script>
<script src="../assets/js/translations.js"></script>
</body>
</html>
