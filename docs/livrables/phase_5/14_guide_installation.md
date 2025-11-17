# 14. GUIDE D'INSTALLATION

## Informations du Document

| Élément | Détail |
|---------|--------|
| **Projet** | PGI Automobile - Système de Gestion Intégré |
| **Phase** | PHASE 5 - Déploiement |
| **Livrable** | Guide d'Installation |
| **Version** | 1.0 |
| **Date** | 17/11/2025 |
| **Auteur** | Équipe DevOps PGI Automobile |

---

## Table des Matières

1. [Introduction](#1-introduction)
2. [Prérequis Système](#2-prérequis-système)
3. [Installation des Dépendances](#3-installation-des-dépendances)
4. [Installation de l'Application](#4-installation-de-lapplication)
5. [Configuration](#5-configuration)
6. [Initialisation de la Base de Données](#6-initialisation-de-la-base-de-données)
7. [Configuration du Serveur Web](#7-configuration-du-serveur-web)
8. [Vérification de l'Installation](#8-vérification-de-linstallation)
9. [Sécurisation](#9-sécurisation)
10. [Dépannage](#10-dépannage)

---

## 1. Introduction

### 1.1 Objectif

Ce guide décrit la procédure d'installation complète du système **PGI Automobile** sur un serveur de production. Il couvre l'installation des dépendances, la configuration du système, et les vérifications nécessaires avant la mise en service.

### 1.2 Public Cible

- Administrateurs système
- Équipe DevOps
- Techniciens informatiques

### 1.3 Durée Estimée

**Installation complète : 2-3 heures** (selon l'expérience)

- Installation dépendances : 30-45 min
- Configuration application : 15-20 min
- Base de données : 10-15 min
- Configuration serveur web : 20-30 min
- Tests et sécurisation : 30-45 min

---

## 2. Prérequis Système

### 2.1 Configuration Matérielle Minimale

#### Environnement de Test

| Composant | Spécification |
|-----------|---------------|
| **CPU** | 2 cores @ 2.0 GHz |
| **RAM** | 4 GB |
| **Disque** | 20 GB SSD |
| **Réseau** | 100 Mbps |

#### Environnement de Production

| Composant | Spécification Recommandée |
|-----------|---------------------------|
| **CPU** | 4 cores @ 2.5 GHz ou plus |
| **RAM** | 8 GB minimum (16 GB recommandé) |
| **Disque** | 50 GB SSD minimum (RAID 1 recommandé) |
| **Réseau** | 1 Gbps |

**Dimensionnement par Usage :**

| Utilisateurs | CPU | RAM | Disque |
|--------------|-----|-----|--------|
| 1-20 | 2 cores | 4 GB | 20 GB |
| 20-50 | 4 cores | 8 GB | 50 GB |
| 50-100 | 8 cores | 16 GB | 100 GB |
| 100+ | 16+ cores | 32+ GB | 200+ GB + Load Balancer |

### 2.2 Système d'Exploitation

**Systèmes Supportés :**

| OS | Version | Statut |
|----|---------|--------|
| **Ubuntu Server** | 20.04 LTS, 22.04 LTS | ✅ Recommandé |
| **Debian** | 11, 12 | ✅ Supporté |
| **CentOS / RHEL** | 8, 9 | ✅ Supporté |
| **Windows Server** | 2019, 2022 | ⚠️ Supporté (non recommandé) |

**Ce guide utilise Ubuntu Server 22.04 LTS comme référence.**

### 2.3 Logiciels Requis

| Logiciel | Version Minimale | Version Recommandée |
|----------|------------------|---------------------|
| **PHP** | 7.4 | 8.1+ |
| **MySQL** | 8.0 | 8.0.35+ |
| **Apache** | 2.4 | 2.4.55+ |
| **Git** | 2.25+ | Dernière version |

**Extensions PHP Requises :**

```
php-cli
php-fpm
php-mysql (ou php-pdo-mysql)
php-mbstring
php-xml
php-curl
php-zip
php-gd (pour manipulation images)
php-intl
php-bcmath
```

### 2.4 Accès et Permissions

- ✅ Accès root ou sudo sur le serveur
- ✅ Accès SSH (port 22)
- ✅ Accès au dépôt Git (si installation depuis Git)
- ✅ Ports ouverts : 80 (HTTP), 443 (HTTPS), 3306 (MySQL - optionnel)

---

## 3. Installation des Dépendances

### 3.1 Mise à Jour du Système

```bash
# Se connecter en SSH
ssh user@votre-serveur.com

# Passer en root
sudo su -

# Mettre à jour les paquets
apt update && apt upgrade -y
```

### 3.2 Installation d'Apache

```bash
# Installer Apache 2.4
apt install apache2 -y

# Vérifier l'installation
apache2 -v
# Devrait afficher : Server version: Apache/2.4.x

# Activer Apache au démarrage
systemctl enable apache2

# Démarrer Apache
systemctl start apache2

# Vérifier le statut
systemctl status apache2
# Devrait afficher : active (running)
```

**Test :** Ouvrir http://votre-serveur-ip dans un navigateur → Devrait afficher la page par défaut d'Apache.

### 3.3 Installation de MySQL

```bash
# Installer MySQL Server 8.0
apt install mysql-server -y

# Vérifier l'installation
mysql --version
# Devrait afficher : mysql Ver 8.0.x

# Activer MySQL au démarrage
systemctl enable mysql

# Démarrer MySQL
systemctl start mysql

# Vérifier le statut
systemctl status mysql
# Devrait afficher : active (running)
```

#### 3.3.1 Sécurisation de MySQL

```bash
# Lancer le script de sécurisation
mysql_secure_installation

# Répondre aux questions :
# - Set root password? YES → Définir un mot de passe fort (ex: MyS3cur3P@ss!)
# - Remove anonymous users? YES
# - Disallow root login remotely? YES
# - Remove test database? YES
# - Reload privilege tables? YES
```

#### 3.3.2 Test de Connexion MySQL

```bash
# Se connecter à MySQL
mysql -u root -p
# Entrer le mot de passe root défini

# Dans le shell MySQL :
mysql> SELECT VERSION();
# Devrait afficher la version 8.0.x

mysql> SHOW DATABASES;
# Devrait lister les bases par défaut

mysql> EXIT;
```

### 3.4 Installation de PHP

```bash
# Installer PHP 8.1 et extensions
apt install php8.1 php8.1-fpm php8.1-cli php8.1-mysql php8.1-mbstring \
            php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-intl \
            php8.1-bcmath libapache2-mod-php8.1 -y

# Vérifier l'installation
php -v
# Devrait afficher : PHP 8.1.x

# Vérifier les extensions
php -m | grep -E "PDO|mysqli|mbstring|curl|gd"
# Devrait afficher toutes les extensions listées
```

#### 3.4.1 Configuration PHP

```bash
# Éditer le fichier php.ini
nano /etc/php/8.1/apache2/php.ini

# Modifier les paramètres suivants :
```

```ini
# Limites mémoire et upload
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 300

# Timezone
date.timezone = Europe/Paris

# Affichage erreurs (DÉSACTIVER en production)
display_errors = Off
display_startup_errors = Off
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
log_errors = On
error_log = /var/log/php/error.log

# Session
session.gc_maxlifetime = 1800
session.cookie_httponly = 1
session.cookie_secure = 1  # Si HTTPS
```

```bash
# Créer le répertoire des logs PHP
mkdir -p /var/log/php
chown www-data:www-data /var/log/php

# Redémarrer Apache pour appliquer
systemctl restart apache2
```

### 3.5 Installation de Git (Optionnel)

```bash
# Installer Git
apt install git -y

# Vérifier
git --version
# Devrait afficher : git version 2.x.x
```

### 3.6 Vérification Complète des Dépendances

```bash
# Script de vérification
cat > /tmp/check_deps.sh << 'EOF'
#!/bin/bash
echo "=== Vérification des Dépendances ==="
echo ""

# Apache
if command -v apache2 &> /dev/null; then
    echo "✅ Apache: $(apache2 -v | head -n1)"
else
    echo "❌ Apache: NON INSTALLÉ"
fi

# MySQL
if command -v mysql &> /dev/null; then
    echo "✅ MySQL: $(mysql --version)"
else
    echo "❌ MySQL: NON INSTALLÉ"
fi

# PHP
if command -v php &> /dev/null; then
    echo "✅ PHP: $(php -v | head -n1)"
else
    echo "❌ PHP: NON INSTALLÉ"
fi

# Extensions PHP critiques
echo ""
echo "Extensions PHP:"
for ext in pdo_mysql mbstring curl gd; do
    if php -m | grep -q "^$ext$"; then
        echo "  ✅ $ext"
    else
        echo "  ❌ $ext (MANQUANT)"
    fi
done
EOF

chmod +x /tmp/check_deps.sh
/tmp/check_deps.sh
```

**Sortie Attendue :**

```
=== Vérification des Dépendances ===

✅ Apache: Server version: Apache/2.4.55
✅ MySQL: mysql  Ver 8.0.35
✅ PHP: PHP 8.1.27

Extensions PHP:
  ✅ pdo_mysql
  ✅ mbstring
  ✅ curl
  ✅ gd
```

---

## 4. Installation de l'Application

### 4.1 Création du Répertoire d'Installation

```bash
# Créer le répertoire de l'application
mkdir -p /var/www/pgi-automobile

# Définir les permissions
chown -R www-data:www-data /var/www/pgi-automobile
chmod -R 755 /var/www/pgi-automobile
```

### 4.2 Récupération des Fichiers

#### Option A : Depuis Git (Recommandé)

```bash
# Se placer dans le répertoire parent
cd /var/www

# Cloner le dépôt
git clone https://github.com/votre-org/pgi-automobile.git

# Vérifier
ls -la pgi-automobile/
# Devrait afficher : config/, modules/, assets/, sql/, index.php, login.php, etc.
```

#### Option B : Upload Manuel

```bash
# Sur votre machine locale, créer l'archive
cd /chemin/vers/pgi-automobile
tar -czf pgi-automobile.tar.gz .

# Transférer sur le serveur (depuis votre machine locale)
scp pgi-automobile.tar.gz user@votre-serveur:/tmp/

# Sur le serveur, extraire
cd /var/www
tar -xzf /tmp/pgi-automobile.tar.gz -C pgi-automobile/
```

#### Option C : Télécharger la Release

```bash
# Télécharger la dernière release
cd /tmp
wget https://github.com/votre-org/pgi-automobile/releases/download/v1.0.0/pgi-automobile-v1.0.0.tar.gz

# Extraire
cd /var/www
tar -xzf /tmp/pgi-automobile-v1.0.0.tar.gz
mv pgi-automobile-v1.0.0 pgi-automobile
```

### 4.3 Vérification de la Structure

```bash
cd /var/www/pgi-automobile

# Vérifier la structure
tree -L 2 -d
```

**Structure Attendue :**

```
.
├── assets
│   ├── css
│   ├── js
│   └── images
├── config
├── includes
├── modules
│   ├── admin
│   ├── clients
│   ├── commandes
│   ├── employes
│   ├── statistiques
│   ├── stock
│   ├── vehicules
│   └── ventes
└── sql

14 directories
```

### 4.4 Permissions Finales

```bash
# Définir le propriétaire
chown -R www-data:www-data /var/www/pgi-automobile

# Permissions des répertoires
find /var/www/pgi-automobile -type d -exec chmod 755 {} \;

# Permissions des fichiers
find /var/www/pgi-automobile -type f -exec chmod 644 {} \;

# Répertoire uploads (si existant) en écriture
if [ -d "/var/www/pgi-automobile/uploads" ]; then
    chmod -R 775 /var/www/pgi-automobile/uploads
    chown -R www-data:www-data /var/www/pgi-automobile/uploads
fi

# Répertoire logs (si existant)
if [ -d "/var/www/pgi-automobile/logs" ]; then
    chmod -R 775 /var/www/pgi-automobile/logs
    chown -R www-data:www-data /var/www/pgi-automobile/logs
fi
```

---

## 5. Configuration

### 5.1 Configuration de la Base de Données

```bash
# Éditer le fichier de configuration
nano /var/www/pgi-automobile/config/database.php
```

**Modifier les constantes de connexion :**

```php
<?php
class Database {
    // ... (code existant)

    // MODIFIER CES VALEURS :
    private const DB_HOST = 'localhost';        // ou IP du serveur MySQL distant
    private const DB_NAME = 'pgi_automobile';   // Nom de la base
    private const DB_USER = 'pgi_user';         // Utilisateur MySQL dédié
    private const DB_PASS = 'VotreMo7Pass!';    // Mot de passe fort
    private const DB_CHARSET = 'utf8mb4';

    // ... (reste du code)
}
?>
```

**⚠️ IMPORTANT : Ne JAMAIS utiliser 'root' en production !**

### 5.2 Configuration des Sessions

```bash
# Créer le répertoire des sessions
mkdir -p /var/www/pgi-automobile/sessions
chmod 700 /var/www/pgi-automobile/sessions
chown www-data:www-data /var/www/pgi-automobile/sessions
```

**Optionnel : Modifier la configuration des sessions**

```bash
nano /var/www/pgi-automobile/config/session.php
```

```php
<?php
// Configuration des sessions
ini_set('session.save_path', __DIR__ . '/../sessions');
ini_set('session.gc_maxlifetime', 1800); // 30 minutes
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);     // Si HTTPS
ini_set('session.use_strict_mode', 1);

session_name('PGI_SESSION');
?>
```

### 5.3 Configuration des Logs

```bash
# Créer le répertoire des logs
mkdir -p /var/www/pgi-automobile/logs
chmod 775 /var/www/pgi-automobile/logs
chown www-data:www-data /var/www/pgi-automobile/logs

# Créer les fichiers de logs
touch /var/www/pgi-automobile/logs/app.log
touch /var/www/pgi-automobile/logs/error.log
chmod 664 /var/www/pgi-automobile/logs/*.log
chown www-data:www-data /var/www/pgi-automobile/logs/*.log
```

### 5.4 Variables d'Environnement (Optionnel)

Pour une sécurité renforcée, stocker les credentials hors du code :

```bash
# Créer un fichier .env
nano /var/www/pgi-automobile/.env
```

```bash
# Base de données
DB_HOST=localhost
DB_NAME=pgi_automobile
DB_USER=pgi_user
DB_PASS=VotreMo7Pass!

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pgi-automobile.votredomaine.com
```

```bash
# Protéger le fichier
chmod 600 /var/www/pgi-automobile/.env
chown www-data:www-data /var/www/pgi-automobile/.env
```

**Modifier config/database.php pour utiliser .env :**

```php
<?php
// Charger les variables d'environnement
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    define('DB_HOST', $env['DB_HOST']);
    define('DB_NAME', $env['DB_NAME']);
    define('DB_USER', $env['DB_USER']);
    define('DB_PASS', $env['DB_PASS']);
} else {
    // Valeurs par défaut (fallback)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'pgi_automobile');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}
?>
```

---

## 6. Initialisation de la Base de Données

### 6.1 Création de la Base et de l'Utilisateur

```bash
# Se connecter à MySQL en root
mysql -u root -p
```

```sql
-- Créer la base de données
CREATE DATABASE pgi_automobile CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Créer un utilisateur dédié
CREATE USER 'pgi_user'@'localhost' IDENTIFIED BY 'VotreMo7Pass!';

-- Accorder les privilèges
GRANT ALL PRIVILEGES ON pgi_automobile.* TO 'pgi_user'@'localhost';

-- Appliquer les changements
FLUSH PRIVILEGES;

-- Vérifier
SHOW DATABASES;
SELECT user, host FROM mysql.user WHERE user = 'pgi_user';

-- Quitter
EXIT;
```

### 6.2 Import du Schéma de Base de Données

```bash
# Importer le schéma SQL
mysql -u pgi_user -p pgi_automobile < /var/www/pgi-automobile/sql/database.sql

# Vérifier l'import
mysql -u pgi_user -p pgi_automobile -e "SHOW TABLES;"
```

**Sortie Attendue :**

```
+---------------------------+
| Tables_in_pgi_automobile  |
+---------------------------+
| clients                   |
| commandes                 |
| employes                  |
| factures                  |
| logs_connexion            |
| paies                     |
| roles                     |
| utilisateurs              |
| vehicules                 |
| ventes                    |
+---------------------------+
10 rows in set
```

### 6.3 Import des Données Initiales (Optionnel)

Si un fichier de données de démarrage existe :

```bash
# Importer les données initiales
mysql -u pgi_user -p pgi_automobile < /var/www/pgi-automobile/sql/initial_data.sql
```

### 6.4 Création du Compte Super Admin

```bash
# Se connecter à MySQL
mysql -u pgi_user -p pgi_automobile
```

```sql
-- Insérer le rôle Super Admin si inexistant
INSERT INTO roles (nom, permissions) VALUES
('Super Admin', '["*"]');

-- Récupérer l'ID du rôle
SELECT id FROM roles WHERE nom = 'Super Admin';
-- Supposons que l'ID est 1

-- Créer le compte admin
-- Mot de passe : Admin123! (hashé en bcrypt)
INSERT INTO utilisateurs (nom, prenom, email, password, role_id, actif, date_creation)
VALUES (
    'Administrateur',
    'Système',
    'admin@pgi-auto.local',
    '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5GyFTqGjVaFGK',  -- Admin123!
    1,
    1,
    NOW()
);

-- Vérifier
SELECT id, email, nom, prenom FROM utilisateurs WHERE email = 'admin@pgi-auto.local';

EXIT;
```

**⚠️ IMPORTANT : Changer ce mot de passe immédiatement après la première connexion !**

### 6.5 Vérification de la Base de Données

```bash
# Script de vérification
cat > /tmp/check_db.sh << 'EOF'
#!/bin/bash
DB_USER="pgi_user"
DB_PASS="VotreMo7Pass!"
DB_NAME="pgi_automobile"

echo "=== Vérification Base de Données ==="
echo ""

# Nombre de tables
TABLES=$(mysql -u $DB_USER -p$DB_PASS $DB_NAME -se "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_NAME';")
echo "✅ Nombre de tables : $TABLES (attendu: 10)"

# Utilisateurs
USERS=$(mysql -u $DB_USER -p$DB_PASS $DB_NAME -se "SELECT COUNT(*) FROM utilisateurs;")
echo "✅ Utilisateurs créés : $USERS"

# Rôles
ROLES=$(mysql -u $DB_USER -p$DB_PASS $DB_NAME -se "SELECT COUNT(*) FROM roles;")
echo "✅ Rôles créés : $ROLES"

echo ""
echo "Base de données initialisée avec succès !"
EOF

chmod +x /tmp/check_db.sh
/tmp/check_db.sh
```

---

## 7. Configuration du Serveur Web

### 7.1 Création du Virtual Host Apache

```bash
# Créer le fichier de configuration
nano /etc/apache2/sites-available/pgi-automobile.conf
```

```apache
<VirtualHost *:80>
    ServerName pgi-automobile.votredomaine.com
    ServerAlias www.pgi-automobile.votredomaine.com
    ServerAdmin admin@votredomaine.com

    DocumentRoot /var/www/pgi-automobile

    <Directory /var/www/pgi-automobile>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted

        # Protection des fichiers sensibles
        <FilesMatch "^\.">
            Require all denied
        </FilesMatch>

        <FilesMatch "\.(sql|bak|conf|log)$">
            Require all denied
        </FilesMatch>
    </Directory>

    # Logs
    ErrorLog ${APACHE_LOG_DIR}/pgi-automobile-error.log
    CustomLog ${APACHE_LOG_DIR}/pgi-automobile-access.log combined

    # Headers de sécurité
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"

    # Si HTTPS (à ajouter après configuration SSL)
    # Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
</VirtualHost>
```

### 7.2 Configuration HTTPS avec Let's Encrypt (Recommandé)

```bash
# Installer Certbot
apt install certbot python3-certbot-apache -y

# Obtenir un certificat SSL
certbot --apache -d pgi-automobile.votredomaine.com -d www.pgi-automobile.votredomaine.com

# Répondre aux questions :
# - Email : votre-email@votredomaine.com
# - Accepter les termes : Yes
# - Partager email : No (optionnel)
# - Redirect HTTP to HTTPS? : 2 (Yes, redirect)

# Certbot créera automatiquement la config HTTPS dans :
# /etc/apache2/sites-available/pgi-automobile-le-ssl.conf
```

**Configuration HTTPS Générée :**

```apache
<VirtualHost *:443>
    ServerName pgi-automobile.votredomaine.com
    ServerAlias www.pgi-automobile.votredomaine.com

    DocumentRoot /var/www/pgi-automobile

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/pgi-automobile.votredomaine.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/pgi-automobile.votredomaine.com/privkey.pem
    Include /etc/letsencrypt/options-ssl-apache.conf

    # ... (reste de la config)
</VirtualHost>
```

### 7.3 Activation du Site et Modules

```bash
# Activer les modules Apache nécessaires
a2enmod rewrite
a2enmod headers
a2enmod ssl

# Désactiver le site par défaut
a2dissite 000-default.conf

# Activer le site PGI Automobile
a2ensite pgi-automobile.conf

# Tester la configuration
apache2ctl configtest
# Devrait afficher : Syntax OK

# Redémarrer Apache
systemctl restart apache2

# Vérifier le statut
systemctl status apache2
```

### 7.4 Configuration .htaccess (Protection Supplémentaire)

```bash
# Créer le fichier .htaccess à la racine
nano /var/www/pgi-automobile/.htaccess
```

```apache
# Protection des fichiers sensibles
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# Bloquer l'accès aux fichiers SQL, logs, etc.
<FilesMatch "\.(sql|bak|conf|log|env)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Redirection HTTPS (si SSL activé)
# RewriteEngine On
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Protection contre l'injection de scripts
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Désactiver l'affichage de la version PHP
ServerSignature Off
```

### 7.5 Renouvellement Automatique SSL

```bash
# Tester le renouvellement
certbot renew --dry-run

# Le renouvellement automatique est déjà configuré via systemd timer
systemctl list-timers | grep certbot
# Devrait afficher : certbot.timer
```

---

## 8. Vérification de l'Installation

### 8.1 Test de Connectivité

```bash
# Test local
curl -I http://localhost
# Devrait retourner : HTTP/1.1 302 Found (redirection vers login)

# Test avec domaine
curl -I https://pgi-automobile.votredomaine.com
# Devrait retourner : HTTP/1.1 200 OK ou 302 Found
```

### 8.2 Test via Navigateur

**Étapes :**

1. Ouvrir un navigateur
2. Aller sur : `https://pgi-automobile.votredomaine.com`
3. Vérifier :
   - ✅ Certificat SSL valide (cadenas vert)
   - ✅ Page de login s'affiche
   - ✅ CSS et images chargés correctement
   - ✅ Pas d'erreurs JavaScript (F12 → Console)

### 8.3 Test de Connexion

**Utiliser le compte Super Admin créé :**

```
Email    : admin@pgi-auto.local
Password : Admin123!
```

**Vérifications :**

- ✅ Connexion réussie
- ✅ Redirection vers le dashboard
- ✅ Menu de navigation visible avec tous les modules
- ✅ Nom et rôle affichés en haut à droite
- ✅ Aucune erreur PHP dans les logs

### 8.4 Vérification des Logs

```bash
# Logs Apache (erreurs)
tail -f /var/log/apache2/pgi-automobile-error.log

# Logs Apache (accès)
tail -f /var/log/apache2/pgi-automobile-access.log

# Logs PHP
tail -f /var/log/php/error.log

# Logs application (si configurés)
tail -f /var/www/pgi-automobile/logs/app.log
```

**Sortie Normale : Aucune erreur critique**

### 8.5 Test des Fonctionnalités Critiques

| Module | Test | Résultat Attendu |
|--------|------|------------------|
| **Authentification** | Se connecter / Se déconnecter | ✅ Fonctionne |
| **Dashboard** | Afficher le tableau de bord | ✅ KPIs visibles |
| **Véhicules** | Accéder à la liste | ✅ Liste affichée (vide si aucune donnée) |
| **Ventes** | Accéder au module | ✅ Accessible |
| **Admin** | Accéder aux utilisateurs | ✅ Liste visible |

### 8.6 Script de Vérification Automatique

```bash
cat > /tmp/check_install.sh << 'EOF'
#!/bin/bash
URL="https://pgi-automobile.votredomaine.com"

echo "=== Vérification Installation PGI Automobile ==="
echo ""

# Test HTTP
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" $URL/login.php)
if [ "$HTTP_CODE" == "200" ]; then
    echo "✅ Page login accessible (HTTP $HTTP_CODE)"
else
    echo "❌ Page login inaccessible (HTTP $HTTP_CODE)"
fi

# Test SSL
SSL_EXPIRY=$(echo | openssl s_client -servername pgi-automobile.votredomaine.com -connect pgi-automobile.votredomaine.com:443 2>/dev/null | openssl x509 -noout -enddate | cut -d= -f2)
if [ ! -z "$SSL_EXPIRY" ]; then
    echo "✅ Certificat SSL valide (expire le: $SSL_EXPIRY)"
else
    echo "⚠️  Certificat SSL non trouvé ou invalide"
fi

# Test Base de données
DB_CONN=$(mysql -u pgi_user -pVotreMo7Pass! pgi_automobile -e "SELECT 1" 2>&1)
if [[ $DB_CONN == *"ERROR"* ]]; then
    echo "❌ Connexion base de données échouée"
else
    echo "✅ Connexion base de données OK"
fi

# Test Permissions
PERMS=$(stat -c "%a" /var/www/pgi-automobile)
if [ "$PERMS" == "755" ]; then
    echo "✅ Permissions répertoire OK (755)"
else
    echo "⚠️  Permissions répertoire : $PERMS (attendu: 755)"
fi

echo ""
echo "Installation vérifiée !"
EOF

chmod +x /tmp/check_install.sh
/tmp/check_install.sh
```

---

## 9. Sécurisation

### 9.1 Désactiver l'Affichage des Erreurs PHP

```bash
# Éditer php.ini
nano /etc/php/8.1/apache2/php.ini

# S'assurer que :
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php/error.log
```

### 9.2 Configurer le Firewall

```bash
# Installer UFW (si pas déjà installé)
apt install ufw -y

# Autoriser SSH (IMPORTANT !)
ufw allow 22/tcp

# Autoriser HTTP et HTTPS
ufw allow 80/tcp
ufw allow 443/tcp

# Activer le firewall
ufw enable

# Vérifier le statut
ufw status
```

**Sortie Attendue :**

```
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     ALLOW       Anywhere
80/tcp                     ALLOW       Anywhere
443/tcp                    ALLOW       Anywhere
```

### 9.3 Sécuriser MySQL

```bash
# Se connecter à MySQL
mysql -u root -p
```

```sql
-- Supprimer les utilisateurs anonymes
DELETE FROM mysql.user WHERE User='';

-- Supprimer la base de test
DROP DATABASE IF EXISTS test;

-- Restreindre l'accès root à localhost uniquement
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');

-- Appliquer
FLUSH PRIVILEGES;

EXIT;
```

### 9.4 Limiter les Tentatives de Connexion (Fail2Ban)

```bash
# Installer Fail2Ban
apt install fail2ban -y

# Créer une règle pour Apache
nano /etc/fail2ban/jail.local
```

```ini
[apache-auth]
enabled = true
port = http,https
filter = apache-auth
logpath = /var/log/apache2/pgi-automobile-error.log
maxretry = 5
bantime = 3600
findtime = 600
```

```bash
# Redémarrer Fail2Ban
systemctl restart fail2ban

# Vérifier le statut
fail2ban-client status
```

### 9.5 Backups Automatiques

```bash
# Créer le script de backup
nano /usr/local/bin/backup_pgi.sh
```

```bash
#!/bin/bash
# Script de backup PGI Automobile

BACKUP_DIR="/var/backups/pgi-automobile"
DATE=$(date +%Y%m%d_%H%M%S)
DB_USER="pgi_user"
DB_PASS="VotreMo7Pass!"
DB_NAME="pgi_automobile"

# Créer le répertoire si inexistant
mkdir -p $BACKUP_DIR

# Backup base de données
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Backup fichiers application
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/pgi-automobile --exclude='/var/www/pgi-automobile/logs'

# Supprimer les backups > 7 jours
find $BACKUP_DIR -name "*.gz" -mtime +7 -delete

echo "Backup terminé : $DATE"
```

```bash
# Rendre exécutable
chmod +x /usr/local/bin/backup_pgi.sh

# Tester
/usr/local/bin/backup_pgi.sh

# Ajouter au cron (tous les jours à 2h du matin)
crontab -e
```

```cron
0 2 * * * /usr/local/bin/backup_pgi.sh >> /var/log/pgi-backup.log 2>&1
```

### 9.6 Monitoring avec Monit (Optionnel)

```bash
# Installer Monit
apt install monit -y

# Configurer
nano /etc/monit/monitrc
```

```
# Monitoring Apache
check process apache2 with pidfile /var/run/apache2/apache2.pid
    start program = "/usr/sbin/service apache2 start"
    stop program  = "/usr/sbin/service apache2 stop"
    if failed host localhost port 80 protocol http then restart
    if 5 restarts within 5 cycles then timeout

# Monitoring MySQL
check process mysql with pidfile /var/run/mysqld/mysqld.pid
    start program = "/usr/sbin/service mysql start"
    stop program  = "/usr/sbin/service mysql stop"
    if failed host localhost port 3306 then restart
    if 5 restarts within 5 cycles then timeout

# Monitoring espace disque
check filesystem rootfs with path /
    if space usage > 85% then alert
```

```bash
# Redémarrer Monit
systemctl restart monit

# Vérifier
monit status
```

---

## 10. Dépannage

### 10.1 Problèmes Courants

#### Erreur : "500 Internal Server Error"

**Causes Possibles :**

1. **Erreur PHP** → Vérifier les logs :
   ```bash
   tail -f /var/log/apache2/pgi-automobile-error.log
   tail -f /var/log/php/error.log
   ```

2. **Permissions incorrectes** → Corriger :
   ```bash
   chown -R www-data:www-data /var/www/pgi-automobile
   chmod -R 755 /var/www/pgi-automobile
   ```

3. **Fichier .htaccess** → Désactiver temporairement :
   ```bash
   mv /var/www/pgi-automobile/.htaccess /var/www/pgi-automobile/.htaccess.bak
   ```

#### Erreur : "Could not connect to database"

**Solution :**

```bash
# Vérifier que MySQL fonctionne
systemctl status mysql

# Tester la connexion
mysql -u pgi_user -p pgi_automobile

# Vérifier les credentials dans config/database.php
nano /var/www/pgi-automobile/config/database.php
```

#### Erreur : "Page not found" ou "404"

**Solution :**

```bash
# Vérifier le DocumentRoot dans Apache
grep DocumentRoot /etc/apache2/sites-available/pgi-automobile.conf

# Vérifier que mod_rewrite est activé
a2enmod rewrite
systemctl restart apache2
```

#### Erreur : CSS/JS ne se chargent pas

**Solution :**

```bash
# Vérifier les permissions
ls -la /var/www/pgi-automobile/assets/

# Corriger si nécessaire
chmod -R 755 /var/www/pgi-automobile/assets/
```

### 10.2 Commandes de Diagnostic

```bash
# Vérifier les processus en cours
ps aux | grep -E "apache2|mysql"

# Vérifier les ports en écoute
netstat -tlnp | grep -E ":80|:443|:3306"

# Vérifier l'espace disque
df -h

# Vérifier la mémoire
free -h

# Logs en temps réel
tail -f /var/log/apache2/pgi-automobile-error.log
tail -f /var/log/mysql/error.log
```

### 10.3 Mode Debug Temporaire

**⚠️ UNIQUEMENT pour diagnostiquer, à désactiver immédiatement après !**

```bash
# Activer l'affichage des erreurs
nano /etc/php/8.1/apache2/php.ini
```

```ini
display_errors = On
error_reporting = E_ALL
```

```bash
# Redémarrer Apache
systemctl restart apache2

# NE PAS OUBLIER DE DÉSACTIVER APRÈS DIAGNOSTIC !
```

### 10.4 Restauration depuis Backup

```bash
# Restaurer la base de données
gunzip < /var/backups/pgi-automobile/db_20251217_020000.sql.gz | mysql -u pgi_user -p pgi_automobile

# Restaurer les fichiers
cd /var/www
tar -xzf /var/backups/pgi-automobile/files_20251217_020000.tar.gz

# Corriger les permissions
chown -R www-data:www-data /var/www/pgi-automobile
```

---

## Conclusion

L'installation du système PGI Automobile est maintenant terminée. Le système est opérationnel et sécurisé.

### Checklist Finale

- ✅ Apache, MySQL, PHP installés et configurés
- ✅ Application déployée dans `/var/www/pgi-automobile`
- ✅ Base de données créée et initialisée
- ✅ Virtual Host Apache configuré
- ✅ SSL/HTTPS activé (Let's Encrypt)
- ✅ Compte Super Admin créé
- ✅ Permissions correctes
- ✅ Firewall configuré
- ✅ Backups automatiques planifiés
- ✅ Tests de connexion réussis

### Prochaines Étapes

1. **Former les utilisateurs** → Voir Manuel Utilisateur (Phase 6)
2. **Créer les comptes utilisateurs** → Voir Guide Administration
3. **Importer les données** → Véhicules, clients, employés
4. **Configurer les sauvegardes externes** → S3, FTP, etc.
5. **Planifier la maintenance** → Mises à jour, monitoring

### Support

- **Documentation** : `/docs/livrables/`
- **Logs** : `/var/log/apache2/`, `/var/www/pgi-automobile/logs/`
- **Contact Support** : support@votredomaine.com

---

**Document Version :** 1.0
**Dernière mise à jour :** 17/11/2025
**Auteur :** Équipe DevOps PGI Automobile
