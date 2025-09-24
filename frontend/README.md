# Uni Portal Frontend

This folder contains the PHP-based frontend for the Uni Portal application.

## Structure

- **PHP Pages**: Main application pages (home.php, student_page.php, courses_page.php, etc.)
- **JavaScript Files**: Client-side functionality (auth.js, translations.js)
- **API Endpoints**: PHP API endpoints for data operations
- **Data Files**: CSV files for database seeding
- **Configuration**: Database configuration (config.php)

## How to Run

1. **Start PHP Development Server**:
   ```bash
   cd frontend
   php -S localhost:8000
   ```

2. **Access the Application**:
   - Open your browser and go to `http://localhost:8000`
   - The main entry point is `home.php`

## Features

- **Multi-language Support**: English, Macedonian, Albanian
- **Role-based Access Control**: Admin, Professor, Student roles
- **CRUD Operations**: Add/View students, courses, grades
- **Responsive Design**: Works on desktop and mobile
- **Modal Forms**: Interactive add/edit forms

## Dependencies

- PHP 7.4+
- MySQL/MariaDB database
- Modern web browser with JavaScript enabled

## Backend Integration

This frontend connects to the .NET backend API running on `localhost:5104` for authentication and user management.

