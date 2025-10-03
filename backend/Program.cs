using MySqlConnector;

var builder = WebApplication.CreateBuilder(args);

// Add CORS
builder.Services.AddCors(options =>
{
    options.AddPolicy("AllowAll", policy =>
    {
        policy.WithOrigins("https://uniportal-fr.azurewebsites.net", "https://uniportal-fr-ezc2h8g7gsgxd9be.canadacentral-01.azurewebsites.net", "http://localhost:3000", "http://localhost:8080")
              .AllowAnyMethod()
              .AllowAnyHeader()
              .AllowCredentials();
    });
});

var app = builder.Build();

// Configure the HTTP request pipeline.
app.UseCors("AllowAll");

app.MapGet("/health", () => "OK");
app.MapGet("/api/test", () => "Backend is working! v3.1 - " + DateTime.Now.ToString("yyyy-MM-dd HH:mm"));

// Safety: minimal grades endpoint removed - using full DB version below

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
app.MapGet("/api/students", async (HttpContext ctx) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new MySqlConnection(connStr);
    await conn.OpenAsync();
    var cmd = new MySqlCommand("SELECT student_id, first_name, last_name, email, enrollment_year FROM Students_Table_1", conn);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        results.Add(new
        {
            student_id = reader.GetInt32(0),
            first_name = reader.GetString(1),
            last_name = reader.GetString(2),
            email = reader.GetString(3),
            enrollment_year = reader.IsDBNull(4) ? (int?)null : reader.GetInt32(4)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

app.MapGet("/api/courses", async () =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new MySqlConnection(connStr);
    await conn.OpenAsync();
    var cmd = new MySqlCommand("SELECT course_id, course_name, credits FROM Courses_Table_1", conn);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        results.Add(new
        {
            course_id = reader.GetInt32(0),
            course_name = reader.GetString(1),
            credits = reader.IsDBNull(2) ? (int?)null : reader.GetInt32(2)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

app.MapGet("/api/professors", async () =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new MySqlConnection(connStr);
    await conn.OpenAsync();
    var cmd = new MySqlCommand("SELECT professor_id, first_name, last_name, email, department FROM Professors_Table_1", conn);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        results.Add(new
        {
            professor_id = reader.GetInt32(0),
            first_name = reader.GetString(1),
            last_name = reader.GetString(2),
            email = reader.GetString(3),
            department = reader.IsDBNull(4) ? null : reader.GetString(4)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

app.MapGet("/api/grades", async () =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new MySqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT enrollment_id, student_id, course_id, grade FROM Enrollments_Table_1";
    var cmd = new MySqlCommand(sql, conn);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        results.Add(new
        {
            enrollment_id = reader.IsDBNull(0) ? (int?)null : reader.GetInt32(0),
            student_id = reader.GetInt32(1),
            course_id = reader.GetInt32(2),
            grade = reader.IsDBNull(3) ? (decimal?)null : reader.GetDecimal(3)
        });
    }
    return Results.Ok(new { success = true, data = results });
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