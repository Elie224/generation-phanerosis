# Guide de Déploiement - Generation Phanerosis

## Vue d'ensemble
Cette application Laravel utilise :
- PHP 8.2+
- Laravel 12.0
- Vue.js 3 avec Inertia.js
- Tailwind CSS
- Vite pour le build frontend
- Base de données SQLite (par défaut) ou MySQL/PostgreSQL

## Options de Déploiement

### 1. Déploiement sur VPS/Server Dédié

#### Prérequis
- Serveur Linux (Ubuntu 22.04 recommandé)
- PHP 8.2+
- Composer
- Node.js 18+
- Nginx ou Apache
- Base de données (MySQL/PostgreSQL)

#### Étapes de déploiement

1. **Préparation du serveur**
```bash
# Mise à jour du système
sudo apt update && sudo apt upgrade -y

# Installation de PHP et extensions
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-sqlite3 php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-intl php8.2-redis -y

# Installation de Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installation de Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Installation de Nginx
sudo apt install nginx -y
```

2. **Configuration de la base de données**
```bash
# Pour MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Création de la base de données
sudo mysql -u root -p
CREATE DATABASE generation_phanerosis;
CREATE USER 'phanerosis_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe_securise';
GRANT ALL PRIVILEGES ON generation_phanerosis.* TO 'phanerosis_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

3. **Déploiement de l'application**
```bash
# Création du répertoire de l'application
sudo mkdir -p /var/www/generation-phanerosis
sudo chown -R $USER:$USER /var/www/generation-phanerosis

# Clonage du projet (si depuis Git)
git clone votre_repo /var/www/generation-phanerosis

# Ou transfert des fichiers
# scp -r ./* user@server:/var/www/generation-phanerosis/

cd /var/www/generation-phanerosis

# Installation des dépendances PHP
composer install --optimize-autoloader --no-dev

# Installation des dépendances Node.js
npm install

# Build des assets
npm run build

# Configuration de l'environnement
cp .env.example .env
php artisan key:generate

# Configuration de la base de données dans .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=generation_phanerosis
# DB_USERNAME=phanerosis_user
# DB_PASSWORD=votre_mot_de_passe_securise

# Migration et seeding
php artisan migrate --force
php artisan db:seed --force

# Optimisation pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissions
sudo chown -R www-data:www-data /var/www/generation-phanerosis
sudo chmod -R 755 /var/www/generation-phanerosis
sudo chmod -R 775 /var/www/generation-phanerosis/storage
sudo chmod -R 775 /var/www/generation-phanerosis/bootstrap/cache
```

4. **Configuration Nginx**
```bash
sudo nano /etc/nginx/sites-available/generation-phanerosis
```

Contenu du fichier de configuration :
```nginx
server {
    listen 80;
    server_name votre-domaine.com www.votre-domaine.com;
    root /var/www/generation-phanerosis/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Activation du site
sudo ln -s /etc/nginx/sites-available/generation-phanerosis /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

5. **Configuration SSL avec Let's Encrypt**
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d votre-domaine.com -d www.votre-domaine.com
```

### 2. Déploiement sur Heroku

1. **Installation de Heroku CLI**
```bash
# Windows
# Télécharger depuis https://devcenter.heroku.com/articles/heroku-cli

# Création de l'application
heroku create generation-phanerosis
```

2. **Configuration des add-ons**
```bash
# Base de données PostgreSQL
heroku addons:create heroku-postgresql:mini

# Redis (optionnel)
heroku addons:create heroku-redis:mini
```

3. **Configuration des variables d'environnement**
```bash
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set LOG_CHANNEL=stack
```

4. **Déploiement**
```bash
git add .
git commit -m "Deployment ready"
git push heroku main

# Migration de la base de données
heroku run php artisan migrate --force
```

### 3. Déploiement sur DigitalOcean App Platform

1. **Préparation du fichier app.yaml**
```yaml
name: generation-phanerosis
services:
- name: web
  source_dir: /
  github:
    repo: votre-username/generation-phanerosis
    branch: main
  run_command: vendor/bin/heroku-php-apache2 public/
  environment_slug: php
  instance_count: 1
  instance_size_slug: basic-xxs
  envs:
  - key: APP_ENV
    value: production
  - key: APP_DEBUG
    value: false
  - key: LOG_CHANNEL
    value: stack

databases:
- name: db
  engine: PG
  version: "12"
```

### 4. Déploiement sur Vercel (Frontend) + API Backend

Pour une architecture moderne, vous pouvez séparer le frontend et le backend :

1. **Backend API** (Laravel)
- Déployer sur Railway, Render, ou DigitalOcean
- Configurer comme API REST

2. **Frontend** (Vue.js)
- Déployer sur Vercel ou Netlify
- Utiliser l'API backend

## Configuration de Production

### Variables d'environnement critiques
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=generation_phanerosis
DB_USERNAME=phanerosis_user
DB_PASSWORD=votre_mot_de_passe_securise

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

### Optimisations de performance
```bash
# Cache des configurations
php artisan config:cache

# Cache des routes
php artisan route:cache

# Cache des vues
php artisan view:cache

# Optimisation de l'autoloader
composer install --optimize-autoloader --no-dev

# Build des assets
npm run build
```

### Sécurité
1. **Protection des fichiers sensibles**
```bash
# Vérifier que .env n'est pas accessible
# Configurer les permissions appropriées
```

2. **Configuration du pare-feu**
```bash
# Ouvrir seulement les ports nécessaires
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443
sudo ufw enable
```

3. **Sauvegarde automatique**
```bash
# Créer un script de sauvegarde
#!/bin/bash
mysqldump -u phanerosis_user -p generation_phanerosis > backup_$(date +%Y%m%d_%H%M%S).sql
```

## Monitoring et Maintenance

### Logs
```bash
# Surveiller les logs Laravel
tail -f /var/www/generation-phanerosis/storage/logs/laravel.log

# Surveiller les logs Nginx
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

### Mise à jour
```bash
# Processus de mise à jour
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Support et Dépannage

### Problèmes courants
1. **Erreur 500** : Vérifier les permissions et les logs
2. **Erreur de base de données** : Vérifier la configuration .env
3. **Assets non chargés** : Vérifier le build et les permissions

### Commandes utiles
```bash
# Vérifier l'état des services
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql

# Redémarrer les services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql
```

## Recommandations

1. **Pour un déploiement simple** : Utilisez Heroku ou Railway
2. **Pour un contrôle total** : VPS avec Nginx
3. **Pour une architecture moderne** : Séparation frontend/backend
4. **Pour la production** : Toujours utiliser HTTPS et configurer les sauvegardes

Choisissez l'option qui correspond le mieux à vos besoins en termes de budget, de contrôle et de complexité de maintenance. 