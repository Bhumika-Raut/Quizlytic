FROM php:8.2-cli

# Install dependencies required for SQLite and Composer
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    git \
    unzip \
    && docker-php-ext-install pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Setup SQLite database and permissions
RUN touch database/database.sqlite
RUN chmod -R 777 storage bootstrap/cache database

# Start the application, automatically migrating and seeding the database
CMD php artisan migrate:fresh --seed --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
