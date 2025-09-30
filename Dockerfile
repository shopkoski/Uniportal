## Multi-stage Dockerfile at repo root for Railway

# --- Build stage ---
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /src

# copy csproj and restore as distinct layers
COPY backend/UniPortalBackend.csproj ./
RUN dotnet restore "UniPortalBackend.csproj"

# copy source and publish
COPY backend/ .
RUN dotnet publish "UniPortalBackend.csproj" -c Release -o /app/publish --no-restore

# --- Runtime stage ---
FROM mcr.microsoft.com/dotnet/aspnet:8.0 AS final
WORKDIR /app

COPY --from=build /app/publish .

# Railway sets $PORT dynamically; use shell entrypoint so $PORT expands at runtime
EXPOSE 8080
ENTRYPOINT ["/bin/sh","-c","dotnet UniPortalBackend.dll --urls http://0.0.0.0:$PORT"]
