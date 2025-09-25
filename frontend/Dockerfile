# Use the official PHP image
FROM php:8.2-cli

# Set the working directory
WORKDIR /app

# Copy the application files
COPY . .

# Create a startup script that handles the port dynamically
RUN echo '#!/bin/bash\n\
# Get the port from Railway environment variable\n\
PORT=${PORT:-8080}\n\
echo "Starting PHP server on port $PORT"\n\
\n\
# Start the PHP development server\n\
exec php -S 0.0.0.0:$PORT' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# Expose port 8080 (Railway will map this to their assigned port)
EXPOSE 8080

# Use the startup script
CMD ["/usr/local/bin/start.sh"]
