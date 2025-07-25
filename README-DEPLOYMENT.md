# 🚀 Guide de Déploiement - Generation Phanerosis

## 📋 Vue d'ensemble

Cette application Laravel avec Vue.js et Inertia.js peut être déployée sur plusieurs plateformes. Choisissez l'option qui correspond le mieux à vos besoins.

## 🎯 Options de Déploiement

### 🥇 Recommandé pour débuter : Railway ou Render
- **Facilité** : ⭐⭐⭐⭐⭐
- **Coût** : Gratuit pour commencer
- **Temps de déploiement** : 5-10 minutes
- **Maintenance** : Automatique

### 🥈 Contrôle total : VPS avec Docker
- **Facilité** : ⭐⭐⭐
- **Coût** : $5-20/mois
- **Temps de déploiement** : 30-60 minutes
- **Maintenance** : Manuelle

### 🥉 Architecture moderne : Séparation Frontend/Backend
- **Facilité** : ⭐⭐
- **Coût** : $10-30/mois
- **Temps de déploiement** : 1-2 heures
- **Maintenance** : Modérée

## 🚀 Déploiement Rapide

### Option 1 : Railway (Recommandé)

1. **Forkez ce repository sur GitHub**
2. **Allez sur [Railway.app](https://railway.app)**
3. **Cliquez sur "New Project" → "Deploy from GitHub repo"**
4. **Sélectionnez votre repository**
5. **Railway détectera automatiquement la configuration**

**Fichiers de configuration déjà inclus :**
- `railway.toml`
- `nixpacks.toml`

### Option 2 : Render

1. **Forkez ce repository sur GitHub**
2. **Allez sur [Render.com](https://render.com)**
3. **Cliquez sur "New" → "Web Service"**
4. **Connectez votre repository**
5. **Render utilisera automatiquement `render.yaml`**

## 🔧 Configuration Manuelle

### Variables d'environnement requises

```env
APP_NAME="Generation Phanerosis"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
APP_KEY=base64:votre_cle_32_caracteres

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=generation_phanerosis
DB_USERNAME=phanerosis_user
DB_PASSWORD=votre_mot_de_passe_securise

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="Generation Phanerosis"
```

## 📁 Structure des Fichiers de Déploiement

```
generation-phanerosis/
├── deployment-guide.md          # Guide complet de déploiement
├── deploy-railway.md            # Guide spécifique Railway
├── deploy-render.md             # Guide spécifique Render
├── pre-deploy-checklist.md      # Checklist de vérification
├── deploy-script.sh             # Script de déploiement VPS
├── docker-compose.yml           # Configuration Docker
├── Dockerfile                   # Image Docker
├── Procfile                     # Configuration Heroku
├── app.yaml                     # Configuration Google Cloud
├── railway.toml                 # Configuration Railway
├── nixpacks.toml                # Build Railway
├── render.yaml                  # Configuration Render
├── env.production.example       # Variables d'environnement
└── docker/
    ├── nginx/
    │   └── conf.d/
    │       └── default.conf     # Configuration Nginx
    └── mysql/
        └── my.cnf               # Configuration MySQL
```

## 🛠️ Commandes Utiles

### Préparation locale
```bash
# Installation des dépendances
composer install --optimize-autoloader --no-dev
npm ci --production
npm run build

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Déploiement VPS
```bash
# Rendre le script exécutable
chmod +x deploy-script.sh

# Exécuter le déploiement
./deploy-script.sh production
```

### Déploiement Docker
```bash
# Construire et démarrer les conteneurs
docker-compose up -d --build

# Migration de la base de données
docker-compose exec app php artisan migrate --force
```

## 🔍 Vérification Post-Déploiement

1. **Test de l'application**
   - Page d'accueil accessible
   - Connexion utilisateur fonctionne
   - Fonctionnalités principales testées

2. **Vérification des logs**
   - Pas d'erreurs critiques
   - Performance acceptable
   - Monitoring configuré

3. **Sécurité**
   - HTTPS activé
   - Variables sensibles protégées
   - Permissions correctes

## 🚨 Dépannage

### Erreurs courantes

**Erreur 500**
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vérifier les permissions
chmod -R 775 storage bootstrap/cache
```

**Assets non chargés**
```bash
# Rebuild des assets
npm run build

# Vérifier le lien symbolique
php artisan storage:link
```

**Erreur de base de données**
```bash
# Vérifier la configuration
php artisan config:clear
php artisan migrate:status
```

## 📞 Support

### Documentation
- [Guide complet](deployment-guide.md)
- [Railway](deploy-railway.md)
- [Render](deploy-render.md)
- [Checklist](pre-deploy-checklist.md)

### Ressources externes
- [Documentation Laravel](https://laravel.com/docs)
- [Documentation Railway](https://docs.railway.app)
- [Documentation Render](https://render.com/docs)
- [Documentation Docker](https://docs.docker.com)

### Communauté
- [Laravel Discord](https://discord.gg/laravel)
- [Railway Discord](https://discord.gg/railway)
- [Stack Overflow](https://stackoverflow.com/questions/tagged/laravel)

## 🎉 Félicitations !

Votre application Generation Phanerosis est maintenant déployée et accessible en ligne !

**Prochaines étapes :**
1. Configurer un domaine personnalisé
2. Mettre en place le monitoring
3. Configurer les sauvegardes automatiques
4. Optimiser les performances
5. Planifier les mises à jour

---

**Note :** Ce guide est conçu pour vous accompagner dans le déploiement de votre application. N'hésitez pas à adapter les configurations selon vos besoins spécifiques. 