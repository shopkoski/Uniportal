#!/bin/bash

echo "🔍 Uni Portal Monitor - Checking every 30 seconds..."
echo "Press Ctrl+C to stop monitoring"
echo ""

while true; do
    clear
    echo "🕐 $(date)"
    echo "================================"
    
    # Check if services are running
    BACKEND_RUNNING=false
    FRONTEND_RUNNING=false
    
    if [ -f "logs/backend.pid" ]; then
        BACKEND_PID=$(cat logs/backend.pid)
        if ps -p $BACKEND_PID > /dev/null 2>&1; then
            BACKEND_RUNNING=true
        fi
    fi
    
    if [ -f "logs/frontend.pid" ]; then
        FRONTEND_PID=$(cat logs/frontend.pid)
        if ps -p $FRONTEND_PID > /dev/null 2>&1; then
            FRONTEND_RUNNING=true
        fi
    fi
    
    # Display status
    if [ "$BACKEND_RUNNING" = true ]; then
        echo "✅ Backend: Running (PID: $BACKEND_PID)"
    else
        echo "❌ Backend: Not running"
    fi
    
    if [ "$FRONTEND_RUNNING" = true ]; then
        echo "✅ Frontend: Running (PID: $FRONTEND_PID)"
    else
        echo "❌ Frontend: Not running"
    fi
    
    # Check if website is accessible
    if curl -s http://localhost:8000 > /dev/null 2>&1; then
        echo "✅ Website: Accessible at http://localhost:8000"
    else
        echo "❌ Website: Not accessible"
    fi
    
    if curl -s http://localhost:5104/api > /dev/null 2>&1; then
        echo "✅ API: Accessible at http://localhost:5104"
    else
        echo "❌ API: Not accessible"
    fi
    
    echo ""
    echo "📋 Commands:"
    echo "  Start: ./start_persistent.sh"
    echo "  Stop:  ./stop_services.sh"
    echo "  Status: ./check_status.sh"
    echo ""
    echo "⏰ Next check in 30 seconds... (Ctrl+C to stop)"
    
    sleep 30
done
