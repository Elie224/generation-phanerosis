#!/bin/bash

# Script de déploiement automatisé pour Generation Phanerosis
# Usage: ./deploy-script.sh [production|staging]

set -e

ENVIRONMENT=${1:-production}
APP_DIR="/var/www/generation-phanerosis"
BACKUP_DIR="/var/backups/generation-phanerosis"
DATE=$(date +%Y%m%d_%H%M%S)

echo "🚀 Déploiement de Generation Phanerosis en mode $ENVIRONMENT"

# Création des répertoires de sauvegarde
sudo mkdir -p $BACKUP_DIR

# Sauvegarde de la base de données
echo "📦 Sauvegarde de la base de données..."
if [ "$ENVIRONMENT" = "production" ]; then
    mysqldump -u phanerosis_user -p generation_phanerosis > $BACKUP_DIR/db_backup_$DATE.sql
    echo "✅ Sauvegarde créée: $BACKUP_DIR/db_backup_$DATE.sql"
fi

# Sauvegarde des fichiers uploadés
echo "📁 Sauvegarde des fichiers uploadés..."
if [ -d "$APP_DIR/storage/app/public" ]; then
    sudo cp -r $APP_DIR/storage/app/public $BACKUP_DIR/uploads_backup_$DATE
    echo "✅ Sauvegarde des uploads créée"
fi

# Mise en maintenance
echo "🔧 Mise en maintenance..."
cd $APP_DIR
php artisan down --message="Mise à jour en cours..." --retry=60

# Pull des dernières modifications
echo "⬇️ Récupération des dernières modifications..."
git pull origin main

# Installation des dépendances PHP
echo "📦 Installation des dépendances PHP..."
composer install --optimize-autoloader --no-dev --no-interaction

# Installation des dépendances Node.js
echo "📦 Installation des dépendances Node.js..."
npm ci --production

# Build des assets
echo "🔨 Build des assets..."
npm run build

# Nettoyage du cache
echo "🧹 Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Migration de la base de données
echo "🗄️ Migration de la base de données..."
php artisan migrate --force

# Optimisation pour la production
echo "⚡ Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Mise à jour des permissions
echo "🔐 Mise à jour des permissions..."
sudo chown -R www-data:www-data $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage
sudo chmod -R 775 $APP_DIR/bootstrap/cache

# Redémarrage des services
echo "🔄 Redémarrage des services..."
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx

# Sortie de maintenance
echo "✅ Sortie de maintenance..."
php artisan up

# Nettoyage des anciennes sauvegardes (garder les 7 derniers jours)
echo "🧹 Nettoyage des anciennes sauvegardes..."
find $BACKUP_DIR -name "db_backup_*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "uploads_backup_*" -mtime +7 -exec rm -rf {} \;

echo "🎉 Déploiement terminé avec succès!"
echo "📊 Vérification de l'application..."
curl -f http://localhost > /dev/null && echo "✅ Application accessible" || echo "❌ Problème d'accessibilité"

# Notification (optionnel)
if command -v notify-send &> /dev/null; then
    notify-send "Déploiement terminé" "Generation Phanerosis a été déployé avec succès!"
fi 