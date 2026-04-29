# PGI Automobile — Système de gestion de concession

Application web de gestion intégrée (PGI/ERP) pour concession automobile, développée en PHP natif avec MySQL. Couvre la gestion des véhicules, des ventes, des clients, des ressources humaines, du stock et des demandes d'achat en ligne.

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Serveur | PHP 7.4+ (compatible PHP 8.x) |
| Base de données | MySQL 5.7+ / MariaDB |
| Accès BDD | PDO avec requêtes préparées |
| Frontend | HTML5, CSS3 (variables CSS), JavaScript vanilla |
| Serveur web | Apache 2.4+ ou Nginx (ou serveur PHP intégré en dev) |

---

## Prérequis

- PHP 7.4 minimum (PHP 8.0+ recommandé)
- MySQL 5.7+ ou MariaDB 10.3+
- Serveur web Apache ou Nginx — ou utiliser le serveur PHP intégré en développement
- Environnement tout-en-un conseillé : XAMPP, WAMP, MAMP, ou Laragon

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/votre-username/PGI-Automobile.git
cd PGI-Automobile
```

### 2. Créer et alimenter la base de données

```bash
# Via ligne de commande MySQL
mysql -u root -p < sql/database.sql
```

Ou via phpMyAdmin : importer le fichier `sql/database.sql` (crée automatiquement la base `pgi_automobile`, les 11 tables et les données de test).

### 3. Configurer la connexion à la base de données

Modifier `config/database.php` avec vos identifiants MySQL :

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'pgi_automobile');
define('DB_USER', 'root');
define('DB_PASS', '');   // vide par défaut sur XAMPP/WAMP
```

### 4. Configurer le chemin de base (si nécessaire)

Par défaut, `config/auth.php` suppose que l'application est accessible à `/PGI-Automobile/`.

- **XAMPP/WAMP** (dossier dans `htdocs/`) : aucune modification requise.
- **Serveur PHP intégré** (`php -S localhost:8000` à la racine du projet) : définir la variable d'environnement `PGI_BASE_URL=` (vide) ou ajouter dans `config/database.php` : `define('APP_BASE_URL', '');`
- **Autre chemin** : définir `define('APP_BASE_URL', '/mon-chemin');` dans `config/database.php`, ou utiliser la variable `PGI_BASE_URL`.

### 5. Démarrer l'application

**Avec XAMPP/WAMP :**
1. Copier le dossier dans `htdocs/` (XAMPP) ou `www/` (WAMP)
2. Démarrer Apache et MySQL
3. Accéder à `http://localhost/PGI-Automobile/`

**Avec le serveur PHP intégré (développement uniquement) :**
```bash
php -S localhost:8000
```
Puis accéder à `http://localhost:8000/`

---

## Comptes de test

Le script SQL crée 6 comptes utilisateurs. Mot de passe commun : **`password123`**

| Rôle | Email |
|------|-------|
| Administrateur | `admin@pgi-auto.com` |
| Vendeur | `julie@pgi-auto.com` |
| Vendeur | `thomas@pgi-auto.com` |
| Gestionnaire stock | `marc@pgi-auto.com` |
| Comptable | `claire@pgi-auto.com` |
| Responsable RH | `emma@pgi-auto.com` |

Les clients créent leur compte via la page d'inscription publique (`client-inscription.php`).

**Important :** Changer ces mots de passe avant toute mise en production.

---

## Lancement

| URL | Page |
|-----|------|
| `/` ou `index.php` | Redirection automatique selon l'état de connexion |
| `accueil.php` | Page d'accueil publique |
| `catalogue.php` | Catalogue des véhicules (public) |
| `login.php` | Connexion |
| `client-inscription.php` | Création de compte client |
| `dashboard.php` | Redirection vers le module principal selon le rôle |

Après connexion, l'utilisateur est redirigé vers son module de départ selon son rôle :
- Admin → Gestion des utilisateurs
- Vendeur → Liste des ventes
- Gestionnaire stock → Inventaire
- Comptable → Statistiques
- RH → Liste du personnel
- Client → Catalogue

---

## Structure du projet

