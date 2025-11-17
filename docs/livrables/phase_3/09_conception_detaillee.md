# Conception Détaillée - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile
**Version:** 1.0 (Projet Académique)
**Date:** Novembre 2025
**Auteurs:** Thibaud THOMAS-LAMOTTE & Melissa BENZIDANE
**Contexte:** Projet de L3 - Période du 27/10/2025 au 17/11/2025 (3 semaines)
**Statut:** Validé

---

## 1. Introduction

### 1.1 Objet du Document

Ce document présente la conception détaillée du PGI Automobile : Modèle Logique de Données (MLD), structure détaillée des modules PHP, algorithmes métier critiques, requêtes SQL types et patterns de conception appliqués.

### 1.2 Public Cible

- Développeurs
- Architectes techniques
- Équipe de maintenance

---

## 2. Modèle Logique de Données (MLD)

### 2.1 Schéma Complet des Tables SQL

Le système utilise **10 tables relationnelles** en MySQL 8.0.

#### Table : `vehicules`

```sql
CREATE TABLE vehicules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT NOT NULL,
    couleur VARCHAR(30),
    prix_achat DECIMAL(10,2) NOT NULL,
    prix_vente DECIMAL(10,2) NOT NULL,
    kilometrage INT DEFAULT 0,
    type_vehicule ENUM('berline', 'suv', 'sportive', 'utilitaire', 'citadine') NOT NULL,
    carburant ENUM('essence', 'diesel', 'electrique', 'hybride') NOT NULL,
    statut ENUM('stock', 'vendu', 'reserve') DEFAULT 'stock',
    date_arrivee DATE NOT NULL,
    immatriculation VARCHAR(20) UNIQUE NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_statut (statut),
    INDEX idx_type (type_vehicule),
    INDEX idx_marque (marque),
    INDEX idx_date_arrivee (date_arrivee)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Indexes** :
- `idx_statut` : Filtres fréquents (liste véhicules stock)
- `idx_type` : Filtres catalogue par type
- `idx_marque` : Statistiques par marque
- `idx_date_arrivee` : Calcul durée en stock

#### Table : `clients`

```sql
CREATE TABLE clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
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

#### Table : `ventes`

```sql
CREATE TABLE ventes (
    id INT PRIMARY KEY AUTO_INCREMENT,
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

**Contraintes FK** :
- `ON DELETE RESTRICT` : Pas de suppression si vente existe (intégrité historique)

#### Table : `demandes_achat`

```sql
CREATE TABLE demandes_achat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicule_id INT NOT NULL,
    client_id INT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    message TEXT,
    statut ENUM('en_attente', 'en_cours', 'acceptee', 'refusee', 'finalisee')
        DEFAULT 'en_attente',
    notes_gestionnaire TEXT,
    traitee_par INT,
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

#### Table : `utilisateurs`

```sql
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'vendeur', 'gestionnaire_stock', 'comptable', 'rh', 'client')
        DEFAULT 'client',
    statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
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

#### Table : `permissions`

```sql
CREATE TABLE permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role VARCHAR(50) NOT NULL,
    module VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_permission (role, module, action),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table : `logs_connexion`

