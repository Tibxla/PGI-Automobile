# 12. PLAN DE TEST

## Informations du Document

| Élément | Détail |
|---------|--------|
| **Projet** | PGI Automobile - Système de Gestion Intégré |
| **Phase** | PHASE 4 - Développement & Test |
| **Livrable** | Plan de Test |
| **Version** | 1.0 |
| **Date** | 17/11/2025 |
| **Auteur** | Équipe QA PGI Automobile |

---

> **Note:** Ce document a été réalisé dans le cadre d'un projet académique de Licence 3 par **Thibaud THOMAS-LAMOTTE** et **Melissa BENZIDANE** sur la période du **27/10/2025 au 17/11/2025** (3 semaines).

## Table des Matières

1. [Introduction](#1-introduction)
2. [Stratégie de Test](#2-stratégie-de-test)
3. [Périmètre des Tests](#3-périmètre-des-tests)
4. [Types de Tests](#4-types-de-tests)
5. [Cas de Test Détaillés](#5-cas-de-test-détaillés)
6. [Environnements de Test](#6-environnements-de-test)
7. [Critères d'Acceptation](#7-critères-dacceptation)
8. [Planning et Ressources](#8-planning-et-ressources)

---

## 1. Introduction

### 1.1 Objectif

Le présent plan de test définit la stratégie, les procédures et les critères pour valider le bon fonctionnement du système PGI Automobile avant sa mise en production.

### 1.2 Portée

Les tests couvrent :
- ✅ Les 8 modules fonctionnels
- ✅ La sécurité (authentification, permissions, injections)
- ✅ L'intégrité des données
- ✅ La performance et la scalabilité
- ✅ L'ergonomie et l'accessibilité
- ❌ Les tests de charge extrêmes (hors périmètre MVP)

### 1.3 Références

| Document | Version |
|----------|---------|
| Cahier des charges | 1.0 |
| Spécifications fonctionnelles détaillées | 1.0 |
| Spécifications techniques | 1.0 |
| Documentation technique du code | 1.0 |

---

## 2. Stratégie de Test

### 2.1 Approche Globale

**Modèle en V** : Chaque niveau de spécification a son niveau de test correspondant.

```
Cahier des charges ────────────► Tests d'acceptation (UAT)
        │
Specs fonctionnelles ──────────► Tests système
        │
Conception détaillée ──────────► Tests d'intégration
        │
Code ──────────────────────────► Tests unitaires
```

### 2.2 Niveaux de Test

| Niveau | Objectif | Responsable | Couverture |
|--------|----------|-------------|------------|
| **Unitaire** | Valider chaque fonction isolément | Développeurs | 70% du code |
| **Intégration** | Valider les interactions entre modules | Développeurs + QA | Tous les modules |
| **Système** | Valider les scénarios métier complets | QA | 100% des use cases |
| **Acceptation** | Validation client final | Client + QA | Scénarios critiques |

### 2.3 Priorités

**Criticité des fonctionnalités :**

| Priorité | Fonctionnalités | Impact si bug |
|----------|-----------------|---------------|
| **P0 - Bloquant** | Authentification, Enregistrement vente, Calcul marges | Système inutilisable |
| **P1 - Critique** | CRUD véhicules, CRUD clients, Génération paies | Perte de données |
| **P2 - Majeur** | Statistiques, Recherche, Filtres | Fonctionnalité dégradée |
| **P3 - Mineur** | Exports, Graphiques, Notifications | Confort utilisateur |

### 2.4 Critères d'Entrée et de Sortie

**Critères d'entrée (avant tests) :**
- ✅ Code développé et déployé en environnement de test
- ✅ Base de données initialisée avec jeu de données de test
- ✅ Documentation technique disponible
- ✅ Environnements de test opérationnels

**Critères de sortie (fin des tests) :**
- ✅ 100% des tests P0 passés
- ✅ 95% des tests P1 passés
- ✅ 0 bugs bloquants ouverts
- ✅ Maximum 5 bugs critiques ouverts (avec contournement)
- ✅ Validation du client sur UAT

---

## 3. Périmètre des Tests

### 3.1 Modules à Tester

| # | Module | Fonctionnalités Testées | Priorité |
|---|--------|------------------------|----------|
| 1 | **Authentification** | Login, logout, sessions, permissions | P0 |
| 2 | **Véhicules** | CRUD, recherche, filtres, calcul marges | P0 |
| 3 | **Ventes** | Enregistrement vente, facture, transaction ACID | P0 |
| 4 | **Clients** | CRUD, recherche, historique achats | P1 |
| 5 | **Demandes d'achat** | Création, validation, suivi workflow | P1 |
| 6 | **Employés (RH)** | CRUD, génération paies, congés | P1 |
| 7 | **Stock** | Suivi inventaire, alertes, mouvements | P2 |
| 8 | **Statistiques** | Tableaux de bord, KPIs, exports | P2 |
| 9 | **Administration** | Gestion utilisateurs, rôles, logs | P1 |

### 3.2 Aspects Non Fonctionnels

| Aspect | Tests Prévus |
|--------|--------------|
| **Sécurité** | SQL injection, XSS, CSRF, bruteforce, permissions |
| **Performance** | Temps de réponse < 2s, 50 utilisateurs concurrents |
| **Compatibilité** | Chrome 90+, Firefox 88+, Safari 14+, Edge 90+ |
| **Responsive** | Desktop (1920x1080), Tablet (768px), Mobile (375px) |
| **Accessibilité** | WCAG 2.1 AA (contraste, navigation clavier) |

---

## 4. Types de Tests

### 4.1 Tests Unitaires

**Objectif :** Valider chaque fonction PHP isolément.

**Méthode :** Tests manuels via PHPUnit ou tests directs.

**Fonctions Critiques à Tester :**

| Fonction | Fichier | Tests |
|----------|---------|-------|
| `calculerMarge()` | modules/vehicules/vehicules_traitement.php | Prix négatifs, marges nulles, TVA |
| `enregistrerVente()` | modules/ventes/ventes_traitement.php | Transaction rollback, véhicule déjà vendu |
| `genererPaie()` | modules/employes/employes_traitement.php | Heures sup, primes, cotisations |
| `authenticateUser()` | config/auth.php | Mot de passe valide/invalide, compte désactivé |
| `checkPermission()` | config/auth.php | Permissions valides/refusées |

**Exemple de Test :**

```php
// Test : calculerMarge avec prix achat > prix vente
function test_calculer_marge_negative() {
    $result = calculerMarge(15000, 12000);
    assert($result['marge_ht'] == 0, "Marge négative devrait retourner 0");
    assert($result['taux'] == 0, "Taux négatif devrait retourner 0");
}

// Test : enregistrerVente avec véhicule déjà vendu
function test_vente_vehicule_deja_vendu() {
    $result = enregistrerVente([
        'vehicule_id' => 5, // Véhicule avec statut 'vendu'
        'client_id' => 1,
        'prix_vente' => 18000
    ]);
    assert($result === false, "Vente d'un véhicule déjà vendu devrait échouer");
}
```

### 4.2 Tests d'Intégration

**Objectif :** Valider les interactions entre modules et avec la base de données.

**Scénarios :**

| Test ID | Description | Modules Impliqués |
|---------|-------------|-------------------|
| **INT-001** | Ajout véhicule puis création vente | Véhicules + Ventes + Clients |
| **INT-002** | Génération paie avec salaire modifié | Employés + Calculs |
| **INT-003** | Validation demande puis ajout véhicule | Commandes + Véhicules |
| **INT-004** | Suppression client avec historique ventes | Clients + Ventes (FK) |
| **INT-005** | Modification permissions puis accès module | Admin + Auth |

**Exemple Détaillé INT-001 :**

```
ÉTAPES :
1. Ajouter un véhicule (Peugeot 208, 12 000 €, statut "stock")
2. Ajouter un client (Jean Dupont)
3. Enregistrer une vente (véhicule → client, 15 000 €)

VALIDATIONS :
✓ Véhicule créé avec ID auto-incrémenté
✓ Client créé avec ID auto-incrémenté
✓ Vente enregistrée avec FK vers véhicule et client
✓ Statut véhicule passé à "vendu"
✓ Facture créée automatiquement
✓ Transaction ACID respectée (rollback si erreur)
```

### 4.3 Tests Système

**Objectif :** Valider les scénarios métier complets de bout en bout.

**Scénarios Prioritaires :**

#### SYS-001 : Processus de Vente Complet (P0)

```
ACTEUR : Vendeur
PRÉREQUIS : Authentifié avec rôle "Vendeur"

ÉTAPES :
1. Se connecter (login: vendeur@pgi.com / password: Vendeur123!)
2. Accéder au module Véhicules
3. Rechercher un véhicule disponible (statut "stock")
4. Consulter la fiche détaillée (prix, marque, modèle)
5. Accéder au module Ventes > Nouvelle vente
6. Sélectionner le véhicule
7. Rechercher/sélectionner un client existant
8. Saisir le prix de vente : 18 500 €
9. Choisir mode paiement : "Crédit"
10. Valider la vente
11. Vérifier la facture générée
12. Retourner au module Véhicules
13. Vérifier que le véhicule est maintenant "vendu"

RÉSULTAT ATTENDU :
✓ Vente enregistrée en base
✓ Véhicule marqué "vendu"
✓ Facture créée avec numéro unique FACT-2025-XXXXXX
✓ Marge calculée correctement
✓ Message de succès affiché
✓ Redirection vers liste des ventes
```

#### SYS-002 : Génération de Paie (P1)

```
ACTEUR : Directeur
PRÉREQUIS : Authentifié avec rôle "Directeur"

ÉTAPES :
1. Se connecter
2. Accéder au module Employés
3. Sélectionner un employé
4. Cliquer sur "Générer paie"
5. Sélectionner le mois (ex: Novembre 2025)
6. Valider

RÉSULTAT ATTENDU :
✓ Fiche de paie générée avec :
  - Salaire brut (base + heures sup + primes)
  - Cotisations salariales (23%)
  - Salaire net
  - Cotisations patronales (42%)
  - Coût total employeur
✓ Paie enregistrée en base
✓ PDF téléchargeable
```

#### SYS-003 : Gestion des Permissions (P0)

```
ACTEUR : Super Admin
PRÉREQUIS : Authentifié avec rôle "Super Admin"

ÉTAPES :
1. Se connecter
2. Accéder au module Administration > Utilisateurs
3. Créer un nouvel utilisateur "Magasinier"
4. Attribuer le rôle "Magasinier"
5. Se déconnecter
6. Se connecter avec le compte Magasinier
7. Tenter d'accéder au module Ventes

RÉSULTAT ATTENDU :
✓ Utilisateur créé
✓ Rôle attribué correctement
✓ Connexion réussie
✓ Modules visibles : Véhicules, Stock uniquement
✓ Accès refusé au module Ventes (message d'erreur)
✓ Redirection vers tableau de bord
```

### 4.4 Tests de Sécurité

**Objectif :** Valider la robustesse contre les attaques courantes (OWASP Top 10).

| Test ID | Type d'Attaque | Description | Résultat Attendu |
|---------|----------------|-------------|------------------|
| **SEC-001** | SQL Injection | Tenter `' OR '1'='1` dans champ login | ✓ Requête bloquée, pas d'accès |
| **SEC-002** | SQL Injection | Tenter `1; DROP TABLE vehicules--` dans URL ?id= | ✓ Requête préparée protège |
| **SEC-003** | XSS Reflected | Injecter `<script>alert('XSS')</script>` dans recherche | ✓ Code échappé, pas d'exécution |
| **SEC-004** | XSS Stored | Injecter `<img src=x onerror=alert(1)>` dans nom client | ✓ Stocké échappé à l'affichage |
| **SEC-005** | CSRF | Soumettre formulaire vente sans token CSRF | ✓ Requête rejetée |
| **SEC-006** | Brute Force | Tenter 100 logins avec mots de passe aléatoires | ✓ Rate limiting ou captcha |
| **SEC-007** | Session Hijacking | Voler cookie session et tenter connexion | ✓ Session invalidée |
| **SEC-008** | Path Traversal | Tenter `../../../etc/passwd` dans upload fichier | ✓ Accès refusé |
| **SEC-009** | Permissions | Vendeur tente d'accéder /admin/ directement (URL) | ✓ Redirection + erreur |
| **SEC-010** | Password Strength | Créer compte avec password "123456" | ✓ Refusé, complexité requise |

**Procédure SEC-001 - SQL Injection :**

```
ÉTAPES :
1. Aller sur la page de login
2. Dans le champ "Email", saisir : admin' OR '1'='1'--
3. Dans le champ "Password", saisir : n'importe quoi
4. Cliquer sur "Se connecter"

RÉSULTAT ATTENDU :
✓ Connexion échouée
✓ Message "Email ou mot de passe incorrect"
✓ Pas d'accès au système
✓ Log de tentative suspecte enregistré

VÉRIFICATION TECHNIQUE :
- Requête SQL exécutée : SELECT * FROM utilisateurs WHERE email = ? AND ...
- Le caractère ' est traité comme donnée, pas comme SQL
```

### 4.5 Tests de Performance

**Objectif :** Valider que le système répond dans des délais acceptables.

| Test ID | Scénario | Charge | Temps Attendu |
|---------|----------|--------|---------------|
| **PERF-001** | Charger la liste des véhicules (100 résultats) | 1 utilisateur | < 1 seconde |
| **PERF-002** | Enregistrer une vente | 1 utilisateur | < 2 secondes |
| **PERF-003** | Générer tableau de bord statistiques | 1 utilisateur | < 3 secondes |
| **PERF-004** | 10 utilisateurs simultanés consultent véhicules | 10 utilisateurs | < 2 secondes |
| **PERF-005** | 50 utilisateurs simultanés (usage réaliste) | 50 utilisateurs | < 5 secondes |
| **PERF-006** | Recherche avec filtres complexes | 1 utilisateur | < 2 secondes |

**Procédure PERF-005 :**

```
MÉTHODE : Simulation de charge avec Apache JMeter ou similaire

CONFIGURATION :
- 50 threads (utilisateurs virtuels)
- Ramp-up : 10 secondes (5 users/sec)
- Durée : 5 minutes
- Scénario mixte :
  * 40% consultation véhicules
  * 30% consultation statistiques
  * 20% création/modification données
  * 10% ventes

MÉTRIQUES À MESURER :
- Temps de réponse moyen
- Temps de réponse 95e percentile
- Taux d'erreur (< 1%)
- Utilisation CPU serveur (< 80%)
- Utilisation RAM (< 70%)

RÉSULTAT ATTENDU :
✓ Temps réponse moyen < 3 secondes
✓ Temps réponse P95 < 5 secondes
✓ 0% erreurs
✓ Aucun timeout
```

### 4.6 Tests de Compatibilité

**Navigateurs :**

| Navigateur | Version | Résolution | Tests |
|------------|---------|------------|-------|
| Chrome | 90+ | 1920x1080 | Tous scénarios système |
| Firefox | 88+ | 1920x1080 | Scénarios critiques P0 |
| Safari | 14+ | 1920x1080 | Scénarios critiques P0 |
| Edge | 90+ | 1920x1080 | Scénarios critiques P0 |
| Chrome Mobile | Dernière | 375x667 | Navigation, consultation |

**Responsive Design :**

| Device | Résolution | Tests |
|--------|------------|-------|
| Desktop | 1920x1080 | ✓ Tous modules accessibles et fonctionnels |
| Laptop | 1366x768 | ✓ Layout adapté, pas de scroll horizontal |
| Tablet | 768x1024 | ✓ Menu burger, layout colonne unique |
| Mobile | 375x667 | ✓ Touch-friendly, formulaires utilisables |

---

## 5. Cas de Test Détaillés

### 5.1 Module Authentification

#### TC-AUTH-001 : Connexion Réussie

| Champ | Valeur |
|-------|--------|
| **ID** | TC-AUTH-001 |
| **Titre** | Connexion avec identifiants valides |
| **Priorité** | P0 - Bloquant |
| **Prérequis** | Utilisateur existe : admin@pgi.com / Admin123! |

**Étapes :**
1. Aller sur `/login.php`
2. Saisir email : `admin@pgi.com`
3. Saisir password : `Admin123!`
4. Cliquer sur "Se connecter"

**Résultat Attendu :**
- ✓ Redirection vers `/index.php` (tableau de bord)
- ✓ Menu de navigation affiché avec tous les modules
- ✓ Nom utilisateur affiché en haut à droite
- ✓ Rôle "Super Admin" visible
- ✓ Session créée avec `$_SESSION['user_id']`

**Données de Test :**
```
Email: admin@pgi.com
Password: Admin123!
Résultat: SUCCESS
```

#### TC-AUTH-002 : Connexion Échouée - Mot de Passe Incorrect

| Champ | Valeur |
|-------|--------|
| **ID** | TC-AUTH-002 |
| **Priorité** | P0 |

**Étapes :**
1. Aller sur `/login.php`
2. Saisir email : `admin@pgi.com`
3. Saisir password : `MauvaisPassword`
4. Cliquer sur "Se connecter"

**Résultat Attendu :**
- ✓ Reste sur `/login.php`
- ✓ Message d'erreur : "Email ou mot de passe incorrect"
- ✓ Pas de session créée
- ✓ Tentative loggée dans `logs_connexion` avec `success = 0`

#### TC-AUTH-003 : Accès Refusé - Permission Manquante

| Champ | Valeur |
|-------|--------|
| **ID** | TC-AUTH-003 |
| **Priorité** | P0 |

**Étapes :**
1. Se connecter avec vendeur@pgi.com (rôle "Vendeur")
2. Tenter d'accéder directement à `/modules/admin/` (URL dans navigateur)

**Résultat Attendu :**
- ✓ Redirection vers `/index.php`
- ✓ Message d'erreur : "Vous n'avez pas les permissions nécessaires"
- ✓ Pas d'accès au module Admin
- ✓ Vérification : `checkPermission('admin_access')` retourne false

#### TC-AUTH-004 : Déconnexion

| Champ | Valeur |
|-------|--------|
| **ID** | TC-AUTH-004 |
| **Priorité** | P1 |

**Étapes :**
1. Être connecté
2. Cliquer sur "Déconnexion" dans le menu

**Résultat Attendu :**
- ✓ Redirection vers `/login.php`
- ✓ Session détruite (`session_destroy()`)
- ✓ Tenter d'accéder à `/index.php` → redirige vers login
- ✓ Pas de données dans `$_SESSION`

### 5.2 Module Véhicules

#### TC-VEH-001 : Ajouter un Véhicule

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VEH-001 |
| **Priorité** | P0 |
| **Prérequis** | Connecté avec permission `vehicules_create` |

**Étapes :**
1. Accéder à `/modules/vehicules/ajouter.php`
2. Remplir le formulaire :
   - Immatriculation : `AA-123-BB`
   - Marque : `Renault`
   - Modèle : `Clio 5`
   - Année : `2023`
   - Prix achat : `12500.00`
   - Prix vente : `15900.00`
   - Kilométrage : `5000`
   - Carburant : `Essence`
   - Couleur : `Bleu`
3. Cliquer sur "Enregistrer"

**Résultat Attendu :**
- ✓ Redirection vers `/modules/vehicules/index.php`
- ✓ Message succès : "Véhicule ajouté avec succès"
- ✓ Véhicule visible dans la liste avec statut "Stock"
- ✓ Marge calculée automatiquement : 3 400 € (27.2%)
- ✓ En base : 1 ligne insérée dans `vehicules`

**Données de Vérification SQL :**
```sql
SELECT * FROM vehicules WHERE immatriculation = 'AA-123-BB';
-- Doit retourner 1 ligne avec statut = 'stock'
```

#### TC-VEH-002 : Ajouter Véhicule - Immatriculation Déjà Existante

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VEH-002 |
| **Priorité** | P1 |

**Étapes :**
1. Ajouter un premier véhicule avec immatriculation `AA-123-BB` (via TC-VEH-001)
2. Tenter d'ajouter un second véhicule avec la même immatriculation `AA-123-BB`

**Résultat Attendu :**
- ✓ Erreur : "Cette immatriculation existe déjà"
- ✓ Véhicule non enregistré
- ✓ Contrainte UNIQUE respectée en base
- ✓ Redirection vers formulaire avec champs pré-remplis

#### TC-VEH-003 : Modifier un Véhicule

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VEH-003 |
| **Priorité** | P1 |

**Étapes :**
1. Accéder à la liste des véhicules
2. Cliquer sur "Modifier" pour le véhicule AA-123-BB
3. Modifier le prix de vente : `16500.00` (au lieu de 15900)
4. Cliquer sur "Enregistrer"

**Résultat Attendu :**
- ✓ Message succès : "Véhicule modifié"
- ✓ Prix vente mis à jour : 16 500 €
- ✓ Marge recalculée : 4 000 € (32%)
- ✓ Date de modification mise à jour

#### TC-VEH-004 : Supprimer un Véhicule - Avec Vente Liée

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VEH-004 |
| **Priorité** | P1 |

**Étapes :**
1. Créer un véhicule
2. Créer une vente liée à ce véhicule
3. Tenter de supprimer le véhicule

**Résultat Attendu :**
- ✓ Erreur : "Impossible de supprimer : ventes associées"
- ✓ Véhicule non supprimé (protection par FK)
- ✓ Données de vente préservées
- ✓ Contrainte `FOREIGN KEY ON DELETE RESTRICT` respectée

#### TC-VEH-005 : Recherche et Filtres

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VEH-005 |
| **Priorité** | P2 |

**Étapes :**
1. Accéder à `/modules/vehicules/index.php`
2. Appliquer le filtre "Statut : Stock"
3. Appliquer le filtre "Marque : Peugeot"
4. Cliquer sur "Rechercher"

**Résultat Attendu :**
- ✓ Liste filtrée affichée
- ✓ Uniquement véhicules Peugeot en stock
- ✓ Compteur : "X véhicules trouvés"
- ✓ Pagination si > 20 résultats

### 5.3 Module Ventes

#### TC-VNT-001 : Enregistrer une Vente

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VNT-001 |
| **Priorité** | P0 - Bloquant |

**Étapes :**
1. Accéder à `/modules/ventes/nouvelle_vente.php`
2. Sélectionner véhicule : Renault Clio (AA-123-BB, statut "stock")
3. Sélectionner client : Jean Dupont
4. Saisir prix vente : `15900.00`
5. Sélectionner mode paiement : "Crédit"
6. Cliquer sur "Enregistrer la vente"

**Résultat Attendu :**
- ✓ Message succès : "Vente enregistrée avec succès"
- ✓ Vente créée en base avec ID auto-incrémenté
- ✓ Statut véhicule passé de "stock" à "vendu"
- ✓ Facture générée : FACT-2025-000001
- ✓ Transaction ACID : tout ou rien (pas de vente sans facture)
- ✓ Redirection vers détails de la vente

**Vérification SQL :**
```sql
SELECT * FROM ventes WHERE vehicule_id = (SELECT id FROM vehicules WHERE immatriculation = 'AA-123-BB');
SELECT statut FROM vehicules WHERE immatriculation = 'AA-123-BB';
-- statut doit être 'vendu'
SELECT * FROM factures WHERE vente_id = <vente_id>;
```

#### TC-VNT-002 : Vente - Véhicule Déjà Vendu

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VNT-002 |
| **Priorité** | P0 |

**Étapes :**
1. Enregistrer une vente pour véhicule AA-123-BB (via TC-VNT-001)
2. Tenter d'enregistrer une seconde vente pour le même véhicule

**Résultat Attendu :**
- ✓ Erreur : "Ce véhicule n'est plus disponible"
- ✓ Vente refusée
- ✓ Transaction rollback
- ✓ Véhicule reste "vendu" (pas de doublon)

#### TC-VNT-003 : Transaction ACID - Rollback sur Erreur

| Champ | Valeur |
|-------|--------|
| **ID** | TC-VNT-003 |
| **Priorité** | P0 |

**Étapes :**
1. Simuler une erreur lors de la création de facture (ex: contrainte SQL violée)
2. Vérifier l'état de la base

**Résultat Attendu :**
- ✓ Transaction rollback complète
- ✓ Vente NON enregistrée
- ✓ Statut véhicule reste "stock" (pas modifié)
- ✓ Aucune facture créée
- ✓ Message erreur affiché à l'utilisateur

### 5.4 Module Employés (RH)

#### TC-EMP-001 : Générer une Paie

| Champ | Valeur |
|-------|--------|
| **ID** | TC-EMP-001 |
| **Priorité** | P1 |

**Étapes :**
1. Accéder à `/modules/employes/`
2. Sélectionner employé : Marie Martin (Vendeuse)
   - Salaire base : 2 000 €
   - Heures sup : 10h
   - Primes : 200 €
3. Cliquer sur "Générer paie"
4. Sélectionner mois : Novembre 2025
5. Valider

**Résultat Attendu :**
- ✓ Fiche de paie affichée avec :
  - Salaire brut : 2 355 € (2000 + 10*15.50 + 200)
  - Cotisations salariales : 541.65 € (23%)
  - Salaire net : 1 813.35 €
  - Cotisations patronales : 989.10 € (42%)
  - Coût total : 3 344.10 €
- ✓ Paie enregistrée en base
- ✓ PDF téléchargeable

**Vérification SQL :**
```sql
SELECT * FROM paies WHERE employe_id = <id> AND mois = '2025-11';
```

### 5.5 Module Statistiques

#### TC-STAT-001 : Tableau de Bord - KPIs du Mois

| Champ | Valeur |
|-------|--------|
| **ID** | TC-STAT-001 |
| **Priorité** | P2 |

**Étapes :**
1. Accéder à `/modules/statistiques/`
2. Sélectionner période : "Mois en cours"
3. Observer les KPIs

**Résultat Attendu :**
- ✓ Affichage des cartes KPI :
  - Nombre de ventes
  - Chiffre d'affaires
  - Panier moyen
  - Marge totale
  - Taux de marge moyen
  - Stock disponible
  - Valeur du stock
- ✓ Graphiques :
  - Évolution CA (courbe)
  - Répartition par marque (camembert)
  - Top 5 vendeurs (barres)
- ✓ Données calculées en temps réel depuis la base
- ✓ Temps de chargement < 3 secondes

---

## 6. Environnements de Test

### 6.1 Configuration des Environnements

| Environnement | Usage | URL | Base de Données |
|---------------|-------|-----|-----------------|
| **Développement** | Développement actif | http://localhost/pgi | pgi_automobile_dev |
| **Test (QA)** | Tests fonctionnels et intégration | http://test.pgi-auto.local | pgi_automobile_test |
| **Staging** | Tests UAT avec client | http://staging.pgi-auto.com | pgi_automobile_staging |
| **Production** | Environnement final | http://pgi-auto.com | pgi_automobile_prod |

### 6.2 Jeu de Données de Test

**Base de Test avec Données Réalistes :**

```sql
-- 20 véhicules variés (10 en stock, 7 vendus, 3 réservés)
-- 15 clients avec historique d'achats
-- 8 employés (1 par rôle)
-- 25 ventes sur les 6 derniers mois
-- 10 demandes d'achat (5 validées, 3 en attente, 2 refusées)
-- 48 fiches de paie (6 mois × 8 employés)
```

**Script d'Initialisation :**

```bash
# Réinitialiser la base de test
mysql -u root -p pgi_automobile_test < sql/database.sql
mysql -u root -p pgi_automobile_test < sql/test_data.sql
```

### 6.3 Utilisateurs de Test

| Email | Password | Rôle | Permissions |
|-------|----------|------|-------------|
| admin@test.com | Admin123! | Super Admin | Toutes |
| directeur@test.com | Dir123! | Directeur | Toutes sauf admin |
| vendeur@test.com | Vend123! | Vendeur | Véhicules, Ventes, Clients |
| comptable@test.com | Compt123! | Comptable | Lecture seule |
| magasinier@test.com | Mag123! | Magasinier | Véhicules, Stock |

---

## 7. Critères d'Acceptation

### 7.1 Critères Fonctionnels

| Critère | Seuil d'Acceptation | Statut |
|---------|---------------------|--------|
| Tests P0 passés | 100% | ☐ |
| Tests P1 passés | ≥ 95% | ☐ |
| Tests P2 passés | ≥ 90% | ☐ |
| Bugs bloquants | 0 | ☐ |
| Bugs critiques | ≤ 5 (avec contournement) | ☐ |

### 7.2 Critères de Sécurité

| Critère | Seuil | Statut |
|---------|-------|--------|
| Tests SQL Injection | 0 vulnérabilité | ☐ |
| Tests XSS | 0 vulnérabilité | ☐ |
| Tests CSRF | 0 vulnérabilité | ☐ |
| Tests Permissions | 100% respectées | ☐ |
| Passwords hashés | 100% bcrypt | ☐ |

### 7.3 Critères de Performance

| Critère | Seuil | Statut |
|---------|-------|--------|
| Temps réponse pages | < 2 secondes (90% des requêtes) | ☐ |
| Temps réponse dashboard | < 3 secondes | ☐ |
| Support 50 utilisateurs concurrents | < 5 secondes | ☐ |
| Taux d'erreur | < 1% | ☐ |

### 7.4 Critères de Compatibilité

| Critère | Seuil | Statut |
|---------|-------|--------|
| Chrome/Firefox/Safari/Edge | 100% fonctionnel | ☐ |
| Responsive Desktop/Tablet/Mobile | 100% fonctionnel | ☐ |
| Accessibilité WCAG 2.1 AA | Contraste, navigation clavier OK | ☐ |

---

## 8. Planning et Ressources

### 8.1 Planning des Tests

| Phase | Durée | Dates | Responsable |
|-------|-------|-------|-------------|
| **Tests Unitaires** | 5 jours | 18/11 - 22/11/2025 | Développeurs |
| **Tests Intégration** | 3 jours | 25/11 - 27/11/2025 | Dev + QA |
| **Tests Système** | 5 jours | 28/11 - 04/12/2025 | QA |
| **Tests Sécurité** | 2 jours | 05/12 - 06/12/2025 | QA Security |
| **Tests Performance** | 1 jour | 09/12/2025 | QA + DevOps |
| **Tests UAT** | 3 jours | 10/12 - 12/12/2025 | Client + QA |
| **Corrections Bugs** | 3 jours | 13/12 - 17/12/2025 | Développeurs |
| **Re-tests** | 2 jours | 18/12 - 19/12/2025 | QA |
| **Sign-off** | 1 jour | 20/12/2025 | Client |

**Total : 25 jours (5 semaines)**

### 8.2 Ressources

| Rôle | Nom | Allocation | Responsabilités |
|------|-----|------------|-----------------|
| **QA Lead** | Sophie Bernard | 100% | Coordination tests, rapports |
| **QA Tester 1** | Luc Moreau | 100% | Tests fonctionnels |
| **QA Tester 2** | Claire Dubois | 100% | Tests fonctionnels |
| **QA Security** | Marc Lefebvre | 50% | Tests sécurité |
| **Développeur 1** | Thomas Petit | 30% | Tests unitaires, corrections |
| **Développeur 2** | Emma Rousseau | 30% | Tests unitaires, corrections |
| **Product Owner** | Client | 20% | Tests UAT, validation |

### 8.3 Outils de Test

| Outil | Usage |
|-------|-------|
| **Navigateurs** | Chrome, Firefox, Safari, Edge (dernières versions) |
| **Postman** | Tests API et requêtes HTTP |
| **Apache JMeter** | Tests de charge et performance |
| **OWASP ZAP** | Tests de sécurité automatisés |
| **BrowserStack** | Tests multi-navigateurs et devices |
| **Excel/Google Sheets** | Suivi des cas de test |
| **Jira** | Gestion des bugs |
| **MySQL Workbench** | Vérifications base de données |

### 8.4 Gestion des Anomalies

**Classification des Bugs :**

| Sévérité | Définition | Délai Correction |
|----------|------------|------------------|
| **Bloquant** | Système inutilisable | < 24h |
| **Critique** | Fonctionnalité majeure KO | < 48h |
| **Majeur** | Fonctionnalité dégradée | < 1 semaine |
| **Mineur** | Problème cosmétique | Avant release |

**Workflow Bug :**

```
1. QA détecte → Crée ticket Jira (titre, steps, screenshots)
2. QA Lead valide → Assigne sévérité
3. Développeur corrige → Commit + référence ticket
4. QA re-teste → Valide ou rouvre
5. Fermé si OK
```

---

## Conclusion

Ce plan de test garantit une validation complète et rigoureuse du système PGI Automobile avant sa mise en production. Le respect des critères d'acceptation et du planning assurera la qualité et la fiabilité du produit final.

**Points Clés :**
- ✅ 100% des fonctionnalités P0 testées
- ✅ Sécurité validée (OWASP Top 10)
- ✅ Performance conforme (< 2s)
- ✅ UAT avec client final
- ✅ Traçabilité complète (tests ↔ specs)

---

**Document Version :** 1.0
**Dernière mise à jour :** 17/11/2025
**Auteur :** Équipe QA PGI Automobile
