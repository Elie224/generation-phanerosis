# 🚀 Guide de Déploiement Render - Generation Phanerosis

## ✅ Vérifications préalables

Tous les fichiers de configuration ont été vérifiés et corrigés :

- ✅ `render.yaml` - Configuration du service web
- ✅ `package.json` - Dépendances Node.js
- ✅ `composer.json` - Dépendances PHP
- ✅ `vite.config.js` - Configuration Vite
- ✅ `.renderignore` - Fichiers exclus du déploiement
- ✅ Migrations Laravel - Toutes à jour

## 🎯 Étapes de déploiement

### 1. Accès à Render

1. **Allez sur** [Render.com](https://render.com)
2. **Connectez-vous** avec votre compte GitHub
3. **Cliquez sur "New"** → **"Web Service"**

### 2. Connexion du repository

1. **Sélectionnez "Connect a repository"**
2. **Autorisez Render** à accéder à vos repositories
3. **Choisissez** `Elie224/generation-phanerosis`
4. **Cliquez sur "Connect"**

### 3. Configuration automatique

Render va automatiquement détecter :
- ✅ Type d'application : PHP
- ✅ Fichier de configuration : `render.yaml`
- ✅ Variables d'environnement de base

### 4. Configuration manuelle

**Informations de base :**
- **Name** : `generation-phanerosis`
- **Environment** : `PHP`
- **Region** : `Oregon (US West)` ou `Frankfurt (EU Central)`

**Build & Deploy :**
- **Build Command** : (automatiquement configuré)
- **Start Command** : (automatiquement configuré)

### 5. Variables d'environnement

Dans l'onglet "Environment", ajoutez :

```env
APP_NAME="Generation Phanerosis"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-app.onrender.com
LOG_CHANNEL=stack

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@votre-app.onrender.com"
MAIL_FROM_NAME="Generation Phanerosis"
```

### 6. Création de la base de données

1. **Dans votre projet**, cliquez sur "New"
2. **Sélectionnez "PostgreSQL"**
3. **Nom** : `generation-phanerosis-db`
4. **Plan** : `Free`

### 7. Lancement du déploiement

1. **Cliquez sur "Create Web Service"**
2. **Attendez** 5-10 minutes pour le premier déploiement
3. **Surveillez** les logs de build

## 🔧 Post-déploiement

### Migration de la base de données

Une fois déployé :
1. **Allez dans l'onglet "Shell"**
2. **Exécutez** :
```bash
php artisan migrate --force
php artisan db:seed --force
```

### Test de l'application

1. **Cliquez sur l'URL** générée par Render
2. **Vérifiez** que la page d'accueil s'affiche
3. **Testez** la connexion utilisateur

## 🚨 Dépannage

### Erreurs courantes

**Build échoue :**
- Vérifiez les logs de build
- Assurez-vous que toutes les dépendances sont correctes

**Application ne démarre pas :**
- Vérifiez les variables d'environnement
- Assurez-vous que APP_KEY est généré

**Erreur de base de données :**
- Vérifiez que la base PostgreSQL est créée
- Vérifiez les variables DB_*

### Logs utiles

- **Build logs** : Installation des dépendances
- **Runtime logs** : Exécution de l'application
- **Health check logs** : Vérifications de santé

## 🎉 Succès !

Une fois déployé avec succès :
- ✅ Application accessible via l'URL Render
- ✅ Base de données PostgreSQL fonctionnelle
- ✅ Migrations exécutées
- ✅ Assets compilés et optimisés

## 📞 Support

- [Documentation Render](https://render.com/docs)
- [Discord Render](https://discord.gg/render)
- [GitHub Issues](https://github.com/render-oss/render/issues) 