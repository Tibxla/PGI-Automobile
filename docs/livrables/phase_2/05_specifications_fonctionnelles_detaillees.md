# Spécifications Fonctionnelles Détaillées (SFD) - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile
**Version:** 1.0 (Projet Académique)
**Date:** Novembre 2025
**Auteurs:** Thibaud & Melissa
**Contexte:** Projet de L3 - Période du 27/10/2025 au 17/11/2025 (3 semaines)
**Statut:** Validé

---

## 1. Introduction

### 1.1 Objet du Document

Ce document décrit de manière **exhaustive et détaillée** chaque fonctionnalité du PGI Automobile. Pour chaque cas d'utilisation, il précise :
- Les scénarios nominaux (cas standard)
- Les scénarios alternatifs (variantes)
- Les scénarios d'erreur (cas exceptionnels)
- Les enchaînements d'écrans
- Les règles de validation
- Les messages utilisateur

### 1.2 Public Cible

- Équipe de développement (implémentation)
- Testeurs (cas de tests fonctionnels)
- Maîtrise d'ouvrage (validation détaillée)

### 1.3 Conventions

#### Notation des Scénarios

- **[NOMINAL]** : Scénario standard, chemin heureux
- **[ALTERNATIF]** : Variante valide du scénario nominal
- **[ERREUR]** : Cas d'erreur, validation échouée

#### Codes Fonctionnalités

Format : `MODULE-FONCTION-NUMERO`
Exemple : `VEH-CRUD-001` = Véhicules > CRUD > Fonction 001

---

## 2. Module Véhicules - Spécifications Détaillées

### 2.1 VEH-CRUD-001 : Ajouter un Véhicule

#### 2.1.1 Description

Permettre au gestionnaire de stock d'ajouter un nouveau véhicule dans l'inventaire avec toutes ses caractéristiques.

#### 2.1.2 Acteurs

- **Principal** : Gestionnaire Stock
- **Secondaire** : Administrateur

#### 2.1.3 Préconditions

- L'utilisateur est authentifié
- L'utilisateur a la permission `vehicules:create`
- L'utilisateur accède à la page `/modules/vehicules/liste.php`

#### 2.1.4 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Gestionnaire clique sur "Ajouter un véhicule" | Affiche formulaire `/modules/vehicules/ajouter.php` |
| 2 | Gestionnaire remplit le formulaire : | Validation temps réel JavaScript (optionnel) |
| | - Marque : "Peugeot" | |
| | - Modèle : "208 GT Line" | |
| | - Année : 2023 | |
| | - Type : "citadine" | |
| | - Carburant : "essence" | |
| | - Prix d'achat : 15000 | |
| | - Prix de vente : 18500 | |
| | - Kilométrage : 5000 | |
| | - Couleur : "Blanc Nacré" | |
| | - Immatriculation : "AB-123-CD" | |
| | - Statut : "stock" | |
| | - Date d'arrivée : "2023-06-01" | |
| | - Image : (optionnel) upload fichier | |
| 3 | Gestionnaire clique "Enregistrer" | **Validations côté serveur** : |
| | | - Tous champs obligatoires renseignés ✅ |
| | | - Immatriculation unique (requête BDD) ✅ |
| | | - Année entre 1900 et année courante+1 ✅ |
| | | - Prix achat > 0 ✅ |
| | | - Prix vente >= Prix achat (warning si marge négative) ⚠️ |
| | | - Type dans énumération valide ✅ |
| | | - Carburant dans énumération valide ✅ |
| 4 | | **Insertion en base de données** : |
| | | ```sql |
| | | INSERT INTO vehicules (marque, modele, annee, ...) |
| | | VALUES (?, ?, ?, ...) |
| | | ``` |
| 5 | | Upload image si fournie → `/assets/images/vehicules/` |
| 6 | | Redirection vers `/modules/vehicules/liste.php` |
| 7 | | Affichage message succès : |
| | | ✅ "Véhicule ajouté avec succès !" (alerte verte) |

#### 2.1.5 Scénarios Alternatifs

**[ALT-001] Marge Négative (Prix Vente < Prix Achat)**

| Étape | Description |
|-------|-------------|
| 3.1 | Validation détecte : Prix Vente (14000€) < Prix Achat (15000€) |
| 3.2 | Système affiche warning (alerte orange) : |
| | ⚠️ "Attention : marge négative détectée (-1000€). Confirmez-vous ?" |
| 3.3 | Gestionnaire peut : |
| | - Modifier les prix → Retour étape 2 |
| | - Confirmer malgré warning → Continue étape 4 |

**[ALT-002] Image Non Fournie**

| Étape | Description |
|-------|-------------|
| 2.1 | Gestionnaire ne charge pas d'image |
| 5.1 | Système n'effectue pas d'upload |
| 5.2 | Champ `image_url` reste NULL en base |
| 7.1 | Liste véhicules affiche image placeholder par défaut |

#### 2.1.6 Scénarios d'Erreur

**[ERR-001] Immatriculation Déjà Existante**

| Étape | Description |
|-------|-------------|
| 3.1 | Validation détecte : "AB-123-CD" déjà en base (requête `SELECT`) |
| 3.2 | Système bloque insertion |
| 3.3 | Affichage erreur (alerte rouge) : |
| | ❌ "Erreur : Cette immatriculation existe déjà dans le système." |
| 3.4 | Formulaire conserve les données saisies |
| 3.5 | Champ "Immatriculation" surligné en rouge |

**[ERR-002] Champs Obligatoires Manquants**

| Étape | Description |
|-------|-------------|
| 3.1 | Validation détecte : "Modèle" vide |
| 3.2 | Système bloque insertion |
| 3.3 | Affichage erreur (alerte rouge) : |
| | ❌ "Erreur : Veuillez remplir tous les champs obligatoires." |
| 3.4 | Champs manquants surlignés en rouge |

**[ERR-003] Année Invalide**

| Étape | Description |
|-------|-------------|
| 2.1 | Gestionnaire saisit Année : "2030" (futur trop éloigné) |
| 3.1 | Validation détecte : Année > (année courante + 1) |
| 3.2 | Système bloque insertion |
| 3.3 | Affichage erreur : |
| | ❌ "Erreur : L'année doit être comprise entre 1900 et 2026." |

**[ERR-004] Upload Image Échoué**

| Étape | Description |
|-------|-------------|
| 5.1 | Erreur lors de l'upload (fichier > 5 MB ou format invalide) |
| 5.2 | Système enregistre le véhicule SANS image |
| 5.3 | Affichage warning (alerte orange) : |
| | ⚠️ "Véhicule ajouté mais l'image n'a pu être chargée (format ou taille invalide)." |

#### 2.1.7 Post-conditions

- ✅ Nouveau véhicule inséré dans table `vehicules`
- ✅ Statut par défaut : "stock"
- ✅ Champ `created_at` renseigné automatiquement (TIMESTAMP)
- ✅ Véhicule visible dans la liste pour tous utilisateurs ayant `vehicules:read`

