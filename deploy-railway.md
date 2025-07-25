# Déploiement sur Railway - Generation Phanerosis

## Prérequis
- Compte Railway (https://railway.app)
- Projet Git (GitHub, GitLab, etc.)

## Étapes de déploiement

### 1. Préparation du projet

1. **Ajouter le fichier `railway.toml` à la racine du projet :**
```toml
[build]
builder = "nixpacks"

[deploy]
startCommand = "php artisan serve --host=0.0.0.0 --port=$PORT"
healthcheckPath = "/"
healthcheckTimeout = 300
restartPolicyType = "on_failure"
```

2. **Créer un fichier `nixpacks.toml` :**
```toml
[phases.setup]
nixPkgs = ["php82", "composer", "nodejs", "npm"]

[phases.install]
cmds = [
    "composer install --optimize-autoloader --no-dev",
    "npm ci --production",
    "npm run build"
]

[phases.build]
cmds = [
    "php artisan key:generate --force",
    "php artisan storage:link",
    "php artisan config:cache",
    "php artisan route:cache",
    "php artisan view:cache"
]

[start]
cmd = "php artisan serve --host=0.0.0.0 --port=$PORT"
```

### 2. Configuration sur Railway

1. **Connecter votre repository GitHub**
   - Allez sur https://railway.app
   - Cliquez sur "New Project"
   - Sélectionnez "Deploy from GitHub repo"
   - Choisissez votre repository

2. **Ajouter une base de données PostgreSQL**
   - Dans votre projet Railway, cliquez sur "New"
   - Sélectionnez "Database" → "PostgreSQL"
   - Railway générera automatiquement les variables d'environnement

3. **Configurer les variables d'environnement**
   - Allez dans l'onglet "Variables"
   - Ajoutez les variables suivantes :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-app.railway.app
LOG_CHANNEL=stack

DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${PGPASSWORD}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@votre-app.railway.app"
MAIL_FROM_NAME="Generation Phanerosis"
```

### 3. Déploiement

1. **Push du code**
```bash
git add .
git commit -m "Railway deployment configuration"
git push origin main
```

2. **Railway déploiera automatiquement**
   - Le déploiement se fait automatiquement à chaque push
   - Vous pouvez suivre le processus dans l'onglet "Deployments"

3. **Migration de la base de données**
   - Une fois déployé, allez dans l'onglet "Deployments"
   - Cliquez sur le dernier déploiement
   - Ouvrez un terminal et exécutez :
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 4. Configuration du domaine personnalisé (optionnel)

1. **Dans Railway, allez dans l'onglet "Settings"**
2. **Cliquez sur "Domains"**
3. **Ajoutez votre domaine personnalisé**
4. **Configurez les DNS selon les instructions**

### 5. Monitoring et logs

- **Logs** : Disponibles dans l'onglet "Deployments"
- **Métriques** : CPU, mémoire, réseau dans l'onglet "Metrics"
- **Variables d'environnement** : Modifiables dans l'onglet "Variables"

### 6. Mise à jour

Pour mettre à jour l'application :
```bash
git add .
git commit -m "Update application"
git push origin main
```

Railway redéploiera automatiquement.

## Avantages de Railway

✅ **Déploiement automatique** depuis Git  
✅ **Base de données gérée**  
✅ **SSL automatique**  
✅ **Scaling automatique**  
✅ **Monitoring intégré**  
✅ **Variables d'environnement sécurisées**  
✅ **Domaine personnalisé**  
✅ **Logs en temps réel**  

## Coûts

- **Gratuit** : 500 heures/mois, 512MB RAM, 1GB stockage
- **Payant** : À partir de $5/mois pour plus de ressources

## Support

- Documentation : https://docs.railway.app
- Discord : https://discord.gg/railway
- Email : support@railway.app 