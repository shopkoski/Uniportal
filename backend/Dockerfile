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

# Railway provides PORT env; bind Kestrel to it
ENV ASPNETCORE_URLS=http://+:$PORT

COPY --from=build /app/publish .

EXPOSE 8080

ENTRYPOINT ["dotnet", "UniPortalBackend.dll"]
