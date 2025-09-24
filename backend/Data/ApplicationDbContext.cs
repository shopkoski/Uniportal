using Microsoft.EntityFrameworkCore;
using UniPortalBackend.Models;

namespace UniPortalBackend.Data
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options)
            : base(options)
        {
        }

        public DbSet<User> Users { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);

            // Configure User entity
            modelBuilder.Entity<User>(entity =>
            {
                entity.HasKey(e => e.Id);
                entity.Property(e => e.Email).IsRequired().HasMaxLength(255);
                entity.HasIndex(e => e.Email).IsUnique();
                entity.Property(e => e.PasswordHash).IsRequired();
                entity.Property(e => e.FirstName).IsRequired().HasMaxLength(100);
                entity.Property(e => e.LastName).IsRequired().HasMaxLength(100);
                entity.Property(e => e.Role).HasMaxLength(50);
            });

            // Seed default admin user
            modelBuilder.Entity<User>().HasData(new User
            {
                Id = 1,
                Email = "admin@uniportal.com",
                PasswordHash = "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu",
                FirstName = "Admin",
                LastName = "User",
                Role = "Admin",
                CreatedAt = new DateTime(2024, 1, 1, 0, 0, 0, DateTimeKind.Utc),
                IsActive = true
            },
            new User
            {
                Id = 2,
                Email = "john@student.uniportal.com",
                PasswordHash = "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", // password: admin123
                FirstName = "John",
                LastName = "Doe",
                Role = "Student",
                CreatedAt = new DateTime(2024, 1, 1, 0, 0, 0, DateTimeKind.Utc),
                IsActive = true
            },
            new User
            {
                Id = 3,
                Email = "jane@student.uniportal.com",
                PasswordHash = "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", // password: admin123
                FirstName = "Jane",
                LastName = "Smith",
                Role = "Student",
                CreatedAt = new DateTime(2024, 1, 1, 0, 0, 0, DateTimeKind.Utc),
                IsActive = true
            },
            new User
            {
                Id = 4,
                Email = "mike@student.uniportal.com",
                PasswordHash = "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", // password: admin123
                FirstName = "Mike",
                LastName = "Johnson",
                Role = "Student",
                CreatedAt = new DateTime(2024, 1, 1, 0, 0, 0, DateTimeKind.Utc),
                IsActive = true
            },
            new User
            {
                Id = 100,
                Email = "k.stefanovska@univ.mk",
                PasswordHash = "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", // password: admin123
                FirstName = "Kristina",
                LastName = "Stefanovska",
                Role = "Professor",
                CreatedAt = new DateTime(2024, 1, 1, 0, 0, 0, DateTimeKind.Utc),
                IsActive = true
            });
        }
    }
}