#### 2.1.8 Règles de Validation

| Champ | Règle | Message Erreur |
|-------|-------|----------------|
| **Marque** | Requis, max 50 caractères | "Marque obligatoire (max 50 caractères)" |
| **Modèle** | Requis, max 50 caractères | "Modèle obligatoire (max 50 caractères)" |
| **Année** | Requis, entier entre 1900 et (année courante + 1) | "Année invalide (1900 - 2026)" |
| **Type** | Requis, dans [berline, SUV, sportive, utilitaire, citadine] | "Type invalide" |
| **Carburant** | Requis, dans [essence, diesel, electrique, hybride] | "Carburant invalide" |
| **Prix achat** | Requis, décimal > 0 | "Prix d'achat invalide (doit être > 0)" |
| **Prix vente** | Requis, décimal > 0 | "Prix de vente invalide (doit être > 0)" |
| **Kilométrage** | Requis, entier >= 0 | "Kilométrage invalide (>= 0)" |
| **Immatriculation** | Requis, unique, format XX-XXX-XX | "Immatriculation invalide ou déjà existante" |
| **Statut** | Requis, dans [stock, vendu, reserve] | "Statut invalide" |
| **Date arrivée** | Requis, format DATE | "Date d'arrivée invalide" |
| **Image** | Optionnel, formats [jpg, jpeg, png, webp], max 5 MB | "Image invalide (format ou taille)" |

#### 2.1.9 Interfaces

**Écran : Formulaire Ajout Véhicule** (`/modules/vehicules/ajouter.php`)

```
┌─────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile                  Sophie Martin (Gest. Stock) │
├─────────────────────────────────────────────────────────┤
│ Véhicules > Ajouter un véhicule                          │
├─────────────────────────────────────────────────────────┤
│                                                          │
│ Informations Générales                                   │
│ ┌──────────────────┐  ┌──────────────────┐             │
│ │ Marque *         │  │ Modèle *         │             │
│ │ Peugeot          │  │ 208 GT Line      │             │
│ └──────────────────┘  └──────────────────┘             │
│                                                          │
│ ┌────────┐  ┌──────────────┐  ┌──────────────┐         │
│ │ Année *│  │ Type *       │  │ Carburant *  │         │
│ │ 2023   │  │ ▼ citadine   │  │ ▼ essence    │         │
│ └────────┘  └──────────────┘  └──────────────┘         │
│                                                          │
│ Informations Commerciales                                │
│ ┌──────────────────┐  ┌──────────────────┐             │
│ │ Prix d'achat * € │  │ Prix de vente * €│             │
│ │ 15000            │  │ 18500            │             │
│ └──────────────────┘  └──────────────────┘             │
│ Marge : 3500€ (calculée automatiquement)                │
│                                                          │
│ Caractéristiques                                         │
│ ┌────────────────┐  ┌──────────────────┐               │
│ │ Kilométrage *  │  │ Couleur          │               │
│ │ 5000 km        │  │ Blanc Nacré      │               │
│ └────────────────┘  └──────────────────┘               │
│                                                          │
│ ┌──────────────────┐  ┌──────────────┐                 │
│ │ Immatriculation *│  │ Statut *     │                 │
│ │ AB-123-CD        │  │ ▼ stock      │                 │
│ └──────────────────┘  └──────────────┘                 │
│                                                          │
│ ┌──────────────────┐                                    │
│ │ Date d'arrivée * │                                    │
│ │ 2023-06-01       │  (format: YYYY-MM-DD)              │
│ └──────────────────┘                                    │
│                                                          │
│ Image                                                    │
│ ┌──────────────────────────────────────┐                │
│ │ [Choisir un fichier]  Aucun fichier  │                │
│ └──────────────────────────────────────┘                │
│ Formats acceptés : JPG, PNG, WebP (max 5 MB)            │
│                                                          │
│ ┌──────────────┐  ┌──────────┐                          │
│ │ ✓ Enregistrer│  │ Annuler  │                          │
│ └──────────────┘  └──────────┘                          │
└─────────────────────────────────────────────────────────┘
```

---

### 2.2 VEH-CRUD-002 : Consulter Liste Véhicules avec Filtres

#### 2.2.1 Description

Afficher la liste de tous les véhicules avec possibilité de filtrer par type, carburant, statut et recherche textuelle.

#### 2.2.2 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Utilisateur accède à `/modules/vehicules/liste.php` | Requête SQL : |
| | | ```sql |
| | | SELECT * FROM vehicules ORDER BY created_at DESC |
| | | ``` |
| 2 | | Affichage tableau avec colonnes : |
| | | - Image (thumbnail) |
| | | - Marque / Modèle |
| | | - Année |
| | | - Type (badge coloré) |
| | | - Carburant (icône) |
| | | - Prix Achat / Prix Vente |
| | | - Marge (calculée) |
| | | - Statut (badge) |
| | | - Actions (Modifier, Supprimer) |
| 3 | Utilisateur applique filtre Type : "SUV" | Requête SQL dynamique : |
| | | ```sql |
| | | WHERE type = 'SUV' |
| | | ``` |
| 4 | | Tableau mis à jour → Affiche uniquement les SUV |
| 5 | Utilisateur ajoute filtre Carburant : "électrique" | Requête SQL cumulée : |
| | | ```sql |
| | | WHERE type = 'SUV' AND carburant = 'electrique' |
| | | ``` |
| 6 | | Tableau affiche : SUV électriques uniquement |
| 7 | Utilisateur saisit recherche : "Tesla" | Requête SQL cumulée : |
| | | ```sql |
| | | WHERE type = 'SUV' |
| | | AND carburant = 'electrique' |
| | | AND (marque LIKE '%Tesla%' OR modele LIKE '%Tesla%') |
| | | ``` |
| 8 | | Tableau affiche : Tesla Model X (SUV électrique) |

#### 2.2.3 Scénarios Alternatifs

**[ALT-001] Aucun Résultat Trouvé**

| Étape | Description |
|-------|-------------|
| 8.1 | Filtres combinés ne retournent aucun véhicule |
| 8.2 | Affichage message : |
| | ℹ️ "Aucun véhicule ne correspond à vos critères." |
| 8.3 | Suggestion : "Réinitialiser les filtres" (lien cliquable) |

**[ALT-002] Réinitialiser Filtres**

| Étape | Description |
|-------|-------------|
| 1 | Utilisateur clique "Réinitialiser" |
| 2 | Système recharge page sans paramètres GET |
| 3 | Affichage liste complète (sans filtres) |

#### 2.2.4 Règles de Calcul Affichées

| Colonne | Calcul |
|---------|--------|
| **Marge** | Prix Vente - Prix Achat |
| **Couleur Badge Statut** | stock = bleu, vendu = vert, réservé = orange |
| **Tri par défaut** | Date création DESC (plus récents en premier) |

