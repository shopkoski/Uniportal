## Multi-stage Dockerfile optimized for Railway

# --- Build stage ---
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src

# copy csproj and restore as distinct layers
COPY UniPortalBackend.csproj ./
RUN dotnet restore "UniPortalBackend.csproj"

# copy everything and publish
COPY . .
RUN dotnet publish "UniPortalBackend.csproj" -c Release -o /app/publish --no-restore

# --- Runtime stage ---
FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS final
WORKDIR /app

COPY --from=build /app/publish .

# Railway sets $PORT dynamically; expose default and fallback to 8080
EXPOSE 8080
ENTRYPOINT ["/bin/sh","-c","dotnet UniPortalBackend.dll --urls http://0.0.0.0:${PORT:-8080}"]
