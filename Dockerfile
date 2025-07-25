FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Création de l'utilisateur www-data
RUN useradd -G www-data,root -u 1000 -d /home/www-data www-data
RUN mkdir -p /home/www-data/.composer && \
    chown -R www-data:www-data /home/www-data

# Définition du répertoire de travail
WORKDIR /var/www

# Copie des fichiers de l'application
COPY . /var/www

# Installation des dépendances PHP
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Installation des dépendances Node.js
RUN npm ci --production

# Build des assets
RUN npm run build

# Configuration des permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www
RUN chmod -R 775 /var/www/storage
RUN chmod -R 775 /var/www/bootstrap/cache

# Création du lien symbolique pour le stockage
RUN php artisan storage:link

# Exposition du port 9000 pour PHP-FPM
EXPOSE 9000

# Démarrage de PHP-FPM
CMD ["php-fpm"] 