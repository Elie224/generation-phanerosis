# Checklist Pré-Déploiement - Generation Phanerosis

## ✅ Vérifications de Sécurité

### 1. Variables d'environnement
- [ ] `.env` n'est pas commité dans Git
- [ ] `.env.example` est à jour
- [ ] Variables sensibles sont configurées sur le serveur
- [ ] `APP_DEBUG=false` en production
- [ ] `APP_ENV=production`

### 2. Base de données
- [ ] Migrations sont à jour
- [ ] Seeds sont prêts pour la production
- [ ] Sauvegarde de la base de données existante
- [ ] Permissions de base de données correctes

### 3. Fichiers et permissions
- [ ] `storage/` et `bootstrap/cache/` sont écrivables
- [ ] Lien symbolique storage créé
- [ ] Fichiers uploadés sauvegardés
- [ ] Permissions correctes sur tous les dossiers

## ✅ Vérifications de Performance

### 1. Assets
- [ ] `npm run build` exécuté
- [ ] Assets minifiés et optimisés
- [ ] Vite configuré pour la production
- [ ] Images optimisées

### 2. Cache
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Cache Redis/Memcached configuré

### 3. Optimisations
- [ ] `composer install --optimize-autoloader --no-dev`
- [ ] Logs configurés pour la production
- [ ] Queue workers configurés
- [ ] Monitoring configuré

## ✅ Vérifications Fonctionnelles

### 1. Tests
- [ ] Tests unitaires passent
- [ ] Tests d'intégration passent
- [ ] Tests de régression effectués
- [ ] Tests de performance OK

### 2. Fonctionnalités critiques
- [ ] Authentification fonctionne
- [ ] Upload de fichiers fonctionne
- [ ] Envoi d'emails configuré
- [ ] Notifications fonctionnent
- [ ] Paiements Stripe configurés

### 3. Compatibilité
- [ ] Compatible avec la version PHP du serveur
- [ ] Extensions PHP requises installées
- [ ] Compatible avec la base de données
- [ ] Compatible avec le serveur web

## ✅ Vérifications d'Infrastructure

### 1. Serveur
- [ ] PHP 8.2+ installé
- [ ] Extensions PHP requises
- [ ] Composer installé
- [ ] Node.js installé
- [ ] Nginx/Apache configuré

### 2. Base de données
- [ ] MySQL/PostgreSQL installé
- [ ] Base de données créée
- [ ] Utilisateur avec permissions
- [ ] Sauvegarde automatique configurée

### 3. SSL/HTTPS
- [ ] Certificat SSL installé
- [ ] Redirection HTTP vers HTTPS
- [ ] Headers de sécurité configurés
- [ ] CSP configuré

## ✅ Vérifications de Monitoring

### 1. Logs
- [ ] Logs Laravel configurés
- [ ] Logs serveur web configurés
- [ ] Rotation des logs configurée
- [ ] Alertes configurées

### 2. Métriques
- [ ] Monitoring CPU/Mémoire
- [ ] Monitoring base de données
- [ ] Monitoring réseau
- [ ] Alertes de performance

### 3. Sauvegarde
- [ ] Sauvegarde automatique base de données
- [ ] Sauvegarde fichiers uploadés
- [ ] Test de restauration effectué
- [ ] Rétention des sauvegardes configurée

## ✅ Vérifications Post-Déploiement

### 1. Tests de fumée
- [ ] Page d'accueil accessible
- [ ] Connexion utilisateur fonctionne
- [ ] Fonctionnalités principales testées
- [ ] Performance acceptable

### 2. Monitoring
- [ ] Logs sans erreurs critiques
- [ ] Métriques dans les normes
- [ ] Alertes configurées
- [ ] Dashboard de monitoring accessible

### 3. Documentation
- [ ] Procédure de rollback documentée
- [ ] Procédure de mise à jour documentée
- [ ] Contacts d'urgence listés
- [ ] Documentation utilisateur à jour

## 🚨 Points d'Attention

### Sécurité
- Vérifier que les mots de passe sont forts
- S'assurer que les clés API sont sécurisées
- Vérifier les permissions de fichiers
- Tester les vulnérabilités connues

### Performance
- Optimiser les requêtes de base de données
- Minimiser les assets
- Configurer le cache approprié
- Monitorer les temps de réponse

### Disponibilité
- Configurer la haute disponibilité si nécessaire
- Planifier les fenêtres de maintenance
- Préparer les procédures de rollback
- Tester la récupération après sinistre

## 📋 Script de Vérification Automatique

```bash
#!/bin/bash
echo "🔍 Vérification pré-déploiement..."

# Vérification PHP
php -v
php -m | grep -E "(pdo|mbstring|xml|zip|gd|bcmath)"

# Vérification Composer
composer --version

# Vérification Node.js
node --version
npm --version

# Vérification des dépendances
composer install --no-dev --optimize-autoloader
npm ci --production

# Build des assets
npm run build

# Vérification des migrations
php artisan migrate:status

# Tests
php artisan test

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Vérification terminée!"
```

## 📞 Contacts d'Urgence

- **Développeur principal** : [Votre nom] - [Votre email]
- **DevOps** : [Nom] - [Email]
- **Support technique** : [Nom] - [Email]
- **Hébergeur** : [Nom] - [Email/Téléphone]

## 📚 Ressources

- [Documentation Laravel](https://laravel.com/docs)
- [Guide de déploiement](deployment-guide.md)
- [Documentation serveur](docs/)
- [Procédures d'urgence](docs/emergency.md) 