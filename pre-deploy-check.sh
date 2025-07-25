#!/bin/bash

echo "🔍 Vérification pré-déploiement Render - Generation Phanerosis"
echo "================================================================"

# Vérification PHP
echo "✅ Vérification PHP..."
php -v
php -m | grep -E "(pdo|mbstring|xml|zip|gd|bcmath)" || echo "❌ Extensions PHP manquantes"

# Vérification Composer
echo "✅ Vérification Composer..."
composer --version

# Vérification Node.js
echo "✅ Vérification Node.js..."
node --version
npm --version

# Vérification des dépendances
echo "✅ Vérification des dépendances..."
if [ -f "composer.json" ]; then
    echo "✅ composer.json trouvé"
else
    echo "❌ composer.json manquant"
    exit 1
fi

if [ -f "package.json" ]; then
    echo "✅ package.json trouvé"
else
    echo "❌ package.json manquant"
    exit 1
fi

# Vérification des fichiers de configuration
echo "✅ Vérification des fichiers de configuration..."
if [ -f "render.yaml" ]; then
    echo "✅ render.yaml trouvé"
else
    echo "❌ render.yaml manquant"
    exit 1
fi

if [ -f ".renderignore" ]; then
    echo "✅ .renderignore trouvé"
else
    echo "❌ .renderignore manquant"
    exit 1
fi

# Vérification des migrations
echo "✅ Vérification des migrations..."
php artisan migrate:status --no-ansi

# Test de build des assets
echo "✅ Test de build des assets..."
npm run build

# Vérification des permissions
echo "✅ Vérification des permissions..."
if [ -w "storage" ]; then
    echo "✅ storage écrivable"
else
    echo "❌ storage non écrivable"
fi

if [ -w "bootstrap/cache" ]; then
    echo "✅ bootstrap/cache écrivable"
else
    echo "❌ bootstrap/cache non écrivable"
fi

# Vérification de la configuration Laravel
echo "✅ Vérification de la configuration Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "================================================================"
echo "🎉 Vérification terminée !"
echo "📋 Prochaines étapes :"
echo "1. Aller sur https://render.com"
echo "2. Créer un nouveau Web Service"
echo "3. Connecter le repository GitHub"
echo "4. Configurer les variables d'environnement"
echo "5. Créer la base de données PostgreSQL"
echo "6. Lancer le déploiement" 