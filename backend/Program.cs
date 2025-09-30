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

// TEMP endpoints to unblock the UI until full DB API is restored
app.MapGet("/api/students", () =>
{
    var students = new[]
    {
        new { student_id = 1, first_name = "John", last_name = "Doe", email = "john@student.uniportal.com", enrollment_year = 2022 },
        new { student_id = 2, first_name = "Jane", last_name = "Smith", email = "jane@student.uniportal.com", enrollment_year = 2022 }
    };
    return Results.Ok(new { success = true, data = students });
});

app.MapGet("/api/courses", () =>
{
    var courses = new[]
    {
        new { course_id = 1, course_name = "Databases", credits = 6 },
        new { course_id = 2, course_name = "Web Development", credits = 6 }
    };
    return Results.Ok(new { success = true, data = courses });
});

app.MapGet("/api/professors", () =>
{
    var professors = new[]
    {
        new { professor_id = 1, first_name = "Kristina", last_name = "Stefanovska", email = "k.stefanovska@univ.mk", department = "Computer Science" }
    };
    return Results.Ok(new { success = true, data = professors });
});

app.MapGet("/api/grades", () =>
{
    var grades = new[]
    {
        new { enrollment_id = 1, student_id = 1, course_id = 1, grade = 8.00 },
        new { enrollment_id = 2, student_id = 2, course_id = 1, grade = 9.00 }
    };
    return Results.Ok(new { success = true, data = grades });
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