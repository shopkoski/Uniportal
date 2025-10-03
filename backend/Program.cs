using Microsoft.Data.SqlClient;

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
app.MapGet("/api/test", () => "Backend is working! v4.3 - Azure SQL with Relationships - " + DateTime.Now.ToString("yyyy-MM-dd HH:mm"));

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
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var cmd = new SqlCommand("SELECT student_id, first_name, last_name, email, enrollment_year FROM Students_Table_1", conn);
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
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT 
        c.course_id, 
        c.course_name, 
        c.credits,
        COUNT(e.student_id) as enrolled_students
    FROM Courses_Table_1 c
    LEFT JOIN Enrollments_Table_1 e ON c.course_id = e.course_id
    GROUP BY c.course_id, c.course_name, c.credits";
    var cmd = new SqlCommand(sql, conn);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        results.Add(new
        {
            course_id = reader.GetInt32(0),
            course_name = reader.GetString(1),
            credits = reader.IsDBNull(2) ? (int?)null : reader.GetInt32(2),
            enrolled_students = reader.GetInt32(3)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

app.MapGet("/api/professors", async () =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT 
        p.professor_id, 
        p.first_name, 
        p.last_name, 
        p.email, 
        p.department,
        COUNT(c.course_id) as course_count
    FROM Professors_Table_1 p
    LEFT JOIN Courses_Table_1 c ON p.professor_id = c.professor_id
    GROUP BY p.professor_id, p.first_name, p.last_name, p.email, p.department";
    var cmd = new SqlCommand(sql, conn);
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
            department = reader.IsDBNull(4) ? null : reader.GetString(4),
            course_count = reader.GetInt32(5)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

app.MapGet("/api/grades", async () =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT 
        e.enrollment_id, 
        e.student_id, 
        s.first_name + ' ' + s.last_name as student_name,
        e.course_id, 
        c.course_name,
        e.grade,
        ISNULL(p.first_name + ' ' + p.last_name, 'Not assigned') as professor_name
    FROM Enrollments_Table_1 e
    LEFT JOIN Students_Table_1 s ON e.student_id = s.student_id
    LEFT JOIN Courses_Table_1 c ON e.course_id = c.course_id
    LEFT JOIN Professors_Table_1 p ON c.professor_id = p.professor_id";
    var cmd = new SqlCommand(sql, conn);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        var grade = reader.IsDBNull(5) ? (decimal?)null : reader.GetDecimal(5);
        var letterGrade = grade.HasValue ? GetLetterGrade(grade.Value) : "N/A";
        
        results.Add(new
        {
            enrollment_id = reader.IsDBNull(0) ? (int?)null : reader.GetInt32(0),
            student_id = reader.GetInt32(1),
            student_name = reader.IsDBNull(2) ? null : reader.GetString(2),
            course_id = reader.GetInt32(3),
            course_name = reader.IsDBNull(4) ? null : reader.GetString(4),
            grade = grade,
            letter_grade = letterGrade,
            professor_name = reader.IsDBNull(6) ? "Not assigned" : reader.GetString(6)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

// Additional endpoints for frontend functionality
app.MapGet("/api/courses/{courseId}/students", async (int courseId) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT COUNT(*) as student_count FROM Enrollments_Table_1 WHERE course_id = @courseId";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@courseId", courseId);
    var count = await cmd.ExecuteScalarAsync();
    return Results.Ok(new { success = true, student_count = count });
});

app.MapGet("/api/professors/{professorId}/courses", async (int professorId) =>
{
    // For now, return 0 courses since we don't have professor-course relationships
    return Results.Ok(new { success = true, course_count = 0 });
});

app.MapGet("/api/students/{studentId}/courses", async (int studentId) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT 
        c.course_id, 
        c.course_name, 
        c.credits,
        e.grade
    FROM Enrollments_Table_1 e
    LEFT JOIN Courses_Table_1 c ON e.course_id = c.course_id
    WHERE e.student_id = @studentId";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@studentId", studentId);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        results.Add(new
        {
            course_id = reader.GetInt32(0),
            course_name = reader.GetString(1),
            credits = reader.IsDBNull(2) ? (int?)null : reader.GetInt32(2),
            grade = reader.IsDBNull(3) ? (decimal?)null : reader.GetDecimal(3)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

// Dynamic CRUD operations
app.MapPost("/api/students", async (StudentRequest request) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"INSERT INTO Students_Table_1 (first_name, last_name, email, enrollment_year) 
                VALUES (@first_name, @last_name, @email, @enrollment_year);
                SELECT SCOPE_IDENTITY() as student_id;";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@first_name", request.first_name);
    cmd.Parameters.AddWithValue("@last_name", request.last_name);
    cmd.Parameters.AddWithValue("@email", request.email);
    cmd.Parameters.AddWithValue("@enrollment_year", request.enrollment_year);
    var studentId = await cmd.ExecuteScalarAsync();
    return Results.Ok(new { success = true, student_id = studentId });
});

app.MapDelete("/api/students/{id}", async (int id) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = "DELETE FROM Students_Table_1 WHERE student_id = @id";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@id", id);
    var rowsAffected = await cmd.ExecuteNonQueryAsync();
    return Results.Ok(new { success = rowsAffected > 0, message = rowsAffected > 0 ? "Student deleted successfully" : "Student not found" });
});

app.MapPost("/api/courses", async (CourseRequest request) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"INSERT INTO Courses_Table_1 (course_name, credits, professor_id) 
                VALUES (@course_name, @credits, @professor_id);
                SELECT SCOPE_IDENTITY() as course_id;";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@course_name", request.course_name);
    cmd.Parameters.AddWithValue("@credits", request.credits);
    cmd.Parameters.AddWithValue("@professor_id", request.professor_id ?? (object)DBNull.Value);
    var courseId = await cmd.ExecuteScalarAsync();
    return Results.Ok(new { success = true, course_id = courseId });
});

app.MapDelete("/api/courses/{id}", async (int id) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = "DELETE FROM Courses_Table_1 WHERE course_id = @id";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@id", id);
    var rowsAffected = await cmd.ExecuteNonQueryAsync();
    return Results.Ok(new { success = rowsAffected > 0, message = rowsAffected > 0 ? "Course deleted successfully" : "Course not found" });
});