#### 2.2.5 Interface

**Écran : Liste Véhicules** (`/modules/vehicules/liste.php`)

```
┌────────────────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile                         Sophie Martin (Gest. Stock) │
├────────────────────────────────────────────────────────────────────┤
│ Véhicules > Liste (25 véhicules)          [+ Ajouter un véhicule]  │
├────────────────────────────────────────────────────────────────────┤
│ Filtres :                                                           │
│ ┌────────────┐ ┌─────────────┐ ┌──────────┐ ┌──────────────────┐  │
│ │Type: Tous ▼│ │Carb.: Tous ▼│ │Statut: ▼│ │🔍 Rechercher...  │  │
│ └────────────┘ └─────────────┘ └──────────┘ └──────────────────┘  │
│ [Réinitialiser filtres]                                             │
├────────────────────────────────────────────────────────────────────┤
│ Image  │ Véhicule         │ Année │ Type    │ Carb. │ Prix  │ Marge│ Statut │ Actions │
├────────────────────────────────────────────────────────────────────┤
│ [📷]   │ Peugeot 208      │ 2023  │ citad.  │ ⚡    │ 18500 │ +3500│ stock  │ ✏️ 🗑️  │
│ [📷]   │ BMW Série 3      │ 2022  │ berline │ ⛽    │ 35000 │ +5000│ vendu  │ ✏️ 🗑️  │
│ [📷]   │ Tesla Model 3    │ 2023  │ berline │ 🔋    │ 42000 │ +4000│ stock  │ ✏️ 🗑️  │
│ ...                                                                 │
└────────────────────────────────────────────────────────────────────┘
Pagination : [1] 2 3 ... 5
```

---

### 2.3 VEH-CRUD-003 : Modifier un Véhicule

#### 2.3.1 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Gestionnaire clique "✏️ Modifier" sur véhicule ID=5 | Redirection vers `/modules/vehicules/modifier.php?id=5` |
| 2 | | Requête SQL : |
| | | ```sql |
| | | SELECT * FROM vehicules WHERE id = 5 |
| | | ``` |
| 3 | | Affichage formulaire pré-rempli avec données existantes |
| 4 | Gestionnaire modifie Prix Vente : 18500 → 17900 | Validation temps réel (optionnel) |
| 5 | Gestionnaire modifie Kilométrage : 5000 → 8000 | |
| 6 | Gestionnaire clique "Enregistrer" | **Validations côté serveur** (identiques ajout) |
| 7 | | Requête SQL : |
| | | ```sql |
| | | UPDATE vehicules SET |
| | | prix_vente = 17900, |
| | | kilometrage = 8000, |
| | | updated_at = NOW() |
| | | WHERE id = 5 |
| | | ``` |
| 8 | | Redirection vers `/modules/vehicules/liste.php` |
| 9 | | Message succès : |
| | | ✅ "Véhicule modifié avec succès !" |

#### 2.3.2 Scénarios d'Erreur

**[ERR-001] Véhicule Vendu (Protection)**

| Étape | Description |
|-------|-------------|
| 2.1 | Système détecte : véhicule ID=5 a statut "vendu" |
| 2.2 | ET utilisateur n'est pas admin |
| 2.3 | Redirection vers `/modules/vehicules/liste.php` |
| 2.4 | Message erreur : |
| | ❌ "Erreur : Seul un administrateur peut modifier un véhicule vendu." |

**[ERR-002] Véhicule Introuvable**

| Étape | Description |
|-------|-------------|
| 2.1 | Requête SQL avec ID=999 (inexistant) retourne 0 résultat |
| 2.2 | Redirection vers `/modules/vehicules/liste.php` |
| 2.3 | Message erreur : |
| | ❌ "Erreur : Véhicule introuvable." |

---

### 2.4 VEH-CRUD-004 : Supprimer un Véhicule

#### 2.4.1 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Gestionnaire clique "🗑️ Supprimer" sur véhicule ID=5 | Affichage popup confirmation JavaScript : |
| | | "Êtes-vous sûr de vouloir supprimer ce véhicule ?" |
| 2 | Gestionnaire clique "Confirmer" | Requête SQL de vérification : |
| | | ```sql |
| | | SELECT statut FROM vehicules WHERE id = 5 |
| | | ``` |
| 3 | | Système vérifie : statut != "vendu" ✅ |
| 4 | | Requête SQL : |
| | | ```sql |
| | | DELETE FROM vehicules WHERE id = 5 |
| | | ``` |
| 5 | | Rechargement page `/modules/vehicules/liste.php` |
| 6 | | Message succès : |
| | | ✅ "Véhicule supprimé avec succès." |

#### 2.4.2 Scénarios d'Erreur

**[ERR-001] Véhicule Vendu (Intégrité Historique)**

| Étape | Description |
|-------|-------------|
| 3.1 | Système détecte : statut = "vendu" |
| 3.2 | Blocage suppression |
| 3.3 | Message erreur : |
| | ❌ "Erreur : Un véhicule vendu ne peut être supprimé (intégrité historique)." |
| 3.4 | Suggestion : "Vous pouvez archiver le véhicule." |

**[ERR-002] Véhicule Référencé dans Ventes (Contrainte BDD)**

| Étape | Description |
|-------|-------------|
| 4.1 | Tentative DELETE déclenche contrainte FK (foreign key) |
| 4.2 | MySQL retourne erreur : Cannot delete (integrity constraint) |
| 4.3 | Système capture exception PDO |
| 4.4 | Message erreur : |
| | ❌ "Erreur : Ce véhicule ne peut être supprimé car il est référencé dans des ventes." |

---

## 3. Module Ventes - Spécifications Détaillées

### 3.1 VTE-VENTE-001 : Enregistrer une Vente

#### 3.1.1 Description

Enregistrer une transaction de vente complète : association véhicule + client + modalités, avec mise à jour automatique du statut véhicule.

