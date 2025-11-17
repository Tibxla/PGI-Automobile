# Cahier des Charges - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile (Projet Académique)
**Version:** 1.0 (Projet Académique)
**Date:** Novembre 2025
**Auteurs:** Thibaud THOMAS-LAMOTTE & Melissa BENZIDANE
**Contexte:** Projet de L3 - Période du 27/10/2025 au 17/11/2025 (3 semaines)

---

## 1. Contexte et Enjeux du Projet

### 1.1 Contexte Académique

Ce projet a été réalisé dans le cadre d'un devoir de Licence 3 sur une période de **3 semaines** (du 27 octobre au 17 novembre 2025) par **Thibaud THOMAS-LAMOTTE** et **Melissa BENZIDANE**.

**Objectif pédagogique** : Concevoir et développer un système de gestion intégré pour démontrer nos compétences en :
- Développement web (PHP/MySQL/HTML/CSS)
- Modélisation de bases de données
- Architecture logicielle
- Gestion de projet

### 1.2 Problématiques Identifiées

Les concessions automobiles rencontrent généralement :
- **Dispersion des données** : fichiers Excel, logiciels métier non connectés
- **Manque de visibilité** : difficulté à suivre la performance commerciale
- **Gestion RH manuelle** : paie, congés, suivi du personnel fragmentés
- **Expérience client limitée** : absence de portail en ligne

### 1.3 Enjeux du Système

Le PGI Automobile répond aux enjeux suivants :
- **Centralisation** : une base de données unique pour toutes les opérations
- **Automatisation** : calculs automatiques (marges, paie, statistiques)
- **Interface moderne** : design responsive et ergonomique
- **Sécurité** : authentification et contrôle d'accès par rôles

---

## 2. Objectifs Fonctionnels du PGI

### 2.1 Objectif Général

Développer un progiciel web complet permettant de gérer l'ensemble des activités d'une concession automobile : gestion commerciale, stocks, ressources humaines, comptabilité et relation client.

### 2.2 Objectifs Spécifiques

| Domaine | Objectif |
|---------|----------|
| **Gestion Commerciale** | Gérer le cycle de vente complet : véhicules → clients → ventes → facturation |
| **Gestion des Stocks** | Suivre en temps réel le stock, alerter sur véhicules longue durée, calculer taux de rotation |
| **Ressources Humaines** | Gérer le personnel, congés, bulletins de paie avec calcul automatisé |
| **Relation Client** | Offrir un portail clients pour consultation catalogue et demandes d'achat en ligne |
| **Statistiques & Pilotage** | Fournir des KPI temps réel (CA, marges, top clients/marques, évolution mensuelle) |
| **Administration** | Gérer utilisateurs, rôles, permissions et logs de sécurité |

---

## 3. Périmètre du Projet

### 3.1 Modules Inclus

Le PGI Automobile intègre **8 modules fonctionnels** :

#### Module 1 : Gestion des Véhicules
- CRUD complet (Create, Read, Update, Delete)
- Caractéristiques : marque, modèle, année, type, carburant, kilométrage, immatriculation
- Gestion financière : prix achat, prix vente, calcul marge automatique
- Statuts : en stock, vendu, réservé
- Upload d'images
- Filtres avancés (type, carburant, prix, recherche textuelle)

#### Module 2 : Gestion des Ventes
- Enregistrement des ventes avec association véhicule-client
- Modes de paiement : comptant, crédit, leasing
- Mise à jour automatique du statut véhicule (vendu)
- Historique complet des transactions
- Génération de factures

#### Module 3 : Gestion des Demandes d'Achat
- Formulaire en ligne pour clients (connectés ou prospects)
- Workflow de traitement : en attente → en cours → acceptée/refusée → finalisée
- Notes privées pour gestionnaires
- Traçabilité (qui traite, quand)

#### Module 4 : Gestion des Clients
- Base de données clients (BtoC)
- Fiche complète : identité, coordonnées, historique achats
- Espace client sécurisé pour suivi demandes

#### Module 5 : Ressources Humaines
- Gestion du personnel (employés actifs, en congé, inactifs)
- **Gestion des congés** : CP, RTT, maladie avec workflow d'approbation
- **Bulletins de paie** : salaire base, primes, déductions, net à payer
- Calcul masse salariale totale

