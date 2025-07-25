# 🔒 Système de Sécurité de l'Administrateur Principal

## Vue d'ensemble

Ce document décrit le système de sécurité mis en place pour protéger l'administrateur principal de la plateforme Génération Phanérosis.

## 🛡️ Niveaux de Protection

### 1. Protection au Niveau du Modèle (Eloquent)

**Fichier :** `app/Models/User.php`

- **Hooks Eloquent :** Utilisation des événements `saving` et `deleting`
- **Forçage des valeurs :** L'email, le rôle et le statut sont automatiquement restaurés
- **Exception automatique :** La suppression est empêchée par une exception

```php
// Protection automatique lors de la sauvegarde
static::saving(function ($user) {
    if ($user->isMainAdmin()) {
        $user->email = config('admin_security.main_admin.email');
        $user->role = config('admin_security.main_admin.forced_role');
        $user->is_active = config('admin_security.main_admin.forced_status');
    }
});
```

### 2. Protection au Niveau de la Base de Données

**Fichier :** `database/migrations/2025_07_25_175801_add_main_admin_protection_constraints.php`

- **Triggers MySQL :** 3 triggers pour empêcher les modifications, suppressions et désactivations
- **Messages d'erreur explicites :** Chaque tentative est bloquée avec un message clair

```sql
-- Trigger pour empêcher la modification
CREATE TRIGGER prevent_main_admin_modification 
BEFORE UPDATE ON users 
FOR EACH ROW 
BEGIN
    IF OLD.email = "kouroumaelisee@gmail.com" THEN
        SIGNAL SQLSTATE "45000" 
        SET MESSAGE_TEXT = "L'administrateur principal ne peut pas être modifié";
    END IF;
END
```

### 3. Protection au Niveau du Contrôleur

**Fichier :** `app/Http/Controllers/AdminUserController.php`

- **Méthodes de vérification :** `canBeModifiedBy()` et `canBeDeletedBy()`
- **Logs détaillés :** Chaque tentative est enregistrée avec contexte complet
- **Gestion d'erreurs :** Try-catch avec messages d'erreur appropriés

### 4. Protection au Niveau du Middleware

**Fichier :** `app/Http/Middleware/MainAdminSecurityMiddleware.php`

- **Surveillance automatique :** Tous les accès sont surveillés
- **Logs d'accès :** Chaque action est enregistrée
- **Traçabilité complète :** IP, User-Agent, timestamp

## 📊 Système de Logging

### Niveaux de Log

- **INFO :** Accès normaux et actions de l'admin principal
- **WARNING :** Tentatives de modification non autorisées
- **CRITICAL :** Tentatives de suppression

### Informations Enregistrées

```php
[
    'user_id' => $user->id,
    'target_user_email' => $user->email,
    'attempted_by' => $currentUser->id,
    'attempted_by_email' => $currentUser->email,
    'ip' => request()->ip(),
    'user_agent' => request()->userAgent(),
    'timestamp' => now()
]
```

## 🔧 Configuration

**Fichier :** `config/admin_security.php`

### Paramètres Principaux

```php
'main_admin' => [
    'email' => env('MAIN_ADMIN_EMAIL', 'kouroumaelisee@gmail.com'),
    'forced_role' => 'admin',
    'forced_status' => true,
    'prevent_modification' => true,
    'prevent_deletion' => true,
    'prevent_deactivation' => true,
],
```

### Variables d'Environnement

```env
MAIN_ADMIN_EMAIL=kouroumaelisee@gmail.com
ADMIN_SECURITY_LOGGING=true
ADMIN_SECURITY_EMAIL_NOTIFICATIONS=false
ADMIN_SECURITY_ALERT_EMAIL=kouroumaelisee@gmail.com
ADMIN_SECURITY_ALERT_THRESHOLD=3
ADMIN_SECURITY_RATE_LIMITING=true
ADMIN_SECURITY_MAX_ATTEMPTS=5
ADMIN_SECURITY_BLOCK_DURATION=15
ADMIN_SECURITY_IP_WHITELIST=false
ADMIN_SECURITY_ALLOWED_IPS=
```

## 📋 Commandes de Surveillance

