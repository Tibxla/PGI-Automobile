# 🚗 PGI Automobile - Système de Gestion de Concession

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/license-Proprietary-red.svg)]()

**PGI Automobile** est une application web complète de gestion de concession automobile développée en PHP natif. Elle permet de gérer l'ensemble des opérations d'une concession : inventaire des véhicules, ventes, clients, ressources humaines, statistiques et demandes d'achat.

---

## 📑 Table des Matières

1. [Présentation du Projet](#-présentation-du-projet)
2. [Objectifs](#-objectifs)
3. [Architecture](#-architecture)
4. [Technologies Utilisées](#-technologies-utilisées)
5. [Fonctionnalités](#-fonctionnalités)
6. [Installation Locale](#-installation-locale)
7. [Configuration](#-configuration)
8. [Exécution](#-exécution)
9. [Structure du Code](#-structure-du-code)
10. [Utilisation](#-utilisation)
11. [Sécurité](#-sécurité)
12. [Améliorations Futures](#-améliorations-futures)
13. [Licence](#-licence)

---

## 🎯 Présentation du Projet

PGI Automobile est un **Progiciel de Gestion Intégré (PGI/ERP)** spécialisé pour les concessions automobiles. Il centralise toutes les opérations métier dans une interface web moderne et intuitive.

### Contexte
Ce système a été conçu pour répondre aux besoins des concessionnaires automobiles en matière de :
- Gestion de l'inventaire des véhicules (neufs et occasions)
- Suivi des ventes et de la relation client
- Gestion des ressources humaines (personnel, paie, congés)
- Analyse statistique des performances commerciales
- Gestion des demandes d'achat clients

### Public cible
- **Administrateurs** : Gestion complète du système
- **Vendeurs** : Gestion des ventes et des clients
- **Gestionnaires de stock** : Inventaire et véhicules
- **Responsables RH** : Personnel, paie, congés
- **Comptables** : Suivi financier et statistiques
- **Clients** : Consultation du catalogue et demandes d'achat

---

## 🎯 Objectifs

### Objectifs fonctionnels
- ✅ Centraliser la gestion des véhicules (stock, ventes, réservations)
- ✅ Automatiser le suivi des ventes et des marges
- ✅ Faciliter la gestion des clients et leurs demandes
- ✅ Digitaliser la gestion RH (congés, paie, personnel)
- ✅ Fournir des statistiques et tableaux de bord en temps réel
- ✅ Permettre aux clients de consulter le catalogue en ligne

### Objectifs techniques
- ✅ Architecture modulaire et maintenable
- ✅ Interface responsive (desktop et mobile)
- ✅ Sécurité renforcée (authentification, permissions, XSS)
- ✅ Performance optimale (CSS en cache, requêtes optimisées)
- ✅ Code propre et bien documenté

---

## 🏗️ Architecture

### Architecture globale

```
┌─────────────────────────────────────────────────────────┐
│                   CLIENT (Navigateur)                    │
│                    HTML + CSS + JS                       │
└────────────────────────┬────────────────────────────────┘
                         │ HTTP/HTTPS
┌────────────────────────▼────────────────────────────────┐
│              SERVEUR WEB (Apache/Nginx)                  │
│                    PHP 7.4+                              │
├──────────────────────────────────────────────────────────┤
│  ┌───────────────┐  ┌───────────────┐  ┌──────────────┐ │
│  │   Frontend    │  │   Backend     │  │    Config    │ │
│  │  (Pages PHP)  │  │   (Modules)   │  │  (Database)  │ │
│  └───────────────┘  └───────────────┘  └──────────────┘ │
└────────────────────────┬────────────────────────────────┘
                         │ PDO (MySQL)
┌────────────────────────▼────────────────────────────────┐
│                 BASE DE DONNÉES (MySQL)                  │
│           10 tables (véhicules, ventes, etc.)            │
└──────────────────────────────────────────────────────────┘
```

### Architecture modulaire

Le projet suit une architecture **MVC simplifiée** avec séparation des préoccupations :

```
PGI-Automobile/
│
├── config/          # Configuration (BDD, auth, permissions)
├── includes/        # Composants réutilisables (header, footer, sidebar)
├── modules/         # Modules métier (8 modules)
│   ├── admin/       # Gestion des utilisateurs et permissions
│   ├── clients/     # Gestion des clients
│   ├── profil/      # Profil utilisateur
│   ├── rh/          # Ressources Humaines (personnel, paie, congés)
│   ├── statistiques/# Tableaux de bord et statistiques
│   ├── stock/       # Inventaire et gestion du stock
│   ├── vehicules/   # CRUD véhicules
│   └── ventes/      # Gestion des ventes et demandes
│
├── assets/          # Ressources statiques
│   ├── css/         # Feuilles de style (7 fichiers CSS)
│   ├── js/          # Scripts JavaScript
│   └── images/      # Images et assets visuels
│
├── sql/             # Scripts de base de données
├── *.php            # Pages publiques (accueil, catalogue, login, etc.)
└── README.md        # Documentation
```

### Modèle de données

**10 tables principales :**

1. **vehicules** : Inventaire des véhicules (marque, modèle, prix, statut)
2. **ventes** : Transactions de vente (prix, marge, date)
3. **clients** : Base clients (nom, email, téléphone)
4. **utilisateurs** : Comptes système (admin, vendeurs, etc.)
5. **personnel** : Employés de la concession
6. **conges** : Gestion des congés du personnel
7. **bulletins_paie** : Paie et salaires
8. **fournisseurs** : Fournisseurs de véhicules
9. **demandes_achat** : Demandes clients via le site
10. **permissions** : Système de permissions par rôle

---

## 🛠️ Technologies Utilisées

### Backend
| Technologie | Version | Usage |
|-------------|---------|-------|
| **PHP** | 7.4+ | Langage serveur principal |
| **MySQL** | 5.7+ | Base de données relationnelle |
| **PDO** | - | Accès base de données sécurisé |

### Frontend
| Technologie | Usage |
|-------------|-------|
| **HTML5** | Structure des pages |
| **CSS3** | Style et mise en page (7 fichiers CSS) |
| **JavaScript** | Interactivité (vanilla JS) |

### Sécurité
- **PDO Prepared Statements** : Protection contre les injections SQL
- **htmlspecialchars()** : Protection XSS sur toutes les sorties
- **Sessions PHP** : Gestion sécurisée de l'authentification
- **Système de permissions** : Contrôle d'accès par rôle

### Architecture
- **MVC simplifié** : Séparation logique/présentation
- **Architecture modulaire** : 8 modules indépendants
- **Design responsive** : Compatible mobile et desktop
- **CSS Variables** : Thème unifié et maintenable

---

## ✨ Fonctionnalités

### 🚙 Gestion des Véhicules
- ✅ CRUD complet (Créer, Lire, Modifier, Supprimer)
- ✅ Fiche détaillée (marque, modèle, année, prix, kilométrage)
- ✅ Gestion des statuts (stock, vendu, réservé)
- ✅ Upload d'images
- ✅ Filtrage et recherche avancée
- ✅ Types de véhicules (berline, SUV, sportive, utilitaire, citadine)
- ✅ Types de carburant (essence, diesel, électrique, hybride)

### 💰 Gestion des Ventes
- ✅ Enregistrement des ventes
- ✅ Calcul automatique des marges
- ✅ Historique des transactions
- ✅ Association client-véhicule
- ✅ Suivi du vendeur assigné
- ✅ Statistiques de ventes

### 👥 Gestion des Clients
- ✅ Base de données clients
- ✅ Fiche client complète (coordonnées, historique)
- ✅ Suivi des demandes d'achat
- ✅ Interface client pour consultation catalogue
- ✅ Demandes d'achat en ligne

### 📊 Statistiques et Tableaux de Bord
- ✅ Dashboard avec KPIs (ventes, CA, marges)
- ✅ Évolution mensuelle des ventes (6 derniers mois)
- ✅ Top 5 des marques vendues
- ✅ Top 5 des meilleurs clients
- ✅ Taux de rotation du stock
- ✅ Indicateurs de performance

### 📦 Gestion du Stock
- ✅ Inventaire en temps réel
- ✅ Répartition par type de véhicule
- ✅ Répartition par carburant
- ✅ Alertes véhicules en stock longue durée (> 6 mois)
- ✅ Valeur totale du stock
- ✅ Marge potentielle

### 🧑‍💼 Ressources Humaines
- ✅ Gestion du personnel (employés, postes, salaires)
- ✅ Gestion des congés (demandes, validation)
- ✅ Génération des bulletins de paie
- ✅ Suivi des heures et salaires

### 🔐 Gestion des Utilisateurs et Permissions
- ✅ Système d'authentification sécurisé
- ✅ 6 rôles prédéfinis (admin, vendeur, gestionnaire_stock, comptable, rh, client)
- ✅ Permissions granulaires par module
- ✅ Gestion des utilisateurs (CRUD)
- ✅ Logs de connexion

### 📋 Demandes d'Achat
- ✅ Formulaire client pour demandes en ligne
- ✅ Suivi des demandes par statut (en attente, en cours, acceptée, refusée, finalisée)
- ✅ Tableau de bord des demandes pour les vendeurs
- ✅ Filtrage par statut
- ✅ Détails des demandes avec informations client

---

## 💻 Installation Locale

### Prérequis

Avant de commencer, assurez-vous d'avoir installé :

| Logiciel | Version minimale | Recommandé |
|----------|------------------|------------|
| **PHP** | 7.4 | 8.0+ |
| **MySQL** | 5.7 | 8.0+ |
| **Apache/Nginx** | 2.4 | 2.4+ |
| **Composer** | - | Optionnel |

**Recommandation** : Utilisez XAMPP, WAMP ou MAMP pour une installation tout-en-un.

---

### Étapes d'installation

#### **Étape 1 : Cloner le repository**

```bash
# Via HTTPS
git clone https://github.com/votre-username/PGI-Automobile.git

# Via SSH
git clone git@github.com:votre-username/PGI-Automobile.git

# Se placer dans le dossier
cd PGI-Automobile
```

#### **Étape 2 : Configurer le serveur web**

##### Option A : XAMPP/WAMP
1. Copier le dossier `PGI-Automobile` dans `htdocs/` (XAMPP) ou `www/` (WAMP)
2. Démarrer Apache et MySQL depuis le panneau de contrôle
3. Accéder à : `http://localhost/PGI-Automobile/`

##### Option B : Serveur PHP intégré (développement uniquement)
```bash
php -S localhost:8000
```

#### **Étape 3 : Créer la base de données**

##### Méthode 1 : Via phpMyAdmin
1. Accéder à `http://localhost/phpmyadmin`
2. Créer une nouvelle base : `pgi_automobile`
3. Importer le fichier : `sql/database.sql`

##### Méthode 2 : Via ligne de commande
```bash
# Créer la base et importer les données
mysql -u root -p < sql/database.sql
```

**Note** : Le script SQL crée automatiquement :
- La base `pgi_automobile`
- 10 tables avec structure complète
- Données de test (véhicules, utilisateurs, ventes)
- 6 comptes utilisateurs de test

#### **Étape 4 : Configurer la connexion à la base de données**

Ouvrir le fichier `config/database.php` et modifier les paramètres :

```php
<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');      // Hôte MySQL
define('DB_NAME', 'pgi_automobile'); // Nom de la base
define('DB_USER', 'root');           // Utilisateur MySQL
define('DB_PASS', '');               // Mot de passe MySQL (vide par défaut sur XAMPP)
```

**Pour un serveur de production :**
```php
define('DB_HOST', 'votre-serveur.com');
define('DB_NAME', 'pgi_automobile');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'mot_de_passe_securise');
```

#### **Étape 5 : Vérifier les permissions des fichiers**

```bash
# Sur Linux/Mac (uniquement si nécessaire)
chmod -R 755 PGI-Automobile/
chmod -R 777 PGI-Automobile/assets/images/
```

#### **Étape 6 : Tester l'installation**

1. Accéder à : `http://localhost/PGI-Automobile/`
2. Vous devriez voir la page d'accueil
3. Tester la connexion avec un compte de test :

---

## ⚙️ Configuration

### Comptes utilisateurs de test

Le système est pré-configuré avec **6 comptes utilisateurs** pour tester toutes les fonctionnalités :

| Rôle | Email | Mot de passe | Permissions |
|------|-------|--------------|-------------|
| **Administrateur** | admin@pgi-auto.com | `password123` | Accès complet (tous modules) |
| **Vendeur 1** | sophie.martin@pgi-auto.com | `password123` | Ventes, clients, demandes |
| **Vendeur 2** | lucas.bernard@pgi-auto.com | `password123` | Ventes, clients |
| **Gestionnaire Stock** | julie.petit@pgi-auto.com | `password123` | Véhicules, stock, inventaire |
| **Comptable** | thomas.robert@pgi-auto.com | `password123` | Ventes, statistiques, comptabilité |
| **RH** | marie.dubois@pgi-auto.com | `password123` | Personnel, paie, congés |

**⚠️ IMPORTANT** : Changez immédiatement ces mots de passe en production !

### Configuration des permissions

Les permissions sont gérées par rôle dans la base de données (table `permissions`). Pour modifier :

1. Se connecter en tant qu'administrateur
2. Accéder à `modules/admin/utilisateurs.php`
3. Modifier les rôles et permissions

**Modules disponibles :**
- `vehicules` : Gestion des véhicules
- `ventes` : Gestion des ventes
- `clients` : Gestion des clients
- `demandes` : Demandes d'achat
- `stock` : Inventaire et stock
- `statistiques` : Tableaux de bord
- `rh` : Ressources humaines
- `paie` : Paie et salaires
- `conges` : Gestion des congés
- `utilisateurs` : Gestion des utilisateurs

**Actions disponibles :**
- `read` : Lecture
- `write` : Création/Modification
- `delete` : Suppression

### Variables CSS personnalisables

Les couleurs et le thème sont centralisés dans `assets/css/style.css` :

```css
:root {
    /* Couleurs principales */
    --primary: #667eea;
    --primary-dark: #764ba2;
    --secondary: #4facfe;
    --success: #10b981;
    --danger: #ef4444;
    --warning: #f59e0b;

    /* Gradients */
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
```

Pour personnaliser le thème, modifiez ces variables.

---

## 🚀 Exécution

### Démarrage de l'application

#### Environnement local (XAMPP/WAMP)
1. Démarrer Apache et MySQL
2. Accéder à : `http://localhost/PGI-Automobile/`
3. Se connecter avec un compte de test

#### Serveur PHP intégré
```bash
cd PGI-Automobile
php -S localhost:8000
```
Accéder à : `http://localhost:8000/`

### Navigation dans l'application

#### Pages publiques (sans authentification)
- **Accueil** : `accueil.php`
- **Catalogue** : `catalogue.php`
- **Inscription client** : `client-inscription.php`
- **Connexion** : `login.php`

#### Espace administration (après connexion)
- **Dashboard** : `index.php`
- **Modules** : `modules/{nom-module}/`

### Commandes utiles

#### Réinitialiser la base de données
```bash
mysql -u root -p pgi_automobile < sql/database.sql
```

#### Vérifier les erreurs PHP
```bash
# Activer l'affichage des erreurs en développement
# Dans php.ini :
display_errors = On
error_reporting = E_ALL
```

#### Vider le cache du navigateur
Si vous rencontrez des problèmes CSS :
- **Chrome/Edge** : `Ctrl + Shift + R` (Windows) / `Cmd + Shift + R` (Mac)
- **Firefox** : `Ctrl + F5` (Windows) / `Cmd + Shift + R` (Mac)

---

## 📂 Structure du Code

### Arborescence détaillée

```
PGI-Automobile/
│
├── 📁 config/                      # Configuration globale
│   ├── database.php                # Connexion BDD + fonctions utilitaires
│   ├── auth.php                    # Authentification et sessions
│   └── permissions.php             # Gestion des permissions
│
├── 📁 includes/                    # Composants réutilisables
│   ├── header.php                  # Header commun (navigation)
│   ├── header-client.php           # Header pour clients
│   ├── footer.php                  # Footer commun
│   └── sidebar.php                 # Sidebar navigation
│
├── 📁 modules/                     # Modules métier (8 modules)
│   │
│   ├── 📁 admin/                   # Gestion des utilisateurs
│   │   └── utilisateurs.php        # CRUD utilisateurs + permissions
│   │
│   ├── 📁 clients/                 # Gestion des clients
│   │   ├── liste.php               # Liste des clients
│   │   ├── ajouter.php             # Ajouter un client
│   │   ├── modifier.php            # Modifier un client
│   │   └── mes-demandes.php        # Demandes du client connecté
│   │
│   ├── 📁 profil/                  # Profil utilisateur
│   │   └── mon-profil.php          # Modifier son profil
│   │
│   ├── 📁 rh/                      # Ressources Humaines
│   │   ├── liste.php               # Liste du personnel
│   │   ├── ajouter.php             # Ajouter un employé
│   │   ├── modifier.php            # Modifier un employé
│   │   ├── conges.php              # Gestion des congés
│   │   └── paie.php                # Bulletins de paie
│   │
│   ├── 📁 statistiques/            # Statistiques et KPIs
│   │   └── dashboard.php           # Tableau de bord avec stats
│   │
│   ├── 📁 stock/                   # Gestion du stock
│   │   └── inventaire.php          # Inventaire + alertes
│   │
│   ├── 📁 vehicules/               # Gestion des véhicules
│   │   ├── liste.php               # Liste des véhicules
│   │   ├── ajouter.php             # Ajouter un véhicule
│   │   └── modifier.php            # Modifier un véhicule
│   │
│   └── 📁 ventes/                  # Gestion des ventes
│       ├── liste.php               # Liste des ventes
│       ├── nouvelle.php            # Nouvelle vente
│       ├── demandes-liste.php      # Liste des demandes clients
│       └── demandes-detail.php     # Détail d'une demande
│
├── 📁 assets/                      # Ressources statiques
│   │
│   ├── 📁 css/                     # Feuilles de style
│   │   ├── style.css               # Style principal + variables CSS
│   │   ├── public.css              # Pages publiques
│   │   ├── auth.css                # Pages authentification
│   │   ├── catalogue-moderne.css   # Catalogue
│   │   ├── demande.css             # Page demande
│   │   ├── demandes-liste.css      # Liste demandes
│   │   ├── home.css                # Page d'accueil
│   │   └── mes-demandes.css        # Mes demandes client
│   │
│   ├── 📁 js/                      # Scripts JavaScript
│   │   └── main.js                 # Scripts communs
│   │
│   └── 📁 images/                  # Images et assets
│       └── (images des véhicules)
│
├── 📁 sql/                         # Scripts SQL
│   └── database.sql                # Script complet de création BDD
│
├── 📄 *.php                        # Pages publiques (11 fichiers)
│   ├── index.php                   # Redirection vers dashboard
│   ├── accueil.php                 # Page d'accueil publique
│   ├── catalogue.php               # Catalogue des véhicules
│   ├── demande.php                 # Formulaire de demande
│   ├── login.php                   # Connexion
│   ├── logout.php                  # Déconnexion
│   ├── client-inscription.php      # Inscription client
│   ├── dashboard.php               # Dashboard admin
│   └── acces-refuse.php            # Page d'erreur permissions
│
└── 📄 README.md                    # Cette documentation
```

### Conventions de code

#### Nommage
- **Fichiers PHP** : kebab-case (`liste-clients.php`)
- **Variables PHP** : snake_case (`$nom_client`)
- **Classes CSS** : kebab-case (`.card-header`)
- **Variables CSS** : kebab-case (`--primary-color`)

#### Structure des fichiers PHP
```php
<?php
// 1. Imports et configuration
session_start();
require_once 'config/database.php';

// 2. Authentification et permissions
requireAuth();
requirePermission('module', 'action');

// 3. Traitement des données (POST, GET)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement
}

// 4. Requêtes SQL
$stmt = $pdo->prepare("SELECT ...");
$stmt->execute();
$data = $stmt->fetchAll();

// 5. Configuration page
$page_title = "Titre de la page";
$additional_css = ['assets/css/custom.css'];

// 6. Inclusion du header
include 'includes/header.php';
?>

<!-- 7. HTML de la page -->
<div class="container">
    <!-- Contenu -->
</div>

<?php
// 8. Inclusion du footer
include 'includes/footer.php';
?>
```

#### Sécurité
**Toujours échapper les sorties :**
```php
// ✅ BON
echo htmlspecialchars($user_input);

// ❌ MAUVAIS
echo $user_input;
```

**Toujours utiliser des requêtes préparées :**
```php
// ✅ BON
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);

// ❌ MAUVAIS
$query = "SELECT * FROM users WHERE id = $user_id";
```

---

## 🖥️ Utilisation

### Connexion à l'application

1. Accéder à : `http://localhost/PGI-Automobile/login.php`
2. Saisir identifiants :
   - **Email** : `admin@pgi-auto.com`
   - **Mot de passe** : `password123`
3. Cliquer sur "Se connecter"

### Parcours type : Administrateur

#### 1. Consulter le dashboard
- Affichage des KPIs principaux
- Statistiques de ventes
- Alertes et notifications

#### 2. Ajouter un véhicule
1. `Modules > Véhicules > Ajouter`
2. Remplir le formulaire (marque, modèle, prix, etc.)
3. Upload d'une photo (optionnel)
4. Enregistrer

#### 3. Enregistrer une vente
1. `Modules > Ventes > Nouvelle vente`
2. Sélectionner le véhicule
3. Sélectionner le client
4. Saisir le prix de vente
5. La marge est calculée automatiquement
6. Valider

#### 4. Gérer les demandes clients
1. `Modules > Ventes > Demandes d'achat`
2. Voir toutes les demandes avec filtres par statut
3. Cliquer sur "Détails" pour traiter une demande
4. Changer le statut (en cours, acceptée, refusée)

### Parcours type : Client

#### 1. S'inscrire
1. Cliquer sur "Créer un compte client"
2. Remplir le formulaire
3. Se connecter

#### 2. Consulter le catalogue
1. Page `catalogue.php`
2. Filtrer par type, carburant, prix
3. Voir les détails d'un véhicule

#### 3. Faire une demande d'achat
1. Cliquer sur "Demander ce véhicule"
2. Remplir le formulaire (téléphone, message)
3. Envoyer la demande

#### 4. Suivre ses demandes
1. `Mes demandes` dans le menu
2. Voir l'historique avec statuts

### Parcours type : Vendeur

#### 1. Consulter les demandes clients
1. Dashboard > Demandes en attente
2. Traiter les demandes

#### 2. Créer une vente
1. `Nouvelle vente`
2. Associer client + véhicule
3. Enregistrer

### Captures d'écran (exemples)

```
┌──────────────────────────────────────────────┐
│  📊 Dashboard Administrateur                 │
├──────────────────────────────────────────────┤
│  ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐    │
│  │ 42   │  │ 15K€ │  │ 28   │  │ 3.2K │    │
│  │Ventes│  │  CA  │  │Stock │  │Marge │    │
│  └──────┘  └──────┘  └──────┘  └──────┘    │
│                                              │
│  📈 Graphique évolution mensuelle            │
│  🏆 Top 5 marques vendues                    │
└──────────────────────────────────────────────┘
```

---

## 🔒 Sécurité

### Mesures de sécurité implémentées

#### 1. Protection contre les injections SQL
- ✅ **PDO avec requêtes préparées** sur 100% des requêtes
- ✅ Pas de concaténation de variables dans les requêtes
- ✅ Validation des types (intval, trim, etc.)

```php
// Exemple
$stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
$stmt->execute([intval($id)]);
```

#### 2. Protection XSS (Cross-Site Scripting)
- ✅ **htmlspecialchars()** sur toutes les sorties utilisateur
- ✅ Encodage UTF-8
- ✅ Flags ENT_QUOTES

```php
// Exemple
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
```

#### 3. Gestion des sessions
- ✅ Sessions PHP sécurisées
- ✅ Vérification de l'authentification sur chaque page
- ✅ Timeout de session
- ✅ Logs de connexion

#### 4. Système de permissions
- ✅ Contrôle d'accès par rôle (RBAC)
- ✅ Vérification des permissions avant chaque action
- ✅ Redirection si accès refusé

```php
requirePermission('vehicules', 'write');
```

#### 5. Validation des données
- ✅ Validation côté serveur (PHP)
- ✅ Validation côté client (HTML5 + JS)
- ✅ Sanitisation des entrées

### Recommandations pour la production

#### Configuration PHP
```ini
# php.ini (production)
display_errors = Off
log_errors = On
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT

session.cookie_httponly = 1
session.cookie_secure = 1  # Si HTTPS
session.use_strict_mode = 1
```

#### Sécurisation base de données
```sql
-- Créer un utilisateur dédié (ne PAS utiliser root)
CREATE USER 'pgi_user'@'localhost' IDENTIFIED BY 'mot_de_passe_fort';
GRANT SELECT, INSERT, UPDATE, DELETE ON pgi_automobile.* TO 'pgi_user'@'localhost';
FLUSH PRIVILEGES;
```

#### HTTPS obligatoire
```apache
# .htaccess (redirection HTTPS)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

#### Headers de sécurité
```php
// À ajouter dans config/database.php
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
```

#### Changer les mots de passe par défaut
```sql
-- Changer immédiatement les mots de passe de test
UPDATE utilisateurs SET mot_de_passe = PASSWORD('nouveau_mdp_fort') WHERE email = 'admin@pgi-auto.com';
```

### Checklist de sécurité

- [ ] Mots de passe de test changés
- [ ] Utilisateur MySQL dédié (pas root)
- [ ] HTTPS activé
- [ ] Headers de sécurité configurés
- [ ] Logs d'erreurs activés
- [ ] Sauvegardes automatiques BDD
- [ ] Firewall configuré
- [ ] Accès SSH sécurisé (clé + port non-standard)

---

## 🚀 Améliorations Futures

### Fonctionnalités prioritaires

#### Court terme (1-3 mois)
- [ ] **Système de notifications** (email, SMS)
  - Alertes stock faible
  - Notifications nouvelles demandes
  - Rappels congés validés

- [ ] **Export de données** (Excel, PDF)
  - Export catalogue
  - Export ventes
  - Bulletins de paie PDF

- [ ] **Recherche avancée** avec filtres multiples

- [ ] **Upload multiple d'images** pour véhicules

- [ ] **Historique des modifications** (logs audit)

#### Moyen terme (3-6 mois)
- [ ] **API REST** pour intégrations tierces

- [ ] **Dashboard en temps réel** (WebSockets)

- [ ] **Système de réservation** en ligne pour clients

- [ ] **Chat en direct** client-vendeur

- [ ] **Calendrier partagé** (rendez-vous essais, livraisons)

- [ ] **Module de facturation** automatique

- [ ] **Gestion documentaire** (contrats, assurances)

#### Long terme (6-12 mois)
- [ ] **Application mobile** (React Native / Flutter)

- [ ] **Intelligence artificielle**
  - Prédiction des ventes
  - Recommandations clients
  - Détection fraudes

- [ ] **Multi-concession** (plusieurs sites)

- [ ] **Marketplace** (ventes entre concessions)

- [ ] **Programme de fidélité** clients

- [ ] **CRM avancé** (segmentation, campagnes marketing)

### Améliorations techniques

#### Performance
- [ ] Mise en cache (Redis / Memcached)
- [ ] Lazy loading des images
- [ ] Minification CSS/JS
- [ ] CDN pour assets statiques
- [ ] Optimisation requêtes SQL (indexes)

#### Architecture
- [ ] Migration vers framework PHP (Laravel / Symfony)
- [ ] Séparation Frontend/Backend (API REST)
- [ ] Tests unitaires et d'intégration
- [ ] CI/CD (GitHub Actions / GitLab CI)
- [ ] Containerisation (Docker)

#### Sécurité
- [ ] Authentification à deux facteurs (2FA)
- [ ] OAuth 2.0 (connexion Google, Facebook)
- [ ] Audit de sécurité complet
- [ ] Certificat SSL wildcard
- [ ] WAF (Web Application Firewall)

#### UX/UI
- [ ] Mode sombre
- [ ] Accessibilité WCAG 2.1
- [ ] Progressive Web App (PWA)
- [ ] Traductions (i18n)
- [ ] Onboarding guidé

---

## 📊 Statistiques du Projet

| Métrique | Valeur |
|----------|--------|
| **Lignes de code** | ~5000+ lignes PHP |
| **Fichiers PHP** | 43 fichiers |
| **Modules** | 8 modules métier |
| **Pages** | 30+ pages |
| **Tables BDD** | 10 tables |
| **Fichiers CSS** | 7 fichiers |
| **Rôles utilisateurs** | 6 rôles |
| **Permissions** | 10 modules × 3 actions = 30 permissions |

---

## 📞 Support et Contribution

### Signaler un bug

Si vous trouvez un bug, créez une issue GitHub avec :
1. **Titre descriptif**
2. **Étapes pour reproduire**
3. **Comportement attendu vs observé**
4. **Captures d'écran** (si pertinent)
5. **Environnement** (OS, PHP version, navigateur)

### Proposer une amélioration

1. Fork le repository
2. Créer une branche : `git checkout -b feature/ma-fonctionnalite`
3. Commit : `git commit -m "Ajout de ma fonctionnalité"`
4. Push : `git push origin feature/ma-fonctionnalite`
5. Créer une Pull Request

### Normes de contribution

- **Code propre** : PSR-12 pour PHP
- **Documentation** : Commenter le code
- **Tests** : Ajouter des tests si possible
- **Commits** : Messages clairs et en français

---

## 📜 Licence

Ce projet est sous licence **propriétaire**. Tous droits réservés.

**© 2025 PGI Automobile. Utilisation commerciale interdite sans autorisation.**

---

## 🙏 Remerciements

- **Développement** : Équipe PGI Automobile
- **Design** : Basé sur les meilleures pratiques UX/UI
- **Technologies** : Communauté open-source PHP/MySQL

---

## 📚 Ressources Utiles

### Documentation officielle
- [PHP Manual](https://www.php.net/manual/fr/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [MDN Web Docs](https://developer.mozilla.org/)

### Tutoriels
- [PHP Best Practices](https://phptherightway.com/)
- [SQL Injection Prevention](https://owasp.org/www-community/attacks/SQL_Injection)
- [XSS Prevention](https://owasp.org/www-community/attacks/xss/)

### Outils recommandés
- **IDE** : PHPStorm, VS Code
- **BDD** : phpMyAdmin, MySQL Workbench
- **API Testing** : Postman, Insomnia
- **Git GUI** : GitKraken, SourceTree

---

**Dernière mise à jour** : 14 novembre 2025
**Version** : 1.0.0
**Statut** : Production Ready ✅

---

<div align="center">
    <p>Développé avec ❤️ par l'équipe PGI Automobile</p>
    <p>
        <a href="#-table-des-matières">⬆️ Retour en haut</a>
    </p>
</div>
