# Railway builds from a PHP Docker image, so nixpacks config is ignored.
# Install the extensions maatwebsite/excel and the app actually need.
FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd zip pdo_mysql bcmath mbstring exif opcache \
    && rm -rf /var/lib/apt/lists/*

# Opcache keeps compiled PHP in memory rather than recompiling every file on
# every request. Without it each request re-parses thousands of framework files.
RUN { \
      echo "opcache.enable=1"; \
      echo "opcache.memory_consumption=192"; \
      echo "opcache.max_accelerated_files=20000"; \
      echo "opcache.validate_timestamps=0"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
ENV COMPOSER_ALLOW_SUPERUSER=1
# Containers have no persistent disk, so file logging hides every error.
# Default to stderr so exceptions reach the platform log stream.
ENV LOG_CHANNEL=stderr

# Dependencies first so this layer caches across code-only changes.
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction \
        --prefer-dist --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload --optimize --no-dev

# .dockerignore strips the contents of storage/framework/*, and Docker does not
# create empty directories, so these must be recreated and made writable.
RUN mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Migrations and caching run at start, not build: they need APP_KEY and the
# database, neither of which exists while the image is being built.
# route:cache is omitted deliberately - routes/web.php defines routes with
# closures, which cannot be serialised.
EXPOSE 8080
CMD php artisan migrate --force && php artisan config:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
