#!/bin/bash

echo "Starting database migration..."

# Wait for database to be ready
echo "Waiting for database connection..."
sleep 10

# Run Entity Framework migrations
echo "Running Entity Framework migrations..."
dotnet ef database update --verbose

echo "Migration completed!"
