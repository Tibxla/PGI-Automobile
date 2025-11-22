# 11. DOCUMENTATION TECHNIQUE DU CODE

## Informations du Document

| Élément | Détail |
|---------|--------|
| **Projet** | PGI Automobile - Système de Gestion Intégré |
| **Phase** | PHASE 4 - Développement & Test |
| **Livrable** | Documentation Technique du Code |
| **Version** | 1.0 |
| **Date** | 17/11/2025 |
| **Auteur** | Équipe Développement PGI Automobile |

---

> **Note:** Ce document a été réalisé dans le cadre d'un projet académique de Licence 3 par **Thibaud** et **Melissa** sur la période du **27/10/2025 au 17/11/2025** (3 semaines).

## Table des Matières

1. [Introduction](#1-introduction)
2. [Architecture du Code](#2-architecture-du-code)
3. [Structure des Répertoires](#3-structure-des-répertoires)
4. [Standards de Codage](#4-standards-de-codage)
5. [Modules et Composants](#5-modules-et-composants)
6. [Documentation des Fichiers Clés](#6-documentation-des-fichiers-clés)
7. [Gestion de la Sécurité](#7-gestion-de-la-sécurité)
8. [Base de Données](#8-base-de-données)
9. [Guide du Développeur](#9-guide-du-développeur)

---

## 1. Introduction

### 1.1 Objectif du Document

Ce document fournit une documentation technique complète du code source du système PGI Automobile. Il est destiné aux développeurs qui maintiendront, feront évoluer ou débogueront le système.

### 1.2 Technologies Utilisées

| Technologie | Version | Usage |
|------------|---------|-------|
| PHP | 7.4+ | Backend, logique métier |
| MySQL | 8.0+ | Base de données relationnelle |
| HTML5 | - | Structure des pages |
| CSS3 | - | Styles et mise en page |
| JavaScript | ES6+ | Interactions client |
| Apache | 2.4+ | Serveur web |

### 1.3 Conventions de Nommage

```
Fichiers PHP :      snake_case.php (ex: gestion_vehicules.php)
Classes :           PascalCase (ex: Database, Auth)
Fonctions :         camelCase (ex: checkPermission, getVehicles)
Variables :         snake_case (ex: $user_id, $prix_achat)
Constantes :        UPPER_SNAKE_CASE (ex: DB_HOST, MAX_ATTEMPTS)
Tables SQL :        snake_case pluriel (ex: vehicules, utilisateurs)
```

---

## 2. Architecture du Code

### 2.1 Pattern Architectural

Le système utilise une architecture **MVC simplifiée** sans framework :

```
┌─────────────────────────────────────────────────────────┐
│                    PRÉSENTATION                          │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Pages PHP (*.php)                                │  │
│  │  - Affichage HTML                                 │  │
│  │  - Formulaires                                    │  │
│  │  - Appels AJAX                                    │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────┐
│                   LOGIQUE MÉTIER                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │  Scripts de traitement (*_traitement.php)        │  │
│  │  - Validation des données                         │  │
│  │  - Règles métier                                  │  │
│  │  - Appels à la couche données                     │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────┐
│                   COUCHE DONNÉES                         │
│  ┌──────────────────────────────────────────────────┐  │
│  │  config/database.php (PDO)                        │  │
│  │  - Connexion MySQL                                │  │
│  │  - Requêtes préparées                             │  │
│  │  - Transactions                                   │  │
│  └──────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

### 2.2 Patterns de Conception

**Singleton** - Classe Database
```php
class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
```

**Template Method** - Structure des pages
```php
// Toutes les pages suivent ce template :
include('config/auth.php');       // 1. Authentification
checkPermission('MODULE');         // 2. Vérification permissions
include('includes/header.php');   // 3. En-tête commun
// Contenu spécifique de la page
include('includes/footer.php');   // 4. Pied de page commun
```

**Strategy** - Calcul des marges
```php
// Différentes stratégies selon le type de véhicule
if ($type == 'neuf') {
    $marge = $prix_vente - $prix_achat;
} else if ($type == 'occasion') {
    $marge = ($prix_vente - $prix_achat) - $cout_revision;
}
```

---

## 3. Structure des Répertoires

```
PGI-Automobile/
│
├── config/                      # Configuration système
│   ├── database.php            # Connexion PDO (Singleton)
│   └── auth.php                # Authentification et RBAC
│
├── includes/                    # Composants réutilisables
│   ├── header.php              # En-tête commun (menu, navigation)
│   └── footer.php              # Pied de page commun
│
├── assets/                      # Ressources statiques
│   ├── css/
│   │   ├── style.css           # Styles globaux (2 838 lignes)
│   │   └── *.css               # Styles par module
│   ├── js/
│   │   └── main.js             # Scripts JavaScript (318 lignes)
│   └── images/                 # Images et logos
│
├── sql/
│   └── database.sql            # Schéma complet (459 lignes)
│
├── modules/                     # Modules fonctionnels (8)
│   ├── vehicules/              # Gestion véhicules (4 fichiers)
│   ├── ventes/                 # Gestion ventes (5 fichiers)
│   ├── commandes/              # Demandes d'achat (4 fichiers)
│   ├── clients/                # Gestion clients (4 fichiers)
│   ├── employes/               # Gestion RH (6 fichiers)
│   ├── stock/                  # Gestion stock (3 fichiers)
│   ├── statistiques/           # Tableaux de bord (2 fichiers)
│   └── admin/                  # Administration (6 fichiers)
│
├── login.php                    # Page de connexion
├── logout.php                   # Déconnexion
├── index.php                    # Tableau de bord principal
└── README.md                    # Documentation utilisateur

Total: 44 fichiers PHP (8 088 lignes)
```

---

## 4. Standards de Codage

### 4.1 Indentation et Formatage

```php
// ✅ BON : Indentation 4 espaces, accolades K&R style
function calculateMargin($prix_achat, $prix_vente) {
    if ($prix_vente < $prix_achat) {
        return 0;
    }
    return $prix_vente - $prix_achat;
}

// ❌ MAUVAIS : Pas d'indentation, accolades mal placées
function calculateMargin($prix_achat,$prix_vente){
if($prix_vente<$prix_achat){return 0;}
return $prix_vente-$prix_achat;}
```

### 4.2 Commentaires

```php
/**
 * Enregistre une nouvelle vente dans le système
 *
 * @param int $vehicule_id ID du véhicule vendu
 * @param int $client_id ID du client acheteur
 * @param float $prix_vente Prix de vente TTC
 * @param string $mode_paiement cash|credit|leasing
 * @return int|false ID de la vente créée ou false en cas d'erreur
 * @throws PDOException Si erreur base de données
 */
function enregistrerVente($vehicule_id, $client_id, $prix_vente, $mode_paiement) {
    // Validation des paramètres
    if ($prix_vente <= 0) {
        return false;
    }

    // Transaction pour garantir l'intégrité
    $pdo->beginTransaction();
    try {
        // 1. Insérer la vente
        $stmt = $pdo->prepare("INSERT INTO ventes ...");

        // 2. Mettre à jour le statut véhicule
        $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'vendu' ...");

        $pdo->commit();
        return $vente_id;
    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }
}
```

### 4.3 Gestion des Erreurs

```php
// ✅ BON : Try-catch avec logging
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} catch (PDOException $e) {
    error_log("Erreur SQL: " . $e->getMessage());
    $_SESSION['error'] = "Une erreur est survenue. Veuillez réessayer.";
    header('Location: error.php');
    exit;
}

// ❌ MAUVAIS : Affichage direct de l'erreur SQL (faille de sécurité)
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
```

### 4.4 Sécurité

**Requêtes Préparées (100% du code)**
```php
// ✅ BON : Requête préparée PDO
$stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
$stmt->execute([$id]);

// ❌ MAUVAIS : Concaténation directe (SQL Injection)
$sql = "SELECT * FROM vehicules WHERE id = " . $_GET['id'];
```

**Échappement XSS**
```php
// ✅ BON : Échappement systématique
echo "<h1>" . htmlspecialchars($vehicule['marque'], ENT_QUOTES, 'UTF-8') . "</h1>";

// ❌ MAUVAIS : Affichage brut
echo "<h1>" . $vehicule['marque'] . "</h1>";
```

**Validation des Entrées**
```php
// ✅ BON : Validation stricte
$prix = filter_input(INPUT_POST, 'prix', FILTER_VALIDATE_FLOAT);
if ($prix === false || $prix < 0) {
    die("Prix invalide");
}

// ❌ MAUVAIS : Pas de validation
$prix = $_POST['prix'];
```

---

## 5. Modules et Composants

### 5.1 Module Véhicules

**Fichiers :**
```
modules/vehicules/
├── index.php                    # Liste des véhicules (filtres, pagination)
├── ajouter.php                  # Formulaire d'ajout
├── modifier.php                 # Formulaire de modification
└── vehicules_traitement.php     # Traitement CRUD
```

**Fonctions Principales :**

```php
/**
 * Récupère la liste des véhicules avec filtres
 *
 * @param array $filters ['statut' => 'stock', 'marque' => 'Peugeot']
 * @param int $limit Nombre de résultats
 * @param int $offset Décalage pour pagination
 * @return array Liste des véhicules
 */
function getVehicules($filters = [], $limit = 20, $offset = 0) {
    $sql = "SELECT v.*, COUNT(c.id) as nb_commandes
            FROM vehicules v
            LEFT JOIN commandes c ON v.id = c.vehicule_id
            WHERE 1=1";

    $params = [];

    if (isset($filters['statut'])) {
        $sql .= " AND v.statut = ?";
        $params[] = $filters['statut'];
    }

    if (isset($filters['marque'])) {
        $sql .= " AND v.marque LIKE ?";
        $params[] = '%' . $filters['marque'] . '%';
    }

    $sql .= " GROUP BY v.id ORDER BY v.date_ajout DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Calcule la marge sur un véhicule
 *
 * @param float $prix_achat Prix d'achat HT
 * @param float $prix_vente Prix de vente TTC
 * @return array ['marge_ht' => float, 'taux' => float]
 */
function calculerMarge($prix_achat, $prix_vente) {
    $prix_vente_ht = $prix_vente / 1.20; // Déduction TVA 20%
    $marge = $prix_vente_ht - $prix_achat;
    $taux = ($prix_achat > 0) ? ($marge / $prix_achat) * 100 : 0;

    return [
        'marge_ht' => round($marge, 2),
        'taux' => round($taux, 2)
    ];
}
```

### 5.2 Module Ventes

**Fichiers :**
```
modules/ventes/
├── index.php                    # Liste des ventes
├── nouvelle_vente.php           # Formulaire de vente
├── details_vente.php            # Détails d'une vente
├── modifier_vente.php           # Modification vente
└── ventes_traitement.php        # Traitement CRUD
```

**Transaction de Vente (ACID) :**

```php
/**
 * Enregistre une vente avec gestion transactionnelle
 *
 * @param array $data Données de la vente
 * @return int|false ID vente ou false
 */
function enregistrerVente($data) {
    global $pdo;

    $pdo->beginTransaction();

    try {
        // 1. Vérifier disponibilité du véhicule
        $stmt = $pdo->prepare("SELECT statut FROM vehicules WHERE id = ? FOR UPDATE");
        $stmt->execute([$data['vehicule_id']]);
        $vehicule = $stmt->fetch();

        if ($vehicule['statut'] !== 'stock') {
            throw new Exception("Véhicule non disponible");
        }

        // 2. Insérer la vente
        $stmt = $pdo->prepare("
            INSERT INTO ventes (vehicule_id, client_id, prix_vente, mode_paiement, date_vente)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['vehicule_id'],
            $data['client_id'],
            $data['prix_vente'],
            $data['mode_paiement']
        ]);
        $vente_id = $pdo->lastInsertId();

        // 3. Mettre à jour le statut du véhicule
        $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'vendu' WHERE id = ?");
        $stmt->execute([$data['vehicule_id']]);

        // 4. Créer la facture
        $stmt = $pdo->prepare("
            INSERT INTO factures (vente_id, numero, montant_ttc, date_emission)
            VALUES (?, ?, ?, NOW())
        ");
        $numero_facture = 'FACT-' . date('Y') . '-' . str_pad($vente_id, 6, '0', STR_PAD_LEFT);
        $stmt->execute([$vente_id, $numero_facture, $data['prix_vente']]);

        $pdo->commit();
        return $vente_id;

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erreur vente: " . $e->getMessage());
        return false;
    }
}
```

### 5.3 Module Employés (RH)

**Génération de Paie :**

```php
/**
 * Génère une fiche de paie pour un employé
 *
 * @param int $employe_id ID de l'employé
 * @param string $mois Format YYYY-MM
 * @return array Détail de la paie
 */
function genererPaie($employe_id, $mois) {
    global $pdo;

    // Récupérer les infos employé
    $stmt = $pdo->prepare("
        SELECT salaire_base, heures_supplementaires, primes
        FROM employes
        WHERE id = ?
    ");
    $stmt->execute([$employe_id]);
    $employe = $stmt->fetch();

    // Calculs
    $salaire_brut = $employe['salaire_base'];
    $heures_sup = $employe['heures_supplementaires'] * 15.50; // Taux horaire
    $primes = $employe['primes'];

    $salaire_brut_total = $salaire_brut + $heures_sup + $primes;

    // Cotisations sociales (environ 23% du brut)
    $cotisations_salariales = round($salaire_brut_total * 0.23, 2);
    $salaire_net = $salaire_brut_total - $cotisations_salariales;

    // Cotisations patronales (environ 42% du brut)
    $cotisations_patronales = round($salaire_brut_total * 0.42, 2);
    $cout_total = $salaire_brut_total + $cotisations_patronales;

    // Enregistrer la paie
    $stmt = $pdo->prepare("
        INSERT INTO paies (employe_id, mois, salaire_brut, salaire_net, cotisations)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $employe_id,
        $mois,
        $salaire_brut_total,
        $salaire_net,
        $cotisations_salariales + $cotisations_patronales
    ]);

    return [
        'salaire_brut' => $salaire_brut_total,
        'cotisations_salariales' => $cotisations_salariales,
        'salaire_net' => $salaire_net,
        'cotisations_patronales' => $cotisations_patronales,
        'cout_total' => $cout_total
    ];
}
```

### 5.4 Module Statistiques

**Requête Dashboard :**

```php
/**
 * Récupère les KPIs pour le tableau de bord
 *
 * @param string $periode 'jour'|'semaine'|'mois'|'annee'
 * @return array KPIs
 */
function getKPIs($periode = 'mois') {
    global $pdo;

    // Déterminer la clause WHERE selon période
    $where = match($periode) {
        'jour' => "DATE(date_vente) = CURDATE()",
        'semaine' => "YEARWEEK(date_vente) = YEARWEEK(NOW())",
        'mois' => "MONTH(date_vente) = MONTH(NOW()) AND YEAR(date_vente) = YEAR(NOW())",
        'annee' => "YEAR(date_vente) = YEAR(NOW())",
        default => "1=1"
    };

    // KPI : Chiffre d'affaires
    $stmt = $pdo->query("
        SELECT
            COUNT(*) as nb_ventes,
            SUM(prix_vente) as ca_total,
            AVG(prix_vente) as panier_moyen
        FROM ventes
        WHERE $where
    ");
    $ventes = $stmt->fetch();

    // KPI : Marges
    $stmt = $pdo->query("
        SELECT
            SUM(v.prix_vente - ve.prix_achat) as marge_totale,
            AVG((v.prix_vente - ve.prix_achat) / ve.prix_achat * 100) as taux_marge_moyen
        FROM ventes v
        JOIN vehicules ve ON v.vehicule_id = ve.id
        WHERE $where
    ");
    $marges = $stmt->fetch();

    // KPI : Stock
    $stmt = $pdo->query("
        SELECT
            COUNT(*) as nb_vehicules,
            SUM(CASE WHEN statut = 'stock' THEN 1 ELSE 0 END) as nb_disponibles,
            SUM(prix_achat) as valeur_stock
        FROM vehicules
    ");
    $stock = $stmt->fetch();

    return [
        'ventes' => [
            'nombre' => $ventes['nb_ventes'],
            'ca' => $ventes['ca_total'],
            'panier_moyen' => $ventes['panier_moyen']
        ],
        'marges' => [
            'total' => $marges['marge_totale'],
            'taux_moyen' => $marges['taux_marge_moyen']
        ],
        'stock' => [
            'total' => $stock['nb_vehicules'],
            'disponibles' => $stock['nb_disponibles'],
            'valeur' => $stock['valeur_stock']
        ]
    ];
}
```

---

## 6. Documentation des Fichiers Clés

### 6.1 config/database.php

**Rôle :** Gestion de la connexion à la base de données MySQL via PDO avec pattern Singleton.

```php
<?php
/**
 * Configuration et connexion à la base de données
 *
 * Utilise le pattern Singleton pour garantir une seule connexion
 * PDO avec mode d'erreur en exceptions pour faciliter le débogage
 *
 * @package PGI-Automobile
 * @version 1.0
 */

class Database {
    private static $instance = null;
    private $pdo;

    // Paramètres de connexion
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'pgi_automobile';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHARSET = 'utf8mb4';

    /**
     * Constructeur privé (Singleton)
     */
    private function __construct() {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            self::DB_HOST,
            self::DB_NAME,
            self::DB_CHARSET
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Erreur connexion DB: " . $e->getMessage());
            die("Erreur de connexion à la base de données");
        }
    }

    /**
     * Récupère l'instance unique
     *
     * @return Database Instance unique
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Récupère l'objet PDO
     *
     * @return PDO
     */
    public function getConnection() {
        return $this->pdo;
    }

    // Empêcher le clonage
    private function __clone() {}

    // Empêcher la désérialisation
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Utilisation globale
$pdo = Database::getInstance()->getConnection();
?>
```

**Points Clés :**
- Pattern Singleton : Une seule connexion réutilisée
- PDO avec requêtes préparées : Protection contre SQL Injection
- Mode erreur EXCEPTION : Facilite le débogage
- Charset UTF-8MB4 : Support des emojis et caractères spéciaux

### 6.2 config/auth.php

**Rôle :** Gestion de l'authentification et des permissions (RBAC).

```php
<?php
/**
 * Système d'authentification et contrôle d'accès
 *
 * Gère la connexion utilisateur et les permissions basées sur les rôles (RBAC)
 *
 * @package PGI-Automobile
 * @version 1.0
 */

session_start();

require_once __DIR__ . '/database.php';

/**
 * Vérifie si l'utilisateur est authentifié
 * Redirige vers login.php si non connecté
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Vérifie si l'utilisateur a une permission spécifique
 *
 * @param string $permission Nom de la permission (ex: 'vehicules_read')
 * @return bool
 */
function hasPermission($permission) {
    if (!isset($_SESSION['permissions'])) {
        return false;
    }
    return in_array($permission, $_SESSION['permissions']);
}

/**
 * Vérifie une permission et redirige si refusée
 *
 * @param string $permission Nom de la permission
 */
function checkPermission($permission) {
    requireLogin();

    if (!hasPermission($permission)) {
        $_SESSION['error'] = "Vous n'avez pas les permissions nécessaires";
        header('Location: /index.php');
        exit;
    }
}

/**
 * Authentifie un utilisateur
 *
 * @param string $email Email de l'utilisateur
 * @param string $password Mot de passe en clair
 * @return bool|array Données utilisateur ou false
 */
function authenticateUser($email, $password) {
    global $pdo;

    // Récupérer l'utilisateur
    $stmt = $pdo->prepare("
        SELECT u.*, r.nom as role_nom, r.permissions
        FROM utilisateurs u
        JOIN roles r ON u.role_id = r.id
        WHERE u.email = ? AND u.actif = 1
    ");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        return false;
    }

    // Vérifier le mot de passe (bcrypt)
    if (!password_verify($password, $user['password'])) {
        // Logger la tentative échouée
        logLoginAttempt($user['id'], false);
        return false;
    }

    // Créer la session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role_nom'];
    $_SESSION['permissions'] = json_decode($user['permissions'], true);

    // Logger la connexion réussie
    logLoginAttempt($user['id'], true);

    return $user;
}

/**
 * Enregistre une tentative de connexion
 *
 * @param int $user_id ID utilisateur
 * @param bool $success Succès ou échec
 */
function logLoginAttempt($user_id, $success) {
    global $pdo;

    $stmt = $pdo->prepare("
        INSERT INTO logs_connexion (user_id, ip_address, success, date_tentative)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([
        $user_id,
        $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        $success ? 1 : 0
    ]);
}

/**
 * Déconnecte l'utilisateur
 */
function logout() {
    session_destroy();
    header('Location: /login.php');
    exit;
}

/**
 * Matrice de permissions par rôle
 */
const ROLE_PERMISSIONS = [
    'Super Admin' => ['*'], // Toutes permissions
    'Directeur' => [
        'vehicules_read', 'vehicules_create', 'vehicules_update', 'vehicules_delete',
        'ventes_read', 'ventes_create', 'ventes_update',
        'clients_read', 'clients_create', 'clients_update',
        'employes_read', 'employes_create', 'employes_update',
        'stats_read', 'reports_read'
    ],
    'Vendeur' => [
        'vehicules_read',
        'ventes_read', 'ventes_create',
        'clients_read', 'clients_create', 'clients_update'
    ],
    'Comptable' => [
        'vehicules_read',
        'ventes_read',
        'employes_read',
        'stats_read', 'reports_read'
    ],
    'Magasinier' => [
        'vehicules_read', 'vehicules_create', 'vehicules_update',
        'stock_read', 'stock_update'
    ]
];
?>
```

**Points Clés :**
- Sessions PHP pour état d'authentification
- Passwords hashés avec bcrypt
- RBAC avec 6 rôles et permissions granulaires
- Logs de connexion pour audit

### 6.3 includes/header.php

**Rôle :** En-tête HTML commun avec navigation dynamique selon permissions.

```php
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'PGI Automobile' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <h1>🚗 PGI Automobile</h1>
            </div>

            <nav class="main-nav">
                <ul>
                    <li><a href="/index.php">Tableau de bord</a></li>

                    <?php if (hasPermission('vehicules_read')): ?>
                    <li><a href="/modules/vehicules/">Véhicules</a></li>
                    <?php endif; ?>

                    <?php if (hasPermission('ventes_read')): ?>
                    <li><a href="/modules/ventes/">Ventes</a></li>
                    <?php endif; ?>

                    <?php if (hasPermission('clients_read')): ?>
                    <li><a href="/modules/clients/">Clients</a></li>
                    <?php endif; ?>

                    <?php if (hasPermission('employes_read')): ?>
                    <li><a href="/modules/employes/">Employés</a></li>
                    <?php endif; ?>

                    <?php if (hasPermission('stats_read')): ?>
                    <li><a href="/modules/statistiques/">Statistiques</a></li>
                    <?php endif; ?>

                    <?php if (hasPermission('admin_access')): ?>
                    <li><a href="/modules/admin/">Administration</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="user-menu">
                <span>👤 <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></span>
                <span class="badge"><?= htmlspecialchars($_SESSION['role']) ?></span>
                <a href="/logout.php" class="btn-logout">Déconnexion</a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
```

**Points Clés :**
- Navigation dynamique selon permissions
- Affichage du rôle et nom utilisateur
- Messages flash (success/error)
- Échappement XSS avec `htmlspecialchars()`

---

## 7. Gestion de la Sécurité

### 7.1 Protection SQL Injection

**100% des requêtes utilisent PDO avec requêtes préparées :**

```php
// ✅ Toutes les requêtes dans le projet
$stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("INSERT INTO ventes (client_id, prix) VALUES (?, ?)");
$stmt->execute([$client_id, $prix]);

$stmt = $pdo->prepare("UPDATE employes SET salaire = ? WHERE id = ?");
$stmt->execute([$salaire, $id]);
```

### 7.2 Protection XSS

**Échappement systématique des sorties :**

```php
// Affichage de données
echo htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

// Dans les attributs HTML
<input type="text" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">

// Dans JavaScript
<script>
var data = <?= json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
</script>
```

### 7.3 Protection CSRF

**Tokens CSRF sur tous les formulaires :**

```php
// Génération du token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Dans le formulaire
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

// Validation
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    die("Token CSRF invalide");
}
```

### 7.4 Hachage des Mots de Passe

**Utilisation de bcrypt (algorithme recommandé) :**

```php
// Création de compte
$password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = $pdo->prepare("INSERT INTO utilisateurs (email, password) VALUES (?, ?)");
$stmt->execute([$email, $password_hash]);

// Vérification
$stmt = $pdo->prepare("SELECT password FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (password_verify($password, $user['password'])) {
    // Mot de passe correct
}
```

### 7.5 Protection des Fichiers Sensibles

**Fichier .htaccess à la racine :**

```apache
# Bloquer l'accès aux fichiers sensibles
<FilesMatch "^\.">
    Order allow,deny
    Deny from all
</FilesMatch>

# Bloquer .sql, .bak, etc.
<FilesMatch "\.(sql|bak|conf|log)$">
    Order allow,deny
    Deny from all
</FilesMatch>

# Headers de sécurité
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
```

---

## 8. Base de Données

### 8.1 Schéma Relationnel

**10 Tables avec Contraintes d'Intégrité :**

```sql
-- Table principale : véhicules
CREATE TABLE vehicules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    immatriculation VARCHAR(20) UNIQUE NOT NULL,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT NOT NULL,
    prix_achat DECIMAL(10,2) NOT NULL,
    prix_vente DECIMAL(10,2),
    statut ENUM('stock', 'vendu', 'reserve') DEFAULT 'stock',
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_statut (statut),
    INDEX idx_marque (marque)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ventes avec FK
CREATE TABLE ventes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicule_id INT NOT NULL,
    client_id INT NOT NULL,
    prix_vente DECIMAL(10,2) NOT NULL,
    mode_paiement ENUM('cash', 'credit', 'leasing') NOT NULL,
    date_vente DATE NOT NULL,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE RESTRICT,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE RESTRICT,
    INDEX idx_date (date_vente)
) ENGINE=InnoDB;
```

### 8.2 Indexes pour Performance

**Stratégie d'indexation :**

```sql
-- Index sur colonnes fréquemment filtrées
CREATE INDEX idx_vehicules_statut ON vehicules(statut);
CREATE INDEX idx_vehicules_marque ON vehicules(marque);
CREATE INDEX idx_ventes_date ON ventes(date_vente);

-- Index composites pour jointures fréquentes
CREATE INDEX idx_ventes_vehicule_client ON ventes(vehicule_id, client_id);

-- Index UNIQUE pour contraintes métier
CREATE UNIQUE INDEX idx_unique_immat ON vehicules(immatriculation);
```

### 8.3 Transactions ACID

**Exemple : Vente de véhicule :**

```php
try {
    $pdo->beginTransaction();

    // 1. Vérifier et verrouiller le véhicule
    $stmt = $pdo->prepare("SELECT statut FROM vehicules WHERE id = ? FOR UPDATE");
    $stmt->execute([$vehicule_id]);

    // 2. Insérer vente
    $stmt = $pdo->prepare("INSERT INTO ventes (...) VALUES (...)");
    $stmt->execute([...]);

    // 3. Mettre à jour véhicule
    $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'vendu' WHERE id = ?");
    $stmt->execute([$vehicule_id]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    throw $e;
}
```

---

## 9. Guide du Développeur

### 9.1 Installation de l'Environnement

**Prérequis :**
```bash
# Vérifier versions
php -v    # >= 7.4
mysql --version  # >= 8.0
apache2 -v  # >= 2.4
```

**Installation :**
```bash
# 1. Cloner le projet
git clone https://github.com/votre-org/pgi-automobile.git
cd pgi-automobile

# 2. Importer la base de données
mysql -u root -p < sql/database.sql

# 3. Configurer database.php
nano config/database.php
# Modifier DB_USER, DB_PASS selon votre config

# 4. Configurer Apache
sudo nano /etc/apache2/sites-available/pgi-automobile.conf
# DocumentRoot /var/www/pgi-automobile
sudo a2ensite pgi-automobile
sudo systemctl reload apache2

# 5. Permissions
sudo chown -R www-data:www-data /var/www/pgi-automobile
sudo chmod -R 755 /var/www/pgi-automobile
```

### 9.2 Ajouter un Nouveau Module

**Structure à suivre :**

```
1. Créer le répertoire : modules/nouveau_module/

2. Fichiers requis :
   - index.php (liste/affichage)
   - ajouter.php (formulaire création)
   - modifier.php (formulaire édition)
   - traitement.php (logique CRUD)

3. Ajouter les permissions dans config/auth.php :
   'nouveau_module_read'
   'nouveau_module_create'
   'nouveau_module_update'
   'nouveau_module_delete'

4. Ajouter le lien dans includes/header.php :
   <?php if (hasPermission('nouveau_module_read')): ?>
   <li><a href="/modules/nouveau_module/">Nouveau Module</a></li>
   <?php endif; ?>

5. Créer la/les tables SQL si nécessaire

6. Tester toutes les opérations CRUD
```

### 9.3 Debugging

**Activer le mode debug (config/database.php) :**

```php
// En développement uniquement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Logs PDO
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
```

**Logs personnalisés :**

```php
function debug_log($message, $context = []) {
    $log_file = __DIR__ . '/../logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $context_str = !empty($context) ? json_encode($context) : '';
    $log_entry = "[$timestamp] $message $context_str\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Usage
debug_log("Erreur lors de la vente", ['vehicule_id' => $id, 'error' => $e->getMessage()]);
```

### 9.4 Tests Manuels

**Checklist avant commit :**

```
☐ Toutes les requêtes SQL utilisent PDO préparé
☐ Toutes les sorties sont échappées (htmlspecialchars)
☐ Les formulaires ont des tokens CSRF
☐ Les permissions sont vérifiées (checkPermission)
☐ Les transactions sont utilisées pour opérations critiques
☐ Les erreurs sont loggées (error_log)
☐ Le code respecte les conventions de nommage
☐ Les commentaires sont à jour
☐ Pas de var_dump() ou echo de debug
☐ Pas de credentials en dur
```

### 9.5 Maintenance

**Tâches régulières :**

```sql
-- Nettoyer les vieux logs (> 6 mois)
DELETE FROM logs_connexion WHERE date_tentative < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- Optimiser les tables
OPTIMIZE TABLE vehicules, ventes, clients, employes;

-- Analyser les index
ANALYZE TABLE vehicules, ventes;

-- Vérifier l'espace disque
SELECT
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS "Size (MB)"
FROM information_schema.TABLES
WHERE table_schema = 'pgi_automobile'
ORDER BY (data_length + index_length) DESC;
```

---

## Conclusion

Ce document technique fournit une vue d'ensemble complète du code source du système PGI Automobile. Les développeurs doivent :

1. **Respecter les standards** de codage établis
2. **Utiliser PDO** exclusivement pour les requêtes SQL
3. **Vérifier les permissions** avant toute opération sensible
4. **Logger les erreurs** pour faciliter le débogage
5. **Tester** manuellement avant chaque commit
6. **Documenter** les nouvelles fonctionnalités

Pour toute question, consulter le README.md ou contacter l'équipe technique.

---

**Document Version :** 1.0
**Dernière mise à jour :** 17/11/2025
**Auteur :** Équipe Développement PGI Automobile