#### 3.1.2 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Vendeur accède à `/modules/ventes/ajouter.php` | Affichage formulaire |
| 2 | Vendeur sélectionne véhicule (dropdown) : "Peugeot 208 (AB-123-CD)" | Liste déroulante alimentée par : |
| | | ```sql |
| | | SELECT id, CONCAT(marque, ' ', modele, ' (', immatriculation, ')') |
| | | FROM vehicules |
| | | WHERE statut IN ('stock', 'reserve') |
| | | ORDER BY marque, modele |
| | | ``` |
| 3 | | Système charge prix véhicule : |
| | | - Prix d'achat : 15000€ |
| | | - Prix de vente catalogue : 18500€ |
| | | - Affichage automatique dans formulaire |
| 4 | Vendeur sélectionne client (dropdown) : "Jean Dupont" | Liste déroulante : |
| | | ```sql |
| | | SELECT id, CONCAT(nom, ' ', prenom, ' (', email, ')') |
| | | FROM clients ORDER BY nom |
| | | ``` |
| 5 | Vendeur saisit prix de vente négocié : 17500€ | Calcul marge automatique : |
| | | Marge = 17500 - 15000 = 2500€ |
| | | Affichage en temps réel |
| 6 | Vendeur sélectionne mode de paiement : "crédit" | |
| 7 | Vendeur saisit date de vente : "2023-08-20" | |
| 8 | Vendeur saisit notes (optionnel) : |
| | "Client satisfait. Reprise ancienne voiture : 3000€" | |
| 9 | Vendeur clique "Enregistrer la vente" | **Validations** : |
| | | - Véhicule sélectionné ✅ |
| | | - Client sélectionné ✅ |
| | | - Prix vente > 0 ✅ |
| | | - Date valide ✅ |
| 10 | | **Transaction SQL** (BEGIN) : |
| | | ```sql |
| | | -- Insertion vente |
| | | INSERT INTO ventes (vehicule_id, client_id, prix_vente, |
| | |   mode_paiement, date_vente, marge, notes) |
| | | VALUES (5, 12, 17500, 'credit', '2023-08-20', 2500, '...'); |
| | | |
| | | -- Mise à jour statut véhicule |
| | | UPDATE vehicules SET statut = 'vendu' WHERE id = 5; |
| | | ``` |
| | | COMMIT |
| 11 | | Redirection vers `/modules/ventes/liste.php` |
| 12 | | Message succès : |
| | | ✅ "Vente enregistrée avec succès ! Le véhicule est maintenant vendu." |
| 13 | | Proposition : |
| | | [📄 Générer la facture] (bouton lien vers `/modules/ventes/facture.php?id=XX`) |

#### 3.1.3 Scénarios Alternatifs

**[ALT-001] Créer un Nouveau Client Directement**

| Étape | Description |
|-------|-------------|
| 4.1 | Vendeur ne trouve pas le client dans la liste |
| 4.2 | Vendeur clique "➕ Créer un nouveau client" (lien modal ou redirection) |
| 4.3 | Système affiche formulaire client (popup ou page dédiée) |
| 4.4 | Vendeur saisit infos client (nom, prénom, email, téléphone) |
| 4.5 | Système insère client en BDD |
| 4.6 | Système sélectionne automatiquement le nouveau client dans dropdown |
| 4.7 | Retour étape 5 (saisie prix) |

**[ALT-002] Marge Négative Acceptée**

| Étape | Description |
|-------|-------------|
| 5.1 | Vendeur saisit prix négocié : 14000€ (< prix achat 15000€) |
| 5.2 | Système calcule marge : -1000€ (négative) |
| 5.3 | Affichage warning (alerte orange) : |
| | ⚠️ "Attention : marge négative (-1000€). Vente à perte." |
| 5.4 | Vendeur peut : |
| | - Ajuster le prix → Retour étape 5 |
| | - Continuer malgré warning → Continue étape 9 |
| 5.5 | Système enregistre vente avec marge négative (cas exceptionnel autorisé) |

#### 3.1.4 Scénarios d'Erreur

**[ERR-001] Véhicule Déjà Vendu (Race Condition)**

| Étape | Description |
|-------|-------------|
| 2.1 | Vendeur sélectionne véhicule "Peugeot 208" (statut stock à T0) |
| ... | Entre-temps, un autre vendeur vend ce véhicule (T1) |
| 10.1 | Tentative UPDATE statut → véhicule déjà "vendu" |
| 10.2 | Système détecte incohérence (SELECT pour vérifier) : |
| | ```sql |
| | SELECT statut FROM vehicules WHERE id = 5; |
| | -- Retourne "vendu" au lieu de "stock" |
| | ``` |
| 10.3 | ROLLBACK transaction |
| 10.4 | Message erreur : |
| | ❌ "Erreur : Ce véhicule vient d'être vendu par un autre utilisateur. Veuillez recharger la page." |

**[ERR-002] Client Supprimé Entre-Temps**

| Étape | Description |
|-------|-------------|
| 4.1 | Vendeur sélectionne client "Jean Dupont" (ID=12, existant à T0) |
| ... | Entre-temps, admin supprime client ID=12 (T1) |
| 10.1 | Tentative INSERT vente avec `client_id = 12` |
| 10.2 | Contrainte FK échoue (client_id référence table clients) |
| 10.3 | Exception PDO capturée |
| 10.4 | ROLLBACK transaction |
| 10.5 | Message erreur : |
| | ❌ "Erreur : Client introuvable. Veuillez actualiser la page." |

#### 3.1.5 Règles Métier Critiques

| ID | Règle |
|----|-------|
| **RG-VTE-STOCK** | Seuls véhicules statut "stock" ou "réservé" sont proposés au choix |
| **RG-VTE-ATOMIC** | L'insertion vente + mise à jour statut véhicule se font en TRANSACTION (atomicité) |
| **RG-VTE-MARGE** | La marge est calculée et stockée en base (pas recalculée à la volée) |
| **RG-VTE-HISTORIQUE** | Une vente enregistrée ne peut être supprimée (historique immuable) |

#### 3.1.6 Interface

**Écran : Enregistrer Vente** (`/modules/ventes/ajouter.php`)

```
┌─────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile                  Sophie Martin (Vendeur) │
├─────────────────────────────────────────────────────────┤
│ Ventes > Nouvelle vente                                  │
├─────────────────────────────────────────────────────────┤
│ Véhicule                                                 │
│ ┌──────────────────────────────────────────────────┐    │
│ │ Sélectionner un véhicule *                      ▼│    │
│ │ Peugeot 208 (AB-123-CD) - 18500€                 │    │
│ └──────────────────────────────────────────────────┘    │
│                                                          │
│ Prix d'achat : 15000€ (non modifiable)                  │
│ Prix de vente catalogue : 18500€                        │
│                                                          │
│ Client                                                   │
│ ┌──────────────────────────────────────────────────┐    │
│ │ Sélectionner un client *                        ▼│    │
│ │ Jean Dupont (jean.dupont@example.com)            │    │
│ └──────────────────────────────────────────────────┘    │
│ [➕ Créer un nouveau client]                             │
│                                                          │
│ Conditions de Vente                                      │
│ ┌──────────────────┐  ┌──────────────────┐             │
│ │ Prix négocié * € │  │ Mode paiement * ▼│             │
│ │ 17500            │  │ crédit           │             │
│ └──────────────────┘  └──────────────────┘             │
│ Marge réalisée : 2500€ (calculée automatiquement)       │
│                                                          │
│ ┌──────────────────┐                                    │
│ │ Date de vente *  │                                    │
│ │ 2023-08-20       │                                    │
│ └──────────────────┘                                    │
│                                                          │
│ Notes (optionnel)                                        │
│ ┌──────────────────────────────────────────────────┐    │
│ │ Client satisfait. Reprise ancienne voiture 3000€ │    │
│ │                                                   │    │
│ └──────────────────────────────────────────────────┘    │
│                                                          │
│ ┌──────────────┐  ┌──────────┐                          │
│ │ ✓ Enregistrer│  │ Annuler  │                          │
│ └──────────────┘  └──────────┘                          │
└─────────────────────────────────────────────────────────┘
```