### Surveillance des Accès

```bash
# Analyser les 7 derniers jours
php artisan admin:monitor-access

# Analyser les 30 derniers jours
php artisan admin:monitor-access --days=30
```

### Sortie de la Commande

```
📊 Statistiques de sécurité :
+------------------------------+--------+
| Type d'événement            | Nombre |
+------------------------------+--------+
| Accès à l'admin principal   | 5      |
| Actions de l'admin principal| 12     |
| Tentatives de modification  | 0      |
| Tentatives de suppression   | 0      |
+------------------------------+--------+
✅ Aucune tentative suspecte détectée.
```

## 🚨 Gestion des Alertes

### Seuils d'Alerte

- **Modification :** Immédiat (WARNING)
- **Suppression :** Immédiat (CRITICAL)
- **Accès multiples :** Configurable via `ADMIN_SECURITY_ALERT_THRESHOLD`

### Notifications

- **Email :** Configurable via `ADMIN_SECURITY_EMAIL_NOTIFICATIONS`
- **Logs :** Toujours actifs
- **Console :** Commande de surveillance

## 🔐 Hiérarchie des Permissions

### Administrateur Principal
- ✅ **Tous les droits** sur tous les utilisateurs
- ✅ Peut modifier, supprimer et désactiver tous les utilisateurs
- ✅ Peut promouvoir des utilisateurs au rang d'administrateur
- ❌ **Ne peut pas** être modifié, supprimé ou désactivé

### Administrateurs Normaux
- ❌ **Aucun droit** de modification sur les utilisateurs
- ❌ **Ne peut pas** modifier, supprimer ou désactiver des utilisateurs
- ❌ **Ne peut pas** promouvoir des utilisateurs
- ✅ Peut seulement **consulter** la liste des utilisateurs

### Pasteurs
- ❌ **Aucun droit** de modification sur les utilisateurs
- ❌ **Ne peut pas** modifier, supprimer ou désactiver des utilisateurs
- ❌ **Ne peut pas** promouvoir des utilisateurs
- ✅ Peut seulement **consulter** la liste des utilisateurs

### Membres et Leaders
- ❌ **Aucun accès** à la gestion des utilisateurs

## 🛠️ Maintenance

### Vérification de l'Intégrité

```bash
# Vérifier les logs de sécurité
php artisan admin:monitor-access

# Vérifier les triggers de base de données
SHOW TRIGGERS WHERE `Table` = 'users';

# Vérifier la configuration
php artisan config:show admin_security
```

### Mise à Jour de la Configuration

1. Modifier `config/admin_security.php`
2. Mettre à jour les variables d'environnement
3. Vider le cache de configuration : `php artisan config:clear`

### Sauvegarde de Sécurité

```bash
# Exporter les logs de sécurité
grep -i "administrateur principal" storage/logs/laravel.log > security_logs.txt

# Sauvegarder la configuration
cp config/admin_security.php backup_admin_security.php
```

## ⚠️ Points d'Attention

1. **Email de l'Admin Principal :** Ne jamais modifier dans la base de données
2. **Triggers MySQL :** Vérifier leur existence après les migrations
3. **Logs :** Surveiller régulièrement les tentatives d'accès
4. **Configuration :** Tester après chaque modification
5. **Permissions :** Vérifier la hiérarchie des rôles

## 🔍 Dépannage

### Problèmes Courants

1. **Erreur de trigger MySQL :** Vérifier la syntaxe et les permissions
2. **Logs non générés :** Vérifier les permissions d'écriture
3. **Configuration non prise en compte :** Vider le cache de configuration
4. **Middleware non appliqué :** Vérifier l'enregistrement dans Kernel.php

### Commandes de Diagnostic

```bash
# Vérifier l'état du système
php artisan admin:monitor-access

# Vérifier les logs en temps réel
tail -f storage/logs/laravel.log | grep -i "administrateur"

# Tester la configuration
php artisan tinker
>>> config('admin_security.main_admin.email')
```

---

**⚠️ IMPORTANT :** Ce système de sécurité est critique pour la protection de la plateforme. Toute modification doit être testée en environnement de développement avant d'être déployée en production. 