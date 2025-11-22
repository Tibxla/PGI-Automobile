# 15. GUIDE D'ADMINISTRATION

## Informations du Document

| Élément | Détail |
|---------|--------|
| **Projet** | PGI Automobile - Système de Gestion Intégré |
| **Phase** | PHASE 5 - Déploiement |
| **Livrable** | Guide d'Administration |
| **Version** | 1.0 |
| **Date** | 17/11/2025 |
| **Auteur** | Équipe Administration PGI Automobile |

---

> **Note:** Ce document a été réalisé dans le cadre d'un projet académique de Licence 3 par **Thibaud** et **Melissa** sur la période du **27/10/2025 au 17/11/2025** (3 semaines).

## Table des Matières

1. [Introduction](#1-introduction)
2. [Gestion des Utilisateurs](#2-gestion-des-utilisateurs)
3. [Gestion des Rôles et Permissions](#3-gestion-des-rôles-et-permissions)
4. [Maintenance de la Base de Données](#4-maintenance-de-la-base-de-données)
5. [Sauvegardes et Restauration](#5-sauvegardes-et-restauration)
6. [Monitoring et Logs](#6-monitoring-et-logs)
7. [Sécurité](#7-sécurité)
8. [Performance et Optimisation](#8-performance-et-optimisation)
9. [Mises à Jour](#9-mises-à-jour)
10. [Procédures d'Urgence](#10-procédures-durgence)

---

## 1. Introduction

### 1.1 Objectif

Ce guide fournit les procédures et bonnes pratiques pour administrer le système **PGI Automobile** au quotidien. Il est destiné aux administrateurs système et aux Super Admin.

### 1.2 Rôles Administratifs

| Rôle | Accès | Responsabilités |
|------|-------|-----------------|
| **Super Admin** | Application complète | Gestion utilisateurs, rôles, configuration système |
| **Administrateur Système** | Serveur + Base de données | Maintenance infrastructure, backups, monitoring |
| **Directeur** | Modules métier | Supervision activité, validation actions importantes |

### 1.3 Outils Requis

- Accès SSH au serveur
- Client MySQL (MySQL Workbench, phpMyAdmin, ou ligne de commande)
- Navigateur web (pour l'interface admin)
- Outils de monitoring (Monit, Grafana, etc.)

---

## 2. Gestion des Utilisateurs

### 2.1 Accéder au Module Administration

**Via l'interface web :**

1. Se connecter avec un compte **Super Admin**
2. Cliquer sur **Administration** dans le menu principal
3. Sélectionner **Gestion des Utilisateurs**

**URL directe :** `https://votre-domaine.com/modules/admin/utilisateurs.php`

### 2.2 Créer un Nouvel Utilisateur

#### Via l'Interface Web

**Étapes :**

1. Cliquer sur **+ Nouvel Utilisateur**
2. Remplir le formulaire :
   - **Nom** : Nom de famille
   - **Prénom** : Prénom
   - **Email** : Adresse email (identifiant de connexion)
   - **Mot de passe** : Générer un mot de passe fort
   - **Rôle** : Sélectionner le rôle approprié
   - **Actif** : Cocher pour activer le compte
3. Cliquer sur **Enregistrer**
4. Communiquer les identifiants à l'utilisateur

**⚠️ Exigences du Mot de Passe :**
- Minimum 8 caractères
- Au moins 1 majuscule
- Au moins 1 minuscule
- Au moins 1 chiffre
- Au moins 1 caractère spécial

**Exemple de mot de passe fort :** `Pgi2025!Auto`

#### Via SQL (Méthode Alternative)

```sql
-- Se connecter à MySQL
mysql -u pgi_user -p pgi_automobile

-- Générer le hash du mot de passe (utiliser un outil en ligne ou PHP)
-- Exemple avec PHP :
-- php -r "echo password_hash('Pgi2025!Auto', PASSWORD_BCRYPT);"

-- Insérer l'utilisateur
INSERT INTO utilisateurs (nom, prenom, email, password, role_id, actif, date_creation)
VALUES (
    'Dupont',
    'Jean',
    'jean.dupont@entreprise.com',
    '$2y$10$lQJF8gGh5M.6L8Vx8kY8xO3zN4wY5xZ6A7bC8dD9eE0fF1gG2hH3i',  -- Hash de 'Pgi2025!Auto'
    3,  -- ID du rôle (3 = Vendeur par exemple)
    1,
    NOW()
);

-- Vérifier
SELECT id, email, nom, prenom, actif FROM utilisateurs WHERE email = 'jean.dupont@entreprise.com';
```

### 2.3 Modifier un Utilisateur

**Étapes :**

1. Dans la liste des utilisateurs, cliquer sur **Modifier** à côté de l'utilisateur
2. Modifier les champs souhaités :
   - Nom, Prénom
   - Email
   - Rôle
   - Statut (Actif/Inactif)
3. **Mot de passe** : Laisser vide pour ne pas le modifier
4. Cliquer sur **Enregistrer**

**Cas d'usage :**
- Changement de poste → Modifier le rôle
- Départ temporaire → Désactiver le compte (décocher "Actif")
- Email modifié → Mettre à jour l'email

### 2.4 Désactiver / Réactiver un Utilisateur

**Désactivation (départ, suspension) :**

```sql
UPDATE utilisateurs SET actif = 0 WHERE email = 'utilisateur@entreprise.com';
```

**Via l'interface :**
- Modifier l'utilisateur → Décocher "Actif" → Enregistrer

**Réactivation :**

```sql
UPDATE utilisateurs SET actif = 1 WHERE email = 'utilisateur@entreprise.com';
```

**⚠️ Un utilisateur désactivé ne peut plus se connecter.**

### 2.5 Réinitialiser un Mot de Passe

**Procédure manuelle (sans email automatique) :**

1. **Générer un nouveau mot de passe temporaire**
   ```bash
   # Générer un mot de passe aléatoire
   openssl rand -base64 12
   # Exemple de sortie : Xk9mP2nQ4rT8
   ```

2. **Hasher le mot de passe**
   ```bash
   php -r "echo password_hash('Xk9mP2nQ4rT8', PASSWORD_BCRYPT);"
   # Copier le hash généré
   ```

3. **Mettre à jour en base**
   ```sql
   UPDATE utilisateurs
   SET password = '$2y$10$...'  -- Insérer le hash généré
   WHERE email = 'utilisateur@entreprise.com';
   ```

4. **Communiquer le mot de passe temporaire à l'utilisateur**

5. **Demander à l'utilisateur de le changer à la première connexion**

**⚠️ Sécurité : Ne JAMAIS communiquer les mots de passe par email non chiffré.**

### 2.6 Supprimer un Utilisateur

**⚠️ ATTENTION : Suppression définitive !**

**Avant de supprimer, vérifier :**
- Aucune vente enregistrée par cet utilisateur
- Aucune action critique liée à ce compte
- Si doute → Désactiver au lieu de supprimer

**Via SQL :**

```sql
-- Vérifier les dépendances
SELECT COUNT(*) FROM ventes WHERE vendeur_id = <user_id>;
SELECT COUNT(*) FROM logs_connexion WHERE user_id = <user_id>;

-- Si OK, supprimer
DELETE FROM utilisateurs WHERE id = <user_id>;
```

**Alternative recommandée : Archivage**

```sql
-- Créer une table d'archivage
CREATE TABLE utilisateurs_archives LIKE utilisateurs;

-- Déplacer l'utilisateur
INSERT INTO utilisateurs_archives SELECT * FROM utilisateurs WHERE id = <user_id>;
DELETE FROM utilisateurs WHERE id = <user_id>;
```

### 2.7 Audit des Connexions

**Consulter l'historique des connexions :**

```sql
SELECT
    u.nom,
    u.prenom,
    u.email,
    l.ip_address,
    l.success,
    l.date_tentative
FROM logs_connexion l
JOIN utilisateurs u ON l.user_id = u.id
ORDER BY l.date_tentative DESC
LIMIT 100;
```

**Détecter les tentatives échouées suspectes :**

```sql
SELECT
    u.email,
    COUNT(*) as tentatives_echouees,
    MAX(l.date_tentative) as derniere_tentative
FROM logs_connexion l
JOIN utilisateurs u ON l.user_id = u.id
WHERE l.success = 0
  AND l.date_tentative > DATE_SUB(NOW(), INTERVAL 24 HOUR)
GROUP BY u.email
HAVING tentatives_echouees > 5
ORDER BY tentatives_echouees DESC;
```

**Identifier les comptes inactifs (> 90 jours) :**

```sql
SELECT
    u.id,
    u.email,
    u.nom,
    u.prenom,
    MAX(l.date_tentative) as derniere_connexion
FROM utilisateurs u
LEFT JOIN logs_connexion l ON u.id = l.user_id AND l.success = 1
WHERE u.actif = 1
GROUP BY u.id
HAVING derniere_connexion < DATE_SUB(NOW(), INTERVAL 90 DAY)
   OR derniere_connexion IS NULL;
```

---

## 3. Gestion des Rôles et Permissions

### 3.1 Rôles Existants

| ID | Rôle | Permissions | Description |
|----|------|-------------|-------------|
| 1 | **Super Admin** | `["*"]` | Accès total au système |
| 2 | **Directeur** | Tous modules sauf Admin | Gestion opérationnelle complète |
| 3 | **Vendeur** | Véhicules (R), Ventes (CR), Clients (CRU) | Vente et relation client |
| 4 | **Comptable** | Lecture seule sur tous modules | Consultation finances |
| 5 | **Magasinier** | Véhicules (CRU), Stock (CRU) | Gestion du parc |
| 6 | **RH** | Employés (CRUD), Paies (CRUD) | Gestion ressources humaines |

**Légende Permissions :**
- **C** : Create (Créer)
- **R** : Read (Consulter)
- **U** : Update (Modifier)
- **D** : Delete (Supprimer)

### 3.2 Matrice de Permissions

| Module | Super Admin | Directeur | Vendeur | Comptable | Magasinier | RH |
|--------|-------------|-----------|---------|-----------|------------|----|
| **Véhicules** | CRUD | CRUD | R | R | CRU | R |
| **Ventes** | CRUD | CRU | CR | R | - | R |
| **Clients** | CRUD | CRU | CRU | R | - | R |
| **Demandes Achat** | CRUD | CRUD | CR | R | CRU | - |
| **Employés** | CRUD | CRU | - | R | - | CRUD |
| **Stock** | CRUD | CRUD | R | R | CRU | - |
| **Statistiques** | R | R | - | R | - | R |
| **Administration** | CRUD | - | - | - | - | - |

### 3.3 Créer un Nouveau Rôle

**Exemple : Créer un rôle "Assistant Vendeur"**

```sql
-- Définir les permissions (format JSON)
INSERT INTO roles (nom, permissions, description, date_creation)
VALUES (
    'Assistant Vendeur',
    '["vehicules_read", "clients_read", "clients_create", "ventes_read"]',
    'Consultation véhicules et clients, ajout clients, consultation ventes',
    NOW()
);

-- Récupérer l'ID du nouveau rôle
SELECT id, nom FROM roles WHERE nom = 'Assistant Vendeur';
```

**Liste des Permissions Disponibles :**

```json
{
  "vehicules": ["vehicules_read", "vehicules_create", "vehicules_update", "vehicules_delete"],
  "ventes": ["ventes_read", "ventes_create", "ventes_update", "ventes_delete"],
  "clients": ["clients_read", "clients_create", "clients_update", "clients_delete"],
  "commandes": ["commandes_read", "commandes_create", "commandes_update", "commandes_delete"],
  "employes": ["employes_read", "employes_create", "employes_update", "employes_delete"],
  "stock": ["stock_read", "stock_update"],
  "stats": ["stats_read", "reports_read"],
  "admin": ["admin_access", "users_manage", "roles_manage"]
}
```

### 3.4 Modifier un Rôle Existant

**Exemple : Donner au Vendeur l'accès aux statistiques**

```sql
-- Récupérer les permissions actuelles
SELECT permissions FROM roles WHERE nom = 'Vendeur';

-- Mettre à jour (ajouter "stats_read")
UPDATE roles
SET permissions = '["vehicules_read", "ventes_read", "ventes_create", "clients_read", "clients_create", "clients_update", "stats_read"]'
WHERE nom = 'Vendeur';
```

**⚠️ Les utilisateurs doivent se reconnecter pour que les nouvelles permissions soient appliquées.**

### 3.5 Supprimer un Rôle

**⚠️ Vérifier qu'aucun utilisateur n'a ce rôle avant de supprimer !**

```sql
-- Vérifier les utilisateurs affectés
SELECT COUNT(*) FROM utilisateurs WHERE role_id = <role_id>;

-- Si 0, supprimer
DELETE FROM roles WHERE id = <role_id>;
```

---

## 4. Maintenance de la Base de Données

### 4.1 Tâches de Maintenance Quotidiennes

#### Vérifier l'Espace Disque

```bash
# Espace disque global
df -h

# Taille de la base de données
mysql -u pgi_user -p -e "
SELECT
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.TABLES
WHERE table_schema = 'pgi_automobile'
GROUP BY table_schema;
"
```

#### Vérifier les Processus MySQL

```bash
# Voir les processus en cours
mysql -u pgi_user -p -e "SHOW PROCESSLIST;"

# Tuer un processus bloquant (si nécessaire)
mysql -u pgi_user -p -e "KILL <process_id>;"
```

### 4.2 Optimisation des Tables

**Exécuter mensuellement :**

```sql
-- Analyser les tables
ANALYZE TABLE vehicules, ventes, clients, employes, commandes, factures, paies, logs_connexion;

-- Optimiser les tables
OPTIMIZE TABLE vehicules, ventes, clients, employes, commandes, factures, paies, logs_connexion;

-- Réparer les tables (si corruption détectée)
REPAIR TABLE vehicules;
```

**Via Script Automatisé :**

```bash
# Créer le script
cat > /usr/local/bin/optimize_pgi_db.sh << 'EOF'
#!/bin/bash
DB_USER="pgi_user"
DB_PASS="VotreMo7Pass!"
DB_NAME="pgi_automobile"

echo "Optimisation de la base de données PGI Automobile..."

mysql -u $DB_USER -p$DB_PASS $DB_NAME << SQL
ANALYZE TABLE vehicules, ventes, clients, employes, commandes, factures, paies, logs_connexion;
OPTIMIZE TABLE vehicules, ventes, clients, employes, commandes, factures, paies, logs_connexion;
SQL

echo "Optimisation terminée : $(date)"
EOF

chmod +x /usr/local/bin/optimize_pgi_db.sh

# Ajouter au cron (1er du mois à 3h)
crontab -e
# Ajouter : 0 3 1 * * /usr/local/bin/optimize_pgi_db.sh >> /var/log/pgi-optimize.log 2>&1
```

### 4.3 Nettoyage des Logs Anciens

**Supprimer les logs de connexion > 6 mois :**

```sql
DELETE FROM logs_connexion
WHERE date_tentative < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- Vérifier combien de lignes supprimées
SELECT ROW_COUNT();
```

**Via Script Automatisé (tous les mois) :**

```bash
cat > /usr/local/bin/clean_pgi_logs.sh << 'EOF'
#!/bin/bash
DB_USER="pgi_user"
DB_PASS="VotreMo7Pass!"
DB_NAME="pgi_automobile"

echo "Nettoyage des logs anciens..."

ROWS=$(mysql -u $DB_USER -p$DB_PASS $DB_NAME -se "
DELETE FROM logs_connexion WHERE date_tentative < DATE_SUB(NOW(), INTERVAL 6 MONTH);
SELECT ROW_COUNT();
")

echo "$ROWS lignes supprimées : $(date)"
EOF

chmod +x /usr/local/bin/clean_pgi_logs.sh

# Ajouter au cron (1er du mois à 4h)
crontab -e
# Ajouter : 0 4 1 * * /usr/local/bin/clean_pgi_logs.sh >> /var/log/pgi-clean.log 2>&1
```

### 4.4 Vérification de l'Intégrité

**Vérifier les contraintes de clés étrangères :**

```sql
-- Vérifier qu'il n'y a pas de ventes orphelines
SELECT COUNT(*)
FROM ventes v
LEFT JOIN vehicules ve ON v.vehicule_id = ve.id
WHERE ve.id IS NULL;
-- Devrait retourner 0

SELECT COUNT(*)
FROM ventes v
LEFT JOIN clients c ON v.client_id = c.id
WHERE c.id IS NULL;
-- Devrait retourner 0

-- Vérifier les utilisateurs sans rôle
SELECT COUNT(*)
FROM utilisateurs u
LEFT JOIN roles r ON u.role_id = r.id
WHERE r.id IS NULL;
-- Devrait retourner 0
```

### 4.5 Monitoring de la Performance

**Identifier les requêtes lentes :**

```bash
# Activer le log des requêtes lentes
mysql -u root -p

SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;  -- Requêtes > 2 secondes
SET GLOBAL slow_query_log_file = '/var/log/mysql/slow-query.log';

EXIT;
```

**Analyser le log :**

```bash
# Voir les dernières requêtes lentes
tail -f /var/log/mysql/slow-query.log

# Résumé avec mysqldumpslow
mysqldumpslow -s t -t 10 /var/log/mysql/slow-query.log
```

---

## 5. Sauvegardes et Restauration

### 5.1 Stratégie de Sauvegarde

**Recommandations :**

| Type | Fréquence | Rétention | Stockage |
|------|-----------|-----------|----------|
| **Backup complet** | Quotidien | 7 jours | Local + Cloud |
| **Backup différentiel** | Toutes les 6h | 48h | Local |
| **Backup archives** | Mensuel | 12 mois | Cloud |

### 5.2 Backup Manuel

#### Backup Base de Données

```bash
# Backup complet avec compression
mysqldump -u pgi_user -p pgi_automobile | gzip > /tmp/pgi_db_$(date +%Y%m%d_%H%M%S).sql.gz

# Backup avec structure uniquement (sans données)
mysqldump -u pgi_user -p --no-data pgi_automobile > /tmp/pgi_structure.sql

# Backup d'une table spécifique
mysqldump -u pgi_user -p pgi_automobile vehicules > /tmp/vehicules_backup.sql
```

#### Backup Fichiers Application

```bash
# Backup complet de l'application
tar -czf /tmp/pgi_files_$(date +%Y%m%d_%H%M%S).tar.gz \
    /var/www/pgi-automobile \
    --exclude='/var/www/pgi-automobile/logs' \
    --exclude='/var/www/pgi-automobile/sessions'

# Backup des uploads uniquement
tar -czf /tmp/pgi_uploads_$(date +%Y%m%d).tar.gz /var/www/pgi-automobile/uploads
```

### 5.3 Backup Automatique Complet

**Script de backup complet (déjà configuré lors de l'installation) :**

```bash
#!/bin/bash
# /usr/local/bin/backup_pgi.sh

BACKUP_DIR="/var/backups/pgi-automobile"
DATE=$(date +%Y%m%d_%H%M%S)
DB_USER="pgi_user"
DB_PASS="VotreMo7Pass!"
DB_NAME="pgi_automobile"
RETENTION_DAYS=7

# Créer le répertoire
mkdir -p $BACKUP_DIR

echo "=== Backup PGI Automobile - $DATE ==="

# Backup BDD
echo "Backup base de données..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz
if [ $? -eq 0 ]; then
    echo "✅ Base de données sauvegardée"
else
    echo "❌ Erreur backup BDD"
    exit 1
fi

# Backup fichiers
echo "Backup fichiers application..."
tar -czf $BACKUP_DIR/files_$DATE.tar.gz \
    /var/www/pgi-automobile \
    --exclude='/var/www/pgi-automobile/logs' \
    --exclude='/var/www/pgi-automobile/sessions' 2>/dev/null
if [ $? -eq 0 ]; then
    echo "✅ Fichiers sauvegardés"
else
    echo "❌ Erreur backup fichiers"
    exit 1
fi

# Nettoyer anciens backups
echo "Nettoyage des backups > $RETENTION_DAYS jours..."
find $BACKUP_DIR -name "*.gz" -mtime +$RETENTION_DAYS -delete
CLEANED=$(find $BACKUP_DIR -name "*.gz" -mtime +$RETENTION_DAYS | wc -l)
echo "✅ $CLEANED anciens backups supprimés"

# Taille totale des backups
TOTAL_SIZE=$(du -sh $BACKUP_DIR | cut -f1)
echo "Espace utilisé : $TOTAL_SIZE"

echo "=== Backup terminé ==="
```

**Vérifier les backups :**

```bash
# Lister les backups
ls -lh /var/backups/pgi-automobile/

# Vérifier l'intégrité d'un backup BDD
gunzip -c /var/backups/pgi-automobile/db_20251217_020000.sql.gz | head -20

# Vérifier l'intégrité d'un backup fichiers
tar -tzf /var/backups/pgi-automobile/files_20251217_020000.tar.gz | head -20
```

### 5.4 Sauvegarde vers le Cloud (Optionnel)

#### Option A : AWS S3

```bash
# Installer AWS CLI
apt install awscli -y

# Configurer
aws configure
# Entrer : Access Key, Secret Key, Region (eu-west-3), Format (json)

# Script de sync
cat > /usr/local/bin/backup_pgi_s3.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/pgi-automobile"
S3_BUCKET="s3://votre-bucket-pgi/backups"

# Sync vers S3
aws s3 sync $BACKUP_DIR $S3_BUCKET --storage-class STANDARD_IA

echo "Backup synced to S3 : $(date)"
EOF

chmod +x /usr/local/bin/backup_pgi_s3.sh

# Ajouter au cron (quotidien à 5h)
crontab -e
# Ajouter : 0 5 * * * /usr/local/bin/backup_pgi_s3.sh >> /var/log/pgi-s3-sync.log 2>&1
```

#### Option B : FTP/SFTP

```bash
# Installer lftp
apt install lftp -y

# Script de sync FTP
cat > /usr/local/bin/backup_pgi_ftp.sh << 'EOF'
#!/bin/bash
BACKUP_DIR="/var/backups/pgi-automobile"
FTP_HOST="ftp.votre-hebergeur.com"
FTP_USER="backup_user"
FTP_PASS="MotDePasseFTP"
FTP_DIR="/backups/pgi-automobile"

lftp -e "
set ftp:ssl-allow no;
open $FTP_HOST;
user $FTP_USER $FTP_PASS;
mirror -R $BACKUP_DIR $FTP_DIR;
bye
"

echo "Backup uploaded via FTP : $(date)"
EOF

chmod +x /usr/local/bin/backup_pgi_ftp.sh
```

### 5.5 Restauration depuis Backup

#### Restaurer la Base de Données

```bash
# Restaurer un backup complet
gunzip < /var/backups/pgi-automobile/db_20251217_020000.sql.gz | mysql -u pgi_user -p pgi_automobile

# Restaurer une table spécifique
mysql -u pgi_user -p pgi_automobile < /tmp/vehicules_backup.sql

# Vérifier après restauration
mysql -u pgi_user -p pgi_automobile -e "SELECT COUNT(*) FROM vehicules;"
```

**⚠️ Restauration complète = Perte des données depuis le backup !**

#### Restaurer les Fichiers Application

```bash
# Arrêter Apache
systemctl stop apache2

# Sauvegarder l'état actuel (au cas où)
mv /var/www/pgi-automobile /var/www/pgi-automobile.bak

# Extraire le backup
mkdir -p /var/www/pgi-automobile
tar -xzf /var/backups/pgi-automobile/files_20251217_020000.tar.gz -C /

# Corriger les permissions
chown -R www-data:www-data /var/www/pgi-automobile
chmod -R 755 /var/www/pgi-automobile

# Redémarrer Apache
systemctl start apache2

# Vérifier le site
curl -I https://votre-domaine.com
```

### 5.6 Test de Restauration (à faire trimestriellement)

**Procédure de test :**

1. **Créer un environnement de test isolé**
2. **Restaurer le dernier backup**
3. **Vérifier l'intégrité des données**
4. **Tester la connexion et les fonctionnalités**
5. **Documenter les résultats**

```bash
# Script de test de restauration
cat > /tmp/test_restore.sh << 'EOF'
#!/bin/bash
echo "=== Test de Restauration PGI Automobile ==="

# Créer une BDD de test
mysql -u root -p -e "CREATE DATABASE pgi_automobile_test;"

# Restaurer le dernier backup
LAST_BACKUP=$(ls -t /var/backups/pgi-automobile/db_*.sql.gz | head -1)
echo "Restauration de : $LAST_BACKUP"
gunzip < $LAST_BACKUP | mysql -u root -p pgi_automobile_test

# Vérifier les tables
TABLES=$(mysql -u root -p pgi_automobile_test -se "SHOW TABLES;" | wc -l)
echo "Nombre de tables restaurées : $TABLES (attendu: 10)"

# Vérifier les données
VEHICULES=$(mysql -u root -p pgi_automobile_test -se "SELECT COUNT(*) FROM vehicules;")
echo "Véhicules : $VEHICULES"

VENTES=$(mysql -u root -p pgi_automobile_test -se "SELECT COUNT(*) FROM ventes;")
echo "Ventes : $VENTES"

# Nettoyer
mysql -u root -p -e "DROP DATABASE pgi_automobile_test;"

echo "=== Test terminé ==="
EOF

chmod +x /tmp/test_restore.sh
```

---

## 6. Monitoring et Logs

### 6.1 Logs à Surveiller

| Log | Emplacement | Objectif |
|-----|-------------|----------|
| **Apache Access** | `/var/log/apache2/pgi-automobile-access.log` | Trafic, requêtes HTTP |
| **Apache Error** | `/var/log/apache2/pgi-automobile-error.log` | Erreurs 500, PHP |
| **PHP Error** | `/var/log/php/error.log` | Erreurs PHP |
| **MySQL Error** | `/var/log/mysql/error.log` | Erreurs MySQL |
| **MySQL Slow Query** | `/var/log/mysql/slow-query.log` | Requêtes lentes |
| **Application** | `/var/www/pgi-automobile/logs/app.log` | Logs métier |
| **Backup** | `/var/log/pgi-backup.log` | Résultats backups |

### 6.2 Consulter les Logs en Temps Réel

```bash
# Apache errors
tail -f /var/log/apache2/pgi-automobile-error.log

# PHP errors
tail -f /var/log/php/error.log

# Tous les logs PGI
tail -f /var/log/apache2/pgi-automobile-*.log /var/log/php/error.log /var/www/pgi-automobile/logs/*.log

# Filtrer les erreurs critiques
grep -i "fatal\|error\|critical" /var/log/apache2/pgi-automobile-error.log | tail -20
```

### 6.3 Analyser le Trafic

**Statistiques d'accès (requêtes par jour) :**

```bash
cat /var/log/apache2/pgi-automobile-access.log | \
    awk '{print $4}' | \
    cut -d: -f1 | \
    uniq -c
```

**Pages les plus consultées :**

```bash
cat /var/log/apache2/pgi-automobile-access.log | \
    awk '{print $7}' | \
    sort | uniq -c | sort -rn | head -20
```

**Adresses IP les plus actives :**

```bash
cat /var/log/apache2/pgi-automobile-access.log | \
    awk '{print $1}' | \
    sort | uniq -c | sort -rn | head -20
```

**Codes HTTP retournés :**

```bash
cat /var/log/apache2/pgi-automobile-access.log | \
    awk '{print $9}' | \
    sort | uniq -c | sort -rn
```

### 6.4 Rotation des Logs

**Configuration logrotate (déjà configuré par défaut) :**

```bash
# Vérifier la config
cat /etc/logrotate.d/apache2

# Forcer la rotation manuellement (test)
logrotate -f /etc/logrotate.d/apache2
```

**Configuration personnalisée pour logs application :**

```bash
cat > /etc/logrotate.d/pgi-automobile << 'EOF'
/var/www/pgi-automobile/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0664 www-data www-data
    sharedscripts
    postrotate
        systemctl reload apache2 > /dev/null 2>&1
    endscript
}
EOF
```

### 6.5 Alertes Automatiques (Email)

**Configuration des alertes critiques :**

```bash
# Installer mailutils
apt install mailutils -y

# Script de monitoring
cat > /usr/local/bin/monitor_pgi.sh << 'EOF'
#!/bin/bash
ADMIN_EMAIL="admin@votredomaine.com"
ALERT=0

# Vérifier Apache
if ! systemctl is-active --quiet apache2; then
    echo "ALERTE : Apache est arrêté !" | mail -s "PGI AUTO - Apache DOWN" $ADMIN_EMAIL
    ALERT=1
fi

# Vérifier MySQL
if ! systemctl is-active --quiet mysql; then
    echo "ALERTE : MySQL est arrêté !" | mail -s "PGI AUTO - MySQL DOWN" $ADMIN_EMAIL
    ALERT=1
fi

# Vérifier espace disque (> 85%)
DISK_USAGE=$(df / | grep / | awk '{print $5}' | sed 's/%//g')
if [ $DISK_USAGE -gt 85 ]; then
    echo "ALERTE : Espace disque à ${DISK_USAGE}% !" | mail -s "PGI AUTO - Disque Plein" $ADMIN_EMAIL
    ALERT=1
fi

# Vérifier erreurs PHP (> 10 dans la dernière heure)
PHP_ERRORS=$(grep "$(date '+%d-%b-%Y %H')" /var/log/php/error.log | wc -l)
if [ $PHP_ERRORS -gt 10 ]; then
    echo "ALERTE : $PHP_ERRORS erreurs PHP dans la dernière heure !" | mail -s "PGI AUTO - Erreurs PHP" $ADMIN_EMAIL
    ALERT=1
fi

if [ $ALERT -eq 0 ]; then
    echo "Monitoring OK : $(date)"
else
    echo "ALERTES ENVOYÉES : $(date)"
fi
EOF

chmod +x /usr/local/bin/monitor_pgi.sh

# Ajouter au cron (toutes les 15 minutes)
crontab -e
# Ajouter : */15 * * * * /usr/local/bin/monitor_pgi.sh >> /var/log/pgi-monitor.log 2>&1
```

### 6.6 Dashboard de Monitoring (Optionnel)

**Installation de Grafana + Prometheus (monitoring avancé) :**

Voir documentation externe : https://grafana.com/docs/

---

## 7. Sécurité

### 7.1 Audit de Sécurité Mensuel

**Checklist :**

- ☐ Vérifier les comptes utilisateurs actifs
- ☐ Analyser les logs de connexion (tentatives échouées)
- ☐ Vérifier les mises à jour de sécurité système
- ☐ Scanner les vulnérabilités (OWASP ZAP)
- ☐ Vérifier les certificats SSL (expiration)
- ☐ Contrôler les permissions fichiers
- ☐ Vérifier les règles Firewall
- ☐ Tester les backups

### 7.2 Changement de Mot de Passe Administrateur

**Procédure trimestrielle :**

```sql
-- Générer un nouveau mot de passe
-- php -r "echo password_hash('NouveauMo7PassF0rt!', PASSWORD_BCRYPT);"

UPDATE utilisateurs
SET password = '$2y$10$...'  -- Nouveau hash
WHERE email = 'admin@pgi-auto.local';
```

### 7.3 Vérifier les Permissions Fichiers

```bash
# Permissions correctes
find /var/www/pgi-automobile -type d -exec chmod 755 {} \;
find /var/www/pgi-automobile -type f -exec chmod 644 {} \;

# Propriétaire correct
chown -R www-data:www-data /var/www/pgi-automobile

# Vérifier les fichiers accessibles en écriture (ne devrait pas y en avoir hors logs/uploads)
find /var/www/pgi-automobile -type f -perm -o+w ! -path "*/logs/*" ! -path "*/uploads/*"
```

### 7.4 Scan de Vulnérabilités

**Avec Nikto (scanner web) :**

```bash
# Installer Nikto
apt install nikto -y

# Scanner
nikto -h https://pgi-automobile.votredomaine.com -ssl

# Analyser le rapport
```

**Avec OWASP ZAP (GUI) :**

1. Télécharger OWASP ZAP : https://www.zaproxy.org/
2. Lancer un scan automatique sur l'URL du site
3. Analyser les alertes
4. Corriger les vulnérabilités détectées

### 7.5 Certificat SSL - Renouvellement

**Vérifier l'expiration :**

```bash
# Vérifier la date d'expiration
echo | openssl s_client -servername pgi-automobile.votredomaine.com \
    -connect pgi-automobile.votredomaine.com:443 2>/dev/null | \
    openssl x509 -noout -dates

# Renouveler manuellement (si nécessaire)
certbot renew

# Le renouvellement automatique est configuré via systemd timer
systemctl list-timers | grep certbot
```

**Alertes d'expiration (30 jours avant) :**

```bash
cat > /usr/local/bin/check_ssl_expiry.sh << 'EOF'
#!/bin/bash
DOMAIN="pgi-automobile.votredomaine.com"
ADMIN_EMAIL="admin@votredomaine.com"

EXPIRY_DATE=$(echo | openssl s_client -servername $DOMAIN -connect $DOMAIN:443 2>/dev/null | openssl x509 -noout -enddate | cut -d= -f2)
EXPIRY_EPOCH=$(date -d "$EXPIRY_DATE" +%s)
NOW_EPOCH=$(date +%s)
DAYS_LEFT=$(( ($EXPIRY_EPOCH - $NOW_EPOCH) / 86400 ))

if [ $DAYS_LEFT -lt 30 ]; then
    echo "ALERTE : Le certificat SSL expire dans $DAYS_LEFT jours !" | mail -s "PGI AUTO - Certificat SSL" $ADMIN_EMAIL
fi

echo "Certificat SSL : $DAYS_LEFT jours restants"
EOF

chmod +x /usr/local/bin/check_ssl_expiry.sh

# Cron hebdomadaire
crontab -e
# Ajouter : 0 8 * * 1 /usr/local/bin/check_ssl_expiry.sh >> /var/log/ssl-check.log 2>&1
```

---

## 8. Performance et Optimisation

### 8.1 Identifier les Goulots d'Étranglement

**Temps de réponse par page :**

```bash
# Analyser les temps dans access.log
cat /var/log/apache2/pgi-automobile-access.log | \
    awk '{print $7, $NF}' | \
    sort | uniq -c | sort -rn | head -20
```

**Utilisation ressources en temps réel :**

```bash
# CPU et RAM
htop

# Processus MySQL
mytop

# Connexions MySQL actives
mysql -u pgi_user -p -e "SHOW PROCESSLIST;"
```

### 8.2 Optimiser les Requêtes SQL

**Identifier les requêtes lentes (déjà configuré) :**

```bash
tail -f /var/log/mysql/slow-query.log
```

**Analyser une requête avec EXPLAIN :**

```sql
EXPLAIN SELECT v.*, COUNT(c.id) as nb_commandes
FROM vehicules v
LEFT JOIN commandes c ON v.id = c.vehicule_id
WHERE v.statut = 'stock'
GROUP BY v.id
ORDER BY v.date_ajout DESC
LIMIT 20;
```

**Ajouter des index si nécessaire :**

```sql
-- Exemple : index sur colonne fréquemment filtrée
CREATE INDEX idx_vehicules_statut_date ON vehicules(statut, date_ajout);

-- Vérifier l'amélioration
EXPLAIN SELECT * FROM vehicules WHERE statut = 'stock' ORDER BY date_ajout DESC;
```

### 8.3 Cache Applicatif (Optionnel)

**Installation Redis (cache en mémoire) :**

```bash
# Installer Redis
apt install redis-server php-redis -y

# Vérifier
redis-cli ping
# Devrait retourner : PONG

# Redémarrer Apache
systemctl restart apache2
```

**Utilisation dans le code PHP :**

```php
<?php
// Connexion Redis
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// Cache des KPIs (expire après 5 minutes)
$cache_key = 'kpis_dashboard_' . date('Y-m-d-H-i');
$kpis = $redis->get($cache_key);

if (!$kpis) {
    // Calcul des KPIs (requêtes SQL)
    $kpis = getKPIs();  // Fonction existante

    // Stocker en cache (300 secondes = 5 minutes)
    $redis->setex($cache_key, 300, json_encode($kpis));
}

// Utiliser les données
$kpis = json_decode($kpis, true);
?>
```

### 8.4 Optimisation Apache

**Activer la compression Gzip :**

```bash
# Activer mod_deflate
a2enmod deflate

# Configurer
nano /etc/apache2/mods-available/deflate.conf
```

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
    DeflateCompressionLevel 6
</IfModule>
```

```bash
# Redémarrer
systemctl restart apache2
```

**Activer le cache navigateur :**

```bash
# Activer mod_expires
a2enmod expires

# Configurer dans le VirtualHost ou .htaccess
nano /var/www/pgi-automobile/.htaccess
```

```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType text/css "access plus 1 week"
    ExpiresByType application/javascript "access plus 1 week"
</IfModule>
```

### 8.5 Optimisation PHP

**Activer OPcache :**

```bash
# Vérifier si activé
php -i | grep opcache

# Si non activé, éditer php.ini
nano /etc/php/8.1/apache2/php.ini
```

```ini
[opcache]
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

```bash
# Redémarrer Apache
systemctl restart apache2
```

---

## 9. Mises à Jour

### 9.1 Mises à Jour Système

**Mensuel :**

```bash
# Mettre à jour les paquets
apt update && apt upgrade -y

# Vérifier si redémarrage nécessaire
if [ -f /var/run/reboot-required ]; then
    echo "Redémarrage requis !"
    # Planifier un redémarrage hors heures de pointe
    shutdown -r +60  # Dans 60 minutes
fi
```

### 9.2 Mises à Jour de l'Application

**Procédure :**

1. **Sauvegarder l'état actuel**
   ```bash
   /usr/local/bin/backup_pgi.sh
   ```

2. **Activer le mode maintenance**
   ```bash
   touch /var/www/pgi-automobile/maintenance.flag
   ```

3. **Récupérer la nouvelle version**
   ```bash
   cd /var/www/pgi-automobile
   git pull origin main
   # OU
   # Déployer la nouvelle release manuellement
   ```

4. **Appliquer les migrations SQL (si nécessaire)**
   ```bash
   mysql -u pgi_user -p pgi_automobile < /var/www/pgi-automobile/sql/migrations/v1.1.0.sql
   ```

5. **Tester**
   ```bash
   curl -I https://pgi-automobile.votredomaine.com
   ```

6. **Désactiver le mode maintenance**
   ```bash
   rm /var/www/pgi-automobile/maintenance.flag
   ```

### 9.3 Rollback (en cas de problème)

```bash
# Restaurer les fichiers
cd /var/www
mv pgi-automobile pgi-automobile.failed
tar -xzf /var/backups/pgi-automobile/files_YYYYMMDD_HHMMSS.tar.gz

# Restaurer la BDD
gunzip < /var/backups/pgi-automobile/db_YYYYMMDD_HHMMSS.sql.gz | mysql -u pgi_user -p pgi_automobile

# Redémarrer Apache
systemctl restart apache2
```

---

## 10. Procédures d'Urgence

### 10.1 Serveur Inaccessible

**Diagnostics :**

```bash
# Vérifier Apache
systemctl status apache2

# Vérifier MySQL
systemctl status mysql

# Vérifier logs
tail -f /var/log/apache2/error.log
tail -f /var/log/mysql/error.log
```

**Actions :**

```bash
# Redémarrer Apache
systemctl restart apache2

# Redémarrer MySQL
systemctl restart mysql

# Si échec, redémarrer le serveur
reboot
```

### 10.2 Base de Données Corrompue

**Symptômes :** Erreurs "Table is marked as crashed"

```sql
-- Réparer toutes les tables
REPAIR TABLE vehicules;
REPAIR TABLE ventes;
REPAIR TABLE clients;
-- etc.

-- OU via myisamchk (serveur arrêté)
systemctl stop mysql
myisamchk -r /var/lib/mysql/pgi_automobile/*.MYI
systemctl start mysql
```

### 10.3 Attaque en Cours

**Indicateurs :**
- Pic soudain de trafic
- Nombreuses tentatives de connexion échouées
- CPU/RAM à 100%

**Actions Immédiates :**

```bash
# Identifier les IPs suspectes
tail -1000 /var/log/apache2/pgi-automobile-access.log | awk '{print $1}' | sort | uniq -c | sort -rn | head

# Bloquer une IP avec UFW
ufw deny from <IP_SUSPECTE>

# Activer Fail2Ban (si pas déjà fait)
systemctl enable fail2ban
systemctl start fail2ban

# Forcer la déconnexion de toutes les sessions
mysql -u pgi_user -p pgi_automobile -e "TRUNCATE TABLE sessions;"

# Activer le mode maintenance temporairement
touch /var/www/pgi-automobile/maintenance.flag
```

### 10.4 Données Supprimées par Erreur

**Restaurer depuis backup (le plus récent) :**

```bash
# Identifier le backup
ls -lh /var/backups/pgi-automobile/

# Restaurer la BDD complète
gunzip < /var/backups/pgi-automobile/db_LATEST.sql.gz | mysql -u pgi_user -p pgi_automobile

# OU restaurer une table spécifique
gunzip < /var/backups/pgi-automobile/db_LATEST.sql.gz | mysql -u pgi_user -p pgi_automobile --one-database vehicules
```

### 10.5 Contacts d'Urgence

| Rôle | Nom | Téléphone | Email |
|------|-----|-----------|-------|
| **Admin Système** | - | - | admin@votredomaine.com |
| **Développeur Lead** | - | - | dev@votredomaine.com |
| **Hébergeur** | - | - | support@hebergeur.com |
| **Client** | - | - | client@pgi-auto.local |

---

## Conclusion

Ce guide d'administration couvre les tâches quotidiennes et exceptionnelles pour maintenir le système PGI Automobile en condition opérationnelle optimale.

### Checklist de l'Administrateur

**Quotidien :**
- ☐ Vérifier les logs (erreurs critiques)
- ☐ Vérifier l'espace disque
- ☐ Vérifier les backups

**Hebdomadaire :**
- ☐ Analyser les logs de connexion
- ☐ Vérifier les performances (requêtes lentes)
- ☐ Tester une restauration depuis backup

**Mensuel :**
- ☐ Optimiser les tables SQL
- ☐ Nettoyer les logs anciens
- ☐ Audit de sécurité
- ☐ Mises à jour système

**Trimestriel :**
- ☐ Changer le mot de passe admin
- ☐ Scanner les vulnérabilités
- ☐ Test complet de restauration
- ☐ Revue des utilisateurs actifs

### Documentation Complémentaire

- **Installation** : `/docs/livrables/phase_5/14_guide_installation.md`
- **Manuel Utilisateur** : `/docs/livrables/phase_6/16_manuel_utilisateur.md` (Phase 6)
- **Documentation Technique** : `/docs/livrables/phase_4/11_documentation_technique.md`

---

**Document Version :** 1.0
**Dernière mise à jour :** 17/11/2025
**Auteur :** Équipe Administration PGI Automobile
