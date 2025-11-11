# Dockerfile for PHP-FPM 8.3
FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
    && docker-php-ext-install pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer (copy from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


# Copy application files as a fallback (compose will bind-mount in dev)
COPY . /var/www/html

# Copy entrypoint script into the image and make it executable
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# If the project includes a composer.json, install PHP dependencies during build.
# This is conditional so the build won't fail if there is no composer.json in the repo.
RUN if [ -f /var/www/html/composer.json ]; then \
            composer install --no-interaction --prefer-dist --optimize-autoloader; \
        fi

# Ensure proper permissions for www-data
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
