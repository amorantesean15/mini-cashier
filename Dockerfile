# Stage 1: Build frontend assets
FROM node:18 AS build

# Stage 1: Build assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN chmod +x node_modules/.bin/vite
RUN npm run build

# Stage 2: Set up PHP & Apache
FROM php:8.2-apache

# Install required PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip unzip git curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy Laravel files
COPY . .

# Copy built frontend assets
COPY --from=build /app/public/build ./public/build

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Set Laravel env to production
ENV APP_ENV=production
ENV APP_DEBUG=true
ENV APP_KEY=base64:qJSVPOILeOW/sw0stCGfSxaR9QYr6pa/Cl4tiIf5A0I=

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
