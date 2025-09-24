# Uni Portal Backend

This folder contains the .NET Core backend API for the Uni Portal application.

## Structure

- **Controllers**: API endpoints for authentication and user management
- **Models**: Data models and entities
- **Data**: Database context and migrations
- **Services**: Business logic services
- **Configuration**: App settings and database configuration

## How to Run

1. **Navigate to Backend Directory**:
   ```bash
   cd backend
   ```

2. **Restore Dependencies**:
   ```bash
   dotnet restore
   ```

3. **Run Database Migrations**:
   ```bash
   dotnet ef database update
   ```

4. **Start the Application**:
   ```bash
   dotnet run
   ```

5. **Access the API**:
   - API will be available at `http://localhost:5104`
   - Swagger documentation at `http://localhost:5104/swagger`

## Features

- **JWT Authentication**: Secure token-based authentication
- **User Management**: Admin, Professor, Student role management
- **Database Integration**: Entity Framework Core with MySQL
- **API Endpoints**: RESTful API for frontend integration
- **CORS Support**: Cross-origin resource sharing enabled

## Default Users

- **Admin**: `admin@uniportal.com` / `admin123`
- **Students**: `john@student.uniportal.com` / `admin123`

## Dependencies

- .NET 6.0+
- Entity Framework Core
- MySQL/MariaDB database
- JWT Bearer authentication

## API Endpoints

- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `GET /api/auth/me` - Get current user info

