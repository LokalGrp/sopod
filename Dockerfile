# Railway was building with a PHP Docker image, so nixpacks.toml was ignored
# and the build failed on the extensions maatwebsite/excel needs.
# This installs them explicitly.
FROM php:8.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd zip pdo_mysql bcmath mbstring exif \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
ENV COMPOSER_ALLOW_SUPERUSER=1

# Dependencies first so this layer caches across code-only changes.
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction \
        --prefer-dist --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload --optimize --no-dev

# .dockerignore strips the contents of storage/framework/*, and Docker does
# not create empty directories, so these were missing from the image and
# Laravel could not write compiled views, sessions or the cache. Recreate
# them and make them writable.
RUN mkdir -p storage/framework/views storage/framework/cache/data storage/framework/sessions storage/logs bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Migrations and caching run at start, not build: they need APP_KEY and the
# database, and neither exists during the image build.
EXPOSE 8080
CMD php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
