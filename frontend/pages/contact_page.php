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
    <title>Contact - Uni Portal</title>
    <style>
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-dark: #5a5f9e;
            --background: #fff;
            --sidebar-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-text: #fff;
            --table-header: #f5f5f5;
            --table-border: #e0e0e0;
            --primary-blue: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-blue: #667eea;
            --light-blue: #f0f2ff;
            --white: #ffffff;
            --gray: #f5f5f5;
            --dark-gray: #333333;
            --border: #e0e0e0;
            --header-light: linear-gradient(135deg, #5a5f9e 0%, #6a4a8a 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
            height: calc(100vh - 120px);
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
        
        .main-content {
            flex: 1;
            background: var(--background);
            border-radius: 0 8px 8px 0;
            padding: 40px 40px 100px 40px;
            min-height: 100vh;
        }
        
        .contact-page {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .contact-page h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary-blue);
            margin-bottom: 10px;
            text-align: center;
        }
        
        .contact-page hr {
            border: none;
            height: 2px;
            background: var(--primary-blue);
            margin-bottom: 40px;
            width: 100px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-top: 40px;
        }
        
        .form-section {
            background: var(--white);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .form-section h2 {
            font-size: 1.8rem;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }
        
        .form-section p {
            color: var(--dark-gray);
            margin-bottom: 30px;
            opacity: 0.8;
        }
        
        .form-section form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .form-section label {
            font-weight: 600;
            color: var(--dark-gray);
            margin-bottom: 5px;
        }
        
        .form-section input,
        .form-section textarea {
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
            font-family: inherit;
        }
        
        .form-section input:focus,
        .form-section textarea:focus {
            outline: none;
            border-color: var(--secondary-blue);
        }
        
        .form-section textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-section button {
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        
        .form-section button:hover {
            background: var(--secondary-blue);
        }
        
        .map-section {
            background: var(--light-blue);
            border-radius: 12px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .map-section h3 {
            font-size: 1.5rem;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .contact-item .icon {
            width: 40px;
            height: 40px;
            background: var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 18px;
        }
        
        .contact-item .info h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }
        
        .contact-item .info p {
            color: var(--dark-gray);
            opacity: 0.8;
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
                font-size: 1.1rem;
            }
            .sidebar nav a {
                padding: 10px 10px;
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 768px) {
            .contact-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .form-section,
            .map-section {
                padding: 30px 20px;
            }
            
            .sidebar .logo {
                font-size: 0.9rem;
                padding-left: 20px;
            }
            
            .portal-text {
                font-size: 0.8rem;
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
            <a href="home.php">Home</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='student_page.php' : Auth.showLoginPopup(); return false;">Students</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='courses_page.php' : Auth.showLoginPopup(); return false;">Courses</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='grades_page.php' : Auth.showLoginPopup(); return false;">Grades</a>
            <a href="#" onclick="Auth.isLoggedIn() ? window.location.href='professor_page.php' : Auth.showLoginPopup(); return false;">Professor</a>
            <a href="#" class="active nav-contact">Contact</a>
            <div class="language-flags" style="display: flex; justify-content: flex-start; gap: 15px; padding: 12px 32px;">
                <button onclick="changeLanguage('mk')" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <img src="../assets/images/flags/mk.png" alt="Македонски" width="18" height="14" style="border-radius: 2px;">
                </button>
                <button onclick="changeLanguage('en')" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <img src="../assets/images/flags/en.png" alt="English" width="18" height="14" style="border-radius: 2px;">
                </button>
                <button onclick="changeLanguage('al')" style="background: none; border: none; cursor: pointer; padding: 0;">
                    <img src="../assets/images/flags/al.png" alt="Shqip" width="18" height="14" style="border-radius: 2px;">
                </button>
            </div>
        </nav>
    </aside>
    <main class="main-content">
        <div class="contact-page">
        <h1>Contact</h1>
        <hr>
        <div class="contact-content">
            <!--Form Section-->
            <div class="form-section">
                <h2>Leave Contact With Us!</h2>
                <p>Have a question? We will help you. Send us a message and we will answer as soon as possible.</p>
                <form action="https://formsubmit.co/sopkoski.goce@gmail.com" method="POST">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>

                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject">

                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="8"></textarea>

                    <button type="submit">Send</button>
                </form>
            </div>

            <!--Map and Contact Info Section-->
            <div class="map-section">
                <h3>Get in Touch</h3>
                <p>We'd love to hear from you. Here's how you can reach us:</p>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="icon">📍</div>
                        <div class="info">
                            <h4>Address</h4>
                            <p>University Campus, Skopje, Macedonia</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="icon">📧</div>
                        <div class="info">
                            <h4>Email</h4>
                            <p>info@uniportal.edu.mk</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="icon">📞</div>
                        <div class="info">
                            <h4>Phone</h4>
                            <p>+389 2 1234 567</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="icon">🕒</div>
                        <div class="info">
                            <h4>Working Hours</h4>
                            <p>Monday - Friday: 8:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

    <script src="../assets/js/auth.js"></script>
    <script src="../assets/js/translations.js"></script>
    
    <script>
    // Update logout button with user info when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize translations
        updatePageContent();
        
        if (Auth.isLoggedIn()) {
            const logoutText = document.querySelector('.logout-text');
            if (logoutText) {
                const user = Auth.getUser();
                const userName = user?.firstName || user?.email || 'User';
                const userRole = user?.role || 'User';
                
                // Translate role
                let translatedRole;
                if (userRole.toLowerCase() === 'admin') {
                    translatedRole = t('role_admin');
                } else if (userRole.toLowerCase() === 'student') {
                    translatedRole = t('role_student');
                } else {
                    translatedRole = userRole;
                }
                
                // Use the template with user info
                const logoutTemplate = t('logout_with_user');
                logoutText.textContent = logoutTemplate
                    .replace('{name}', userName)
                    .replace('{role}', translatedRole);
            }
        }
    });
    </script>
</body>
</html>
