var builder = WebApplication.CreateBuilder(args);

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

// Configure the HTTP request pipeline.
app.UseCors("AllowAll");

app.MapGet("/health", () => "OK");
app.MapGet("/api/test", () => "Backend is working!");

// Login endpoint
app.MapPost("/api/auth/login", (LoginRequest request) =>
{
    if (string.IsNullOrEmpty(request.Email) || string.IsNullOrEmpty(request.Password))
    {
        return Results.BadRequest(new { message = "Email and password are required" });
    }

    // Hardcoded users for testing
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

public class LoginRequest
{
    public string Email { get; set; } = string.Empty;
    public string Password { get; set; } = string.Empty;
}

public class LoginResponse
{
    public bool Success { get; set; }
    public string Token { get; set; } = string.Empty;
    public string Message { get; set; } = string.Empty;
    public UserInfo? User { get; set; }
}

public class UserInfo
{
    public int Id { get; set; }
    public string Email { get; set; } = string.Empty;
    public string FirstName { get; set; } = string.Empty;
    public string LastName { get; set; } = string.Empty;
    public string Role { get; set; } = string.Empty;
}