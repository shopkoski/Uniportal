# 🚀 Uni Portal Server Management

This guide explains how to keep your Uni Portal server running continuously.

## 📋 Available Scripts

### Basic Management
- `./start_persistent.sh` - Start services in background
- `./stop_services.sh` - Stop all services
- `./check_status.sh` - Check if services are running
- `./monitor.sh` - Monitor services in real-time

### Advanced Setup
- `./setup_system_service.sh` - Set up as system service (auto-start on boot)

## 🎯 Quick Start

### Option 1: Simple Background Process
```bash
# Start services in background
./start_persistent.sh

# Check if they're running
./check_status.sh

# Stop services when needed
./stop_services.sh
```

### Option 2: System Service (Recommended)
```bash
# Set up as system service (auto-starts on boot)
./setup_system_service.sh

# Check status
./check_status.sh
```

### Option 3: Real-time Monitoring
```bash
# Monitor services (updates every 30 seconds)
./monitor.sh
```

## 🔧 Service Details

### Backend (.NET)
- **Port:** 5104
- **URL:** http://localhost:5104
- **Swagger:** http://localhost:5104/swagger
- **Log:** logs/backend.log
- **PID:** logs/backend.pid

### Frontend (PHP)
- **Port:** 8000
- **URL:** http://localhost:8000
- **Log:** logs/frontend.log
- **PID:** logs/frontend.pid

## 📊 Monitoring Commands

### Check Service Status
```bash
./check_status.sh
```

### View Logs
```bash
# Backend logs
tail -f logs/backend.log

# Frontend logs
tail -f logs/frontend.log

# Both logs
tail -f logs/*.log
```

### Check Ports
```bash
# Check if ports are in use
lsof -i :5104  # Backend
lsof -i :8000  # Frontend
```

## 🔄 Auto-Restart Options

### macOS (launchd)
Services automatically restart if they crash and start on system boot.

### Linux (systemd)
Services automatically restart if they crash and start on system boot.

### Manual Background Process
Services run in background but won't restart automatically if they crash.

## 🛠️ Troubleshooting

### Services Won't Start
1. Check if ports are already in use:
   ```bash
   lsof -i :5104
   lsof -i :8000
   ```

2. Kill existing processes:
   ```bash
   ./stop_services.sh
   ```

3. Check logs for errors:
   ```bash
   cat logs/backend.log
   cat logs/frontend.log
   ```

### Website Not Accessible
1. Check if services are running:
   ```bash
   ./check_status.sh
   ```

2. Test connectivity:
   ```bash
   curl http://localhost:8000
   curl http://localhost:5104/api
   ```

### Permission Issues
Make sure scripts are executable:
```bash
chmod +x *.sh
```

## 🌐 Access URLs

- **Main Website:** http://localhost:8000
- **Backend API:** http://localhost:5104
- **API Documentation:** http://localhost:5104/swagger

## 🔑 Default Login

- **Admin:** admin@uniportal.com / admin123
- **Student:** john@student.uniportal.com / admin123
- **Professor:** k.stefanovska@univ.mk / admin123

## 📝 Notes

- Services will continue running even if you close the terminal
- Logs are stored in the `logs/` directory
- PID files are stored in the `logs/` directory for process management
- Use `./monitor.sh` for continuous monitoring
- System services (launchd/systemd) provide the most reliable auto-restart