---

### 3.2 VTE-FACT-001 : Générer une Facture

#### 3.2.1 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Vendeur clique "📄 Facture" sur vente ID=8 | Redirection `/modules/ventes/facture.php?id=8` |
| 2 | | Requête SQL complexe (jointures) : |
| | | ```sql |
| | | SELECT v.*, ve.*, c.* |
| | | FROM ventes v |
| | | JOIN vehicules ve ON v.vehicule_id = ve.id |
| | | JOIN clients c ON v.client_id = c.id |
| | | WHERE v.id = 8 |
| | | ``` |
| 3 | | Génération HTML facture avec : |
| | | - En-tête : Logo + Coordonnées concession |
| | | - Infos client : Nom, adresse |
| | | - Infos véhicule : Marque, modèle, immatriculation |
| | | - Prix TTC, TVA (20%), Mode paiement |
| | | - Date vente, Numéro facture (auto-incrémenté) |
| 4 | | Affichage page HTML formatée (impression possible) |
| 5 | Vendeur clique "🖨️ Imprimer" | Déclenchement `window.print()` (JavaScript) |
| 6 | | Dialogue d'impression navigateur |

#### 3.2.2 Scénarios Alternatifs

**[ALT-001] Export PDF (Version Future)**

| Étape | Description |
|-------|-------------|
| 5.1 | Vendeur clique "📥 Télécharger PDF" |
| 5.2 | Système génère PDF via bibliothèque (ex: TCPDF) |
| 5.3 | Téléchargement fichier `facture_8.pdf` |

---

## 4. Module Demandes d'Achat - Spécifications Détaillées

### 4.1 DEM-CREATE-001 : Client Crée une Demande d'Achat

#### 4.1.1 Scénario Nominal (Client Connecté)

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Client consulte `/catalogue.php` | Affichage grille véhicules disponibles (statut stock) |
| 2 | Client clique "💬 Demander ce véhicule" sur Peugeot 208 | Vérification authentification : |
| | | - Si connecté ✅ → Suite étape 3 |
| | | - Si non connecté → Redirection `/login.php?redirect=demande.php?vehicule_id=5` |
| 3 | | Redirection `/demande.php?vehicule_id=5` |
| 4 | | Affichage formulaire pré-rempli : |
| | | - Véhicule : Peugeot 208 (non modifiable) |
| | | - Nom : Dupont (session utilisateur) |
| | | - Prénom : Jean (session utilisateur) |
| | | - Email : jean.dupont@example.com (session) |
| | | - Téléphone : (à saisir) |
| | | - Message : (optionnel) |
| 5 | Client saisit téléphone : "06 12 34 56 78" | |
| 6 | Client saisit message : |
| | "Intéressé par ce véhicule. Possibilité de crédit sur 36 mois ?" | |
| 7 | Client clique "Envoyer la demande" | **Validations** : |
| | | - Véhicule existe ✅ |
| | | - Téléphone renseigné ✅ |
| 8 | | Requête SQL : |
| | | ```sql |
| | | INSERT INTO demandes_achat |
| | | (vehicule_id, client_id, nom, prenom, email, telephone, |
| | |  message, statut, created_at) |
| | | VALUES |
| | | (5, 12, 'Dupont', 'Jean', 'jean.dupont@example.com', |
| | |  '06 12 34 56 78', 'Intéressé...', 'en_attente', NOW()) |
| | | ``` |
| 9 | | Redirection `/modules/clients/mes-demandes.php` |
| 10 | | Message succès : |
| | | ✅ "Votre demande a été envoyée ! Un conseiller vous contactera sous 24h." |

#### 4.1.2 Scénarios Alternatifs

**[ALT-001] Client Non Connecté (Formulaire Guest)**

| Étape | Description |
|-------|-------------|
| 2.1 | Vérification authentification : non connecté |
| 2.2 | Redirection `/login.php?redirect=...` OU affichage formulaire guest |
| 3.1 | Affichage formulaire avec TOUS les champs vides : |
| | - Nom * |
| | - Prénom * |
| | - Email * |
| | - Téléphone * |
| | - Message (optionnel) |
| 7.1 | Client saisit TOUTES les infos manuellement |
| 8.1 | Insertion avec `client_id = NULL` (pas de compte associé) |

**[ALT-002] Employé Tente de Créer une Demande (Blocage)**

| Étape | Description |
|-------|-------------|
| 2.1 | Système détecte : utilisateur connecté avec rôle "vendeur" (ou autre employé) |
| 2.2 | Blocage accès |
| 2.3 | Redirection `/acces-refuse.php` |
| 2.4 | Message erreur : |
| | ❌ "Accès refusé : Les employés ne peuvent créer de demandes d'achat." |

#### 4.1.3 Règles Métier

| ID | Règle |
|----|-------|
| **RG-DEM-CLIENT** | Seuls les clients (rôle "client") peuvent créer des demandes |
| **RG-DEM-GUEST** | Les visiteurs non inscrits peuvent créer des demandes (formulaire guest) |
| **RG-DEM-STATUT** | Statut initial par défaut : "en_attente" |
| **RG-DEM-EMAIL** | Un email de notification est envoyé aux vendeurs (version future) |

---

### 4.2 DEM-TREAT-001 : Vendeur Traite une Demande

#### 4.2.1 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Vendeur accède `/modules/ventes/demandes-liste.php` | Requête SQL : |
| | | ```sql |
| | | SELECT d.*, v.marque, v.modele |
| | | FROM demandes_achat d |
| | | JOIN vehicules v ON d.vehicule_id = v.id |
| | | ORDER BY d.created_at DESC |
| | | ``` |
| 2 | | Affichage tableau avec filtres : |
| | | - Statut (dropdown) : Tous, en_attente, en_cours, etc. |
| | | - Recherche (nom client, véhicule) |
| 3 | Vendeur filtre : Statut = "en_attente" | Requête WHERE statut = 'en_attente' |
| 4 | | Affichage 5 demandes en attente |
| 5 | Vendeur clique "Voir détails" sur demande ID=15 | Redirection `/modules/ventes/demandes-detail.php?id=15` |
| 6 | | Affichage fiche détaillée : |
| | | - Véhicule concerné (image, caractéristiques) |
| | | - Informations client (nom, email, téléphone) |
| | | - Message client |
| | | - Date demande |
| | | - Statut actuel |
| | | - Formulaire traitement (dropdown statut + textarea notes) |
| 7 | Vendeur contacte client par téléphone | (Action hors système) |
| 8 | Vendeur revient sur détail demande | |
| 9 | Vendeur change statut → "en_cours" | |
| 10 | Vendeur saisit notes gestionnaire : |
| | "Client rappelé le 20/08 à 14h30. RDV prévu samedi 25/08 à 10h pour essai." | |
| 11 | Vendeur clique "Enregistrer" | Requête SQL : |
| | | ```sql |
| | | UPDATE demandes_achat SET |
| | | statut = 'en_cours', |
| | | notes_gestionnaire = '...', |
| | | traitee_par = 3, -- ID vendeur |
| | | date_traitement = NOW(), |
| | | updated_at = NOW() |
| | | WHERE id = 15 |
| | | ``` |
| 12 | | Redirection `/modules/ventes/demandes-liste.php` |
| 13 | | Message succès : |
| | | ✅ "Demande mise à jour avec succès." |