```sql
CREATE TABLE logs_connexion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT,
    action ENUM('connexion', 'deconnexion', 'tentative_echec') NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,

    INDEX idx_user (utilisateur_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table : `personnel`

```sql
CREATE TABLE personnel (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    poste VARCHAR(100) NOT NULL,
    salaire DECIMAL(10,2) NOT NULL,
    email VARCHAR(100),
    telephone VARCHAR(20),
    date_embauche DATE NOT NULL,
    statut ENUM('actif', 'conge', 'inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table : `conges`

```sql
CREATE TABLE conges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    personnel_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('en_attente', 'approuve', 'refuse') DEFAULT 'en_attente',
    commentaire TEXT,
    commentaire_gestion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (personnel_id) REFERENCES personnel(id) ON DELETE CASCADE,

    INDEX idx_statut (statut),
    INDEX idx_dates (date_debut, date_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Table : `bulletins_paie`

```sql
CREATE TABLE bulletins_paie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    personnel_id INT NOT NULL,
    mois_reference DATE NOT NULL,
    salaire_base DECIMAL(10,2) NOT NULL,
    prime DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    net_a_payer DECIMAL(10,2) NOT NULL,
    statut ENUM('brouillon', 'valide') DEFAULT 'brouillon',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (personnel_id) REFERENCES personnel(id) ON DELETE CASCADE,
    UNIQUE KEY unique_personnel_mois (personnel_id, mois_reference),

    INDEX idx_mois (mois_reference),
    INDEX idx_statut (statut)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 3. Structure Détaillée des Modules PHP

### 3.1 Architecture des Fichiers par Module

Chaque module suit la même structure standardisée :

```
modules/
├── vehicules/
│   ├── liste.php          # Liste + filtres
│   ├── ajouter.php        # Formulaire création
│   ├── modifier.php       # Formulaire édition
│   └── supprimer.php      # Action suppression
├── ventes/
│   ├── liste.php
│   ├── ajouter.php
│   ├── demandes-liste.php      # Demandes d'achat
│   ├── demandes-detail.php
│   └── facture.php
├── clients/
│   ├── liste.php
│   ├── ajouter.php
│   ├── modifier.php
│   ├── supprimer.php
│   └── mes-demandes.php        # Vue client
└── ...
```

### 3.2 Template Standard d'un Module

**Exemple : `modules/vehicules/liste.php`**

```php
<?php
/**
 * MODULE VÉHICULES - LISTE
 * Affichage liste véhicules avec filtres dynamiques
 */

// === 1. IMPORTS & SÉCURITÉ ===
require_once '../../config/database.php';
require_once '../../config/auth.php';

requireAuth();
requirePermission('vehicules', 'read');

// === 2. RÉCUPÉRATION PARAMÈTRES ===
$type = $_GET['type'] ?? null;
$carburant = $_GET['carburant'] ?? null;
$statut = $_GET['statut'] ?? null;
$recherche = $_GET['recherche'] ?? null;

// === 3. CONSTRUCTION REQUÊTE SQL ===
$sql = "SELECT * FROM vehicules WHERE 1=1";
$params = [];

if ($type) {
    $sql .= " AND type_vehicule = ?";
    $params[] = $type;
}
if ($carburant) {
    $sql .= " AND carburant = ?";
    $params[] = $carburant;
}
if ($statut) {
    $sql .= " AND statut = ?";
    $params[] = $statut;
}
if ($recherche) {
    $sql .= " AND (marque LIKE ? OR modele LIKE ?)";
    $params[] = "%{$recherche}%";
    $params[] = "%{$recherche}%";
}

$sql .= " ORDER BY created_at DESC";

// === 4. EXÉCUTION REQUÊTE ===
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehicules = $stmt->fetchAll();

// === 5. AFFICHAGE VUE ===
$page_title = "Liste des Véhicules";
include '../../includes/header.php';
?>

<div class="container">
    <h1><?= escape($page_title) ?></h1>

    <!-- Filtres -->
    <form method="GET" class="filters">
        <select name="type">
            <option value="">Tous types</option>
            <option value="berline" <?= $type === 'berline' ? 'selected' : '' ?>>Berline</option>
            <option value="suv" <?= $type === 'suv' ? 'selected' : '' ?>>SUV</option>
            <!-- ... -->
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <!-- Tableau -->
    <table class="data-table">
        <thead>
            <tr>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Prix Vente</th>
                <th>Marge</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicules as $v): ?>
            <tr>
                <td><?= escape($v['marque']) ?></td>
                <td><?= escape($v['modele']) ?></td>
                <td><?= formatPrice($v['prix_vente']) ?></td>
                <td><?= formatPrice($v['prix_vente'] - $v['prix_achat']) ?></td>
                <td><span class="badge badge-<?= $v['statut'] ?>"><?= $v['statut'] ?></span></td>
                <td>
                    <?php if (hasPermission('vehicules', 'update')): ?>
                        <a href="modifier.php?id=<?= $v['id'] ?>">✏️ Modifier</a>
                    <?php endif; ?>
                    <?php if (hasPermission('vehicules', 'delete')): ?>
                        <a href="supprimer.php?id=<?= $v['id'] ?>"
                           onclick="return confirm('Confirmer suppression ?')">🗑️ Supprimer</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>
```

---

## 4. Algorithmes Métier Critiques

### 4.1 Calcul Automatique de la Marge

**Contexte** : Lors d'une vente, calculer la marge réalisée.

**Algorithme** :

```php
/**
 * Calcule la marge d'une vente
 *
 * @param float $prix_vente Prix de vente négocié
 * @param int $vehicule_id ID du véhicule
 * @return float Marge (peut être négative)
 */
function calculerMarge($prix_vente, $vehicule_id) {
    global $pdo;

    // Récupération prix d'achat
    $stmt = $pdo->prepare("SELECT prix_achat FROM vehicules WHERE id = ?");
    $stmt->execute([$vehicule_id]);
    $vehicule = $stmt->fetch();

    if (!$vehicule) {
        throw new Exception("Véhicule introuvable");
    }

    $marge = $prix_vente - $vehicule['prix_achat'];

    return $marge;
}
```

**Utilisation** :

```php
// modules/ventes/ajouter.php
$prix_vente = $_POST['prix_vente'];
$vehicule_id = $_POST['vehicule_id'];

$marge = calculerMarge($prix_vente, $vehicule_id);

// Insertion vente avec marge
$stmt = $pdo->prepare("INSERT INTO ventes (vehicule_id, client_id, prix_vente, marge, date_vente)
                       VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([$vehicule_id, $client_id, $prix_vente, $marge]);
```

### 4.2 Calcul Net à Payer (Bulletins de Paie)

**Algorithme** :

```php
/**
 * Calcule le net à payer d'un bulletin de paie
 *
 * @param float $salaire_base Salaire de base mensuel
 * @param float $primes Primes diverses
 * @param float $deductions Retenues (absences, avances, etc.)
 * @return float Net à payer
 */
function calculerNetAPayer($salaire_base, $primes = 0, $deductions = 0) {
    $net_a_payer = $salaire_base + $primes - $deductions;

    // Arrondi à 2 décimales
    $net_a_payer = round($net_a_payer, 2);

    // Validation: ne peut être négatif
    if ($net_a_payer < 0) {
        throw new Exception("Net à payer ne peut être négatif");
    }

    return $net_a_payer;
}
```

**Utilisation** :

```php
// modules/rh/paie.php
$salaire_base = $_POST['salaire_base'];
$prime = $_POST['prime'] ?? 0;
$deductions = $_POST['deductions'] ?? 0;

$net_a_payer = calculerNetAPayer($salaire_base, $prime, $deductions);

// Insertion bulletin
$stmt = $pdo->prepare("INSERT INTO bulletins_paie
    (personnel_id, mois_reference, salaire_base, prime, deductions, net_a_payer, statut)
    VALUES (?, ?, ?, ?, ?, ?, 'brouillon')");
$stmt->execute([$personnel_id, $mois_reference, $salaire_base, $prime, $deductions, $net_a_payer]);
```

### 4.3 Vérification Disponibilité Véhicule

**Algorithme** :

```php
/**
 * Vérifie si un véhicule est disponible pour la vente
 *
 * @param int $vehicule_id ID du véhicule
 * @return bool True si disponible, false sinon
 */
function isVehiculeDisponible($vehicule_id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT statut FROM vehicules WHERE id = ?");
    $stmt->execute([$vehicule_id]);
    $vehicule = $stmt->fetch();

    if (!$vehicule) {
        return false;
    }

    // Disponible si statut = stock ou reserve
    return in_array($vehicule['statut'], ['stock', 'reserve']);
}
```

### 4.4 Génération KPI Statistiques

**Algorithme : Chiffre d'Affaires Année en Cours**

```php
/**
 * Calcule le chiffre d'affaires de l'année en cours
 *
 * @return float CA total
 */
function getCAAnneeCourante() {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(prix_vente), 0) AS ca
        FROM ventes
        WHERE YEAR(date_vente) = YEAR(NOW())
    ");
    $stmt->execute();
    $result = $stmt->fetch();

    return (float) $result['ca'];
}
```

**Algorithme : Top 5 Marques Vendues**

```php
/**
 * Retourne le top 5 des marques vendues (année en cours)
 *
 * @return array Tableau associatif [marque => nb_ventes]
 */
function getTop5Marques() {
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT ve.marque, COUNT(*) AS nb_ventes
        FROM ventes v
        JOIN vehicules ve ON v.vehicule_id = ve.id
        WHERE YEAR(v.date_vente) = YEAR(NOW())
        GROUP BY ve.marque
        ORDER BY nb_ventes DESC
        LIMIT 5
    ");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}
```

---

## 5. Requêtes SQL Types

### 5.1 Requêtes CRUD de Base

#### CREATE (Insert)

```sql
-- Insertion véhicule
INSERT INTO vehicules (marque, modele, annee, type_vehicule, carburant,
                       prix_achat, prix_vente, kilometrage, immatriculation,
                       statut, date_arrivee)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'stock', ?);
```

#### READ (Select)

```sql
-- Lecture simple
SELECT * FROM vehicules WHERE id = ?;

-- Lecture avec filtres multiples
SELECT * FROM vehicules
WHERE statut = ?
  AND type_vehicule = ?
  AND marque LIKE ?
ORDER BY created_at DESC
LIMIT 50 OFFSET 0;
```

#### UPDATE

```sql
-- Mise à jour véhicule
UPDATE vehicules
SET marque = ?, modele = ?, prix_vente = ?, updated_at = NOW()
WHERE id = ?;

-- Mise à jour statut (transaction vente)
UPDATE vehicules
SET statut = 'vendu'
WHERE id = ?;
```

#### DELETE

```sql
-- Suppression véhicule (si aucune vente associée)
DELETE FROM vehicules WHERE id = ? AND statut != 'vendu';
```

### 5.2 Requêtes Complexes (Jointures)

#### Jointure 2 Tables : Ventes avec Détails Véhicule

```sql
SELECT v.*, ve.marque, ve.modele, ve.immatriculation,
       (v.prix_vente - ve.prix_achat) AS marge_calculee
FROM ventes v
JOIN vehicules ve ON v.vehicule_id = ve.id
WHERE YEAR(v.date_vente) = YEAR(NOW())
ORDER BY v.date_vente DESC;
```

#### Jointure 3 Tables : Ventes Complètes

```sql
SELECT v.id, v.date_vente, v.prix_vente, v.marge,
       ve.marque, ve.modele, ve.immatriculation,
       c.nom, c.prenom, c.email AS client_email
FROM ventes v
JOIN vehicules ve ON v.vehicule_id = ve.id
JOIN clients c ON v.client_id = c.id
WHERE v.date_vente BETWEEN ? AND ?
ORDER BY v.date_vente DESC;
```

### 5.3 Requêtes d'Agrégation (Statistiques)

#### Évolution Mensuelle CA (6 Derniers Mois)

```sql
SELECT DATE_FORMAT(date_vente, '%Y-%m') AS mois,
       COUNT(*) AS nb_ventes,
       SUM(prix_vente) AS ca_total,
       AVG(prix_vente) AS panier_moyen,
       SUM(marge) AS marge_totale
FROM ventes
WHERE date_vente >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
GROUP BY DATE_FORMAT(date_vente, '%Y-%m')
ORDER BY mois ASC;
```

#### Répartition Véhicules par Type (Stock Actuel)

```sql
SELECT type_vehicule,
       COUNT(*) AS nb_vehicules,
       SUM(prix_achat) AS valeur_stock,
       SUM(prix_vente - prix_achat) AS marge_potentielle
FROM vehicules
WHERE statut = 'stock'
GROUP BY type_vehicule
ORDER BY nb_vehicules DESC;
```

#### Alertes Stock Longue Durée (> 6 Mois)

```sql
SELECT id, marque, modele, immatriculation, date_arrivee,
       DATEDIFF(NOW(), date_arrivee) AS jours_en_stock
FROM vehicules
WHERE statut = 'stock'
  AND DATEDIFF(NOW(), date_arrivee) > 180
ORDER BY jours_en_stock DESC;
```

---

## 6. Patterns de Conception Appliqués

### 6.1 Singleton (Connexion BDD)

**Implémentation** : `config/database.php`

```php
<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }

    private function __clone() {}
    private function __wakeup() {}
}

// Usage global
$pdo = Database::getInstance();
?>
```

### 6.2 Template Method (Header/Footer)

**Implémentation** : `includes/header.php`, `includes/footer.php`

```php
// Toute page utilise le template
<?php
$page_title = "Ma Page";
include '../../includes/header.php';
?>

<!-- Contenu spécifique page -->

<?php include '../../includes/footer.php'; ?>
```

### 6.3 Strategy (Formatage Données)

**Implémentation** : `config/database.php` (fonctions utilitaires)

```php
/**
 * Formate un prix en euros
 */
function formatPrice($price) {
    return number_format($price, 2, ',', ' ') . ' €';
}

/**
 * Formate une date DD/MM/YYYY
 */
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

/**
 * Échappe HTML (protection XSS)
 */
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
```

### 6.4 Repository Pattern (Simplifié)

**Concept** : Abstraction accès données (non implémenté strictement, mais principe respecté)

```php
// Exemple conceptuel (non implémenté dans le projet actuel)
class VehiculeRepository {
    private $pdo;

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM vehicules ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO vehicules (...) VALUES (...)");
        $stmt->execute([...]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE vehicules SET ... WHERE id = ?");
        $stmt->execute([...]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM vehicules WHERE id = ?");
        $stmt->execute([$id]);
    }
}
```

---

## 7. Gestion des Transactions

### 7.1 Transaction Vente (Critique)

**Code** : `modules/ventes/ajouter.php`

```php
<?php
$pdo->beginTransaction();

try {
    // 1. Vérification véhicule disponible
    $stmt = $pdo->prepare("SELECT statut FROM vehicules WHERE id = ? FOR UPDATE");
    $stmt->execute([$vehicule_id]);
    $vehicule = $stmt->fetch();

    if (!$vehicule || !in_array($vehicule['statut'], ['stock', 'reserve'])) {
        throw new Exception("Véhicule non disponible");
    }

    // 2. Insertion vente
    $stmt = $pdo->prepare("INSERT INTO ventes (vehicule_id, client_id, prix_vente, marge, date_vente, mode_paiement)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$vehicule_id, $client_id, $prix_vente, $marge, $date_vente, $mode_paiement]);

    // 3. Mise à jour statut véhicule
    $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'vendu' WHERE id = ?");
    $stmt->execute([$vehicule_id]);

    // 4. Log (optionnel)
    $stmt = $pdo->prepare("INSERT INTO logs_ventes (vente_id, user_id, action) VALUES (?, ?, 'creation')");
    $stmt->execute([$pdo->lastInsertId(), $_SESSION['user_id']]);

    $pdo->commit();

    header('Location: liste.php?success=1');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
}
?>
```

**Garanties** :
- **Atomicité** : Toutes opérations réussies OU aucune
- **Cohérence** : Contraintes FK respectées
- **Isolation** : `FOR UPDATE` verrouille ligne véhicule (pas de race condition)
- **Durabilité** : Commit persiste données

---

## 8. Validation et Sécurité

### 8.1 Validation Côté Serveur

**Exemple** : Validation formulaire ajout véhicule

```php
<?php
// modules/vehicules/ajouter.php
$errors = [];

// Validation marque
if (empty($_POST['marque']) || strlen($_POST['marque']) > 50) {
    $errors[] = "Marque invalide (max 50 caractères)";
}

// Validation année
$annee = (int) $_POST['annee'];
if ($annee < 1900 || $annee > (date('Y') + 1)) {
    $errors[] = "Année invalide (1900 - " . (date('Y') + 1) . ")";
}

// Validation prix
$prix_achat = (float) $_POST['prix_achat'];
$prix_vente = (float) $_POST['prix_vente'];
if ($prix_achat <= 0 || $prix_vente <= 0) {
    $errors[] = "Prix invalides (doivent être > 0)";
}

// Validation type (whitelist)
$types_valides = ['berline', 'suv', 'sportive', 'utilitaire', 'citadine'];
if (!in_array($_POST['type_vehicule'], $types_valides)) {
    $errors[] = "Type véhicule invalide";
}

// Validation immatriculation unique
$stmt = $pdo->prepare("SELECT COUNT(*) FROM vehicules WHERE immatriculation = ?");
$stmt->execute([$_POST['immatriculation']]);
if ($stmt->fetchColumn() > 0) {
    $errors[] = "Immatriculation déjà existante";
}

if (!empty($errors)) {
    // Affichage erreurs
    foreach ($errors as $error) {
        echo "<div class='alert error'>" . escape($error) . "</div>";
    }
} else {
    // Insertion
}
?>
```

### 8.2 Protection XSS (Échappement Sorties)

```php
<!-- TOUJOURS utiliser escape() pour affichage données utilisateur -->
<td><?= escape($vehicule['marque']) ?></td>
<td><?= escape($client['nom']) ?></td>
<input type="text" value="<?= escape($_POST['recherche'] ?? '') ?>">
```

### 8.3 Protection CSRF (Future)

**Génération Token** :

```php
<?php
// Génération token session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!-- Formulaire -->
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <!-- Champs formulaire -->
</form>
```

**Vérification Token** :

```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur CSRF : Token invalide");
    }

    // Traitement formulaire
}
?>
```

---

## 9. Optimisations Performances

### 9.1 Pagination Requêtes

```php
<?php
// modules/vehicules/liste.php
$limit = 50;
$page = (int) ($_GET['page'] ?? 1);
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT * FROM vehicules ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->execute([$limit, $offset]);
$vehicules = $stmt->fetchAll();

// Comptage total (pour pagination)
$total = $pdo->query("SELECT COUNT(*) FROM vehicules")->fetchColumn();
$total_pages = ceil($total / $limit);
?>

<!-- Affichage pagination -->
<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>
</div>
```

### 9.2 Indexes Stratégiques

```sql
-- Index sur colonnes fréquemment filtrées
CREATE INDEX idx_statut ON vehicules(statut);
CREATE INDEX idx_type ON vehicules(type_vehicule);
CREATE INDEX idx_marque ON vehicules(marque);
CREATE INDEX idx_date_vente ON ventes(date_vente);

-- Analyse utilisation index
EXPLAIN SELECT * FROM vehicules WHERE statut = 'stock' AND type_vehicule = 'SUV';
```

---

## 10. Validation et Approbation

### 10.1 Signatures

| Rôle | Nom | Signature | Date |
|------|-----|-----------|------|
| **Lead Développeur** | | | |
| **Architecte Logiciel** | | | |
| **Chef de Projet** | | | |

---

**Fin du document**

**Prochaine étape** : Maquettes & Prototypes UI/UX
