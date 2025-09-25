# Use the official PHP image
FROM php:8.2-cli

# Set the working directory
WORKDIR /app

# Copy the application files
COPY . .

# Expose the port
EXPOSE $PORT

# Start the PHP development server
CMD ["php", "-S", "0.0.0.0:$PORT"]
