FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip zip libpq-dev libzip-dev libonig-dev libxml2-dev netcat-openbsd \
  && docker-php-ext-install pdo pdo_pgsql zip \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && rm -rf /var/lib/apt/lists/*

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy everything
COPY . /var/www

# Install PHP deps
RUN composer install --no-interaction --optimize-autoloader

# Set ownership
RUN chown -R www-data:www-data /var/www

# Copy our entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Use entrypoint
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
EXPOSE 9000
