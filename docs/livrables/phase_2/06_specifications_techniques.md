# Spécifications Techniques - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile
**Version:** 1.0 (Projet Académique)
**Date:** Novembre 2025
**Auteurs:** Thibaud THOMAS-LAMOTTE & Melissa BENZIDANE
**Contexte:** Projet de L3 - Période du 27/10/2025 au 17/11/2025 (3 semaines)
**Statut:** Validé

---

## 1. Introduction

### 1.1 Objet du Document

Ce document décrit l'architecture technique complète du PGI Automobile, les choix technologiques, la structure de la base de données, les mécanismes de sécurité et les aspects de performance.

### 1.2 Public Cible

- Équipe de développement
- Administrateurs système
- Architectes techniques
- Auditeurs sécurité

### 1.3 Références

- Spécifications Fonctionnelles Générales (SFG) v1.0
- Spécifications Fonctionnelles Détaillées (SFD) v1.0
- Cahier des charges v1.0

---

## 2. Architecture Générale

### 2.1 Architecture Applicative

Le PGI Automobile suit une architecture **3-tiers simplifiée** :

```
┌─────────────────────────────────────────────────────────┐
│                   COUCHE PRÉSENTATION                    │
│                                                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │   Desktop    │  │   Tablette   │  │   Mobile     │  │
│  │  (1920px)    │  │   (768px)    │  │   (375px)    │  │
│  └──────────────┘  └──────────────┘  └──────────────┘  │
│           Navigateurs Web (Chrome, Firefox, Safari)      │
│                      HTML5 + CSS3 + JavaScript           │
└─────────────────────────────────────────────────────────┘
                            ↓ HTTP/HTTPS
┌─────────────────────────────────────────────────────────┐
│                   COUCHE APPLICATIVE                     │
│                                                          │
│  Serveur Web Apache 2.4 + PHP 7.4+                      │
│                                                          │
│  ┌──────────────────────────────────────────────────┐   │
│  │           Modules Métier (PHP)                   │   │
│  │  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐    │   │
│  │  │Véhicul.│ │ Ventes │ │   RH   │ │  Admin │    │   │
│  │  └────────┘ └────────┘ └────────┘ └────────┘    │   │
│  │                    ...                           │   │
│  └──────────────────────────────────────────────────┘   │
│                                                          │
│  ┌──────────────────────────────────────────────────┐   │
│  │        Composants Transverses                    │   │
│  │  - Authentification (config/auth.php)            │   │
│  │  - Connexion BDD (config/database.php)           │   │
│  │  - Fonctions utilitaires (includes/functions.php)│   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
                            ↓ PDO
┌─────────────────────────────────────────────────────────┐
│                    COUCHE DONNÉES                        │
│                                                          │
│             MySQL 8.0 (SGBDR)                            │
│                                                          │
│  ┌──────────────────────────────────────────────────┐   │
│  │  Base de données : pgi_automobile                │   │
│  │  - 10 tables relationnelles                      │   │
│  │  - Contraintes d'intégrité (FK, UNIQUE)          │   │
│  │  - Indexes pour performances                     │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Pattern Architectural

**MVC Simplifié (sans framework)**

| Composant | Responsabilité | Localisation |
|-----------|----------------|--------------|
| **Modèle (M)** | - Accès données (SQL)<br/>- Logique métier<br/>- Requêtes PDO | Fichiers PHP modules (inline) |
| **Vue (V)** | - Affichage HTML<br/>- Templates PHP<br/>- Includes (header, footer) | Fichiers `.php` avec HTML |
| **Contrôleur (C)** | - Traitement formulaires<br/>- Routage<br/>- Appel modèle + vue | Même fichier que Vue (couplé) |

**Exemple de Fichier MVC Simplifié** (`modules/vehicules/liste.php`)

```php
<?php
// === CONTRÔLEUR ===
require_once '../../config/database.php';
require_once '../../config/auth.php';

requireAuth();
requirePermission('vehicules', 'read');

// Traitement filtres (GET)
$type = $_GET['type'] ?? null;
$carburant = $_GET['carburant'] ?? null;

// === MODÈLE ===
$sql = "SELECT * FROM vehicules WHERE 1=1";
$params = [];