#### 4.2.2 Workflow Complet Demande

```
en_attente → en_cours → acceptée → finalisée
                 ↓
              refusée
```

| Statut | Description | Qui peut changer |
|--------|-------------|------------------|
| **en_attente** | Demande reçue, pas encore traitée | Auto (création) |
| **en_cours** | Vendeur a contacté client, négociation | Vendeur |
| **acceptée** | Client intéressé, vente probable | Vendeur |
| **refusée** | Client pas intéressé ou véhicule inadapté | Vendeur |
| **finalisée** | Vente conclue (lien vers vente ID) | Vendeur |

#### 4.2.3 Règles Métier

| ID | Règle |
|----|-------|
| **RG-DEM-NOTES** | Les notes gestionnaire ne sont visibles QUE par vendeurs et admin (pas par client) |
| **RG-DEM-FINALISEE** | Une demande en statut "finalisée" ne peut plus être modifiée |
| **RG-DEM-TRAITE** | Le champ `traitee_par` enregistre l'ID du vendeur qui a traité la demande |

---

## 5. Module RH - Spécifications Détaillées

### 5.1 RH-PAIE-001 : Créer un Bulletin de Paie

#### 5.1.1 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | RH accède `/modules/rh/paie.php` | Affichage liste bulletins existants + bouton "Créer" |
| 2 | RH clique "➕ Créer un bulletin de paie" | Affichage formulaire |
| 3 | RH sélectionne employé : "Sophie Martin" (ID=3) | Requête SQL : |
| | | ```sql |
| | | SELECT salaire FROM personnel WHERE id = 3 |
| | | -- Retourne : 2500€ |
| | | ``` |
| 4 | | Pré-remplissage automatique : |
| | | - Salaire de base : 2500€ (depuis table personnel) |
| 5 | RH sélectionne mois : "Août 2023" | |
| 6 | RH saisit primes : 300€ | |
| 7 | RH saisit déductions : 150€ | |
| 8 | | **Calcul automatique temps réel** (JavaScript) : |
| | | Net à payer = 2500 + 300 - 150 = 2650€ |
| | | Affichage dynamique |
| 9 | RH saisit notes : "Prime performance mensuelle" | |
| 10 | RH laisse statut : "brouillon" | |
| 11 | RH clique "Enregistrer" | Requête SQL : |
| | | ```sql |
| | | INSERT INTO bulletins_paie |
| | | (personnel_id, mois_reference, salaire_base, prime, |
| | |  deductions, net_a_payer, statut, notes, created_at) |
| | | VALUES |
| | | (3, '2023-08-01', 2500, 300, 150, 2650, |
| | |  'brouillon', 'Prime performance...', NOW()) |
| | | ``` |
| 12 | | Redirection `/modules/rh/paie.php` |
| 13 | | Message succès : |
| | | ✅ "Bulletin de paie créé (brouillon). Validez-le après vérification." |

#### 5.1.2 Scénarios Alternatifs

**[ALT-001] Valider le Bulletin Directement**

| Étape | Description |
|-------|-------------|
| 10.1 | RH change statut → "validé" avant enregistrement |
| 11.1 | Système insère avec `statut = 'valide'` |
| 13.1 | Message succès : |
| | ✅ "Bulletin de paie créé et validé." |

**[ALT-002] Valider un Bulletin Existant**

| Étape | Description |
|-------|-------------|
| 1 | RH consulte liste bulletins |
| 2 | RH clique "✓ Valider" sur bulletin ID=10 (statut brouillon) |
| 3 | Requête SQL : |
| | ```sql |
| | UPDATE bulletins_paie SET statut = 'valide', updated_at = NOW() |
| | WHERE id = 10 AND statut = 'brouillon' |
| | ``` |
| 4 | Message succès : |
| | ✅ "Bulletin de paie validé. Il ne peut plus être modifié." |

#### 5.1.3 Règles Métier

| ID | Règle |
|----|-------|
| **RG-PAIE-CALC** | Net à payer = Salaire base + Primes - Déductions (calcul automatique) |
| **RG-PAIE-VALIDE** | Un bulletin en statut "validé" ne peut plus être modifié ni supprimé |
| **RG-PAIE-SALAIRE** | Le salaire de base est récupéré automatiquement depuis `personnel.salaire` |
| **RG-PAIE-MOIS** | Un seul bulletin par employé par mois (contrainte unique : personnel_id + mois_reference) |

#### 5.1.4 Scénarios d'Erreur

**[ERR-001] Bulletin Déjà Existant pour Ce Mois**

| Étape | Description |
|-------|-------------|
| 11.1 | Tentative INSERT avec personnel_id=3, mois='2023-08-01' |
| 11.2 | Contrainte UNIQUE échoue (déjà un bulletin pour Sophie en août 2023) |
| 11.3 | Exception PDO capturée |
| 11.4 | Message erreur : |
| | ❌ "Erreur : Un bulletin de paie existe déjà pour cet employé pour le mois d'août 2023." |

**[ERR-002] Modification Bulletin Validé**

| Étape | Description |
|-------|-------------|
| 1 | RH tente de modifier bulletin ID=10 (statut validé) |
| 2 | Système détecte `statut = 'valide'` |
| 3 | Blocage édition |
| 4 | Message erreur : |
| | ❌ "Erreur : Un bulletin validé ne peut être modifié. Créez un bulletin correctif si nécessaire." |

---

### 5.2 RH-CONGES-001 : Gérer une Demande de Congés