#### Module 6 : Stock & Inventaire
- Vue d'ensemble temps réel du stock
- Répartition par type de véhicule et carburant
- Valeur totale du stock et marge potentielle
- **Alertes** : véhicules en stock depuis plus de 6 mois
- Calcul taux de rotation

#### Module 7 : Statistiques & Tableaux de Bord
- KPI année en cours : nombre de ventes, chiffre d'affaires, panier moyen, marge totale
- Évolution mensuelle sur 6 mois
- Top 5 marques vendues
- Top 5 clients (volume d'achats)
- Performance commerciale et rotation stock

#### Module 8 : Administration Système
- Gestion des utilisateurs (CRUD)
- Système de rôles (6 rôles : admin, vendeur, gestionnaire stock, comptable, RH, client)
- Permissions granulaires (module + action : create/read/update/delete)
- Logs de connexion (IP, user agent, timestamp)
- Toggle statut utilisateur (actif/inactif/suspendu)

### 3.2 Modules Exclus (Hors Périmètre Initial)

- **Comptabilité avancée** : écritures comptables, grand livre, plan comptable complet
- **SAV/Atelier** : gestion des réparations, pièces détachées, interventions techniques
- **Marketing/CRM** : campagnes emailing, scoring clients, segmentation avancée
- **Fournisseurs** : gestion avancée des approvisionnements (table créée mais module non développé)
- **Gestion documentaire** : GED pour contrats, assurances, certificats
- **Multi-établissements** : gestion de plusieurs concessions/sites

> **Note** : Ces modules pourront être intégrés dans les versions futures selon priorités métier.

---

## 4. Contraintes Techniques

### 4.1 Technologies Imposées

| Composant | Technologie | Version Minimum |
|-----------|-------------|-----------------|
| **Backend** | PHP | 7.4+ |
| **Base de données** | MySQL | 5.7+ ou 8.0+ |
| **Serveur web** | Apache / Nginx | Dernière stable |
| **Frontend** | HTML5, CSS3, JavaScript | Standards modernes |

**Rationale** :
- PHP natif (sans framework) pour simplicité déploiement et maintenabilité
- MySQL pour robustesse et compatibilité hébergements mutualisés
- Pas de dépendances npm/composer pour faciliter installation

### 4.2 Exigences Techniques

#### Sécurité
- **PDO avec requêtes préparées** : 100% des accès base de données
- **Protection XSS** : `htmlspecialchars()` sur toutes les sorties utilisateur
- **Hash mots de passe** : bcrypt via `password_hash()`
- **Sessions PHP sécurisées** : authentification robuste
- **RBAC** : Role-Based Access Control avec permissions granulaires
- **Logs d'audit** : traçabilité des connexions

#### Performance
- Chargement page < 2 secondes (environnement standard)
- Support jusqu'à 10 000 véhicules en base
- Optimisation requêtes SQL (indexes, jointures)
- Responsive design (mobile-first)

#### Compatibilité
- Navigateurs modernes : Chrome 90+, Firefox 88+, Safari 14+, Edge 90+
- Responsive : desktop (1920px), tablette (768px), mobile (375px)
- Affichage adaptatif (pas d'application mobile native)

### 4.3 Contraintes d'Hébergement

- **Environnement mutualisé** compatible (pas de VPS obligatoire)
- **Stockage** : ~500 Mo (code + base + images)
- **Base de données** : MySQL accessible via PDO
- **PHP** : version 7.4 minimum, extensions requises : pdo_mysql, session, hash
- **HTTPS** : obligatoire en production (certificat SSL)

---

## 5. Contraintes du Projet Académique

### 5.1 Équipe Projet

| Rôle | Responsable | Responsabilités |
|------|-------------|-----------------|
| **Développeur Full-Stack** | Thibaud THOMAS-LAMOTTE | Backend PHP, Base de données MySQL, Architecture |
| **Développeur Full-Stack** | Melissa BENZIDANE | Frontend HTML/CSS/JS, Design, Tests |
| **Gestion de projet** | Les deux étudiants | Planning, coordination, documentation |

### 5.2 Contraintes Temporelles

- **Durée totale** : **3 semaines** (21 jours calendaires)
- **Date de début** : 27 octobre 2025
- **Date de fin** : 17 novembre 2025
- **Jalons** :
  - Semaine 1 (27/10-03/11) : Conception + Setup + Modules de base
  - Semaine 2 (04/11-10/11) : Développement modules principaux
  - Semaine 3 (11/11-17/11) : Finalisation + Tests + Documentation

### 5.3 Compétences Mobilisées

**Compétences techniques développées** :
- PHP natif (sans framework)
- MySQL (modélisation, requêtes, optimisation)
- HTML5/CSS3 (Flexbox, Grid, responsive design)
- JavaScript vanilla
- Sécurité web (PDO, bcrypt, RBAC)
- Git pour le versioning

**Compétences métier appliquées** :
- Analyse des besoins métier
- Modélisation de données (MCD/MLD)
- Architecture logicielle
- Gestion de projet agile

---

## 6. Acteurs du Système

### 6.1 Utilisateurs Internes (Employés)

| Rôle | Description | Effectif Type |
|------|-------------|---------------|
| **Administrateur** | Accès complet, gestion utilisateurs/permissions, supervision | 1-2 |
| **Vendeur** | Gestion clients, ventes, demandes d'achat, consultation véhicules | 3-10 |
| **Gestionnaire Stock** | Gestion véhicules (CRUD), inventaire, alertes | 1-2 |
| **Comptable** | Consultation ventes, statistiques, export données | 1-2 |
| **Responsable RH** | Gestion personnel, congés, bulletins de paie | 1 |

### 6.2 Utilisateurs Externes

| Rôle | Description |
|------|-------------|
| **Client** | Consultation catalogue, demande d'achat en ligne, suivi demandes |
| **Visiteur** | Consultation catalogue public (sans compte) |

### 6.3 Matrice Permissions par Rôle

| Module | Admin | Vendeur | Gestionnaire Stock | Comptable | RH | Client |
|--------|-------|---------|-------------------|-----------|-------|--------|
| **Véhicules** | CRUD | R | CRUD | - | - | - |
| **Clients** | CRUD | CRUD | - | - | - | - |
| **Ventes** | CRUD | CRUD | - | R | - | - |
| **Demandes** | CRUD | RU | - | - | - | CR |
| **RH** | CRUD | - | - | - | CRUD | - |
| **Congés** | CRUD | - | - | - | CRUD | - |
| **Paie** | CRUD | - | - | - | CRUD | - |
| **Stock** | R | - | RU | - | - | - |
| **Statistiques** | R | R | - | R | - | - |
| **Administration** | CRUD | - | - | - | - | - |
| **Catalogue** | R | R | R | - | - | R |

**Légende** : C = Create, R = Read, U = Update, D = Delete

---

## 7. Règles de Gestion Métier

### 7.1 Gestion des Véhicules

| ID | Règle |
|----|-------|
| RG-VH-01 | Un véhicule possède un statut unique : stock, vendu ou réservé |
| RG-VH-02 | Un véhicule vendu ne peut plus être modifié (sauf par admin) |
| RG-VH-03 | La marge est calculée automatiquement : Prix Vente - Prix Achat |
| RG-VH-04 | L'immatriculation doit être unique dans le système |
| RG-VH-05 | Les types autorisés sont : berline, SUV, sportive, utilitaire, citadine |
| RG-VH-06 | Les carburants autorisés sont : essence, diesel, électrique, hybride |

### 7.2 Gestion des Ventes

| ID | Règle |
|----|-------|
| RG-VT-01 | Seuls les véhicules en statut "stock" ou "réservé" peuvent être vendus |
| RG-VT-02 | À la validation d'une vente, le véhicule passe automatiquement en statut "vendu" |
| RG-VT-03 | Le prix de vente enregistré peut différer du prix catalogue (négociation) |
| RG-VT-04 | Modes de paiement autorisés : comptant, crédit, leasing |
| RG-VT-05 | La marge de la vente est stockée en base pour analyses statistiques |

### 7.3 Gestion des Demandes d'Achat

| ID | Règle |
|----|-------|
| RG-DA-01 | Seuls les clients (connectés) et visiteurs peuvent créer des demandes |
| RG-DA-02 | Les employés ne peuvent pas créer de demandes d'achat |
| RG-DA-03 | Statuts possibles : en_attente, en_cours, acceptée, refusée, finalisée |
| RG-DA-04 | Les notes gestionnaire ne sont visibles que par vendeurs/admin |
| RG-DA-05 | Une demande finalisée ne peut plus être modifiée |

### 7.4 Gestion RH

| ID | Règle |
|----|-------|
| RG-RH-01 | Un bulletin de paie est lié à un employé et un mois de référence |
| RG-RH-02 | Net à payer = Salaire base + Primes - Déductions |
| RG-RH-03 | Les congés doivent être approuvés par un responsable RH ou admin |
| RG-RH-04 | Types de congés : CP, RTT, Maladie |
| RG-RH-05 | Statuts employés : actif, congé, inactif |

### 7.5 Gestion du Stock

| ID | Règle |
|----|-------|
| RG-ST-01 | Alerte si véhicule en stock > 6 mois (rotation faible) |
| RG-ST-02 | Valeur stock = Somme des prix d'achat des véhicules en stock |
| RG-ST-03 | Marge potentielle = Somme (Prix vente - Prix achat) des véhicules en stock |
| RG-ST-04 | Taux de rotation = Ventes / Stock moyen |

### 7.6 Sécurité et Permissions

| ID | Règle |
|----|-------|
| RG-SEC-01 | Tous les mots de passe sont hashés en bcrypt |
| RG-SEC-02 | Les permissions sont vérifiées à chaque accès module/action |
| RG-SEC-03 | Toutes les connexions sont loggées (IP, user agent, timestamp) |
| RG-SEC-04 | Un utilisateur inactif ne peut se connecter |
| RG-SEC-05 | Seul l'admin peut créer, modifier, supprimer des utilisateurs |

---

## 8. Critères de Réussite du Projet

### 8.1 Critères Fonctionnels

| Critère | Objectif |
|---------|----------|
| **Complétude** | Les 8 modules fonctionnels développés et opérationnels |
| **Précision calculs** | Marges, paie, statistiques calculées correctement |
| **Design** | Interface moderne et responsive |
| **Navigation** | Ergonomie intuitive et cohérente |

### 8.2 Critères Techniques

| Critère | Objectif |
|---------|----------|
| **Architecture** | Code structuré, modulaire et maintenable |
| **Sécurité** | PDO, bcrypt, RBAC, protection XSS |
| **Performance** | Temps de chargement < 3 secondes |
| **Qualité code** | Code commenté et documentation complète |

### 8.3 Critères Académiques

| Critère | Objectif |
|---------|----------|
| **Documentation technique** | Livrables complets et professionnels |
| **Respect du planning** | Livraison dans les 3 semaines |
| **Fonctionnalités** | Toutes les fonctionnalités clés implémentées |
| **Démonstration** | Capacité à présenter et expliquer le système |

---

## 9. Livrables du Projet

| Livrable | Format |
|----------|--------|
| **Code source complet** | Repository Git |
| **Base de données** | Script SQL avec données de test |
| **Documentation technique** | 20 livrables Markdown (7 phases SI) |
| **Guide d'installation** | Markdown |
| **Manuel utilisateur** | Markdown |
| **Présentation du projet** | Support de soutenance |

---

## 10. Risques et Contraintes

### 10.1 Contraintes du Projet

- **Délai très court** : 3 semaines pour 8 modules fonctionnels
- **Équipe réduite** : 2 étudiants polyvalents
- **Absence de framework** : développement PHP natif
- **Contraintes académiques** : respect du cahier des charges pédagogique

### 10.2 Risques Identifiés

| Risque | Mitigation |
|--------|------------|
| **Manque de temps** | Priorisation des fonctionnalités essentielles |
| **Complexité technique** | Démarrage rapide, prototypage itératif |
| **Bugs** | Tests réguliers, validation continue |
| **Charge de travail** | Répartition équitable, entraide |

---

## Annexes

### A. Glossaire

- **PGI** : Progiciel de Gestion Intégré (équivalent français d'ERP - Enterprise Resource Planning)
- **CRUD** : Create, Read, Update, Delete (opérations de base sur données)
- **RBAC** : Role-Based Access Control (contrôle d'accès basé sur les rôles)
- **KPI** : Key Performance Indicator (indicateur clé de performance)
- **CA** : Chiffre d'Affaires
- **PDO** : PHP Data Objects (extension PHP pour accès base de données)
- **XSS** : Cross-Site Scripting (vulnérabilité web)

### B. Références

- Documentation PHP 8.x : https://www.php.net/
- OWASP Top 10 : https://owasp.org/www-project-top-ten/
- PSR-12 Coding Standard : https://www.php-fig.org/psr/psr-12/

---

**Fin du document**
