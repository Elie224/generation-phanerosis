#!/bin/bash

echo "🚀 Déploiement Heroku - Generation Phanerosis"
echo "=============================================="

# Vérification Heroku CLI
if ! command -v heroku &> /dev/null; then
    echo "❌ Heroku CLI non installé"
    echo "Installez-le avec: npm install -g heroku"
    exit 1
fi

# Vérification de la connexion
echo "🔐 Vérification de la connexion Heroku..."
if ! heroku auth:whoami &> /dev/null; then
    echo "❌ Non connecté à Heroku"
    echo "Connectez-vous avec: heroku login"
    exit 1
fi

# Nom de l'application
APP_NAME="generation-phanerosis-app"

# Création de l'application si elle n'existe pas
echo "📱 Création de l'application Heroku..."
if ! heroku apps:info --app $APP_NAME &> /dev/null; then
    heroku create $APP_NAME
    echo "✅ Application créée: $APP_NAME"
else
    echo "✅ Application existe déjà: $APP_NAME"
fi

# Configuration des variables d'environnement
echo "⚙️ Configuration des variables d'environnement..."
heroku config:set APP_ENV=production --app $APP_NAME
heroku config:set APP_DEBUG=false --app $APP_NAME
heroku config:set LOG_CHANNEL=stack --app $APP_NAME
heroku config:set CACHE_DRIVER=file --app $APP_NAME
heroku config:set SESSION_DRIVER=file --app $APP_NAME
heroku config:set QUEUE_CONNECTION=sync --app $APP_NAME
heroku config:set MAIL_MAILER=log --app $APP_NAME

# Ajout de la base de données PostgreSQL
echo "🗄️ Configuration de la base de données..."
if ! heroku addons:info postgresql --app $APP_NAME &> /dev/null; then
    heroku addons:create heroku-postgresql:mini --app $APP_NAME
    echo "✅ Base de données PostgreSQL ajoutée"
else
    echo "✅ Base de données PostgreSQL existe déjà"
fi

# Configuration de la base de données
echo "🔧 Configuration des variables de base de données..."
DATABASE_URL=$(heroku config:get DATABASE_URL --app $APP_NAME)
if [ ! -z "$DATABASE_URL" ]; then
    heroku config:set DB_CONNECTION=pgsql --app $APP_NAME
    heroku config:set DB_HOST=$(echo $DATABASE_URL | cut -d@ -f2 | cut -d/ -f1) --app $APP_NAME
    heroku config:set DB_PORT=5432 --app $APP_NAME
    heroku config:set DB_DATABASE=$(echo $DATABASE_URL | cut -d/ -f4) --app $APP_NAME
    heroku config:set DB_USERNAME=$(echo $DATABASE_URL | cut -d: -f2 | cut -d@ -f1 | cut -d/ -f3) --app $APP_NAME
    heroku config:set DB_PASSWORD=$(echo $DATABASE_URL | cut -d: -f3 | cut -d@ -f1) --app $APP_NAME
    echo "✅ Variables de base de données configurées"
fi

# Ajout du remote Heroku
echo "🔗 Ajout du remote Heroku..."
if ! git remote | grep heroku &> /dev/null; then
    heroku git:remote -a $APP_NAME
    echo "✅ Remote Heroku ajouté"
else
    echo "✅ Remote Heroku existe déjà"
fi

# Déploiement
echo "🚀 Déploiement en cours..."
git push heroku main

# Génération de la clé d'application
echo "🔑 Génération de la clé d'application..."
heroku run php artisan key:generate --force --app $APP_NAME

# Migration de la base de données
echo "🗄️ Migration de la base de données..."
heroku run php artisan migrate --force --app $APP_NAME

# Optimisation
echo "⚡ Optimisation de l'application..."
heroku run php artisan config:cache --app $APP_NAME
heroku run php artisan route:cache --app $APP_NAME
heroku run php artisan view:cache --app $APP_NAME

# Ouverture de l'application
echo "🌐 Ouverture de l'application..."
heroku open --app $APP_NAME

echo "=============================================="
echo "🎉 Déploiement terminé avec succès !"
echo "📱 URL: https://$APP_NAME.herokuapp.com"
echo "📊 Logs: heroku logs --tail --app $APP_NAME" 