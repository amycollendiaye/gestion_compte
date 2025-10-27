# Image PHP 8.3 avec Nginx
FROM webdevops/php-nginx:8.3

# Définir le répertoire de travail
WORKDIR /var/www/html 

# Permettre à Composer de tourner en root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copier le projet dans le conteneur
COPY . .

# Installer les dépendances PHP
RUN composer install --optimize-autoloader --no-dev

# Copier la configuration de production
COPY .env.production .env

# Copier le script de démarrage et le rendre exécutable
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Créer les dossiers Laravel si absents
RUN mkdir -p storage bootstrap/cache

# Définir les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Générer la documentation Swagger (ignore l'erreur si problème)
RUN php artisan l5-swagger:generate || true

# Variables d'environnement pour Webdevops + Laravel
ENV SKIP_COMPOSER 1
ENV WEBROOT /app/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Démarrer l'application avec ton script
CMD ["/start.sh"]