```
PGI-Automobile/
├── config/
│   ├── database.php          # Connexion PDO + fonctions formatage
│   ├── auth.php              # Authentification, sessions, permissions RBAC
│   └── config.example.php    # Modèle de configuration avancée
├── includes/
│   ├── header.php            # Header pages protégées (authentification requise)
│   ├── header-client.php     # Header pages publiques/clients
│   ├── footer.php            # Footer commun
│   └── functions.php         # Fonctions utilitaires (pagination, tokens, etc.)
├── modules/
│   ├── admin/                # Gestion utilisateurs, permissions, logs
│   ├── clients/              # CRUD clients, mes demandes
│   ├── profil/               # Profil utilisateur connecté
│   ├── rh/                   # Personnel, congés, bulletins de paie
│   ├── statistiques/         # Tableau de bord statistiques
│   ├── stock/                # Inventaire, alertes stock longue durée
│   ├── vehicules/            # CRUD véhicules
│   └── ventes/               # Ventes, demandes d'achat clients
├── assets/
│   ├── css/                  # 8 fichiers CSS (style.css principal + CSS par module)
│   ├── js/                   # script.js (commun), catalogue.js, home.js
│   └── images/               # Dossier pour les images de véhicules
├── sql/
│   └── database.sql          # Script complet : création BDD + données de test
├── docs/                     # Documentation projet (livrables universitaires, changelog)
├── scripts/
│   └── backup-database.sh    # Script de sauvegarde BDD
├── index.php                 # Dispatcher principal
├── accueil.php               # Page d'accueil publique
├── catalogue.php             # Catalogue véhicules (public)
├── demande.php               # Formulaire demande d'achat (clients connectés)
├── login.php                 # Connexion
├── logout.php                # Déconnexion
├── client-inscription.php    # Inscription client
├── dashboard.php             # Redirection post-login selon le rôle
└── acces-refuse.php          # Page d'accès refusé
```

---

## Modules et fonctionnalités

### Véhicules
- CRUD complet (ajout, modification, suppression)
- Champs : marque, modèle, année, couleur, kilométrage, immatriculation, prix achat/vente, type, carburant, statut (stock / réservé / vendu)
- Filtrage et recherche

### Ventes
- Enregistrement d'une vente avec sélection client + véhicule
- Calcul automatique de la marge
- Modes de paiement : comptant, crédit, leasing
- Historique complet

### Clients
- Base de données clients avec fiche complète
- Suivi des demandes d'achat liées à chaque client

### Demandes d'achat
- Formulaire public pour clients connectés
- Cycle de vie : en attente → en cours → acceptée / refusée → finalisée
- Interface de traitement pour les vendeurs

### Stock
- Inventaire en temps réel avec valeur totale et marge potentielle
- Répartition par type de véhicule et par carburant
- Alertes automatiques pour les véhicules en stock depuis plus de 6 mois

### Statistiques
- Performances de l'année (CA, nombre de ventes, panier moyen, marge)
- Évolution mensuelle sur 6 mois
- Top 5 des marques et des clients
- Taux de rotation du stock

### Ressources humaines
- Gestion du personnel (liste, ajout, modification)
- Demandes de congés avec validation
- Génération et validation des bulletins de paie

### Administration
- Gestion des utilisateurs (CRUD, activation/désactivation)
- Matrice de permissions par rôle (lecture seule, visualisation BDD)
- Logs de connexion avec pagination et filtres

---

## Système de permissions

6 rôles prédéfinis, avec permissions configurées en base de données (table `permissions`) et un fallback sur les permissions codées dans `config/auth.php`.

| Rôle | Modules accessibles |
|------|---------------------|
| `admin` | Tous |
| `vendeur` | Véhicules (lecture), clients, ventes, demandes, stock (lecture), stats |
| `gestionnaire_stock` | Véhicules (CRUD), stock, demandes (lecture), stats |
| `comptable` | Ventes (lecture), stats, clients (lecture) |
| `rh` | RH, congés, paie, stats |
| `client` | Catalogue, demandes (créer + voir les siennes) |

---

## Base de données

11 tables :

| Table | Contenu |
|-------|---------|
| `vehicules` | Inventaire des véhicules |
| `clients` | Base clients |
| `ventes` | Transactions de vente |
| `personnel` | Employés de la concession |
| `conges` | Demandes de congés |
| `bulletins_paie` | Bulletins de paie |
| `fournisseurs` | Fournisseurs |
| `utilisateurs` | Comptes système |
| `logs_connexion` | Journal des connexions (connexion / déconnexion / tentative_echec) |
| `permissions` | Permissions par rôle et module |
| `demandes_achat` | Demandes d'achat clients |

---

## Sécurité

- Requêtes PDO préparées sur l'ensemble des accès BDD (protection injections SQL)
- `htmlspecialchars()` sur toutes les sorties utilisateur (protection XSS)
- Contrôle d'accès par rôle (RBAC) avant chaque action sensible
- Sessions PHP avec vérification sur chaque page protégée
- Logs de connexion (IP, user-agent, horodatage)

**Pour la production :**
- Créer un utilisateur MySQL dédié (pas `root`)
- Activer HTTPS et les headers de sécurité (`X-Frame-Options`, `X-Content-Type-Options`)
- Désactiver `display_errors` dans `php.ini`
- Changer tous les mots de passe de test

---

## Documentation complémentaire

Le dossier `docs/livrables/` contient les documents de projet (cahier des charges, spécifications fonctionnelles et techniques, modèles UML, plan de test, manuel utilisateur, etc.).

---

## Licence

Propriétaire. Tous droits réservés.
