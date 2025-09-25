# Use the official .NET 9 SDK image
FROM mcr.microsoft.com/dotnet/sdk:9.0

# Set the working directory
WORKDIR /app

# Copy the project file
COPY UniPortalBackend.csproj .

# Restore dependencies
RUN dotnet restore

# Copy the rest of the source code
COPY . .

# Build the application
RUN dotnet build --configuration Release --no-restore

# Expose the port (Railway will set this)
EXPOSE 8080

# Set the environment variable for the port
ENV ASPNETCORE_URLS=http://+:8080

# Start the application
ENTRYPOINT ["dotnet", "run", "--project", "UniPortalBackend.csproj", "--configuration", "Release"]
