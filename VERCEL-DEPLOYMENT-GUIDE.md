# 🚀 Déploiement Vercel - Generation Phanerosis

## ⚠️ Important : Limitations Vercel

Vercel est principalement conçu pour les applications frontend. Pour Laravel, il y a des limitations :

- ❌ **Pas de base de données persistante** (stateless)
- ❌ **Pas de stockage de fichiers** persistant
- ❌ **Pas de sessions** persistantes
- ❌ **Pas de queues** ou background jobs

## 🎯 Configuration pour Vercel

### Fichiers de configuration créés :

- ✅ **`vercel.json`** - Configuration principale
- ✅ **`.vercelignore`** - Fichiers exclus
- ✅ **`public/_redirects`** - Gestion des routes

## 🚀 Étapes de déploiement

### 1. Installation Vercel CLI

```bash
npm install -g vercel
```

### 2. Connexion Vercel

```bash
vercel login
```

### 3. Déploiement

```bash
vercel
```

### 4. Configuration des variables d'environnement

Dans l'interface Vercel, ajoutez :

```env
APP_NAME="Generation Phanerosis"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-app.vercel.app
APP_KEY=base64:votre_cle_32_caracteres

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@votre-app.vercel.app"
MAIL_FROM_NAME="Generation Phanerosis"
```

## 🔧 Adaptations nécessaires

### Pour fonctionner sur Vercel, vous devrez :

1. **Désactiver les fonctionnalités** qui nécessitent une base de données
2. **Utiliser des services externes** pour :
   - Base de données : PlanetScale, Supabase, ou Railway
   - Stockage : AWS S3, Cloudinary
   - Sessions : Redis Cloud

### Configuration recommandée :

```env
# Base de données externe
DB_CONNECTION=mysql
DB_HOST=votre-host-externe
DB_PORT=3306
DB_DATABASE=votre-database
DB_USERNAME=votre-username
DB_PASSWORD=votre-password

# Stockage externe
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=votre-key
AWS_SECRET_ACCESS_KEY=votre-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=votre-bucket
```

## 🚨 Alternatives recommandées

Pour une application Laravel complète, considérez :

### 1. **Railway** (recommandé)
- ✅ Base de données PostgreSQL incluse
- ✅ Stockage persistant
- ✅ Sessions et queues supportées

### 2. **Render**
- ✅ Base de données PostgreSQL incluse
- ✅ Configuration simple

### 3. **Heroku**
- ✅ Écosystème mature
- ✅ Add-ons nombreux

## 📞 Support

- [Documentation Vercel](https://vercel.com/docs)
- [Vercel PHP Runtime](https://github.com/vercel/vercel-php)
- [Laravel sur Vercel](https://laravel.com/docs/deployment#vercel) 