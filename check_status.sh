#!/bin/bash

echo "📊 Uni Portal Services Status:"
echo "================================"

# Check Backend
if [ -f "logs/backend.pid" ]; then
    BACKEND_PID=$(cat logs/backend.pid)
    if ps -p $BACKEND_PID > /dev/null 2>&1; then
        echo "✅ Backend: Running (PID: $BACKEND_PID)"
        echo "   🔌 API: http://localhost:5104"
        echo "   📚 Swagger: http://localhost:5104/swagger"
    else
        echo "❌ Backend: Not running (PID file exists but process dead)"
    fi
else
    echo "❌ Backend: Not running (no PID file)"
fi

echo ""

# Check Frontend
if [ -f "logs/frontend.pid" ]; then
    FRONTEND_PID=$(cat logs/frontend.pid)
    if ps -p $FRONTEND_PID > /dev/null 2>&1; then
        echo "✅ Frontend: Running (PID: $FRONTEND_PID)"
        echo "   📱 Website: http://localhost:8000"
    else
        echo "❌ Frontend: Not running (PID file exists but process dead)"
    fi
else
    echo "❌ Frontend: Not running (no PID file)"
fi

echo ""

# Check if ports are in use
echo "🔍 Port Status:"
if lsof -i :5104 > /dev/null 2>&1; then
    echo "✅ Port 5104 (Backend): In use"
else
    echo "❌ Port 5104 (Backend): Free"
fi

if lsof -i :8000 > /dev/null 2>&1; then
    echo "✅ Port 8000 (Frontend): In use"
else
    echo "❌ Port 8000 (Frontend): Free"
fi

echo ""
echo "📋 To start services: ./start_persistent.sh"
echo "📋 To stop services: ./stop_services.sh"
echo "📋 To view logs: tail -f logs/backend.log or tail -f logs/frontend.log"
