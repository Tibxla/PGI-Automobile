# 16. MANUEL UTILISATEUR

## Informations du Document

| Élément | Détail |
|---------|--------|
| **Projet** | PGI Automobile - Système de Gestion Intégré |
| **Phase** | PHASE 6 - Maintenance |
| **Livrable** | Manuel Utilisateur |
| **Version** | 1.0 |
| **Date** | 17/11/2025 |
| **Auteur** | Équipe PGI Automobile |

---

## Table des Matières

1. [Bienvenue](#1-bienvenue)
2. [Premiers Pas](#2-premiers-pas)
3. [Tableau de Bord](#3-tableau-de-bord)
4. [Module Véhicules](#4-module-véhicules)
5. [Module Ventes](#5-module-ventes)
6. [Module Clients](#6-module-clients)
7. [Module Demandes d'Achat](#7-module-demandes-dachat)
8. [Module Employés (RH)](#8-module-employés-rh)
9. [Module Stock](#9-module-stock)
10. [Module Statistiques](#10-module-statistiques)
11. [Module Administration](#11-module-administration)
12. [Conseils et Astuces](#12-conseils-et-astuces)

---

## 1. Bienvenue

### 1.1 Qu'est-ce que PGI Automobile ?

**PGI Automobile** est votre système de gestion intégré conçu spécialement pour les concessionnaires automobiles. Il vous permet de gérer efficacement :

- 🚗 Votre parc de véhicules (stock, achats, ventes)
- 💰 Vos ventes et la facturation
- 👥 Votre portefeuille clients
- 📦 Vos demandes d'achat et commandes
- 👔 Votre personnel et les paies
- 📊 Vos statistiques et performances

### 1.2 À qui s'adresse ce manuel ?

Ce manuel est destiné à **tous les utilisateurs** du système, quel que soit votre rôle :
- Vendeurs
- Magasiniers
- Comptables
- Responsables RH
- Directeurs
- Administrateurs

### 1.3 Conventions Utilisées

| Symbole | Signification |
|---------|---------------|
| 💡 | Astuce ou conseil |
| ⚠️ | Attention ou avertissement |
| ✅ | Action à effectuer |
| 📝 | Information importante |

---

## 2. Premiers Pas

### 2.1 Accéder au Système

**Étape 1 : Ouvrir le navigateur**

Utilisez un navigateur moderne :
- Google Chrome (recommandé)
- Mozilla Firefox
- Microsoft Edge
- Safari (Mac)

**Étape 2 : Aller sur le site**

Dans la barre d'adresse, tapez l'URL fournie par votre administrateur :
```
https://pgi-automobile.votreentreprise.com
```

💡 **Astuce** : Ajoutez le site à vos favoris pour un accès rapide.

### 2.2 Se Connecter

**Page de Connexion**

Vous arrivez sur la page de connexion avec deux champs :

```
┌─────────────────────────────────────┐
│     🚗 PGI AUTOMOBILE                │
│                                      │
│  Email :    [________________]      │
│                                      │
│  Mot de passe : [________________]  │
│                                      │
│         [ Se connecter ]             │
│                                      │
│     Mot de passe oublié ?            │
└─────────────────────────────────────┘
```

**Étapes :**

1. ✅ Saisissez votre **adresse email professionnelle**
   - Exemple : `jean.dupont@entreprise.com`

2. ✅ Saisissez votre **mot de passe**
   - Respectez les majuscules/minuscules

3. ✅ Cliquez sur **"Se connecter"**

**Première connexion :**

Si c'est votre première connexion, votre administrateur vous a fourni un mot de passe temporaire. Vous serez invité à le changer.

📝 **Exigences du mot de passe :**
- Minimum 8 caractères
- Au moins 1 majuscule
- Au moins 1 minuscule
- Au moins 1 chiffre
- Au moins 1 caractère spécial (@, !, #, etc.)

**Exemple de mot de passe fort :** `MonPgi2025!`

### 2.3 Interface Principale

Une fois connecté, vous arrivez sur le **Tableau de Bord** :

```
┌───────────────────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile  [Véhicules] [Ventes] [Clients] ...  👤 Jean D. ▼ │
├───────────────────────────────────────────────────────────────────────┤
│                                                                        │
│  📊 TABLEAU DE BORD                                                   │
│                                                                        │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐            │
│  │ 45 ventes│  │ 125 200€ │  │   18 %   │  │ 23 stock │            │
│  │ Ce mois  │  │    CA    │  │  Marge   │  │Véhicules │            │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘            │
│                                                                        │
│  [Graphiques et statistiques...]                                      │
│                                                                        │
└───────────────────────────────────────────────────────────────────────┘
```

**Éléments de l'interface :**

1. **Barre de navigation (en haut)** : Accès rapide aux différents modules
2. **Zone centrale** : Contenu principal (tableaux de bord, listes, formulaires)
3. **Menu utilisateur (en haut à droite)** : Votre profil, déconnexion
4. **Indicateurs visuels** : Badges de couleur pour les statuts

### 2.4 Navigation

**Accéder à un module :**

Cliquez sur le nom du module dans la barre de navigation en haut :
- **Véhicules** : Gestion du parc automobile
- **Ventes** : Enregistrement et suivi des ventes
- **Clients** : Gestion des clients
- Etc.

💡 **Astuce** : Les modules visibles dépendent de votre rôle. Si vous ne voyez pas un module, c'est normal : vous n'avez pas l'accès.

### 2.5 Se Déconnecter

Pour quitter le système en toute sécurité :

1. ✅ Cliquez sur votre nom en haut à droite
2. ✅ Sélectionnez **"Déconnexion"**

⚠️ **Important** : Déconnectez-vous toujours en fin de journée ou si vous quittez votre poste.

---

## 3. Tableau de Bord

### 3.1 Vue d'Ensemble

Le tableau de bord vous donne une **vue instantanée** de l'activité de votre entreprise.

**Indicateurs Clés (KPIs)**

| Indicateur | Description |
|------------|-------------|
| **Nombre de ventes** | Ventes réalisées sur la période |
| **Chiffre d'affaires** | CA total en euros |
| **Marge moyenne** | Pourcentage de marge sur les ventes |
| **Stock disponible** | Nombre de véhicules en stock |
| **Valeur du stock** | Valeur totale du parc disponible |

### 3.2 Filtrer par Période

Par défaut, les statistiques affichent **le mois en cours**. Vous pouvez changer :

```
Période : [Aujourd'hui ▼] [Cette semaine] [Ce mois] [Cette année]
```

1. ✅ Cliquez sur le menu déroulant "Période"
2. ✅ Sélectionnez la période souhaitée
3. ✅ Le tableau se met à jour automatiquement

### 3.3 Graphiques

**Évolution du Chiffre d'Affaires**

Graphique en courbe montrant l'évolution du CA sur les 12 derniers mois.

**Répartition des Ventes par Marque**

Graphique en camembert montrant les marques les plus vendues.

**Top 5 des Vendeurs**

Classement des 5 meilleurs vendeurs du mois.

💡 **Astuce** : Passez votre souris sur les graphiques pour voir les détails.

---

## 4. Module Véhicules

### 4.1 Accéder au Module

Cliquez sur **"Véhicules"** dans le menu principal.

### 4.2 Liste des Véhicules

Vous voyez la liste de tous les véhicules avec :

| Colonne | Description |
|---------|-------------|
| **Photo** | Image du véhicule |
| **Immatriculation** | Numéro de plaque |
| **Marque / Modèle** | Ex: Peugeot 308 |
| **Année** | Année de mise en circulation |
| **Prix d'achat** | Prix payé par la concession |
| **Prix de vente** | Prix affiché au public |
| **Marge** | Bénéfice potentiel (%) |
| **Statut** | Stock / Vendu / Réservé |
| **Actions** | Voir / Modifier / Supprimer |

**Statuts avec couleurs :**
- 🟢 **Stock** : Disponible à la vente
- 🔴 **Vendu** : Déjà vendu
- 🟠 **Réservé** : Réservé par un client

### 4.3 Rechercher un Véhicule

**Barre de recherche :**

```
Recherche : [Peugeot 308___________] [🔍 Rechercher]

Filtres :
Statut : [Tous ▼]  Marque : [Toutes ▼]  Année : [Toutes ▼]
```

**Étapes :**

1. ✅ Tapez un mot-clé (marque, modèle, immatriculation)
2. ✅ OU utilisez les filtres (statut, marque, année)
3. ✅ Cliquez sur "Rechercher"

💡 **Astuce** : Vous pouvez combiner recherche texte + filtres.

### 4.4 Ajouter un Véhicule

**Qui peut le faire ?** Magasinier, Directeur, Super Admin

**Étapes :**

1. ✅ Cliquez sur le bouton **"+ Nouveau Véhicule"**
2. ✅ Remplissez le formulaire :

**Formulaire d'Ajout**

```
┌─────────────────────────────────────────────────┐
│  INFORMATIONS VÉHICULE                          │
│                                                  │
│  Immatriculation * : [AA-123-BB___]            │
│  Marque *          : [Peugeot______]           │
│  Modèle *          : [308___________]          │
│  Année *           : [2023_]                    │
│                                                  │
│  PRIX                                            │
│                                                  │
│  Prix d'achat HT * : [15000__] €               │
│  Prix de vente TTC*: [18900__] €               │
│  Marge             : [3900 €] (26%)  [calculé] │
│                                                  │
│  CARACTÉRISTIQUES                                │
│                                                  │
│  Kilométrage       : [25000_] km                │
│  Carburant         : [Essence ▼]               │
│  Transmission      : [Manuelle ▼]              │
│  Couleur           : [Blanc_____]              │
│  Nombre de portes  : [5_]                       │
│                                                  │
│  PHOTO                                           │
│                                                  │
│  [Choisir un fichier...]  [Aucun fichier]      │
│                                                  │
│  [ Annuler ]         [✅ Enregistrer]           │
└─────────────────────────────────────────────────┘
```

**Champs obligatoires (marqués d'un *) :**
- Immatriculation
- Marque
- Modèle
- Année
- Prix d'achat
- Prix de vente

3. ✅ Cliquez sur **"Enregistrer"**

**Résultat :**
- Message de confirmation : "Véhicule ajouté avec succès"
- Retour à la liste des véhicules
- Le nouveau véhicule apparaît en haut de la liste avec le statut "Stock"

📝 **Important** : La marge est calculée automatiquement.

💡 **Astuce Photo** : Formats acceptés : JPG, PNG. Taille max : 10 Mo. Nommez votre fichier sans espaces ni accents (ex: `peugeot_308.jpg`).

### 4.5 Modifier un Véhicule

**Qui peut le faire ?** Magasinier, Directeur, Super Admin

**Étapes :**

1. ✅ Dans la liste, cliquez sur l'icône **"Modifier"** (✏️) à côté du véhicule
2. ✅ Modifiez les champs souhaités (même formulaire que l'ajout)
3. ✅ Cliquez sur **"Enregistrer"**

**Cas d'usage :**
- Modifier le prix de vente
- Ajouter une photo
- Corriger une erreur de saisie
- Mettre à jour le kilométrage

⚠️ **Attention** : Vous ne pouvez pas modifier un véhicule déjà vendu.

### 4.6 Consulter les Détails

**Étapes :**

1. ✅ Cliquez sur l'icône **"Voir"** (👁️) ou sur le nom du véhicule

**Page de Détails**

```
┌────────────────────────────────────────────────────────┐
│  ← Retour                    [Modifier] [Supprimer]   │
├────────────────────────────────────────────────────────┤
│  [Photo du véhicule]                                   │
│                                                         │
│  PEUGEOT 308 (2023)                     🟢 EN STOCK   │
│  Immatriculation : AA-123-BB                           │
│                                                         │
│  PRIX                                                   │
│  Prix d'achat HT : 15 000 €                            │
│  Prix de vente TTC : 18 900 €                          │
│  Marge : 3 900 € (26%)                                 │
│                                                         │
│  CARACTÉRISTIQUES                                       │
│  Kilométrage : 25 000 km                               │
│  Carburant : Essence                                    │
│  Transmission : Manuelle                                │
│  Couleur : Blanc                                        │
│  Portes : 5                                             │
│                                                         │
│  HISTORIQUE                                             │
│  Ajouté le : 15/11/2025 par Jean Dupont                │
│  Dernière modification : 17/11/2025                    │
│                                                         │
│  DEMANDES D'ACHAT LIÉES                                │
│  Demande #1234 - Validée le 10/11/2025                 │
└────────────────────────────────────────────────────────┘
```

### 4.7 Supprimer un Véhicule

**Qui peut le faire ?** Super Admin uniquement

⚠️ **Attention** : Action irréversible !

**Restrictions :**
- ❌ Vous ne pouvez pas supprimer un véhicule vendu (contrainte métier)
- ❌ Vous ne pouvez pas supprimer un véhicule avec des ventes associées

**Étapes :**

1. ✅ Cliquez sur l'icône **"Supprimer"** (🗑️)
2. ✅ Confirmez la suppression dans la fenêtre de confirmation
3. ✅ Le véhicule est définitivement supprimé

💡 **Alternative** : Au lieu de supprimer, envisagez de modifier le statut ou d'archiver.

---

## 5. Module Ventes

### 5.1 Accéder au Module

Cliquez sur **"Ventes"** dans le menu principal.

### 5.2 Liste des Ventes

Vous voyez toutes les ventes enregistrées :

| Colonne | Description |
|---------|-------------|
| **N° Facture** | Numéro unique (FACT-2025-XXXXXX) |
| **Date** | Date de la vente |
| **Véhicule** | Marque / Modèle / Immatriculation |
| **Client** | Nom du client |
| **Prix de vente** | Montant TTC |
| **Mode paiement** | Cash / Crédit / Leasing |
| **Vendeur** | Nom du vendeur |
| **Actions** | Voir / Modifier / Annuler |

### 5.3 Enregistrer une Nouvelle Vente

**Qui peut le faire ?** Vendeur, Directeur, Super Admin

**Étapes :**

1. ✅ Cliquez sur **"+ Nouvelle Vente"**

**Formulaire de Vente**

```
┌─────────────────────────────────────────────────┐
│  ENREGISTRER UNE VENTE                          │
│                                                  │
│  ÉTAPE 1 : SÉLECTIONNER LE VÉHICULE            │
│                                                  │
│  Véhicule * : [Rechercher...________________▼] │
│                                                  │
│  ┌───────────────────────────────────────────┐ │
│  │ Peugeot 308 (2023) - AA-123-BB           │ │
│  │ Prix affiché : 18 900 €                  │ │
│  └───────────────────────────────────────────┘ │
│                                                  │
│  ÉTAPE 2 : SÉLECTIONNER LE CLIENT              │
│                                                  │
│  Client * : [Rechercher...________________▼]   │
│  ou [+ Créer un nouveau client]                 │
│                                                  │
│  ┌───────────────────────────────────────────┐ │
│  │ Jean Dupont                               │ │
│  │ jean.dupont@email.com - 0612345678       │ │
│  └───────────────────────────────────────────┘ │
│                                                  │
│  ÉTAPE 3 : INFORMATIONS VENTE                  │
│                                                  │
│  Prix de vente TTC * : [18900__] €            │
│  Mode de paiement * : [Crédit ▼]               │
│    ☐ Cash                                       │
│    ☑ Crédit                                     │
│    ☐ Leasing                                    │
│                                                  │
│  Date de vente : [17/11/2025] (aujourd'hui)    │
│                                                  │
│  Commentaires : [_______________________]       │
│                 [_______________________]       │
│                                                  │
│  [ Annuler ]         [✅ Enregistrer la vente] │
└─────────────────────────────────────────────────┘
```

2. ✅ **Sélectionnez le véhicule**
   - Tapez le début de la marque ou immatriculation
   - Sélectionnez dans la liste déroulante
   - Seuls les véhicules "En Stock" sont proposés

3. ✅ **Sélectionnez le client**
   - Tapez le début du nom ou email
   - Sélectionnez dans la liste
   - Si client nouveau → Cliquez sur "+ Créer un nouveau client"

4. ✅ **Renseignez le prix et le mode de paiement**
   - Le prix est pré-rempli avec le prix affiché
   - Vous pouvez le modifier (négociation)
   - Sélectionnez le mode de paiement

5. ✅ Cliquez sur **"Enregistrer la vente"**

**Résultat :**
- ✅ Vente enregistrée en base de données
- ✅ Véhicule passe automatiquement au statut "Vendu"
- ✅ Facture générée automatiquement (FACT-2025-XXXXXX)
- ✅ Message de confirmation avec lien vers la facture

📝 **Important** : La transaction est atomique (tout ou rien). Si une erreur survient, rien n'est enregistré.

💡 **Astuce** : Vous pouvez imprimer la facture immédiatement après la vente.

### 5.4 Consulter les Détails d'une Vente

**Étapes :**

1. ✅ Cliquez sur le numéro de facture ou l'icône "Voir"

**Page de Détails**

```
┌────────────────────────────────────────────────────────┐
│  ← Retour        FACTURE N° FACT-2025-000123          │
├────────────────────────────────────────────────────────┤
│  Date de vente : 17 novembre 2025                      │
│  Vendeur : Jean Dupont                                 │
│                                                         │
│  CLIENT                                                 │
│  Nom : Martin Durand                                   │
│  Email : martin.durand@email.com                       │
│  Téléphone : 06 12 34 56 78                            │
│  Adresse : 123 Rue de Paris, 75001 Paris              │
│                                                         │
│  VÉHICULE                                               │
│  Peugeot 308 (2023)                                    │
│  Immatriculation : AA-123-BB                           │
│  Kilométrage : 25 000 km                               │
│                                                         │
│  MONTANTS                                               │
│  Prix de vente TTC : 18 900,00 €                       │
│  dont TVA (20%) : 3 150,00 €                           │
│  Prix HT : 15 750,00 €                                 │
│                                                         │
│  Prix d'achat : 15 000,00 €                            │
│  Marge réalisée : 3 900,00 € (26%)                     │
│                                                         │
│  MODE DE PAIEMENT                                       │
│  Crédit sur 48 mois                                    │
│                                                         │
│  [📄 Télécharger la facture PDF]                       │
│  [✉️ Envoyer par email au client]                      │
└────────────────────────────────────────────────────────┘
```

### 5.5 Modifier une Vente

**Qui peut le faire ?** Directeur, Super Admin (pas les Vendeurs)

**Restrictions :**
- ⚠️ Modification possible uniquement dans les 24h suivant la vente
- ⚠️ Certains champs ne peuvent pas être modifiés (numéro facture, date)

**Étapes :**

1. ✅ Cliquez sur "Modifier"
2. ✅ Modifiez les champs autorisés (prix, mode paiement, commentaires)
3. ✅ Cliquez sur "Enregistrer"

### 5.6 Annuler une Vente

**Qui peut le faire ?** Directeur, Super Admin

⚠️ **Attention** : Action sensible !

**Cas d'usage :**
- Vente enregistrée par erreur
- Client se rétracte (délai légal de rétractation)
- Erreur de saisie majeure

**Étapes :**

1. ✅ Dans les détails de la vente, cliquez sur **"Annuler la vente"**
2. ✅ Indiquez le motif de l'annulation
3. ✅ Confirmez

**Conséquences :**
- ✅ Vente marquée comme "Annulée" (pas supprimée, pour traçabilité)
- ✅ Véhicule repasse automatiquement en statut "Stock"
- ✅ Facture annotée "ANNULÉE"

📝 **Important** : L'historique est conservé pour l'audit.

### 5.7 Télécharger / Imprimer une Facture

**Étapes :**

1. ✅ Dans les détails de la vente, cliquez sur **"Télécharger la facture PDF"**
2. ✅ La facture se télécharge au format PDF
3. ✅ Ouvrez le PDF et imprimez (Ctrl+P ou Cmd+P)

💡 **Astuce** : Vous pouvez aussi envoyer la facture directement par email au client en cliquant sur "Envoyer par email".

---

## 6. Module Clients

### 6.1 Accéder au Module

Cliquez sur **"Clients"** dans le menu principal.

### 6.2 Liste des Clients

| Colonne | Description |
|---------|-------------|
| **Nom** | Nom complet du client |
| **Email** | Adresse email |
| **Téléphone** | Numéro de téléphone |
| **Ville** | Ville de résidence |
| **Nombre d'achats** | Nombre de véhicules achetés |
| **CA Total** | Chiffre d'affaires généré |
| **Actions** | Voir / Modifier / Supprimer |

### 6.3 Ajouter un Client

**Qui peut le faire ?** Vendeur, Directeur, Super Admin

**Étapes :**

1. ✅ Cliquez sur **"+ Nouveau Client"**

**Formulaire Client**

```
┌─────────────────────────────────────────────────┐
│  INFORMATIONS CLIENT                            │
│                                                  │
│  Civilité * : ☑ M.  ☐ Mme  ☐ Autre            │
│                                                  │
│  Nom *     : [Dupont_______]                    │
│  Prénom *  : [Jean_________]                    │
│                                                  │
│  CONTACT                                         │
│                                                  │
│  Email *   : [jean.dupont@email.com]           │
│  Téléphone*: [0612345678___]                    │
│  Tel. fixe : [0123456789___]  (optionnel)      │
│                                                  │
│  ADRESSE                                         │
│                                                  │
│  Adresse * : [123 Rue de Paris__________]      │
│  Ville *   : [Paris_______]                     │
│  Code Postal*:[75001]                           │
│  Pays      : [France______]                     │
│                                                  │
│  NOTES                                           │
│                                                  │
│  [Client fidèle, préfère les SUV_____]         │
│  [_________________________________]             │
│                                                  │
│  [ Annuler ]         [✅ Enregistrer]           │
└─────────────────────────────────────────────────┘
```

2. ✅ Remplissez les champs obligatoires (marqués d'un *)
3. ✅ Cliquez sur **"Enregistrer"**

📝 **Important** : L'email doit être unique (un client = un email).

### 6.4 Rechercher un Client

**Barre de recherche :**

```
Recherche : [Dupont_______________] [🔍]
```

1. ✅ Tapez le nom, prénom, email ou téléphone
2. ✅ Cliquez sur "Rechercher" ou appuyez sur Entrée
3. ✅ Les résultats s'affichent en temps réel

💡 **Astuce** : La recherche fonctionne même avec des mots partiels (ex: "Dup" trouvera "Dupont").

### 6.5 Consulter la Fiche Client

**Étapes :**

1. ✅ Cliquez sur le nom du client ou l'icône "Voir"

**Page Fiche Client**

```
┌────────────────────────────────────────────────────────┐
│  ← Retour                    [Modifier] [Supprimer]   │
├────────────────────────────────────────────────────────┤
│  👤 M. Jean DUPONT                                     │
│                                                         │
│  CONTACT                                                │
│  ✉️ jean.dupont@email.com                              │
│  📱 06 12 34 56 78                                     │
│  📍 123 Rue de Paris, 75001 Paris                      │
│                                                         │
│  STATISTIQUES                                           │
│  Nombre d'achats : 2                                   │
│  CA Total : 35 800 €                                   │
│  Client depuis : 15/03/2023                            │
│                                                         │
│  HISTORIQUE DES ACHATS                                 │
│  ┌──────────────────────────────────────────────────┐ │
│  │ 17/11/2025 - Peugeot 308 (18 900 €)            │ │
│  │ 12/05/2024 - Renault Clio (16 900 €)           │ │
│  └──────────────────────────────────────────────────┘ │
│                                                         │
│  NOTES                                                  │
│  Client fidèle, préfère les SUV                        │
│                                                         │
│  [+ Nouvelle vente pour ce client]                     │
└────────────────────────────────────────────────────────┘
```

💡 **Astuce** : Depuis la fiche client, vous pouvez directement lancer une nouvelle vente en cliquant sur le bouton dédié.

### 6.6 Modifier un Client

**Étapes :**

1. ✅ Cliquez sur "Modifier"
2. ✅ Modifiez les champs souhaités (même formulaire que l'ajout)
3. ✅ Cliquez sur "Enregistrer"

**Cas d'usage :**
- Mise à jour du numéro de téléphone
- Changement d'adresse
- Ajout de notes

### 6.7 Supprimer un Client

**Qui peut le faire ?** Directeur, Super Admin

⚠️ **Restriction** : Vous ne pouvez pas supprimer un client qui a des ventes associées.

**Alternative** : Marquer le client comme "Inactif" dans les notes.

---

## 7. Module Demandes d'Achat

### 7.1 Accéder au Module

Cliquez sur **"Demandes d'Achat"** dans le menu principal.

### 7.2 Qu'est-ce qu'une Demande d'Achat ?

Une **demande d'achat** (ou commande fournisseur) est une demande pour acquérir un véhicule auprès d'un fournisseur.

**Workflow :**
```
1. Création → 2. En attente → 3. Validée → 4. Véhicule reçu → 5. Ajouté au stock
```

### 7.3 Liste des Demandes

| Colonne | Description |
|---------|-------------|
| **N° Demande** | Numéro unique |
| **Date** | Date de création |
| **Véhicule** | Marque / Modèle souhaité |
| **Fournisseur** | Nom du fournisseur |
| **Prix** | Prix d'achat prévu |
| **Statut** | En attente / Validée / Refusée / Reçue |
| **Actions** | Voir / Valider / Refuser |

**Statuts avec couleurs :**
- 🟡 **En attente** : Demande créée, en attente de validation
- 🟢 **Validée** : Demande approuvée, en cours de commande
- 🔴 **Refusée** : Demande rejetée
- 🔵 **Reçue** : Véhicule reçu, prêt à être ajouté au stock

### 7.4 Créer une Demande d'Achat

**Qui peut le faire ?** Vendeur, Magasinier, Directeur, Super Admin

**Étapes :**

1. ✅ Cliquez sur **"+ Nouvelle Demande"**

**Formulaire Demande d'Achat**

```
┌─────────────────────────────────────────────────┐
│  NOUVELLE DEMANDE D'ACHAT                       │
│                                                  │
│  VÉHICULE SOUHAITÉ                              │
│                                                  │
│  Marque *  : [Peugeot______]                    │
│  Modèle *  : [3008_________]                    │
│  Année *   : [2024_]                            │
│  Finition  : [GT Line_____]  (optionnel)       │
│                                                  │
│  FOURNISSEUR                                     │
│                                                  │
│  Nom *     : [Auto Distribution SA_____]        │
│  Contact   : [M. Durand____________]            │
│  Email     : [durand@autodist.com__]            │
│  Téléphone : [0123456789___]                    │
│                                                  │
│  PRIX                                            │
│                                                  │
│  Prix d'achat estimé * : [28000__] €           │
│                                                  │
│  JUSTIFICATION                                   │
│                                                  │
│  [Client a demandé ce modèle spécifique___]    │
│  [Stock faible sur les SUV______________]       │
│                                                  │
│  [ Annuler ]         [✅ Soumettre]             │
└─────────────────────────────────────────────────┘
```

2. ✅ Remplissez le formulaire
3. ✅ Cliquez sur **"Soumettre"**

**Résultat :**
- Demande créée avec statut "En attente"
- Notification envoyée au Directeur pour validation
- Numéro de demande généré (ex: DA-2025-001)

### 7.5 Valider / Refuser une Demande

**Qui peut le faire ?** Directeur, Super Admin

**Étapes :**

1. ✅ Cliquez sur la demande
2. ✅ Consultez les détails
3. ✅ Cliquez sur **"Valider"** ou **"Refuser"**
4. ✅ Si refus, indiquez un motif

**Après validation :**
- Statut passe à "Validée"
- Le Magasinier peut passer la commande chez le fournisseur

### 7.6 Marquer comme Reçue

**Qui peut le faire ?** Magasinier, Directeur, Super Admin

Quand le véhicule est physiquement arrivé :

1. ✅ Ouvrez la demande
2. ✅ Cliquez sur **"Marquer comme reçue"**
3. ✅ Saisissez les informations réelles (immatriculation, kilométrage, prix final)
4. ✅ Cliquez sur **"Ajouter au stock"**

**Résultat :**
- Véhicule automatiquement créé dans le module Véhicules
- Demande clôturée

---

## 8. Module Employés (RH)

### 8.1 Accéder au Module

Cliquez sur **"Employés"** dans le menu principal.

**Qui peut accéder ?** Directeur, RH, Super Admin

### 8.2 Liste des Employés

| Colonne | Description |
|---------|-------------|
| **Nom** | Nom complet |
| **Poste** | Fonction (Vendeur, Magasinier, etc.) |
| **Salaire** | Salaire mensuel brut |
| **Date d'embauche** | Date d'entrée |
| **Statut** | Actif / En congé / Inactif |
| **Actions** | Voir / Modifier / Générer paie |

### 8.3 Ajouter un Employé

**Qui peut le faire ?** RH, Directeur, Super Admin

**Étapes :**

1. ✅ Cliquez sur **"+ Nouvel Employé"**
2. ✅ Remplissez le formulaire (similaire au formulaire client + informations RH)
3. ✅ Définissez le salaire et le poste
4. ✅ Cliquez sur "Enregistrer"

### 8.4 Générer une Fiche de Paie

**Qui peut le faire ?** RH, Directeur, Super Admin

**Étapes :**

1. ✅ Dans la liste des employés, cliquez sur **"Générer paie"** pour l'employé concerné
2. ✅ Sélectionnez le mois

**Formulaire Paie**

```
┌─────────────────────────────────────────────────┐
│  GÉNÉRATION DE PAIE                             │
│                                                  │
│  Employé : Jean Dupont - Vendeur               │
│  Mois : [Novembre 2025 ▼]                      │
│                                                  │
│  ÉLÉMENTS DE PAIE                               │
│                                                  │
│  Salaire de base : [2000__] €                  │
│  Heures supplémentaires : [10_] h × 15,50€    │
│    = 155,00 €                                   │
│  Primes : [200__] €                            │
│                                                  │
│  ────────────────────────────────                │
│  Salaire brut : 2 355,00 €                     │
│                                                  │
│  Cotisations salariales (23%) : -541,65 €     │
│  ────────────────────────────────                │
│  SALAIRE NET : 1 813,35 €                      │
│                                                  │
│  Cotisations patronales (42%) : 989,10 €      │
│  Coût total employeur : 3 344,10 €            │
│                                                  │
│  [ Annuler ]    [✅ Générer et enregistrer]    │
└─────────────────────────────────────────────────┘
```

3. ✅ Vérifiez les montants
4. ✅ Cliquez sur **"Générer et enregistrer"**

**Résultat :**
- Fiche de paie enregistrée en base
- PDF généré et téléchargeable
- Email envoyé automatiquement à l'employé (si configuré)

💡 **Astuce** : Vous pouvez générer plusieurs paies en une fois pour tous les employés du mois via le bouton "Générer toutes les paies".

### 8.5 Consulter l'Historique des Paies

Dans la fiche employé, section "Historique des paies" :

```
┌────────────────────────────────────────────────────────┐
│  HISTORIQUE DES PAIES - Jean Dupont                    │
│                                                         │
│  ┌──────────────────────────────────────────────────┐ │
│  │ Novembre 2025 - Net: 1 813,35€  [📄 Télécharger]│ │
│  │ Octobre 2025  - Net: 1 800,00€  [📄 Télécharger]│ │
│  │ Septembre 2025- Net: 1 800,00€  [📄 Télécharger]│ │
│  └──────────────────────────────────────────────────┘ │
└────────────────────────────────────────────────────────┘
```

---

## 9. Module Stock

### 9.1 Accéder au Module

Cliquez sur **"Stock"** dans le menu principal.

**Qui peut accéder ?** Magasinier, Directeur, Super Admin

### 9.2 Vue d'Ensemble

Le module Stock affiche :

**Indicateurs**
- Nombre total de véhicules en stock
- Valeur totale du stock (prix d'achat)
- Rotation du stock (durée moyenne de vente)
- Alertes (véhicules en stock depuis > 90 jours)

**Liste des Véhicules en Stock**

Similaire au module Véhicules, mais filtrée uniquement sur les véhicules "En Stock".

### 9.3 Alertes Stock

**Véhicules en stock depuis longtemps (> 90 jours) :**

```
⚠️ ALERTES STOCK

┌────────────────────────────────────────────────────────┐
│  Peugeot 208 (AA-123-BB) - En stock depuis 120 jours │
│  Prix : 15 900 € - Envisager une remise ?            │
│                                             [Voir]     │
├────────────────────────────────────────────────────────┤
│  Renault Clio (BB-456-CC) - En stock depuis 95 jours │
│  Prix : 16 500 €                                       │
│                                             [Voir]     │
└────────────────────────────────────────────────────────┘
```

💡 **Astuce** : Ces alertes vous aident à identifier les véhicules à promouvoir ou dont le prix doit être ajusté.

### 9.4 Mouvements de Stock

**Consulter l'historique :**

```
MOUVEMENTS DE STOCK

┌────────────────────────────────────────────────────────┐
│ 17/11/2025 - SORTIE - Peugeot 308 (AA-123-BB)        │
│              Motif : Vente (Facture #FACT-2025-123)   │
├────────────────────────────────────────────────────────┤
│ 15/11/2025 - ENTRÉE - Renault Clio (BB-456-CC)       │
│              Motif : Réception commande #DA-2025-045  │
├────────────────────────────────────────────────────────┤
│ 10/11/2025 - SORTIE - Citroën C3 (CC-789-DD)         │
│              Motif : Vente (Facture #FACT-2025-118)   │
└────────────────────────────────────────────────────────┘
```

---

## 10. Module Statistiques

### 10.1 Accéder au Module

Cliquez sur **"Statistiques"** dans le menu principal.

**Qui peut accéder ?** Directeur, Comptable, Super Admin

### 10.2 Tableaux de Bord

**KPIs Principaux**

```
┌─────────────────────────────────────────────────────────┐
│  PÉRIODE : Novembre 2025                    [Modifier]  │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐             │
│  │ 45 ventes│  │ 125 200€ │  │   18.5%  │             │
│  │ Ce mois  │  │    CA    │  │  Marge   │             │
│  └──────────┘  └──────────┘  └──────────┘             │
│                                                          │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐             │
│  │ 2 784€   │  │ 23 véh.  │  │ 487 300€ │             │
│  │ Panier   │  │ En stock │  │ Valeur   │             │
│  └──────────┘  └──────────┘  └──────────┘             │
└─────────────────────────────────────────────────────────┘
```

### 10.3 Graphiques

**Évolution du CA (Courbe)**

Graphique linéaire montrant l'évolution du chiffre d'affaires sur 12 mois.

**Répartition par Marque (Camembert)**

```
        Peugeot (35%)
       /          \
  Renault (25%)   Citroën (15%)
       \          /
    Autres (25%)
```

**Top 5 Vendeurs (Barres)**

```
Jean Dupont    ████████████████ 15 ventes
Marie Martin   ████████████     12 ventes
Luc Moreau     ██████████       10 ventes
Sophie Bernard ████████          8 ventes
Marc Lefebvre  ██████            6 ventes
```

### 10.4 Exporter les Statistiques

**Formats disponibles :**
- 📊 Excel (.xlsx)
- 📄 PDF
- 📋 CSV

**Étapes :**

1. ✅ Sélectionnez la période
2. ✅ Cliquez sur **"Exporter"**
3. ✅ Choisissez le format
4. ✅ Le fichier se télécharge

💡 **Astuce** : Les exports sont utiles pour les présentations ou l'analyse externe.

---

## 11. Module Administration

### 11.1 Accéder au Module

Cliquez sur **"Administration"** dans le menu principal.

**Qui peut accéder ?** Super Admin uniquement

### 11.2 Gestion des Utilisateurs

Voir la section détaillée dans le **Guide d'Administration** (document séparé).

**Fonctionnalités :**
- Créer / Modifier / Supprimer des utilisateurs
- Attribuer des rôles
- Désactiver des comptes
- Réinitialiser des mots de passe

### 11.3 Gestion des Rôles

**Rôles par défaut :**
- Super Admin
- Directeur
- Vendeur
- Comptable
- Magasinier
- RH

Vous pouvez créer des rôles personnalisés avec des permissions spécifiques.

### 11.4 Logs et Audit

**Consulter les logs de connexion :**

```
HISTORIQUE DES CONNEXIONS

┌────────────────────────────────────────────────────────┐
│ 17/11/2025 10:45 - Jean Dupont (Vendeur)             │
│ IP: 192.168.1.45 - Succès                             │
├────────────────────────────────────────────────────────┤
│ 17/11/2025 10:30 - admin@pgi.local (Super Admin)     │
│ IP: 192.168.1.10 - Succès                             │
├────────────────────────────────────────────────────────┤
│ 17/11/2025 09:15 - vendeur@pgi.local (Vendeur)       │
│ IP: 192.168.1.52 - Échec (mot de passe incorrect)    │
└────────────────────────────────────────────────────────┘
```

💡 **Astuce** : Surveillez les tentatives échouées pour détecter d'éventuelles tentatives d'intrusion.

### 11.5 Paramètres Système

**Configuration générale :**
- Nom de l'entreprise
- Logo
- Fuseau horaire
- Format des dates
- Devise
- TVA par défaut

---

## 12. Conseils et Astuces

### 12.1 Raccourcis Clavier

| Raccourci | Action |
|-----------|--------|
| **Ctrl+S** (Cmd+S) | Enregistrer un formulaire |
| **Échap** | Fermer une fenêtre modale |
| **Ctrl+F** (Cmd+F) | Rechercher dans la page |
| **Tab** | Naviguer entre les champs |

### 12.2 Navigation Rapide

💡 **Utilisez les liens contextuels :**
- Depuis une vente → Cliquez sur le nom du client pour voir sa fiche
- Depuis une vente → Cliquez sur le véhicule pour voir ses détails
- Depuis un véhicule → Voir les demandes d'achat liées

### 12.3 Filtres et Tri

**Dans toutes les listes :**
- Cliquez sur un en-tête de colonne pour trier (↑↓)
- Utilisez les filtres en haut de page pour affiner

💡 **Astuce** : Combinez recherche + filtres + tri pour trouver rapidement ce que vous cherchez.

### 12.4 Pagination

Si la liste contient plus de 20 éléments, utilisez la pagination en bas de page :

```
[◀ Précédent]  [1] [2] [3] ... [10]  [Suivant ▶]
```

💡 **Astuce** : Vous pouvez changer le nombre d'éléments par page (20, 50, 100).

### 12.5 Messages et Notifications

**Types de messages :**

✅ **Succès (vert)** : Action réussie
```
✅ Véhicule ajouté avec succès !
```

❌ **Erreur (rouge)** : Action échouée
```
❌ Erreur : Ce véhicule n'est plus disponible.
```

⚠️ **Attention (orange)** : Avertissement
```
⚠️ Attention : Ce véhicule est en stock depuis plus de 90 jours.
```

ℹ️ **Information (bleu)** : Information générale
```
ℹ️ Aucun véhicule ne correspond à votre recherche.
```

### 12.6 Sécurité et Bonnes Pratiques

✅ **DO (À faire) :**
- Déconnectez-vous en fin de journée
- Utilisez un mot de passe fort et unique
- Changez votre mot de passe régulièrement (tous les 3 mois)
- Vérifiez toujours les données avant de valider une vente
- Faites des sauvegardes régulières (si vous êtes admin)

❌ **DON'T (À ne pas faire) :**
- Ne partagez JAMAIS votre mot de passe
- Ne laissez pas votre session ouverte sur un poste non sécurisé
- N'utilisez pas de mots de passe simples (ex: "123456", "password")
- Ne supprimez pas de données sans être sûr
- N'enregistrez pas de ventes fictives

### 12.7 Accessibilité

**Navigation au clavier :**
- Utilisez **Tab** pour naviguer entre les champs
- **Entrée** pour valider
- **Échap** pour annuler

**Zoom :**
- **Ctrl + +** (Cmd + +) : Agrandir
- **Ctrl + -** (Cmd + -) : Réduire
- **Ctrl + 0** (Cmd + 0) : Zoom par défaut

### 12.8 Compatibilité Mobile

Le système est **responsive** et fonctionne sur :
- 💻 Ordinateurs (Windows, Mac, Linux)
- 📱 Tablettes (iPad, Android)
- 📱 Smartphones (iOS, Android)

💡 **Astuce Mobile** : Sur petit écran, le menu devient un "burger menu" (☰) accessible en haut à gauche.

### 12.9 Support

**Besoin d'aide ?**

1. **Consultez la FAQ** : Voir document "17_faq_support.md"
2. **Contactez votre responsable** : Il peut vous guider
3. **Contactez le support technique** : support@votreentreprise.com

**Signaler un bug :**

Envoyez un email avec :
- Votre nom et rôle
- Description du problème
- Captures d'écran si possible
- Étapes pour reproduire le bug

### 12.10 Mises à Jour

Le système est mis à jour régulièrement. Consultez le **Journal des Modifications** (document "18_journal_modifications.md") pour connaître les nouveautés.

📝 **Note** : Vous serez notifié par email avant chaque mise à jour majeure.

---

## Conclusion

Vous avez maintenant toutes les clés en main pour utiliser efficacement **PGI Automobile** !

**Résumé des Modules :**

| Module | Rôles | Fonction Principale |
|--------|-------|---------------------|
| **Tableau de Bord** | Tous | Vue d'ensemble KPIs |
| **Véhicules** | Magasinier, Directeur | Gestion du parc |
| **Ventes** | Vendeur, Directeur | Enregistrement ventes |
| **Clients** | Vendeur, Directeur | Gestion clients |
| **Demandes Achat** | Tous (sauf Comptable) | Commandes fournisseurs |
| **Employés** | RH, Directeur | Gestion RH et paies |
| **Stock** | Magasinier, Directeur | Suivi du stock |
| **Statistiques** | Directeur, Comptable | Analyses et rapports |
| **Administration** | Super Admin | Paramétrage système |

**Pour aller plus loin :**
- **FAQ / Support** : Document 17_faq_support.md
- **Guide d'Administration** : Document 15_guide_administration.md
- **Journal des Modifications** : Document 18_journal_modifications.md

**Bon travail avec PGI Automobile ! 🚗**

---

**Document Version :** 1.0
**Dernière mise à jour :** 17/11/2025
**Auteur :** Équipe PGI Automobile

**Contact Support :** support@votreentreprise.com
