# 🚀 Déploiement Heroku - Generation Phanerosis

## ✅ Fichiers de configuration prêts

- ✅ **`Procfile`** - Configuration du serveur web
- ✅ **`composer.json`** - Dépendances PHP
- ✅ **`package.json`** - Dépendances Node.js

## 🎯 Étapes de déploiement

### 1. Installation Heroku CLI

**Windows :**
```bash
# Télécharger depuis https://devcenter.heroku.com/articles/heroku-cli
# Ou utiliser winget
winget install --id=Heroku.HerokuCLI
```

**Ou installer via npm :**
```bash
npm install -g heroku
```

### 2. Connexion Heroku

```bash
heroku login
```

### 3. Création de l'application

```bash
# Dans le dossier de votre projet
heroku create generation-phanerosis-app
```

### 4. Configuration des variables d'environnement

```bash
# Variables de base
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set LOG_CHANNEL=stack
heroku config:set CACHE_DRIVER=file
heroku config:set SESSION_DRIVER=file
heroku config:set QUEUE_CONNECTION=sync
heroku config:set MAIL_MAILER=log

# Générer une clé d'application
heroku run php artisan key:generate --force
```

### 5. Ajout de la base de données

```bash
# Ajouter PostgreSQL (gratuit)
heroku addons:create heroku-postgresql:mini

# Vérifier les variables de base de données
heroku config | grep DATABASE
```

### 6. Configuration de la base de données

```bash
# Configurer les variables de base de données
heroku config:set DB_CONNECTION=pgsql
heroku config:set DB_HOST=$(heroku config:get DATABASE_URL | cut -d@ -f2 | cut -d/ -f1)
heroku config:set DB_PORT=5432
heroku config:set DB_DATABASE=$(heroku config:get DATABASE_URL | cut -d/ -f4)
heroku config:set DB_USERNAME=$(heroku config:get DATABASE_URL | cut -d: -f2 | cut -d@ -f1 | cut -d/ -f3)
heroku config:set DB_PASSWORD=$(heroku config:get DATABASE_URL | cut -d: -f3 | cut -d@ -f1)
```

### 7. Déploiement

```bash
# Ajouter le remote Heroku
heroku git:remote -a generation-phanerosis-app

# Pousser le code
git push heroku main
```

### 8. Migration de la base de données

```bash
# Exécuter les migrations
heroku run php artisan migrate --force

# Exécuter les seeders (optionnel)
heroku run php artisan db:seed --force
```

### 9. Optimisation

```bash
# Cache des configurations
heroku run php artisan config:cache
heroku run php artisan route:cache
heroku run php artisan view:cache
```

## 🔧 Configuration avancée

### Variables d'environnement complètes

```bash
heroku config:set APP_NAME="Generation Phanerosis"
heroku config:set APP_URL=https://generation-phanerosis-app.herokuapp.com
heroku config:set MAIL_FROM_ADDRESS="noreply@generation-phanerosis-app.herokuapp.com"
heroku config:set MAIL_FROM_NAME="Generation Phanerosis"
```

### Add-ons recommandés

```bash
# Redis pour le cache (optionnel)
heroku addons:create heroku-redis:mini

# Monitoring
heroku addons:create papertrail:choklad
```

## 🚨 Dépannage

### Erreurs courantes

**Build échoue :**
```bash
# Vérifier les logs
heroku logs --tail

# Vérifier les dépendances
heroku run composer install --no-dev
```

**Erreur de base de données :**
```bash
# Vérifier la connexion
heroku run php artisan tinker
# Test: DB::connection()->getPdo();
```

**Assets non chargés :**
```bash
# Rebuild des assets
heroku run npm run build
```

## 📊 Monitoring

```bash
# Voir les logs en temps réel
heroku logs --tail

# Vérifier l'état de l'application
heroku ps

# Ouvrir l'application
heroku open
```

## 🎉 Succès !

Une fois déployé :
- ✅ Application accessible via l'URL Heroku
- ✅ Base de données PostgreSQL fonctionnelle
- ✅ Migrations exécutées
- ✅ Assets compilés

## 📞 Support

- [Documentation Heroku](https://devcenter.heroku.com/)
- [Laravel sur Heroku](https://devcenter.heroku.com/articles/deploying-php)
- [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli) 