# 🔧 Dépannage Railway - Generation Phanerosis

## 🚨 Problèmes courants et solutions

### 1. Erreur de build

**Symptômes :**
- Build échoue pendant l'installation des dépendances
- Erreur "composer install failed"
- Erreur "npm install failed"

**Solutions :**
```bash
# Vérifier les logs Railway
# Aller dans l'onglet "Deployments" → "Show logs"

# Problèmes courants :
# 1. Mémoire insuffisante → Augmenter les ressources
# 2. Timeout → Augmenter le timeout
# 3. Dépendances manquantes → Vérifier composer.json et package.json
```

### 2. Erreur de démarrage

**Symptômes :**
- Build réussi mais application ne démarre pas
- Erreur "Application failed to start"
- Port non accessible

**Solutions :**
```bash
# Vérifier la commande de démarrage
# Doit être : php artisan serve --host=0.0.0.0 --port=$PORT

# Vérifier les variables d'environnement
# APP_KEY doit être généré
# APP_ENV=production
# APP_DEBUG=false
```

### 3. Erreur de base de données

**Symptômes :**
- Erreur "Database connection failed"
- Erreur "Migration failed"

**Solutions :**
```bash
# 1. Ajouter une base de données PostgreSQL
# 2. Configurer les variables DB_*
# 3. Exécuter les migrations manuellement
```

## 🔧 Configuration manuelle

### Variables d'environnement requises

```env
APP_NAME="Generation Phanerosis"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-app.railway.app
APP_KEY=base64:votre_cle_32_caracteres

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

### Commandes de diagnostic

```bash
# Vérifier l'état de l'application
railway status

# Voir les logs en temps réel
railway logs

# Ouvrir un shell sur l'application
railway shell

# Vérifier les variables d'environnement
railway variables
```

## 🚀 Redéploiement

### Option 1 : Via l'interface Railway
1. Aller dans l'onglet "Deployments"
2. Cliquer sur "Redeploy"

### Option 2 : Via Git
```bash
# Faire un commit vide pour déclencher un redéploiement
git commit --allow-empty -m "Trigger redeploy"
git push origin main
```

### Option 3 : Via Railway CLI
```bash
# Installer Railway CLI
npm install -g @railway/cli

# Se connecter
railway login

# Redéployer
railway up
```

## 📞 Support

### Logs utiles
- **Build logs** : Installation des dépendances
- **Runtime logs** : Exécution de l'application
- **Health check logs** : Vérifications de santé

### Contacts
- [Documentation Railway](https://docs.railway.app)
- [Discord Railway](https://discord.gg/railway)
- [GitHub Issues](https://github.com/railwayapp/railway/issues)

## ✅ Checklist de vérification

- [ ] Repository GitHub connecté
- [ ] Variables d'environnement configurées
- [ ] Base de données PostgreSQL ajoutée
- [ ] Migrations exécutées
- [ ] Application accessible via l'URL
- [ ] Logs sans erreurs critiques
- [ ] Health check réussi 