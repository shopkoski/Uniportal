# Use the official .NET 9 SDK image
FROM mcr.microsoft.com/dotnet/sdk:9.0 AS build

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

# Publish the application
RUN dotnet publish --configuration Release --no-build --output /app/publish

# Use the official .NET 9 runtime image
FROM mcr.microsoft.com/dotnet/aspnet:9.0 AS runtime

# Set the working directory
WORKDIR /app

# Copy the published application
COPY --from=build /app/publish .

# Expose the port
EXPOSE 8080

# Set the environment variable for the port
ENV ASPNETCORE_URLS=http://+:8080
ENV PORT=8080

# Start the application
ENTRYPOINT ["dotnet", "UniPortalBackend.dll"]
