# Cahier des Charges - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile
**Version:** 1.0
**Date:** Novembre 2025
**Statut:** Production Ready

---

## 1. Contexte et Enjeux de l'Organisation

### 1.1 Présentation de l'Entreprise

Le secteur de la distribution automobile fait face à des défis croissants :
- Gestion complexe des stocks multi-marques
- Suivi rigoureux des ventes et marges commerciales
- Exigences réglementaires (traçabilité, conformité)
- Attentes clients en matière de digitalisation
- Coordination des équipes commerciales, administratives et RH

### 1.2 Problématiques Identifiées

Avant la mise en place du PGI Automobile, les concessions rencontrent généralement :
- **Dispersion des données** : fichiers Excel, logiciels métier non connectés
- **Manque de visibilité** : difficulté à suivre en temps réel la performance commerciale
- **Inefficacité opérationnelle** : saisies multiples, erreurs de synchronisation
- **Gestion RH manuelle** : paie, congés, suivi du personnel fragmentés
- **Expérience client limitée** : absence de portail client pour demandes d'achat

### 1.3 Enjeux Stratégiques

Le PGI Automobile répond aux enjeux suivants :
- **Centralisation** : une source unique de vérité pour toutes les données métier
- **Productivité** : automatisation des processus (calcul marges, mise à jour stocks, génération factures)
- **Pilotage** : tableaux de bord en temps réel pour la direction
- **Satisfaction client** : portail en ligne pour consultation catalogue et demandes
- **Conformité** : traçabilité complète via système de logs

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

## 5. Contraintes Organisationnelles et Budgétaires

### 5.1 Équipe Projet

| Rôle | Nombre | Responsabilités |
|------|--------|-----------------|
| **Chef de projet** | 1 | Coordination, planning, relation client |
| **Développeur full-stack** | 2 | Développement PHP/MySQL/CSS/JS |
| **Designer UI/UX** | 1 | Maquettes, charte graphique, ergonomie |
| **Testeur QA** | 1 | Tests fonctionnels, rapports anomalies |
| **Expert métier** | 1 | Validation règles de gestion, recette |

### 5.2 Budget Prévisionnel

| Poste | Montant Estimé |
|-------|----------------|
| Développement (400h × 50€) | 20 000 € |
| Design UI/UX (80h × 45€) | 3 600 € |
| Tests & Recette (60h × 40€) | 2 400 € |
| Gestion de projet (100h × 60€) | 6 000 € |
| **TOTAL Développement** | **32 000 €** |
| Hébergement (1 an) | 300 € |
| Licence SSL | 0 € (Let's Encrypt) |
| Documentation & Formation | 2 000 € |
| **TOTAL Global** | **34 300 €** |

### 5.3 Délais

- **Durée totale** : 4 mois (16 semaines)
- **Date de début** : À définir
- **Jalons** :
  - Mois 1 : Analyse + Conception
  - Mois 2-3 : Développement + Tests
  - Mois 4 : Recette + Déploiement

### 5.4 Ressources et Compétences Requises

**Compétences techniques** :
- Maîtrise PHP orienté objet/procédural
- Expertise MySQL (modélisation, optimisation)
- HTML5/CSS3 moderne (Flexbox, Grid, variables CSS)
- JavaScript vanilla (pas de framework requis)
- Sécurité web (OWASP Top 10)

**Compétences métier** :
- Connaissance secteur automobile (cycle de vente, stock)
- Comptabilité de base (marges, CA)
- Gestion RH (paie, congés)

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

## 8. Critères de Réussite

### 8.1 Critères Fonctionnels

| Critère | Indicateur de Succès |
|---------|----------------------|
| **Complétude** | Les 8 modules sont développés et opérationnels |
| **Précision calculs** | Marges, paie, statistiques calculées correctement (0% d'erreur) |
| **Ergonomie** | Taux de satisfaction utilisateur > 80% (enquête post-formation) |
| **Responsive** | Interface utilisable sur desktop, tablette, mobile |

### 8.2 Critères Techniques

| Critère | Indicateur de Succès |
|---------|----------------------|
| **Performance** | Temps de chargement moyen < 2 secondes |
| **Sécurité** | 0 vulnérabilité critique (audit OWASP) |
| **Disponibilité** | Uptime > 99% hors maintenance planifiée |
| **Qualité code** | Code commenté, PSR-12 respecté, pas de fonction > 100 lignes |

### 8.3 Critères Projet

| Critère | Indicateur de Succès |
|---------|----------------------|
| **Délai** | Livraison dans les 4 mois |
| **Budget** | Respect de l'enveloppe 34 300€ (±10%) |
| **Recette** | PV de recette signé par expert métier |
| **Documentation** | Manuel utilisateur, guide admin, doc technique livrés |
| **Formation** | 100% utilisateurs formés (2 sessions de 4h) |

### 8.4 Critères d'Acceptation Métier

- Capacité à gérer une vente complète en < 5 minutes (saisie + génération facture)
- Accès aux statistiques jour J en temps réel
- Traitement d'une demande client en ligne en < 24h
- Génération bulletin de paie en < 10 minutes par employé
- Export données pour comptabilité externe (CSV)

---

## 9. Livrables Attendus

| Livrable | Format | Destinataire |
|----------|--------|--------------|
| **Code source** | Repository Git | Équipe technique |
| **Base de données** | Script SQL + dump | Administrateur système |
| **Manuel utilisateur** | PDF + Markdown | Utilisateurs finaux |
| **Guide administrateur** | PDF + Markdown | Administrateur système |
| **Documentation technique** | Markdown | Développeurs/mainteneurs |
| **Maquettes/Wireframes** | Figma / PNG | Chef de projet |
| **Jeux de données de test** | SQL | Testeurs / Formation |
| **Guide d'installation** | Markdown | Administrateur système |
| **Rapports de tests** | PDF / Excel | Chef de projet |
| **PV de recette** | PDF signé | Direction / Client |

---

## 10. Contraintes et Risques Identifiés

### 10.1 Contraintes

- **Absence de framework** : développement from scratch, pas de scaffolding
- **Budget limité** : pas de marge pour extensions majeures
- **Équipe réduite** : dépendance forte sur développeurs clés
- **Délai serré** : 4 mois pour 8 modules

### 10.2 Risques Projet

| Risque | Probabilité | Impact | Mitigation |
|--------|-------------|--------|------------|
| Dérive fonctionnelle | Moyenne | Élevé | Geler périmètre après phase analyse |
| Complexité sous-estimée | Moyenne | Élevé | Prototypage rapide en semaine 2 |
| Indisponibilité développeur | Faible | Critique | Documentation code + binômage |
| Bug sécurité critique | Faible | Critique | Audit code + tests de pénétration |
| Incompatibilité navigateur | Faible | Moyen | Tests cross-browser dès maquettes |

---

## 11. Validation et Approbation

| Rôle | Nom | Signature | Date |
|------|-----|-----------|------|
| **Maîtrise d'ouvrage** | | | |
| **Chef de projet** | | | |
| **Expert métier** | | | |
| **Responsable technique** | | | |

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
