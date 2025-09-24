#!/bin/bash

echo "🚀 Starting Uni Portal Application in Background..."

# Create logs directory if it doesn't exist
mkdir -p logs

# Kill any existing processes
pkill -f "dotnet run"
pkill -f "php -S"

# Start .NET Backend in background
echo "🔧 Starting .NET Backend..."
cd backend
nohup dotnet run > ../logs/backend.log 2>&1 &
BACKEND_PID=$!
echo "Backend PID: $BACKEND_PID"
cd ..

# Wait a moment for backend to start
sleep 3

# Start PHP Frontend in background
echo "🌐 Starting PHP Frontend..."
cd frontend
nohup php -S localhost:8000 > ../logs/frontend.log 2>&1 &
FRONTEND_PID=$!
echo "Frontend PID: $FRONTEND_PID"
cd ..

# Create logs directory if it doesn't exist
mkdir -p logs

# Save PIDs to file for easy management
echo "$BACKEND_PID" > logs/backend.pid
echo "$FRONTEND_PID" > logs/frontend.pid

echo "✅ Services started successfully in background!"
echo ""
echo "📱 Frontend: http://localhost:8000"
echo "🔌 Backend API: http://localhost:5104"
echo "📚 Swagger Docs: http://localhost:5104/swagger"
echo ""
echo "🔑 Admin Login: admin@uniportal.com / admin123"
echo ""
echo "📋 To stop services: ./stop_services.sh"
echo "📋 To check status: ./check_status.sh"
echo "📋 Logs location: ./logs/"
