#!/bin/bash

echo "🛑 Stopping Uni Portal Services..."

# Read PIDs from files and kill processes
if [ -f "logs/backend.pid" ]; then
    BACKEND_PID=$(cat logs/backend.pid)
    echo "Stopping Backend (PID: $BACKEND_PID)..."
    kill $BACKEND_PID 2>/dev/null
    rm logs/backend.pid
fi

if [ -f "logs/frontend.pid" ]; then
    FRONTEND_PID=$(cat logs/frontend.pid)
    echo "Stopping Frontend (PID: $FRONTEND_PID)..."
    kill $FRONTEND_PID 2>/dev/null
    rm logs/frontend.pid
fi

# Also kill any remaining processes
pkill -f "dotnet run"
pkill -f "php -S"

echo "✅ All services stopped!"
