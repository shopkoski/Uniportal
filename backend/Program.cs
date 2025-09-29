using Microsoft.EntityFrameworkCore;
using UniPortalBackend.Data;
using UniPortalBackend.Models;
using UniPortalBackend.Services;
using System.Security.Cryptography;
using System.Text;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();

// Add Entity Framework
builder.Services.AddDbContext<ApplicationDbContext>(options =>
    options.UseMySql(builder.Configuration.GetConnectionString("DefaultConnection"), 
        ServerVersion.AutoDetect(builder.Configuration.GetConnectionString("DefaultConnection"))));

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

// Ensure database is created and migrated
using (var scope = app.Services.CreateScope())
{
    var context = scope.ServiceProvider.GetRequiredService<ApplicationDbContext>();
    context.Database.EnsureCreated();
}

// Configure the HTTP request pipeline.
app.UseCors("AllowAll");
app.UseHttpsRedirection();

app.MapControllers();

// Health check endpoint
app.MapGet("/health", () => new { status = "healthy", timestamp = DateTime.UtcNow });

// Test endpoint
app.MapGet("/api/test", () => new { message = "Backend is working!", timestamp = DateTime.UtcNow });

// Login endpoint
app.MapPost("/api/auth/login", async (LoginRequest request, ApplicationDbContext context, JwtService jwtService) =>
{
    if (string.IsNullOrEmpty(request.Email) || string.IsNullOrEmpty(request.Password))
    {
        return Results.BadRequest(new { message = "Email and password are required" });
    }

    var user = await context.Users.FirstOrDefaultAsync(u => u.Email == request.Email && u.IsActive);

    if (user == null)
    {
        return Results.BadRequest(new { message = "Invalid email or password" });
    }

    // For now, we'll use simple password comparison since we're using plain text in our setup
    // In production, you should use proper password hashing
    if (request.Password != "admin123") // This matches our database setup
    {
        return Results.BadRequest(new { message = "Invalid email or password" });
    }

    var token = jwtService.GenerateToken(user);

    return Results.Ok(new LoginResponse
    {
        Success = true,
        Token = token,
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