#### 5.2.1 Scénario Nominal (Approbation)

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | RH accède `/modules/rh/conges.php` | Liste demandes de congés |
| 2 | | Filtres : Statut (en_attente, approuvé, refusé), Employé |
| 3 | RH filtre : Statut = "en_attente" | Affichage 2 demandes en attente |
| 4 | | Demande ID=5 : |
| | | - Employé : Sophie Martin |
| | | - Type : CP |
| | | - Du 01/08/2023 au 15/08/2023 (15 jours) |
| | | - Commentaire : "Vacances d'été" |
| | | - Statut : en_attente |
| 5 | RH clique "✓ Approuver" | Affichage modal confirmation : |
| | | "Approuver les congés de Sophie Martin du 01/08 au 15/08 ?" |
| | | Champ : Commentaire gestion (optionnel) |
| 6 | RH saisit commentaire : "Approuvé, faible activité en août." | |
| 7 | RH clique "Confirmer" | Requête SQL : |
| | | ```sql |
| | | UPDATE conges SET |
| | | statut = 'approuve', |
| | | commentaire_gestion = 'Approuvé, faible activité...', |
| | | updated_at = NOW() |
| | | WHERE id = 5 |
| | | ``` |
| 8 | | Rechargement page `/modules/rh/conges.php` |
| 9 | | Message succès : |
| | | ✅ "Congés approuvés pour Sophie Martin." |
| 10 | | (Version future) Email notification envoyé à sophie.martin@concession.fr |

#### 5.2.2 Scénario Alternatif (Refus)

| Étape | Description |
|-------|-------------|
| 5.1 | RH clique "✗ Refuser" au lieu de "Approuver" |
| 6.1 | RH saisit motif refus : "Période forte activité, déjà 2 vendeurs absents." |
| 7.1 | Requête SQL : |
| | ```sql |
| | UPDATE conges SET statut = 'refuse', |
| | commentaire_gestion = '...', updated_at = NOW() WHERE id = 5 |
| | ``` |
| 9.1 | Message : |
| | ℹ️ "Congés refusés. L'employé a été notifié." |

#### 5.2.3 Règles Métier

| ID | Règle |
|----|-------|
| **RG-CONG-STATUT** | Statuts possibles : en_attente, approuvé, refusé |
| **RG-CONG-COMMENT** | Le commentaire gestion est OBLIGATOIRE lors d'un refus |
| **RG-CONG-NOTIF** | Une notification est envoyée à l'employé (email - version future) |

---

## 6. Module Statistiques - Spécifications Détaillées

### 6.1 STAT-DASH-001 : Afficher Tableau de Bord KPI

#### 6.1.1 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Comptable accède `/modules/statistiques/dashboard.php` | Ensemble de requêtes SQL complexes |
| 2 | | **KPI Année en Cours** (4 requêtes) : |
| | | ```sql |
| | | -- Nombre de ventes |
| | | SELECT COUNT(*) FROM ventes |
| | | WHERE YEAR(date_vente) = YEAR(NOW()); |
| | | -- Résultat : 48 ventes |
| | | |
| | | -- Chiffre d'affaires |
| | | SELECT SUM(prix_vente) FROM ventes |
| | | WHERE YEAR(date_vente) = YEAR(NOW()); |
| | | -- Résultat : 720 000€ |
| | | |
| | | -- Panier moyen |
| | | SELECT AVG(prix_vente) FROM ventes |
| | | WHERE YEAR(date_vente) = YEAR(NOW()); |
| | | -- Résultat : 15 000€ |
| | | |
| | | -- Marge totale |
| | | SELECT SUM(marge) FROM ventes |
| | | WHERE YEAR(date_vente) = YEAR(NOW()); |
| | | -- Résultat : 120 000€ |
| | | ``` |
| 3 | | **Évolution Mensuelle (6 derniers mois)** : |
| | | ```sql |
| | | SELECT |
| | | DATE_FORMAT(date_vente, '%Y-%m') AS mois, |
| | | COUNT(*) AS nb_ventes, |
| | | SUM(prix_vente) AS ca |
| | | FROM ventes |
| | | WHERE date_vente >= DATE_SUB(NOW(), INTERVAL 6 MONTH) |
| | | GROUP BY mois |
| | | ORDER BY mois ASC; |
| | | ``` |
| | | Résultat : |
| | | - 2023-03 : 6 ventes, 90 000€ |
| | | - 2023-04 : 8 ventes, 120 000€ |
| | | - ... |
| 4 | | **Top 5 Marques** : |
| | | ```sql |
| | | SELECT ve.marque, COUNT(*) AS nb_ventes |
| | | FROM ventes v |
| | | JOIN vehicules ve ON v.vehicule_id = ve.id |
| | | WHERE YEAR(v.date_vente) = YEAR(NOW()) |
| | | GROUP BY ve.marque |
| | | ORDER BY nb_ventes DESC |
| | | LIMIT 5; |
| | | ``` |
| | | Résultat : |
| | | 1. Peugeot : 12 ventes |
| | | 2. Renault : 10 ventes |
| | | 3. Citroën : 8 ventes |
| | | ... |
| 5 | | **Top 5 Clients** : |
| | | ```sql |
| | | SELECT c.nom, c.prenom, COUNT(*) AS nb_achats, |
| | | SUM(v.prix_vente) AS total_depense |
| | | FROM ventes v |
| | | JOIN clients c ON v.client_id = c.id |
| | | GROUP BY c.id |
| | | ORDER BY total_depense DESC |
| | | LIMIT 5; |
| | | ``` |
| 6 | | Affichage page avec : |
| | | - 4 Cards KPI (ventes, CA, panier moyen, marge) |
| | | - Graphique courbe évolution mensuelle (Chart.js ou similaire) |
| | | - Graphique barres Top 5 marques |
| | | - Tableau Top 5 clients |

#### 6.1.2 Règles Métier

| ID | Règle |
|----|-------|
| **RG-STAT-TEMPS-REEL** | Les statistiques sont recalculées à chaque chargement de page (pas de cache) |
| **RG-STAT-ANNEE** | KPI globaux calculés sur année civile en cours (YEAR(NOW())) |
| **RG-STAT-6MOIS** | Évolution mensuelle sur 6 derniers mois glissants |
| **RG-STAT-PERM** | Accès réservé aux rôles : admin, vendeur, comptable |

---

## 7. Module Administration - Spécifications Détaillées

### 7.1 ADM-USER-001 : Créer un Utilisateur

#### 7.1.1 Scénario Nominal

| Étape | Acteur | Action Système |
|-------|--------|----------------|
| 1 | Admin accède `/modules/admin/ajouter-utilisateur.php` | Affichage formulaire |
| 2 | Admin remplit : | |
| | - Nom : "Martin" | |
| | - Prénom : "Sophie" | |
| | - Email : "sophie.martin@concession.fr" | |
| | - Rôle : "vendeur" (dropdown) | |
| | - Mot de passe : "VendeurSecure2023!" | |
| | - Confirmer mot de passe : "VendeurSecure2023!" | |
| | - Statut : "actif" | |
| 3 | Admin clique "Créer l'utilisateur" | **Validations** : |
| | | - Email unique ✅ |
| | | - Mots de passe identiques ✅ |
| | | - Mot de passe fort (min 8 caractères) ✅ |
| 4 | | **Hash mot de passe** : |
| | | ```php |
| | | $hash = password_hash('VendeurSecure2023!', PASSWORD_BCRYPT); |
| | | // Résultat : $2y$10$... |
| | | ``` |
| 5 | | Requête SQL : |
| | | ```sql |
| | | INSERT INTO utilisateurs |
| | | (nom, prenom, email, password, role, statut, created_at) |
| | | VALUES |
| | | ('Martin', 'Sophie', 'sophie.martin@concession.fr', |
| | |  '$2y$10$...', 'vendeur', 'actif', NOW()) |
| | | ``` |
| 6 | | Redirection `/modules/admin/utilisateurs.php` |
| 7 | | Message succès : |
| | | ✅ "Utilisateur créé avec succès. Identifiants : sophie.martin@concession.fr" |

