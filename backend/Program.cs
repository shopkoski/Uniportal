using Microsoft.EntityFrameworkCore;
using UniPortalBackend.Data;
using UniPortalBackend.Models;
using UniPortalBackend.Services;
using System.Security.Cryptography;
using System.Text;
using Pomelo.EntityFrameworkCore.MySql.Infrastructure;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

// Add Entity Framework
builder.Services.AddDbContext<ApplicationDbContext>(options =>
    options.UseMySql(builder.Configuration.GetConnectionString("DefaultConnection"), 
        new MySqlServerVersion(new Version(8, 0, 21))));

// Add JWT Service
builder.Services.AddScoped<JwtService>();

// Add CORS
builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll", policy =>
    {
        policy.AllowAnyOrigin()
              .AllowAnyMethod()
              .AllowAnyHeader();
    });
});

var app = builder.Build();

// Ensure database is created and migrated (commented out for now to avoid startup issues)
// using (var scope = app.Services.CreateScope())
// {
//     var context = scope.ServiceProvider.GetRequiredService<ApplicationDbContext>();
//     context.Database.EnsureCreated();
// }

// Configure the HTTP request pipeline.
app.UseCors("AllowAll");
app.UseHttpsRedirection();

app.MapControllers();

// Health check endpoint
app.MapGet("/health", () => new { status = "healthy", timestamp = DateTime.UtcNow });

// Test endpoint
app.MapGet("/api/test", () => new { message = "Backend is working!", timestamp = DateTime.UtcNow });

// Login endpoint (simplified for now)
app.MapPost("/api/auth/login", (LoginRequest request) =>
{
    if (string.IsNullOrEmpty(request.Email) || string.IsNullOrEmpty(request.Password))
    {
        return Results.BadRequest(new { message = "Email and password are required" });
    }

    // Hardcoded users for testing (matching our database data)
    var users = new[]
    {
        new { Id = 1, Email = "admin@uniportal.com", Password = "admin123", Role = "Admin", FirstName = "Admin", LastName = "User" },
        new { Id = 2, Email = "john@student.uniportal.com", Password = "admin123", Role = "Student", FirstName = "John", LastName = "Doe" },
        new { Id = 3, Email = "jane@student.uniportal.com", Password = "admin123", Role = "Student", FirstName = "Jane", LastName = "Smith" },
        new { Id = 4, Email = "k.stefanovska@univ.mk", Password = "admin123", Role = "Professor", FirstName = "Kristina", LastName = "Stefanovska" }
    };

    var user = users.FirstOrDefault(u => u.Email == request.Email && u.Password == request.Password);

    if (user == null)
    {
        return Results.BadRequest(new { message = "Invalid email or password" });
    }

    return Results.Ok(new LoginResponse
    {
        Success = true,
        Token = "fake-jwt-token-for-testing",
        Message = "Login successful",
        User = new UserInfo
        {
            Id = user.Id,
            Email = user.Email,
            FirstName = user.FirstName,
            LastName = user.LastName,
            Role = user.Role
        }
    });
});

app.Run();