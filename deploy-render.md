# Déploiement sur Render - Generation Phanerosis

## Prérequis
- Compte Render (https://render.com)
- Projet Git (GitHub, GitLab, etc.)

## Étapes de déploiement

### 1. Préparation du projet

1. **Créer un fichier `render.yaml` à la racine :**
```yaml
services:
  - type: web
    name: generation-phanerosis
    env: php
    buildCommand: |
      composer install --optimize-autoloader --no-dev
      npm ci --production
      npm run build
      php artisan key:generate --force
      php artisan storage:link
      php artisan config:cache
      php artisan route:cache
      php artisan view:cache
    startCommand: php artisan serve --host=0.0.0.0 --port=$PORT
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: false
      - key: LOG_CHANNEL
        value: stack
      - key: CACHE_DRIVER
        value: file
      - key: SESSION_DRIVER
        value: file
      - key: QUEUE_CONNECTION
        value: sync
      - key: MAIL_MAILER
        value: log

databases:
  - name: generation-phanerosis-db
    databaseName: generation_phanerosis
    user: phanerosis_user
```

### 2. Configuration sur Render

1. **Connecter votre repository**
   - Allez sur https://render.com
   - Cliquez sur "New" → "Web Service"
   - Connectez votre repository GitHub

2. **Configuration du service**
   - **Name** : generation-phanerosis
   - **Environment** : PHP
   - **Build Command** : 
     ```bash
     composer install --optimize-autoloader --no-dev
     npm ci --production
     npm run build
     php artisan key:generate --force
     php artisan storage:link
     php artisan config:cache
     php artisan route:cache
     php artisan view:cache
     ```
   - **Start Command** : `php artisan serve --host=0.0.0.0 --port=$PORT`

3. **Variables d'environnement**
   - **APP_ENV** : production
   - **APP_DEBUG** : false
   - **APP_URL** : https://votre-app.onrender.com
   - **LOG_CHANNEL** : stack
   - **DB_CONNECTION** : pgsql
   - **DB_HOST** : ${DB_HOST}
   - **DB_PORT** : ${DB_PORT}
   - **DB_DATABASE** : ${DB_NAME}
   - **DB_USERNAME** : ${DB_USER}
   - **DB_PASSWORD** : ${DB_PASSWORD}

### 3. Création de la base de données

1. **Créer une base de données PostgreSQL**
   - Dans Render, cliquez sur "New" → "PostgreSQL"
   - Nom : generation-phanerosis-db
   - Plan : Free (pour commencer)

2. **Lier la base de données au service web**
   - Dans votre service web, allez dans "Environment"
   - Ajoutez les variables de la base de données

### 4. Déploiement

1. **Push du code**
```bash
git add .
git commit -m "Render deployment configuration"
git push origin main
```

2. **Render déploiera automatiquement**
   - Le premier déploiement peut prendre 5-10 minutes
   - Les déploiements suivants sont plus rapides

3. **Migration de la base de données**
   - Une fois déployé, allez dans "Shell"
   - Exécutez :
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 5. Configuration du domaine personnalisé

1. **Dans votre service web, allez dans "Settings"**
2. **Cliquez sur "Custom Domains"**
3. **Ajoutez votre domaine**
4. **Configurez les DNS selon les instructions**

### 6. Monitoring et logs

- **Logs** : Disponibles dans l'onglet "Logs"
- **Métriques** : CPU, mémoire dans l'onglet "Metrics"
- **Variables d'environnement** : Modifiables dans "Environment"

## Avantages de Render

✅ **Déploiement automatique** depuis Git  
✅ **Base de données gérée**  
✅ **SSL automatique**  
✅ **CDN global**  
✅ **Monitoring intégré**  
✅ **Variables d'environnement sécurisées**  
✅ **Domaine personnalisé**  
✅ **Logs en temps réel**  
✅ **Plan gratuit généreux**  

## Coûts

- **Gratuit** : 750 heures/mois, 512MB RAM, 1GB stockage
- **Payant** : À partir de $7/mois pour plus de ressources

## Support

- Documentation : https://render.com/docs
- Discord : https://discord.gg/render
- Email : support@render.com 