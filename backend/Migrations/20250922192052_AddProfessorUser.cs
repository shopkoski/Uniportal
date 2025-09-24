using System;
using Microsoft.EntityFrameworkCore.Migrations;

#nullable disable

namespace UniPortalBackend.Migrations
{
    /// <inheritdoc />
    public partial class AddProfessorUser : Migration
    {
        /// <inheritdoc />
        protected override void Up(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.InsertData(
                table: "Users",
                columns: new[] { "Id", "CreatedAt", "Email", "FirstName", "IsActive", "LastName", "PasswordHash", "Role" },
                values: new object[] { 100, new DateTime(2024, 1, 1, 0, 0, 0, 0, DateTimeKind.Utc), "k.stefanovska@univ.mk", "Kristina", true, "Stefanovska", "$2y$12$mowcdmZnAtT8jbbBfT.84.IkpQiEI4XigyjSCeyEzLuaj7VLXTkRu", "Professor" });
        }

        /// <inheritdoc />
        protected override void Down(MigrationBuilder migrationBuilder)
        {
            migrationBuilder.DeleteData(
                table: "Users",
                keyColumn: "Id",
                keyValue: 100);
        }
    }
}
