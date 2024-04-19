# Use the official PHP image as a base
FROM php:latest

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP application files to the container
COPY . .

# Expose port 80 (or whichever port your PHP application runs on)
EXPOSE 80

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:80"]
