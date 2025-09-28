using Microsoft.AspNetCore.Mvc;
using Microsoft.IdentityModel.Tokens;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;

namespace UniPortalBackend.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AuthController : ControllerBase
    {
        private readonly IConfiguration _configuration;

        public AuthController(IConfiguration configuration)
        {
            _configuration = configuration;
        }

        [HttpPost("login")]
        public IActionResult Login([FromBody] LoginRequest request)
        {
            // Hardcoded users for testing
            var users = new[]
            {
                new { Email = "admin@uniportal.com", Password = "admin123", Role = "Admin", FirstName = "Admin", LastName = "User" },
                new { Email = "john@student.uniportal.com", Password = "admin123", Role = "Student", FirstName = "John", LastName = "Doe" },
                new { Email = "jane@student.uniportal.com", Password = "admin123", Role = "Student", FirstName = "Jane", LastName = "Smith" },
                new { Email = "k.stefanovska@univ.mk", Password = "admin123", Role = "Professor", FirstName = "Kristina", LastName = "Stefanovska" }
            };

            var user = users.FirstOrDefault(u => u.Email == request.Email && u.Password == request.Password);

            if (user == null)
            {
                return BadRequest(new { message = "Invalid email or password" });
            }

            // Generate JWT token
            var token = GenerateJwtToken(user.Email, user.Role);

            return Ok(new
            {
                token = token,
                user = new
                {
                    email = user.Email,
                    role = user.Role,
                    firstName = user.FirstName,
                    lastName = user.LastName
                }
            });
        }

        private string GenerateJwtToken(string email, string role)
        {
            var key = new SymmetricSecurityKey(Encoding.ASCII.GetBytes(
                _configuration["Jwt:Key"] ?? "your-super-secret-key-with-at-least-32-characters"));
            var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);

            var claims = new[]
            {
                new Claim(ClaimTypes.Email, email),
                new Claim(ClaimTypes.Role, role),
                new Claim(JwtRegisteredClaimNames.Sub, email),
                new Claim(JwtRegisteredClaimNames.Jti, Guid.NewGuid().ToString())
            };

            var token = new JwtSecurityToken(
                issuer: _configuration["Jwt:Issuer"] ?? "UniPortal",
                audience: _configuration["Jwt:Audience"] ?? "UniPortalUsers",
                claims: claims,
                expires: DateTime.UtcNow.AddHours(24),
                signingCredentials: creds);

            return new JwtSecurityTokenHandler().WriteToken(token);
        }
    }

    public class LoginRequest
    {
        public string Email { get; set; } = string.Empty;
        public string Password { get; set; } = string.Empty;
    }
}