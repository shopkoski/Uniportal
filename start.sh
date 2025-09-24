#!/bin/bash

echo "🚀 Starting Uni Portal Application..."

# Function to cleanup background processes
cleanup() {
    echo "🛑 Stopping services..."
    pkill -f "dotnet run"
    pkill -f "php -S"
    exit 0
}

# Set up signal handlers
trap cleanup SIGINT SIGTERM

# Start backend
echo "🔧 Starting .NET Backend..."
cd backend
dotnet run &
BACKEND_PID=$!
cd ..

# Wait a moment for backend to start
sleep 3

# Start frontend
echo "🌐 Starting PHP Frontend..."
cd frontend
php -S localhost:8000 &
FRONTEND_PID=$!
cd ..

echo "✅ Services started successfully!"
echo ""
echo "📱 Frontend: http://localhost:8000"
echo "🔌 Backend API: http://localhost:5104"
echo "📚 Swagger Docs: http://localhost:5104/swagger"
echo ""
echo "🔑 Admin Login: admin@uniportal.com / admin123"
echo ""
echo "Press Ctrl+C to stop all services"

# Wait for user to stop
wait

