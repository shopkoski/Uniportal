#!/bin/bash

echo "🔧 Setting up Uni Portal as System Service..."

# Get the current directory
PROJECT_DIR=$(pwd)
echo "Project directory: $PROJECT_DIR"

# Create launchd plist for macOS
if [[ "$OSTYPE" == "darwin"* ]]; then
    echo "🍎 Setting up for macOS (launchd)..."
    
    # Create plist file for backend
    cat > ~/Library/LaunchAgents/com.uniportal.backend.plist << EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>com.uniportal.backend</string>
    <key>ProgramArguments</key>
    <array>
        <string>/usr/local/bin/dotnet</string>
        <string>run</string>
    </array>
    <key>WorkingDirectory</key>
    <string>$PROJECT_DIR/backend</string>
    <key>RunAtLoad</key>
    <true/>
    <key>KeepAlive</key>
    <true/>
    <key>StandardOutPath</key>
    <string>$PROJECT_DIR/logs/backend.log</string>
    <key>StandardErrorPath</key>
    <string>$PROJECT_DIR/logs/backend.log</string>
</dict>
</plist>
EOF

    # Create plist file for frontend
    cat > ~/Library/LaunchAgents/com.uniportal.frontend.plist << EOF
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>Label</key>
    <string>com.uniportal.frontend</string>
    <key>ProgramArguments</key>
    <array>
        <string>/usr/bin/php</string>
        <string>-S</string>
        <string>localhost:8000</string>
    </array>
    <key>WorkingDirectory</key>
    <string>$PROJECT_DIR/frontend</string>
    <key>RunAtLoad</key>
    <true/>
    <key>KeepAlive</key>
    <true/>
    <key>StandardOutPath</key>
    <string>$PROJECT_DIR/logs/frontend.log</string>
    <key>StandardErrorPath</key>
    <string>$PROJECT_DIR/logs/frontend.log</string>
</dict>
</plist>
EOF

    # Create logs directory
    mkdir -p logs

    # Load the services
    launchctl load ~/Library/LaunchAgents/com.uniportal.backend.plist
    launchctl load ~/Library/LaunchAgents/com.uniportal.frontend.plist

    echo "✅ Services loaded! They will start automatically on system boot."
    echo "📋 To unload: launchctl unload ~/Library/LaunchAgents/com.uniportal.*.plist"
    echo "📋 To check status: launchctl list | grep uniportal"

elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
    echo "🐧 Setting up for Linux (systemd)..."
    
    # Create systemd service files
    sudo tee /etc/systemd/system/uniportal-backend.service > /dev/null << EOF
[Unit]
Description=Uni Portal Backend
After=network.target

[Service]
Type=simple
User=$USER
WorkingDirectory=$PROJECT_DIR/backend
ExecStart=/usr/bin/dotnet run
Restart=always
RestartSec=10
StandardOutput=append:$PROJECT_DIR/logs/backend.log
StandardError=append:$PROJECT_DIR/logs/backend.log

[Install]
WantedBy=multi-user.target
EOF

    sudo tee /etc/systemd/system/uniportal-frontend.service > /dev/null << EOF
[Unit]
Description=Uni Portal Frontend
After=network.target

[Service]
Type=simple
User=$USER
WorkingDirectory=$PROJECT_DIR/frontend
ExecStart=/usr/bin/php -S localhost:8000
Restart=always
RestartSec=10
StandardOutput=append:$PROJECT_DIR/logs/frontend.log
StandardError=append:$PROJECT_DIR/logs/frontend.log

[Install]
WantedBy=multi-user.target
EOF

    # Create logs directory
    mkdir -p logs

    # Reload systemd and enable services
    sudo systemctl daemon-reload
    sudo systemctl enable uniportal-backend
    sudo systemctl enable uniportal-frontend
    sudo systemctl start uniportal-backend
    sudo systemctl start uniportal-frontend

    echo "✅ Services started and enabled! They will start automatically on system boot."
    echo "📋 To check status: sudo systemctl status uniportal-backend uniportal-frontend"
    echo "📋 To stop: sudo systemctl stop uniportal-backend uniportal-frontend"

else
    echo "❌ Unsupported operating system: $OSTYPE"
    echo "Please use the manual scripts: ./start_persistent.sh"
fi