app.MapPost("/api/professors", async (ProfessorRequest request) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"INSERT INTO Professors_Table_1 (first_name, last_name, email, department) 
                VALUES (@first_name, @last_name, @email, @department);
                SELECT SCOPE_IDENTITY() as professor_id;";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@first_name", request.first_name);
    cmd.Parameters.AddWithValue("@last_name", request.last_name);
    cmd.Parameters.AddWithValue("@email", request.email);
    cmd.Parameters.AddWithValue("@department", request.department);
    var professorId = await cmd.ExecuteScalarAsync();
    return Results.Ok(new { success = true, professor_id = professorId });
});

app.MapDelete("/api/professors/{id}", async (int id) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = "DELETE FROM Professors_Table_1 WHERE professor_id = @id";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@id", id);
    var rowsAffected = await cmd.ExecuteNonQueryAsync();
    return Results.Ok(new { success = rowsAffected > 0, message = rowsAffected > 0 ? "Professor deleted successfully" : "Professor not found" });
});

// Course details endpoint for frontend compatibility
app.MapGet("/api/courses/{courseId}/details", async (int courseId) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT 
        c.course_id,
        c.course_name,
        c.credits,
        ISNULL(p.first_name + ' ' + p.last_name, 'Not assigned') as professor_name,
        COUNT(e.student_id) as enrolled_students
    FROM Courses_Table_1 c
    LEFT JOIN Professors_Table_1 p ON c.professor_id = p.professor_id
    LEFT JOIN Enrollments_Table_1 e ON c.course_id = e.course_id
    WHERE c.course_id = @courseId
    GROUP BY c.course_id, c.course_name, c.credits, p.first_name, p.last_name";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@courseId", courseId);
    var reader = await cmd.ExecuteReaderAsync();
    if (await reader.ReadAsync())
    {
        var result = new
        {
            course_id = reader.GetInt32(0),
            course_name = reader.GetString(1),
            credits = reader.GetInt32(2),
            professor_name = reader.IsDBNull(3) ? "Not assigned" : reader.GetString(3),
            enrolled_students = reader.GetInt32(4)
        };
        return Results.Ok(new { success = true, data = result });
    }
    return Results.NotFound(new { success = false, message = "Course not found" });
});

// Professor course details endpoint
app.MapGet("/api/professors/{professorId}/courses", async (int professorId) =>
{
    var connStr = builder.Configuration.GetConnectionString("DefaultConnection");
    await using var conn = new SqlConnection(connStr);
    await conn.OpenAsync();
    var sql = @"SELECT 
        c.course_id,
        c.course_name,
        c.credits,
        COUNT(e.student_id) as enrolled_students
    FROM Courses_Table_1 c
    LEFT JOIN Enrollments_Table_1 e ON c.course_id = e.course_id
    WHERE c.professor_id = @professorId
    GROUP BY c.course_id, c.course_name, c.credits";
    var cmd = new SqlCommand(sql, conn);
    cmd.Parameters.AddWithValue("@professorId", professorId);
    var reader = await cmd.ExecuteReaderAsync();
    var results = new List<object>();
    while (await reader.ReadAsync())
    {
        results.Add(new
        {
            course_id = reader.GetInt32(0),
            course_name = reader.GetString(1),
            credits = reader.GetInt32(2),
            enrolled_students = reader.GetInt32(3)
        });
    }
    return Results.Ok(new { success = true, data = results });
});

app.Run();

// Helper function to convert numeric grade to letter grade
static string GetLetterGrade(decimal grade)
{
    return grade switch
    {
        >= 95 => "A+",
        >= 90 => "A",
        >= 85 => "A-",
        >= 80 => "B+",
        >= 75 => "B",
        >= 70 => "B-",
        >= 65 => "C+",
        >= 60 => "C",
        >= 55 => "C-",
        >= 50 => "D",
        _ => "F"
    };
}

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

public class StudentRequest
{
    public string first_name { get; set; } = string.Empty;
    public string last_name { get; set; } = string.Empty;
    public string email { get; set; } = string.Empty;
    public int enrollment_year { get; set; }
}

public class CourseRequest
{
    public string course_name { get; set; } = string.Empty;
    public int credits { get; set; }
    public int? professor_id { get; set; }
}

public class ProfessorRequest
{
    public string first_name { get; set; } = string.Empty;
    public string last_name { get; set; } = string.Empty;
    public string email { get; set; } = string.Empty;
    public string department { get; set; } = string.Empty;
}