FROM webdevops/php-nginx:8.3

WORKDIR /var/www/html

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . .

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Copier la configuration de production
COPY .env.production .env

# Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Générer la documentation Swagger
RUN php artisan l5-swagger:generate
# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr



CMD ["/start.sh"]