using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

#pragma warning disable CA1814 // Prefer jagged arrays over multidimensional

namespace UniPortalBackend.Migrations
{
    /// <inheritdoc />
    public partial class AddStudentUsers : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.InsertData(
                table: "Users",
                columns: new[] { "Id", "CreatedAt", "Email", "FirstName", "IsActive", "LastName", "PasswordHash", "Role" },
                values: new object[,]
                {
                    { 2, new DateTime(2024, 1, 1, 0, 0, 0, 0, DateTimeKind.Utc), "john.doe@student.com", "John", true, "Doe", "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", "Student" },
                    { 3, new DateTime(2024, 1, 1, 0, 0, 0, 0, DateTimeKind.Utc), "jane.smith@student.com", "Jane", true, "Smith", "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", "Student" },
                    { 4, new DateTime(2024, 1, 1, 0, 0, 0, 0, DateTimeKind.Utc), "mike.johnson@student.com", "Mike", true, "Johnson", "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", "Student" }
                });
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DeleteData(
                table: "Users",
                keyColumn: "Id",
                keyValue: 2);

            migrationBuilder.DeleteData(
                table: "Users",
                keyColumn: "Id",
                keyValue: 3);

            migrationBuilder.DeleteData(
                table: "Users",
                keyColumn: "Id",
                keyValue: 4);
        }
    }
}