if ($type) {
    $sql .= " AND type = ?";
    $params[] = $type;
}
if ($carburant) {
    $sql .= " AND carburant = ?";
    $params[] = $carburant;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehicules = $stmt->fetchAll();

// === VUE ===
include '../../includes/header.php';
?>
<h1>Liste des Véhicules</h1>
<table>
    <?php foreach ($vehicules as $v): ?>
        <tr>
            <td><?= escape($v['marque']) ?></td>
            ...
        </tr>
    <?php endforeach; ?>
</table>
<?php include '../../includes/footer.php'; ?>
```

### 2.3 Structure des Répertoires

```
/home/user/PGI-Automobile/
│
├── config/                        # Configuration système
│   ├── database.php              # Connexion PDO + fonctions utilitaires
│   ├── auth.php                  # Authentification + permissions (RBAC)
│   └── config.example.php        # Template configuration
│
├── includes/                      # Composants réutilisables
│   ├── header.php                # En-tête (navigation, menu dynamique)
│   ├── header-client.php         # En-tête clients
│   ├── footer.php                # Pied de page
│   └── functions.php             # Fonctions utilitaires globales
│
├── modules/                       # Modules métier (8 domaines)
│   ├── admin/                    # Administration (utilisateurs, permissions, logs)
│   ├── clients/                  # Gestion clients
│   ├── profil/                   # Profil utilisateur
│   ├── rh/                       # Ressources Humaines (personnel, congés, paie)
│   ├── statistiques/             # Tableaux de bord et KPI
│   ├── stock/                    # Inventaire et alertes
│   ├── vehicules/                # CRUD véhicules
│   └── ventes/                   # Ventes et demandes d'achat
│
├── assets/                        # Ressources statiques
│   ├── css/                      # Feuilles de style (8 fichiers)
│   ├── js/                       # Scripts JavaScript (3 fichiers)
│   └── images/                   # Images (logo, véhicules)
│
├── sql/                           # Scripts base de données
│   └── database.sql              # Schéma complet + données test
│
├── docs/                          # Documentation
│   ├── changelog.md
│   └── livrables/                # Livrables projet (cette documentation)
│
├── Pages publiques (racine)
│   ├── index.php                 # Redirection intelligente selon rôle
│   ├── accueil.php               # Page d'accueil publique
│   ├── catalogue.php             # Catalogue véhicules (public + client)
│   ├── demande.php               # Formulaire demande d'achat
│   ├── login.php                 # Connexion
│   ├── logout.php                # Déconnexion
│   ├── client-inscription.php    # Inscription client
│   └── acces-refuse.php          # Page erreur 403
│
└── Fichiers configuration
    ├── .env.example              # Variables environnement
    ├── .gitignore                # Fichiers exclus Git
    └── README.md                 # Documentation complète (977 lignes)
```

---

## 3. Technologies Utilisées

### 3.1 Stack Technique

| Couche | Technologie | Version | Justification |
|--------|-------------|---------|---------------|
| **Langage Backend** | PHP | 7.4+ (compatible 8.x) | Maturité, hébergement mutualisé compatible, maîtrise équipe |
| **Base de Données** | MySQL | 8.0 (compatible 5.7+) | Robustesse, transactions ACID, compatibilité |
| **Serveur Web** | Apache | 2.4 | Standard industrie, .htaccess supporté, modules mod_rewrite |
| **Frontend** | HTML5 | - | Sémantique moderne, accessibilité |
| | CSS3 | - | Variables CSS, Flexbox, Grid, responsive |
| | JavaScript | ES6+ (vanilla) | Interactions, validations côté client, pas de dépendance framework |
| **Accès BDD** | PDO | Natif PHP | Requêtes préparées (sécurité), abstraction SGBD |
| **Hachage Mots de Passe** | bcrypt | Natif PHP | `password_hash()`, résistant aux attaques bruteforce |

### 3.2 Versions Minimales Requises

| Composant | Version Minimum | Version Recommandée |
|-----------|----------------|---------------------|
| PHP | 7.4.0 | 8.1+ |
| MySQL | 5.7.0 | 8.0+ |
| Apache | 2.4.0 | 2.4.54+ |
| OpenSSL | 1.1.0 | 1.1.1+ (TLS 1.3) |

### 3.3 Extensions PHP Requises

```ini
; Extensions obligatoires
extension=pdo_mysql    # Accès MySQL via PDO
extension=mysqli       # Fallback (non utilisé mais souvent requis par hébergeurs)
extension=session      # Gestion sessions utilisateur
extension=mbstring     # Manipulation chaînes multi-bytes (UTF-8)
extension=json         # Encodage/décodage JSON (APIs futures)

; Extensions optionnelles
extension=gd           # Manipulation images (resize, thumbnails)
extension=curl         # Requêtes HTTP (APIs externes - futur)
extension=intl         # Internationalisation (formatage dates/prix)
```

### 3.4 Dépendances (Absentes Volontairement)

**Pas de gestionnaire de dépendances** (Composer, npm)

Justification :
- ✅ Simplicité déploiement (pas de `composer install`)
- ✅ Pas de dépendances tierces à maintenir
- ✅ Compatible hébergements mutualisés restreints
- ⚠️ Développement from scratch (pas de helpers)

**Bibliothèques externes futures envisagées** :
- PHPMailer (emails transactionnels)
- TCPDF/DomPDF (génération PDF factures)
- Chart.js (graphiques statistiques - déjà vanilla JS)

---

## 4. Base de Données

### 4.1 Schéma Relationnel (MCD - Modèle Conceptuel de Données)

```
┌─────────────┐
│ utilisateurs│
├─────────────┤
│ id PK       │
│ email UK    │
│ password    │
│ role        │
│ ...         │
└─────────────┘
       │
       │ 1:N (traitee_par)
       ↓
┌──────────────────┐      1:N      ┌─────────────┐
│ demandes_achat   │◄──────────────│  vehicules  │
├──────────────────┤               ├─────────────┤
│ id PK            │               │ id PK       │
│ vehicule_id FK   │               │ immatric. UK│
│ client_id FK     │               │ marque      │
│ nom, prenom      │               │ modele      │
│ statut           │               │ prix_achat  │
│ ...              │               │ prix_vente  │
└──────────────────┘               │ statut      │
       ↑                           │ ...         │
       │ N:1                       └─────────────┘
       │                                  │
┌─────────────┐                          │ N:1
│   clients   │                          ↓
├─────────────┤                   ┌─────────────┐
│ id PK       │                   │   ventes    │
│ email UK    │                   ├─────────────┤
│ nom, prenom │◄─────────────────│ id PK       │
│ ...         │  N:1 (client_id) │ vehicule_id FK
└─────────────┘                   │ client_id FK│
                                  │ prix_vente  │
                                  │ marge       │
┌─────────────┐                   │ ...         │
│  personnel  │                   └─────────────┘
├─────────────┤
│ id PK       │
│ nom, prenom │
│ salaire     │
│ ...         │
└─────────────┘
       │
       │ 1:N
       ├──────────────┬──────────────┐
       ↓              ↓              ↓
┌──────────┐   ┌─────────────┐  ┌───────────────┐
│  conges  │   │bulletins_paie│  │ fournisseurs │
├──────────┤   ├──────────────┤  ├───────────────┤
│ id PK    │   │ id PK        │  │ id PK         │
│ pers.FK  │   │ pers. FK     │  │ nom_entreprise│
│ type     │   │ mois_ref     │  │ ...           │
│ statut   │   │ net_a_payer  │  └───────────────┘
│ ...      │   │ ...          │
└──────────┘   └──────────────┘
```

### 4.2 Tables Détaillées

#### Table 1 : `vehicules`

```sql
CREATE TABLE vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT NOT NULL,
    couleur VARCHAR(30),
    prix_achat DECIMAL(10,2) NOT NULL,
    prix_vente DECIMAL(10,2) NOT NULL,
    kilometrage INT NOT NULL DEFAULT 0,
    type_vehicule ENUM('berline', 'suv', 'sportive', 'utilitaire', 'citadine') NOT NULL,
    carburant ENUM('essence', 'diesel', 'electrique', 'hybride') NOT NULL,
    statut ENUM('stock', 'vendu', 'reserve') NOT NULL DEFAULT 'stock',
    date_arrivee DATE NOT NULL,
    immatriculation VARCHAR(20) NOT NULL UNIQUE,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_statut (statut),
    INDEX idx_type (type_vehicule),
    INDEX idx_marque (marque),
    INDEX idx_date_arrivee (date_arrivee)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Contraintes** :
- PK : `id` (auto-incrémenté)
- UNIQUE : `immatriculation`
- INDEXES : Sur colonnes fréquemment filtrées (statut, type, marque, date_arrivee)

#### Table 2 : `clients`

```sql
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    ville VARCHAR(50),
    code_postal VARCHAR(10),
    date_naissance DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Contraintes** :
- PK : `id`
- UNIQUE : `email`

#### Table 3 : `ventes`

```sql
CREATE TABLE ventes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicule_id INT NOT NULL,
    client_id INT NOT NULL,
    prix_vente DECIMAL(10,2) NOT NULL,
    mode_paiement ENUM('comptant', 'credit', 'leasing') NOT NULL,
    date_vente DATE NOT NULL,
    marge DECIMAL(10,2) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE RESTRICT,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE RESTRICT,

    INDEX idx_date_vente (date_vente),
    INDEX idx_client (client_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Contraintes** :
- PK : `id`
- FK : `vehicule_id` → `vehicules(id)` (ON DELETE RESTRICT - pas de suppression si vente existe)
- FK : `client_id` → `clients(id)` (ON DELETE RESTRICT)

#### Table 4 : `demandes_achat`

```sql
CREATE TABLE demandes_achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicule_id INT NOT NULL,
    client_id INT NULL,  -- NULL si demande guest (non inscrit)
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    message TEXT,
    statut ENUM('en_attente', 'en_cours', 'acceptee', 'refusee', 'finalisee')
        NOT NULL DEFAULT 'en_attente',
    notes_gestionnaire TEXT,
    traitee_par INT,  -- ID utilisateur (vendeur)
    date_traitement DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    FOREIGN KEY (traitee_par) REFERENCES utilisateurs(id) ON DELETE SET NULL,

    INDEX idx_statut (statut),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 5 : `personnel`

```sql
CREATE TABLE personnel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    poste VARCHAR(100) NOT NULL,
    salaire DECIMAL(10,2) NOT NULL,
    email VARCHAR(100),
    telephone VARCHAR(20),
    date_embauche DATE NOT NULL,
    statut ENUM('actif', 'conge', 'inactif') NOT NULL DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 6 : `conges`

```sql
CREATE TABLE conges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    personnel_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,  -- CP, RTT, Maladie, etc.
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('en_attente', 'approuve', 'refuse') NOT NULL DEFAULT 'en_attente',
    commentaire TEXT,
    commentaire_gestion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (personnel_id) REFERENCES personnel(id) ON DELETE CASCADE,

    INDEX idx_statut (statut),
    INDEX idx_dates (date_debut, date_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 7 : `bulletins_paie`

```sql
CREATE TABLE bulletins_paie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    personnel_id INT NOT NULL,
    mois_reference DATE NOT NULL,
    salaire_base DECIMAL(10,2) NOT NULL,
    prime DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    net_a_payer DECIMAL(10,2) NOT NULL,
    statut ENUM('brouillon', 'valide') NOT NULL DEFAULT 'brouillon',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (personnel_id) REFERENCES personnel(id) ON DELETE CASCADE,
    UNIQUE KEY unique_personnel_mois (personnel_id, mois_reference),

    INDEX idx_mois (mois_reference),
    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Contrainte unique** : Un seul bulletin par employé par mois

#### Table 8 : `utilisateurs`

```sql
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Hash bcrypt
    role ENUM('admin', 'vendeur', 'gestionnaire_stock', 'comptable', 'rh', 'client')
        NOT NULL DEFAULT 'client',
    statut ENUM('actif', 'inactif', 'suspendu') NOT NULL DEFAULT 'actif',
    avatar VARCHAR(255),
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    ville VARCHAR(50),
    code_postal VARCHAR(10),
    derniere_connexion DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 9 : `logs_connexion`

```sql
CREATE TABLE logs_connexion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    action ENUM('connexion', 'deconnexion', 'tentative_echec') NOT NULL,
    ip_address VARCHAR(45),  -- IPv6 compatible
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,

    INDEX idx_user (utilisateur_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table 10 : `permissions`

```sql
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(50) NOT NULL,
    module VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,  -- create, read, update, delete
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_permission (role, module, action),

    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 4.3 Encodage et Collation

**Standard UTF-8 Complet**

```sql
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

**Justification** :
- `utf8mb4` : Support complet Unicode (emojis, caractères spéciaux)
- `utf8mb4_unicode_ci` : Comparaisons insensibles à la casse (case-insensitive)

**Configuration MySQL Recommandée** (`my.cnf`) :

```ini
[mysqld]
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

[client]
default-character-set = utf8mb4
```

### 4.4 Moteur de Stockage

**InnoDB** (tous les tables)

**Avantages** :
- Transactions ACID (atomicité, cohérence, isolation, durabilité)
- Support clés étrangères (contraintes d'intégrité)
- Row-level locking (concurrence optimisée)
- Crash recovery (récupération après panne)

---

## 5. Sécurité

### 5.1 Authentification

#### Mécanisme de Connexion

**Fichier** : `login.php`

```php
<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Requête utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND statut = 'actif'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Log connexion
        $stmt = $pdo->prepare("INSERT INTO logs_connexion
            (utilisateur_id, action, ip_address, user_agent) VALUES (?, 'connexion', ?, ?)");
        $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);

        // Mise à jour dernière connexion
        $stmt = $pdo->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = ?");
        $stmt->execute([$user['id']]);

        // Redirection selon rôle
        header('Location: dashboard.php');
        exit;
    } else {
        // Échec authentification
        $error = "Email ou mot de passe incorrect.";

        // Log tentative échouée
        if ($user) {
            $stmt = $pdo->prepare("INSERT INTO logs_connexion
                (utilisateur_id, action, ip_address, user_agent) VALUES (?, 'tentative_echec', ?, ?)");
            $stmt->execute([$user['id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);
        }
    }
}
?>
```

**Sécurité** :
- ✅ Mot de passe vérifié avec `password_verify()` (hash bcrypt)
- ✅ Utilisateur inactif bloqué (`statut = 'actif'`)
- ✅ Logs de connexion (succès et échecs)
- ✅ Sessions PHP (stockage côté serveur)

#### Hash Mots de Passe

**Algorithme** : bcrypt (via `PASSWORD_BCRYPT`)

**Création utilisateur** :

```php
$password_plain = 'MonMotDePasse123!';
$password_hash = password_hash($password_plain, PASSWORD_BCRYPT);
// Résultat : $2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy

$stmt = $pdo->prepare("INSERT INTO utilisateurs (email, password) VALUES (?, ?)");
$stmt->execute([$email, $password_hash]);
```

**Propriétés bcrypt** :
- Coût calculatoire élevé (résistant bruteforce)
- Salt automatique (aléatoire par utilisateur)
- Hash de 60 caractères

### 5.2 Autorisation (RBAC)

#### Système de Permissions

**Fichier** : `config/auth.php`

**Fonction de Vérification** :

```php
function hasPermission($module, $action) {
    global $pdo;

    if (!isset($_SESSION['role'])) {
        return false;
    }

    $role = $_SESSION['role'];

    // Admin a tous les droits (wildcard)
    if ($role === 'admin') {
        return true;
    }

    // Vérification en base de données
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM permissions
        WHERE role = ? AND module = ? AND action = ?");
    $stmt->execute([$role, $module, $action]);

    return $stmt->fetchColumn() > 0;
}

function requirePermission($module, $action) {
    if (!hasPermission($module, $action)) {
        header('Location: /acces-refuse.php');
        exit;
    }
}
```

**Utilisation dans les Modules** :

```php
<?php
// modules/vehicules/ajouter.php
require_once '../../config/auth.php';

requireAuth();
requirePermission('vehicules', 'create');

// Suite du code...
?>
```

#### Matrice Permissions par Défaut

**Table `permissions` pré-remplie** (si vide, fallback sur array PHP)

```php
const DEFAULT_ROLE_PERMISSIONS = [
    'admin' => ['*'], // Wildcard - tous les droits

    'vendeur' => [
        'vehicules:read',
        'clients:create', 'clients:read', 'clients:update', 'clients:delete',
        'ventes:create', 'ventes:read', 'ventes:update', 'ventes:delete',
        'demandes:read', 'demandes:update',
        'statistiques:read'
    ],

    'gestionnaire_stock' => [
        'vehicules:create', 'vehicules:read', 'vehicules:update', 'vehicules:delete',
        'stock:read', 'stock:update'
    ],

    'comptable' => [
        'ventes:read',
        'statistiques:read'
    ],

    'rh' => [
        'rh:create', 'rh:read', 'rh:update', 'rh:delete',
        'conges:create', 'conges:read', 'conges:update', 'conges:delete',
        'paie:create', 'paie:read', 'paie:update', 'paie:delete'
    ],

    'client' => [
        'catalogue:read',
        'demandes:create', 'demandes:read'
    ]
];
```

### 5.3 Protection contre les Vulnérabilités OWASP Top 10

#### A03 : Injection SQL

**Mesure** : PDO avec requêtes préparées (100%)

**✅ Bon (Sécurisé)** :

```php
$stmt = $pdo->prepare("SELECT * FROM vehicules WHERE marque = ?");
$stmt->execute([$_GET['marque']]);
```

**❌ Mauvais (Vulnérable)** :

```php
$query = "SELECT * FROM vehicules WHERE marque = '{$_GET['marque']}'";
$result = mysqli_query($conn, $query);
```

#### A07 : Cross-Site Scripting (XSS)

**Mesure** : Échappement `htmlspecialchars()` sur toutes sorties

**✅ Bon (Sécurisé)** :

```php
<td><?= htmlspecialchars($vehicule['marque'], ENT_QUOTES, 'UTF-8') ?></td>

// Ou via fonction helper
<td><?= escape($vehicule['marque']) ?></td>
```

**Fonction Helper** (`config/database.php`) :

```php
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
```

#### A01 : Broken Access Control

**Mesure** : Vérification permissions à chaque page

**Protection Multicouche** :
1. Vérification session (`requireAuth()`)
2. Vérification rôle (`requireRole()`)
3. Vérification permission granulaire (`requirePermission()`)

**Exemple** :

```php
// modules/admin/utilisateurs.php
require_once '../../config/auth.php';

requireAuth();           // 1. Connecté ?
requireRole(['admin']); // 2. Rôle admin ?
// Pas besoin de requirePermission car admin = wildcard

// Suite du code...
```

#### A02 : Cryptographic Failures

**Mesures** :
- ✅ Mots de passe : bcrypt (jamais en clair)
- ✅ Sessions : `session.cookie_httponly = 1`
- ✅ HTTPS obligatoire en production (certificat SSL)
- ✅ Pas de données sensibles en GET

**Configuration PHP Sessions** (`php.ini`) :

```ini
session.cookie_httponly = 1      ; Empêche accès JavaScript aux cookies
session.cookie_secure = 1        ; HTTPS uniquement (production)
session.use_strict_mode = 1      ; Refuse sessions non initialisées
session.cookie_samesite = "Strict" ; Protection CSRF
```

#### A05 : Security Misconfiguration

**Mesures** :
- ✅ `display_errors = Off` en production
- ✅ `expose_php = Off` (masque version PHP)
- ✅ Fichiers `.env` et `config.php` hors webroot ou `.htaccess` protégé
- ✅ Permissions fichiers : 644 (fichiers), 755 (dossiers)

**`.htaccess` Protection Config** :

```apache
<Files "config.php">
    Require all denied
</Files>

<Files ".env">
    Require all denied
</Files>
```

#### A10 : Server-Side Request Forgery (SSRF)

**Mesure** : Pas d'appels externes non contrôlés

Le système actuel ne fait pas d'appels HTTP sortants (pas de `file_get_contents()` sur URLs externes).

**Version future** (APIs) : Whitelist de domaines autorisés.

### 5.4 Logs et Audit

#### Logs de Connexion

**Table** : `logs_connexion`

**Données capturées** :
- Utilisateur (ID)
- Action (connexion, déconnexion, tentative_echec)
- IP (`$_SERVER['REMOTE_ADDR']`)
- User-Agent (`$_SERVER['HTTP_USER_AGENT']`)
- Timestamp

**Consultation** : `/modules/admin/logs.php` (admin uniquement)

**Rétention** : 12 mois (nettoyage manuel ou cron)

#### Logs Applicatifs (Future)

**Fichier** : `/var/log/pgi-automobile/app.log`

**Format** : JSON Lines

```json
{"timestamp":"2023-08-20T14:30:00Z","level":"ERROR","user_id":3,"action":"vente_create","message":"Échec insertion vente (contrainte FK)","context":{"vehicule_id":999}}
```

**Niveaux** : DEBUG, INFO, WARNING, ERROR, CRITICAL

---

## 6. Performance

### 6.1 Optimisations Base de Données

#### Indexes Stratégiques

| Table | Colonne(s) | Type | Justification |
|-------|-----------|------|---------------|
| `vehicules` | `statut` | INDEX | Filtres fréquents (stock vs vendu) |
| `vehicules` | `type_vehicule` | INDEX | Filtres catalogue |
| `vehicules` | `marque` | INDEX | Recherches et groupements (stats) |
| `vehicules` | `immatriculation` | UNIQUE | Contrainte + recherche rapide |
| `vehicules` | `date_arrivee` | INDEX | Calculs stock longue durée |
| `ventes` | `date_vente` | INDEX | Requêtes statistiques (GROUP BY mois) |
| `ventes` | `client_id` | INDEX | Jointures fréquentes |
| `utilisateurs` | `email` | UNIQUE | Authentification (WHERE email = ?) |
| `permissions` | `role` | INDEX | Vérification permissions |

#### Requêtes Optimisées

**Exemple : Top 5 Marques** (statistiques)

```sql
-- Optimisé avec INDEX sur marque
SELECT ve.marque, COUNT(*) AS nb_ventes
FROM ventes v
JOIN vehicules ve ON v.vehicule_id = ve.id
WHERE YEAR(v.date_vente) = YEAR(NOW())
GROUP BY ve.marque
ORDER BY nb_ventes DESC
LIMIT 5;

-- EXPLAIN montre utilisation index
```

**Pagination** :

```php
$limit = 50;
$offset = ($_GET['page'] ?? 1 - 1) * $limit;

$stmt = $pdo->prepare("SELECT * FROM vehicules LIMIT ? OFFSET ?");
$stmt->execute([$limit, $offset]);
```

### 6.2 Optimisations Frontend

#### CSS

- ✅ Variables CSS (pas de calcul runtime)
- ✅ Pas de framework lourd (Bootstrap, Tailwind)
- ✅ Fichiers séparés par module (chargement sélectif)
- ✅ Minification recommandée en production

#### JavaScript

- ✅ Vanilla JS (pas de dépendance jQuery, React)
- ✅ Scripts en fin de `<body>` (chargement non bloquant)
- ✅ Validation côté client (évite aller-retours serveur)

**Exemple Validation** :

```javascript
document.getElementById('form-vente').addEventListener('submit', function(e) {
    const prixVente = parseFloat(document.getElementById('prix_vente').value);
    const prixAchat = parseFloat(document.getElementById('prix_achat').value);

    if (prixVente < prixAchat) {
        if (!confirm('Attention : marge négative. Confirmer ?')) {
            e.preventDefault();
        }
    }
});
```

#### Images

- ✅ Formats : JPEG (photos), PNG (logos), WebP (si supporté)
- ✅ Taille max : 5 MB (validation upload)
- ⚠️ Future : Resize serveur (max 1920px width)
- ⚠️ Future : Lazy loading (`<img loading="lazy">`)

### 6.3 Cache

#### Sessions PHP

**Stockage** : Fichiers `/tmp` (par défaut) ou Redis (production)

**Configuration Recommandée** :

```ini
session.save_handler = files   ; ou redis en production
session.gc_maxlifetime = 3600  ; 1 heure
session.cache_expire = 180     ; 3 minutes
```

#### Cache Navigateur

**Headers HTTP** (`.htaccess`) :

```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
</IfModule>

<IfModule mod_headers.c>
    <FilesMatch "\.(css|js|jpg|jpeg|png|gif|webp)$">
        Header set Cache-Control "max-age=2592000, public"
    </FilesMatch>
</IfModule>
```

#### OpCache PHP (Recommandé)

**Activation** (`php.ini`) :

```ini
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.revalidate_freq = 2
```

**Gain** : ~30% temps exécution PHP

### 6.4 Monitoring

#### Métriques à Surveiller

| Métrique | Outil | Seuil Alerte |
|----------|-------|--------------|
| **Temps réponse page** | Google Lighthouse | > 3s |
| **Requêtes SQL lentes** | MySQL Slow Query Log | > 1s |
| **Taux erreurs** | Logs Apache/PHP | > 1% |
| **Disponibilité** | Uptime monitoring | < 99% |
| **Utilisation CPU** | `top`, `htop` | > 80% |
| **Utilisation RAM** | `free -m` | > 90% |

#### Slow Query Log MySQL

**Activation** (`my.cnf`) :

```ini
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow-query.log
long_query_time = 1  # Requêtes > 1 seconde
```

**Analyse** :

```bash
mysqldumpslow /var/log/mysql/slow-query.log | head -10
```

---

## 7. Environnements

### 7.1 Développement (Local)

**Stack** :
- OS : Linux / macOS / Windows
- Serveur : XAMPP, MAMP, Laragon ou Docker
- BDD : MySQL 8.0
- PHP : 8.1

**Configuration PHP** (`php.ini`) :

```ini
display_errors = On
error_reporting = E_ALL
log_errors = On
error_log = /var/log/php_errors.log
```

**Variables `.env`** :

```env
APP_ENV=development
APP_DEBUG=true
DB_HOST=localhost
DB_NAME=pgi_automobile_dev
DB_USER=root
DB_PASS=
```

### 7.2 Test (Pré-production)

**Hébergement** : VPS dédié ou environnement staging

**Différences** :
- URL : `test.pgi-auto.fr`
- Base : `pgi_automobile_test`
- HTTPS : Certificat Let's Encrypt
- `APP_DEBUG=false` (mais logs détaillés)

### 7.3 Production

**Hébergement Recommandé** : o2switch Unique (7€/mois)

**Configuration PHP** (`php.ini`) :

```ini
display_errors = Off
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
log_errors = On
error_log = /home/user/logs/php_errors.log

; Sécurité
expose_php = Off
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1

; Performance
opcache.enable = 1
opcache.memory_consumption = 128
```

**Variables `.env`** :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.pgi-auto.fr

DB_HOST=localhost
DB_NAME=pgi_automobile_prod
DB_USER=pgi_user
DB_PASS=Str0ngP@ssw0rd!Prod

SESSION_LIFETIME=3600
```

**SSL/TLS** : Certificat Let's Encrypt (gratuit, auto-renew)

**Sauvegarde** : Script cron quotidien

```bash
#!/bin/bash
# /home/user/scripts/backup.sh

mysqldump -u pgi_user -p'Str0ngP@ssw0rd!Prod' pgi_automobile_prod > /home/user/backups/pgi_$(date +%Y%m%d).sql
tar -czf /home/user/backups/files_$(date +%Y%m%d).tar.gz /home/user/public_html/
```

**Crontab** :

```cron
0 3 * * * /home/user/scripts/backup.sh
```

---

## 8. Déploiement

### 8.1 Prérequis Serveur

| Composant | Version | Vérification |
|-----------|---------|--------------|
| Apache | 2.4+ | `apache2 -v` |
| PHP | 7.4+ | `php -v` |
| MySQL | 5.7+ | `mysql --version` |
| OpenSSL | 1.1.1+ | `openssl version` |

### 8.2 Procédure d'Installation

**Étape 1 : Télécharger le Code**

```bash
cd /home/user/public_html/
git clone https://github.com/your-repo/PGI-Automobile.git .
```

**Étape 2 : Configuration**

```bash
cp .env.example .env
nano .env  # Éditer les variables (BDD, URL, etc.)
```

**Étape 3 : Créer la Base de Données**

```sql
mysql -u root -p

CREATE DATABASE pgi_automobile CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'pgi_user'@'localhost' IDENTIFIED BY 'MotDePasseSecure123!';
GRANT ALL PRIVILEGES ON pgi_automobile.* TO 'pgi_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Étape 4 : Importer le Schéma**

```bash
mysql -u pgi_user -p pgi_automobile < sql/database.sql
```

**Étape 5 : Permissions Fichiers**

```bash
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod 600 .env
```

**Étape 6 : Configuration Apache**

**VirtualHost** (`/etc/apache2/sites-available/pgi-auto.conf`) :

```apache
<VirtualHost *:443>
    ServerName www.pgi-auto.fr
    DocumentRoot /home/user/public_html

    <Directory /home/user/public_html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/pgi-auto.fr/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/pgi-auto.fr/privkey.pem

    ErrorLog ${APACHE_LOG_DIR}/pgi-auto_error.log
    CustomLog ${APACHE_LOG_DIR}/pgi-auto_access.log combined
</VirtualHost>
```

**Activer le site** :

```bash
a2ensite pgi-auto
systemctl reload apache2
```

**Étape 7 : SSL Let's Encrypt**

```bash
certbot --apache -d www.pgi-auto.fr -d pgi-auto.fr
```

**Étape 8 : Tests Post-Déploiement**

1. Accès : `https://www.pgi-auto.fr`
2. Login avec compte test
3. Vérifier CRUD véhicules
4. Vérifier logs (`tail -f /var/log/apache2/pgi-auto_error.log`)

---

## 9. Annexes

### 9.1 Acronymes

- **RBAC** : Role-Based Access Control
- **PDO** : PHP Data Objects
- **CRUD** : Create, Read, Update, Delete
- **FK** : Foreign Key (clé étrangère)
- **PK** : Primary Key (clé primaire)
- **UK** : Unique Key (contrainte unique)
- **SSL** : Secure Sockets Layer
- **TLS** : Transport Layer Security
- **OWASP** : Open Web Application Security Project

### 9.2 Références Techniques

- PHP Documentation : https://www.php.net/
- MySQL 8.0 Reference Manual : https://dev.mysql.com/doc/refman/8.0/en/
- Apache HTTP Server : https://httpd.apache.org/docs/2.4/
- OWASP Top 10 : https://owasp.org/www-project-top-ten/
- Let's Encrypt : https://letsencrypt.org/

---

**Fin du document**

**Prochaine étape** : Modèles UML (Diagrammes)
