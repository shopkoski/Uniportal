# Uni Portal - Academic Management System

A comprehensive academic management system with a PHP frontend and .NET Core backend.

## Project Structure

```
iknowlike/
├── frontend/          # PHP-based frontend application
├── backend/           # .NET Core backend API
├── flags/            # Language flag assets
└── README.md         # This file
```

## Quick Start

### 1. Start the Backend
```bash
cd backend
dotnet restore
dotnet ef database update
dotnet run
```
The backend will be available at `http://localhost:5104`

### 2. Start the Frontend
```bash
cd frontend
php -S localhost:8000
```
The frontend will be available at `http://localhost:8000`

### 3. Access the Application
- Open your browser and go to `http://localhost:8000`
- Login with admin credentials: `admin@uniportal.com` / `admin123`

## Features

### Frontend (PHP)
- **Multi-language Support**: English, Macedonian, Albanian
- **Role-based Access Control**: Admin, Professor, Student roles
- **CRUD Operations**: Add/View students, courses, grades
- **Responsive Design**: Modern UI with modal forms
- **Real-time Updates**: Dynamic content loading

### Backend (.NET Core)
- **JWT Authentication**: Secure token-based authentication
- **User Management**: Role-based user system
- **Database Integration**: Entity Framework Core with MySQL
- **RESTful API**: Clean API endpoints for frontend integration

## Technology Stack

### Frontend
- PHP 7.4+
- HTML5, CSS3, JavaScript
- MySQL/MariaDB

### Backend
- .NET 6.0
- Entity Framework Core
- JWT Bearer Authentication
- MySQL/MariaDB

## Development

### Prerequisites
- .NET 6.0 SDK
- PHP 7.4+
- MySQL/MariaDB
- Modern web browser

### Database Setup
1. Create a MySQL database named `my_test_db`
2. Update connection strings in `backend/appsettings.json` and `frontend/config.php`
3. Run migrations: `dotnet ef database update`

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is for educational purposes.