#### 7.1.2 Scénarios d'Erreur

**[ERR-001] Email Déjà Existant**

| Étape | Description |
|-------|-------------|
| 3.1 | Validation détecte : "sophie.martin@concession.fr" déjà en base |
| 3.2 | Blocage insertion |
| 3.3 | Message erreur : |
| | ❌ "Erreur : Cet email est déjà utilisé." |

**[ERR-002] Mots de Passe Non Identiques**

| Étape | Description |
|-------|-------------|
| 3.1 | Validation détecte : Mot de passe ≠ Confirmation |
| 3.2 | Message erreur : |
| | ❌ "Erreur : Les mots de passe ne correspondent pas." |

#### 7.1.3 Règles de Sécurité

| ID | Règle |
|----|-------|
| **RG-SEC-HASH** | Les mots de passe sont TOUJOURS hashés en bcrypt (jamais stockés en clair) |
| **RG-SEC-FORCE** | Mot de passe minimum 8 caractères (recommandation : 12+, majuscule, chiffre, spécial) |
| **RG-SEC-EMAIL** | Email unique dans le système (contrainte BDD) |

---

## 8. Règles de Validation Transverses

### 8.1 Validation Formulaires (Toutes Pages)

| Type de Champ | Règle de Validation |
|---------------|---------------------|
| **Email** | - Format valide (regex RFC 5322 simplifié)<br/>- Exemple valide : `user@example.com`<br/>- Exemple invalide : `user@example` |
| **Téléphone** | - Format français 10 chiffres<br/>- Regex : `^0[1-9][0-9]{8}$`<br/>- Exemple valide : `0612345678` ou `06 12 34 56 78` |
| **Prix/Montants** | - Décimal >= 0<br/>- Max 2 décimales<br/>- Séparateur décimal : `.` (base) ou `,` (affichage) |
| **Dates** | - Format ISO 8601 : `YYYY-MM-DD`<br/>- Validation calendrier (pas de 30 février) |
| **Texte court** | - Max 255 caractères<br/>- Trimming espaces début/fin |
| **Texte long** | - Max 5000 caractères<br/>- Protection XSS (`htmlspecialchars()`) |

### 8.2 Messages d'Erreur Standards

| Situation | Message Type | Exemple |
|-----------|--------------|---------|
| **Succès** | Alerte verte (✅) | "Opération réussie !" |
| **Erreur validation** | Alerte rouge (❌) | "Erreur : Champ 'Email' invalide." |
| **Warning** | Alerte orange (⚠️) | "Attention : Marge négative détectée." |
| **Info** | Alerte bleue (ℹ️) | "Aucun résultat trouvé." |
| **Accès refusé** | Alerte rouge (❌) | "Accès refusé : Permission insuffisante." |

### 8.3 Gestion Permissions (Toutes Actions)

**Algorithme de Vérification** (appliqué à chaque page)

```
1. Vérifier session active (utilisateur connecté)
   ├─ SI NON → Redirection /login.php
   └─ SI OUI → Suite

2. Récupérer module + action requis
   Exemple : module="vehicules", action="create"

3. Vérifier permission utilisateur
   ├─ Requête BDD :
   │  SELECT COUNT(*) FROM permissions
   │  WHERE role = 'vendeur' AND module = 'vehicules' AND action = 'create'
   │
   ├─ SI COUNT > 0 → Autorisé ✅
   ├─ SI rôle = 'admin' → Autorisé ✅ (wildcard)
   └─ SINON → Refusé ❌

4. SI Refusé
   ├─ Redirection /acces-refuse.php
   └─ Log tentative accès (sécurité)

5. SI Autorisé
   └─ Affichage page
```

---

## 9. Gestion des Erreurs Techniques

### 9.1 Erreurs Base de Données

| Erreur SQL | Code MySQL | Gestion |
|------------|-----------|---------|
| **Contrainte clé étrangère** | 1452 | Message : "Impossible de supprimer cet élément (dépendances existantes)" |
| **Contrainte unique** | 1062 | Message : "Cette valeur existe déjà (email, immatriculation, etc.)" |
| **Connexion échouée** | 2002 | Message : "Erreur serveur, veuillez réessayer plus tard." + Log |
| **Timeout requête** | 1205 | Message : "Opération trop longue, veuillez réessayer." |

### 9.2 Erreurs Fichiers (Upload Images)

| Erreur | Code PHP | Gestion |
|--------|----------|---------|
| **Fichier trop volumineux** | UPLOAD_ERR_INI_SIZE | "Image trop volumineuse (max 5 MB)" |
| **Extension invalide** | Custom | "Format invalide (JPG, PNG, WebP uniquement)" |
| **Erreur écriture disque** | UPLOAD_ERR_CANT_WRITE | "Erreur serveur lors de l'upload" + Log |

### 9.3 Page 404 et Erreurs Génériques

| Page | URL | Affichage |
|------|-----|-----------|
| **404 Not Found** | Toute URL invalide | Page custom avec lien retour accueil |
| **403 Forbidden** | `/acces-refuse.php` | "Accès refusé : Vous n'avez pas les permissions requises." |
| **500 Internal Error** | Exception non gérée | Page générique "Erreur serveur" + Log détaillé |

---

## 10. Validation et Approbation

### 10.1 Checklist de Validation

Ce document est validé si :

- [ ] Tous les scénarios nominaux sont décrits avec enchaînements précis
- [ ] Les scénarios alternatifs et d'erreur sont exhaustifs
- [ ] Les règles de validation sont claires et testables
- [ ] Les interfaces sont maquettées (même en ASCII art)
- [ ] Les messages utilisateur sont définis
- [ ] Les règles métier sont non ambiguës
- [ ] Les requêtes SQL types sont fournies
- [ ] La MOA valide que les workflows correspondent aux processus métier

### 10.2 Signatures

| Rôle | Nom | Signature | Date |
|------|-----|-----------|------|
| **Maîtrise d'Ouvrage** | | | |
| **Expert Métier** | | | |
| **Chef de Projet** | | | |
| **Lead Développeur** | | | |

---

**Fin du document**

**Prochaine étape** : Spécifications Techniques + Modèles UML
