<?php
declare(strict_types=1);

// Show PHP errors while developing
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uni Portal - Home</title>
    <style>
        :root {
            --primary-blue: #1a4a5e;
            --secondary-blue: #1976d2;
            --light-blue: #e3f2fd;
            --white: #ffffff;
            --gray: #f5f5f5;
            --dark-gray: #333333;
            --border: #e0e0e0;
            --header-light: #2a5a6e;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: var(--white);
            color: var(--dark-gray);
        }
        
        .header {
            background: var(--primary-blue);
            color: var(--white);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-top {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .header-top-right {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto;
        }
        
        .header-bottom {
            background: var(--header-light);
            padding: 15px 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        
        .language-buttons {
            display: flex;
            gap: 10px;
        }
        
        .language-buttons button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: transform 0.2s ease;
        }
        
        .language-buttons button:hover {
            transform: scale(1.1);
        }
        
        .language-buttons img {
            width: 20px;
            height: 15px;
            border-radius: 2px;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            background: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            color: var(--primary-blue);
        }
        
        .header-text {
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-bar {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-left: auto;
            font-size: 12px;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.1);
            color: var(--white);
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.5);
        }
        
        .main-content {
            padding: 20px;
            padding-bottom: 100px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: var(--light-blue);
            border-radius: 12px;
        }
        
        .welcome-title {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }
        
        .welcome-subtitle {
            font-size: 16px;
            color: var(--dark-gray);
            opacity: 0.8;
        }
        
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .grid-item {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        
        .grid-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-color: var(--secondary-blue);
        }
        
        .grid-item:active {
            transform: translateY(0);
        }
        
        .icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 15px;
            background: var(--light-blue);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--secondary-blue);
        }
        
        .grid-item:hover .icon {
            background: var(--secondary-blue);
            color: var(--white);
        }
        
        .grid-item-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }
        
        .grid-item-subtitle {
            font-size: 12px;
            color: var(--dark-gray);
            opacity: 0.6;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-blue);
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: var(--dark-gray);
            opacity: 0.7;
        }
        
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .stats-section {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .grid-container {
                grid-template-columns: 1fr;
            }
            
            .stats-section {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-top">
            <div class="logo">🎓</div>
            <div class="header-text">Uni Portal</div>
            <div class="header-top-right">
                <div class="language-buttons">
                    <button onclick="changeLanguage('mk')" title="Македонски"><img src="flags/mk.png" alt="Macedonian" width="20" height="15"></button>
                    <button onclick="changeLanguage('en')" title="English"><img src="flags/en.png" alt="English" width="20" height="15"></button>
                    <button onclick="changeLanguage('al')" title="Shqip"><img src="flags/al.png" alt="Albanian" width="20" height="15"></button>
                </div>

            </div>
        </div>
        <div class="header-bottom">
            <button class="logout-btn" id="loginButton" onclick="Auth.isLoggedIn() ? Auth.logout() : Auth.showLoginPopup()">Log in</button>
        </div>
    </div>
    
    <div class="main-content">
        <div class="welcome-section">
            <div class="welcome-title">Welcome to Uni Portal</div>
            <div class="welcome-subtitle">Your academic management system</div>
        </div>
        
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number">10</div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">9</div>
                <div class="stat-label">Available Courses</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5</div>
                <div class="stat-label">Professors</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">20</div>
                <div class="stat-label">Enrollments</div>
            </div>
        </div>
        
        <div class="grid-container">
            <div class="grid-item" onclick="window.location.href='home.php'">
                <div class="icon">🏠</div>
                <div class="grid-item-title">Home</div>
                <div class="grid-item-subtitle">Return to dashboard</div>
            </div>
            
            <div class="grid-item" onclick="Auth.isLoggedIn() ? window.location.href='student_page.php' : Auth.showLoginPopup()">
                <div class="icon">👥</div>
                <div class="grid-item-title">Students</div>
                <div class="grid-item-subtitle">Manage student records</div>
            </div>
            
            <div class="grid-item" onclick="Auth.isLoggedIn() ? window.location.href='courses_page.php' : Auth.showLoginPopup()">
                <div class="icon">📚</div>
                <div class="grid-item-title">Courses</div>
                <div class="grid-item-subtitle">View course catalog</div>
            </div>
            
            <div class="grid-item" onclick="Auth.isLoggedIn() ? window.location.href='grades_page.php' : Auth.showLoginPopup()">
                <div class="icon">📊</div>
                <div class="grid-item-title">Grades</div>
                <div class="grid-item-subtitle">Check academic performance</div>
            </div>
            
            <div class="grid-item" onclick="Auth.isLoggedIn() ? window.location.href='professor_page.php' : Auth.showLoginPopup()">
                <div class="icon">👨‍🏫</div>
                <div class="grid-item-title">Professor</div>
                <div class="grid-item-subtitle">Faculty information</div>
            </div>
            
            <div class="grid-item" onclick="window.location.href='contact_page.php'">
                <div class="icon">📞</div>
                <div class="grid-item-title">Contact</div>
                <div class="grid-item-subtitle">Get in touch</div>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    
    <script src="auth.js"></script>
    <script src="translations.js"></script>
    
    <script>
    // Update login button state when page loads
    document.addEventListener('DOMContentLoaded', function() {
        Auth.updateLoginButton();
    });
    </script>
</body>
</html>
