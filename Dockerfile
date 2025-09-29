# Use the official .NET 8 SDK image
FROM mcr.microsoft.com/dotnet/sdk:8.0

# Set the working directory
WORKDIR /app

# Copy the project file
COPY backend/UniPortalBackend.csproj .

# Restore dependencies
RUN dotnet restore

# Copy the rest of the source code
COPY backend/ .

# Build the application
RUN dotnet build --configuration Release --no-restore

# Expose the port (Railway will set this)
EXPOSE $PORT

# Set the environment variable for the port
ENV ASPNETCORE_URLS=http://+:$PORT

# Start the application
ENTRYPOINT ["dotnet", "run", "--project", "UniPortalBackend.csproj", "--configuration", "Release"]
