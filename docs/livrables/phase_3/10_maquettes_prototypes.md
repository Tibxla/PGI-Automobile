# Maquettes et Prototypes - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile
**Version:** 1.0 (Projet Académique)
**Date:** Novembre 2025
**Auteurs:** Thibaud & Melissa
**Contexte:** Projet de L3 - Période du 27/10/2025 au 17/11/2025 (3 semaines)
**Statut:** Validé

---

## 1. Introduction

### 1.1 Objet du Document

Ce document présente les maquettes d'interface, les parcours utilisateur (user flows), l'ergonomie responsive et la charte graphique du PGI Automobile.

### 1.2 Public Cible

- Designers UI/UX
- Développeurs frontend
- Testeurs
- Utilisateurs finaux (validation)

---

## 2. Charte Graphique

### 2.1 Palette de Couleurs

Le système utilise une palette moderne basée sur des **gradients violets** avec des couleurs fonctionnelles.

#### Couleurs Principales

```css
:root {
    /* Couleurs principales (gradient violet) */
    --primary: #667eea;           /* Violet principal */
    --primary-dark: #764ba2;      /* Violet foncé */
    --secondary: #4facfe;         /* Bleu secondaire */

    /* Couleurs fonctionnelles */
    --success: #10b981;           /* Vert (succès) */
    --danger: #ef4444;            /* Rouge (erreur) */
    --warning: #f59e0b;           /* Orange (alerte) */
    --info: #17a2b8;              /* Bleu info */

    /* Couleurs neutres */
    --dark: #1f2937;              /* Texte foncé */
    --gray: #6b7280;              /* Gris moyen */
    --light: #f3f4f6;             /* Fond clair */
    --white: #ffffff;             /* Blanc */
}
```

#### Gradients Réutilisables

```css
--gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
--gradient-success: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
--gradient-info: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
--gradient-warning: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
```

### 2.2 Typographie

| Élément | Police | Taille | Poids | Usage |
|---------|--------|--------|-------|-------|
| **Titres H1** | Segoe UI | 32px | 700 (Bold) | Titres pages principales |
| **Titres H2** | Segoe UI | 24px | 600 (Semi-Bold) | Sections |
| **Titres H3** | Segoe UI | 20px | 600 (Semi-Bold) | Sous-sections |
| **Corps de texte** | Segoe UI | 16px | 400 (Regular) | Texte standard |
| **Texte secondaire** | Segoe UI | 14px | 400 (Regular) | Labels, aides |
| **Petit texte** | Segoe UI | 12px | 400 (Regular) | Notes, copyright |

**Polices de secours** : `Tahoma, Geneva, Verdana, sans-serif`

### 2.3 Iconographie

**Émojis Unicode** pour simplicité (pas de bibliothèque d'icônes)

| Icône | Unicode | Usage |
|-------|---------|-------|
| 🚗 | U+1F697 | Véhicules, logo |
| 👤 | U+1F464 | Utilisateur, profil |
| 📊 | U+1F4CA | Statistiques |
| 💰 | U+1F4B0 | Ventes, finances |
| 📝 | U+1F4DD | Formulaires |
| ✏️ | U+270F | Modifier |
| 🗑️ | U+1F5D1 | Supprimer |
| ✅ | U+2705 | Succès |
| ❌ | U+274C | Erreur |

### 2.4 Composants de Base

#### Boutons

```
┌─────────────────────┐
│  ✓ Bouton Primary   │  --gradient-primary
└─────────────────────┘

┌─────────────────────┐
│  + Bouton Success   │  --success
└─────────────────────┘

┌─────────────────────┐
│  ⚠ Bouton Warning   │  --warning
└─────────────────────┘

┌─────────────────────┐
│  ✗ Bouton Danger    │  --danger
└─────────────────────┘
```

#### Badges de Statut

```
[stock]    (badge bleu)
[vendu]    (badge vert)
[réservé]  (badge orange)
```

#### Cards avec Ombre

```
┌────────────────────────────────────┐
│  Card avec Shadow                  │
│  ────────────────────────────────  │
│  Contenu de la card                │
│  border-radius: 12px               │
│  box-shadow: 0 4px 6px rgba(...)   │
└────────────────────────────────────┘
```

---

## 3. Maquettes d'Écrans

### 3.1 Page Connexion (`login.php`)

```
┌────────────────────────────────────────────────────────────┐
│                                                            │
│                      🚗 PGI AUTOMOBILE                      │
│                                                            │
│  ┌────────────────────────────────────────────────────┐   │
│  │                                                    │   │
│  │        Connexion à votre espace                    │   │
│  │                                                    │   │
│  │  Email                                             │   │
│  │  ┌──────────────────────────────────────────────┐ │   │
│  │  │ exemple@pgi-auto.com                         │ │   │
│  │  └──────────────────────────────────────────────┘ │   │
│  │                                                    │   │
│  │  Mot de passe                                      │   │
│  │  ┌──────────────────────────────────────────────┐ │   │
│  │  │ ••••••••                                     │ │   │
│  │  └──────────────────────────────────────────────┘ │   │
│  │                                                    │   │
│  │  ┌──────────────────────────────────────────────┐ │   │
│  │  │         🔒 Se connecter                      │ │   │
│  │  └──────────────────────────────────────────────┘ │   │
│  │                                                    │   │
│  │  Vous êtes client ?                                │   │
│  │  [Créer un compte]                                 │   │
│  │                                                    │   │
│  └────────────────────────────────────────────────────┘   │
│                                                            │
│  💡 Comptes de test disponibles (en développement)        │
│  • admin@pgi-auto.com / password123                       │
│  • vendeur@pgi-auto.com / password123                     │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### 3.2 Catalogue Public (`catalogue.php`)

**Design : Glassmorphism Moderne**

```
┌────────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile          [Connexion] [Inscription Client]│
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Nos Véhicules Disponibles                                │
│                                                            │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                │
│  │Type: Tous│  │Carb.: ▼  │  │🔍 Rechercher              │
│  └──────────┘  └──────────┘  └──────────┘                │
│                                                            │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐│
│  │  [📷 Image]     │  │  [📷 Image]     │  │  [📷]       ││
│  │                 │  │                 │  │             ││
│  │ Peugeot 208     │  │ BMW Série 3     │  │ Tesla M3    ││
│  │ 2023            │  │ 2022            │  │ 2023        ││
│  │ Citadine 🚗     │  │ Berline 🚙      │  │ Berline ⚡   ││
│  │ 18 500 €        │  │ 35 000 €        │  │ 42 000 €    ││
│  │                 │  │                 │  │             ││
│  │ [💬 Demander]   │  │ [💬 Demander]   │  │ [💬]        ││
│  └─────────────────┘  └─────────────────┘  └─────────────┘│
│                                                            │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐│
│  │  [📷]           │  │  [📷]           │  │  [📷]       ││
│  │ ...             │  │ ...             │  │ ...         ││
│  └─────────────────┘  └─────────────────┘  └─────────────┘│
│                                                            │
└────────────────────────────────────────────────────────────┘
```

**Caractéristiques** :
- Grille responsive (3 colonnes desktop, 2 tablette, 1 mobile)
- Cards glassmorphism (transparence + flou)
- Filtres dynamiques JavaScript
- Lazy loading images

### 3.3 Dashboard Employé (`modules/vehicules/liste.php`)

```
┌────────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile                   Sophie Martin (Vendeur)│
│                                     [Mon Profil] [Déconnexi│
├────────────────────────────────────────────────────────────┤
│ 🚗 Véhicules | 💰 Ventes | 👥 Clients | 📊 Statistiques    │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Liste des Véhicules (25)           [+ Ajouter un véhicule│
│                                                            │
│  Filtres :                                                 │
│  ┌─────────┐ ┌──────────┐ ┌─────────┐ ┌──────────────┐   │
│  │Type: ▼  │ │Carb.: ▼  │ │Statut:▼ │ │🔍 Rechercher │   │
│  └─────────┘ └──────────┘ └─────────┘ └──────────────┘   │
│  [Réinitialiser filtres]                                   │
│                                                            │
│  ┌────────────────────────────────────────────────────┐   │
│  │Image│Véhicule        │Année│Prix   │Marge │Stat.│Act.│
│  ├────────────────────────────────────────────────────┤   │
│  │[📷]│Peugeot 208     │2023 │18500€│+3500€│stock│✏️🗑️│   │
│  │[📷]│BMW Série 3     │2022 │35000€│+5000€│vendu│✏️  │   │
│  │[📷]│Tesla Model 3   │2023 │42000€│+4000€│stock│✏️🗑️│   │
│  │[📷]│Renault Clio    │2023 │16000€│+2500€│stock│✏️🗑️│   │
│  │...                                                   │   │
│  └────────────────────────────────────────────────────┘   │
│                                                            │
│  Pagination : [1] 2 3 ... 5                                │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### 3.4 Formulaire Ajout Véhicule (`modules/vehicules/ajouter.php`)

```
┌────────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile                   Sophie Martin (Gest. St│
├────────────────────────────────────────────────────────────┤
│ Véhicules > Ajouter un véhicule                            │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  Informations Générales                                    │
│  ┌──────────────────┐  ┌──────────────────┐              │
│  │ Marque *         │  │ Modèle *         │              │
│  │ Peugeot          │  │ 208 GT Line      │              │
│  └──────────────────┘  └──────────────────┘              │
│                                                            │
│  ┌────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │Année * │  │Type *        │  │Carburant *   │          │
│  │2023    │  │▼ citadine    │  │▼ essence     │          │
│  └────────┘  └──────────────┘  └──────────────┘          │
│                                                            │
│  Informations Commerciales                                 │
│  ┌──────────────────┐  ┌──────────────────┐              │
│  │Prix d'achat * €  │  │Prix de vente * € │              │
│  │15000             │  │18500             │              │
│  └──────────────────┘  └──────────────────┘              │
│  💡 Marge : 3500€ (calculée automatiquement)              │
│                                                            │
│  Caractéristiques                                          │
│  ┌────────────────┐  ┌──────────────────┐                │
│  │Kilométrage *   │  │Couleur           │                │
│  │5000 km         │  │Blanc Nacré       │                │
│  └────────────────┘  └──────────────────┘                │
│                                                            │
│  ┌──────────────────┐  ┌──────────────┐                  │
│  │Immatriculation * │  │Statut *      │                  │
│  │AB-123-CD         │  │▼ stock       │                  │
│  └──────────────────┘  └──────────────┘                  │
│                                                            │
│  Image                                                     │
│  ┌──────────────────────────────────────┐                 │
│  │[Choisir un fichier]  Aucun fichier   │                 │
│  └──────────────────────────────────────┘                 │
│  Formats acceptés : JPG, PNG, WebP (max 5 MB)             │
│                                                            │
│  ┌──────────────┐  ┌──────────┐                           │
│  │✓ Enregistrer │  │ Annuler  │                           │
│  └──────────────┘  └──────────┘                           │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

### 3.5 Dashboard Statistiques (`modules/statistiques/dashboard.php`)

```
┌────────────────────────────────────────────────────────────┐
│ 🚗 PGI Automobile                    Marc (Comptable)      │
├────────────────────────────────────────────────────────────┤
│ Statistiques & Tableaux de Bord                            │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  KPI Année 2023                                            │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌──────┐│
│  │ 48 ventes   │ │  720 000 €  │ │  15 000 €   │ │120k€ ││
│  │ (gradient)  │ │  CA Total   │ │  Panier moy │ │Marge ││
│  └─────────────┘ └─────────────┘ └─────────────┘ └──────┘│
│                                                            │
│  Évolution Mensuelle (6 derniers mois)                     │
│  ┌────────────────────────────────────────────────────┐   │
│  │  📈 Graphique Courbe                               │   │
│  │     |                                              │   │
│  │  CA |     *─*                                      │   │
│  │     |   *     *─*                                  │   │
│  │     | *           *─*                              │   │
│  │     |─────────────────────────────                 │   │
│  │      Mar Avr Mai Jun Jul Aoû                       │   │
│  └────────────────────────────────────────────────────┘   │
│                                                            │
│  ┌──────────────────────┐  ┌──────────────────────────┐  │
│  │ Top 5 Marques        │  │ Top 5 Clients            │  │
│  │ ──────────────────── │  │ ──────────────────────── │  │
│  │ 1. Peugeot    12 ████│  │ 1. Dupont J.   75 000€ ██│  │
│  │ 2. Renault    10 ███ │  │ 2. Martin S.   62 000€ ██│  │
│  │ 3. Citroën     8 ██  │  │ 3. Bernard T.  48 000€ █ │  │
│  │ 4. Tesla       6 █   │  │ 4. Petit L.    42 000€ █ │  │
│  │ 5. BMW         5 █   │  │ 5. Garage C.   38 000€ █ │  │
│  └──────────────────────┘  └──────────────────────────┘  │
│                                                            │
└────────────────────────────────────────────────────────────┘
```

---

## 4. Parcours Utilisateur (User Flows)

### 4.1 Parcours Client : Demande d'Achat

```
START
  ↓
[1. Accès Catalogue Public]
  → Consultation véhicules disponibles
  ↓
[2. Sélection Véhicule]
  → Clic "💬 Demander ce véhicule"
  ↓
[3. Vérification Authentification]
  ├─ Si connecté → [4. Formulaire Pré-rempli]
  └─ Si non connecté → [Redirection Login/Inscription]
  ↓
[4. Formulaire Demande]
  → Saisie téléphone + message
  ↓
[5. Soumission]
  → Validation côté serveur
  ↓
[6. Confirmation]
  → Message "✅ Demande envoyée !"
  → Email confirmation (futur)
  ↓
[7. Suivi Demande]
  → Accès "Mes Demandes"
  → Consultation statut (en_attente → en_cours → acceptée)
  ↓
END
```

### 4.2 Parcours Vendeur : Enregistrer une Vente

```
START
  ↓
[1. Accès Module Ventes]
  → Clic "+ Nouvelle vente"
  ↓
[2. Sélection Véhicule]
  → Dropdown véhicules disponibles (stock/réservé)
  → Chargement automatique prix catalogue
  ↓
[3. Sélection Client]
  ├─ Client existant → Sélection dropdown
  └─ Nouveau client → [Modal Création Client]
  ↓
[4. Saisie Conditions Vente]
  → Prix négocié (modifiable)
  → Calcul marge temps réel (JavaScript)
  → Mode paiement (comptant/crédit/leasing)
  → Date vente
  → Notes (optionnel)
  ↓
[5. Validation]
  → Vérifications côté serveur
  ├─ Marge négative → [Warning + Confirmation]
  └─ OK → [6. Transaction SQL]
  ↓
[6. Transaction SQL]
  → BEGIN TRANSACTION
  → INSERT vente
  → UPDATE véhicule (statut = vendu)
  → COMMIT
  ↓
[7. Confirmation]
  → Redirection liste ventes
  → Message "✅ Vente enregistrée !"
  → Proposition "[📄 Générer facture]"
  ↓
END
```

### 4.3 Parcours RH : Génération Bulletin de Paie

```
START
  ↓
[1. Accès Module Paie]
  → Consultation liste bulletins existants
  → Clic "+ Créer bulletin"
  ↓
[2. Sélection Employé]
  → Dropdown employés actifs
  → Chargement automatique salaire base
  ↓
[3. Saisie Mois Référence]
  → Sélection mois (date picker)
  ↓
[4. Saisie Primes/Déductions]
  → Primes (défaut 0)
  → Déductions (défaut 0)
  → Calcul automatique net à payer (JavaScript)
  ↓
[5. Statut Bulletin]
  ├─ Brouillon → Modifiable ultérieurement
  └─ Validé → Immutable (pas de modification)
  ↓
[6. Enregistrement]
  → Validation côté serveur
  ├─ Erreur (bulletin existe déjà) → [Message erreur]
  └─ OK → [7. Insertion BDD]
  ↓
[7. Confirmation]
  → Redirection liste bulletins
  → Message "✅ Bulletin créé (brouillon)"
  ↓
[8. Validation Ultérieure (Optionnel)]
  → Clic "✓ Valider"
  → Bulletin passe en statut "validé"
  → Plus de modification possible
  ↓
END
```

---

## 5. Ergonomie et Responsive Design

### 5.1 Breakpoints Responsive

| Device | Largeur | Layout | Colonnes Grid |
|--------|---------|--------|---------------|
| **Desktop** | ≥ 1200px | Large écran | 3 colonnes (catalogue) |
| **Laptop** | 992-1199px | Écran moyen | 3 colonnes |
| **Tablette** | 768-991px | Portrait/Paysage | 2 colonnes |
| **Mobile Large** | 576-767px | Smartphone grand | 1 colonne |
| **Mobile** | < 576px | Smartphone | 1 colonne |

### 5.2 Adaptations Responsive

#### Navigation Desktop vs Mobile

**Desktop** :
```
[🚗 PGI Auto] [Véhicules] [Ventes] [Clients] [Stats] [👤 Profil ▼]
```

**Mobile** :
```
[🚗 PGI Auto]                                    [☰ Menu]

(Clic menu → Drawer latéral)
┌──────────────────┐
│ 🚗 Véhicules     │
│ 💰 Ventes        │
│ 👥 Clients       │
│ 📊 Statistiques  │
│ ──────────────── │
│ 👤 Mon Profil    │
│ 🚪 Déconnexion   │
└──────────────────┘
```

#### Tableaux Responsive

**Desktop** : Tableau complet (toutes colonnes)

**Mobile** : Cards empilées

```
┌────────────────────────────────┐
│ Peugeot 208 - 2023             │
│ Type: Citadine | Essence       │
│ Prix: 18 500€ | Marge: +3 500€ │
│ Statut: [stock]                │
│ [✏️ Modifier] [🗑️ Supprimer]   │
└────────────────────────────────┘

┌────────────────────────────────┐
│ BMW Série 3 - 2022             │
│ ...                            │
└────────────────────────────────┘
```

### 5.3 Accessibility (Accessibilité)

| Élément | Implémentation |
|---------|----------------|
| **Contraste** | Ratio ≥ 4.5:1 (WCAG AA) |
| **Navigation clavier** | Tab order logique, focus visible |
| **Labels formulaires** | `<label for="">` sur tous inputs |
| **Textes alternatifs** | `alt=""` sur toutes images |
| **ARIA** | `role`, `aria-label` sur composants interactifs |
| **Responsive** | Viewport meta tag, rem units |

---

## 6. Design System - Composants Réutilisables

### 6.1 Alertes

```html
<!-- Succès -->
<div class="alert alert-success">
    ✅ Opération réussie !
</div>

<!-- Erreur -->
<div class="alert alert-error">
    ❌ Une erreur est survenue.
</div>

<!-- Warning -->
<div class="alert alert-warning">
    ⚠️ Attention : marge négative détectée.
</div>

<!-- Info -->
<div class="alert alert-info">
    ℹ️ Aucun résultat trouvé.
</div>
```

**Styles** :
- Padding : 12px 16px
- Border-radius : 8px
- Border-left : 4px solid (couleur variant)
- Background : couleur variant 10% opacity

### 6.2 Boutons

```html
<!-- Primary -->
<button class="btn btn-primary">
    ✓ Enregistrer
</button>

<!-- Secondary -->
<button class="btn btn-secondary">
    Annuler
</button>

<!-- Danger -->
<button class="btn btn-danger">
    🗑️ Supprimer
</button>

<!-- Success -->
<button class="btn btn-success">
    + Ajouter
</button>
```

**Styles** :
- Padding : 10px 20px
- Border-radius : 8px
- Font-size : 16px
- Transition : 0.3s ease
- Hover : transform scale(1.05), box-shadow

### 6.3 Cards

```html
<div class="card">
    <div class="card-header">
        <h3>Titre de la Card</h3>
    </div>
    <div class="card-body">
        Contenu de la card...
    </div>
    <div class="card-footer">
        <button class="btn">Action</button>
    </div>
</div>
```

**Styles** :
- Background : white
- Border-radius : 12px
- Box-shadow : 0 4px 6px rgba(0,0,0,0.1)
- Padding : 20px
- Margin-bottom : 20px

### 6.4 Badges

```html
<span class="badge badge-stock">stock</span>
<span class="badge badge-vendu">vendu</span>
<span class="badge badge-reserve">réservé</span>
```

**Styles** :
- Padding : 4px 12px
- Border-radius : 20px (pill)
- Font-size : 12px
- Font-weight : 600

---

## 7. Prototypes Interactifs (Futur)

### 7.1 Outils Recommandés

| Outil | Usage | Coût |
|-------|-------|------|
| **Figma** | Design maquettes haute fidélité | Gratuit (3 projets) |
| **Balsamiq** | Wireframes rapides | 9$/mois |
| **InVision** | Prototypes cliquables | Gratuit (1 prototype) |
| **Adobe XD** | Design + prototypage | 12€/mois |

### 7.2 Fonctionnalités Interactives

**Prototype souhaitable** :
- Navigation entre écrans
- Formulaires fonctionnels (validation)
- Transitions animations
- States hover/focus/active
- Responsive preview (mobile/desktop)

---

## 8. Validation et Approbation

### 8.1 Tests Utilisateurs

| Profil | Scénario | Métrique Succès |
|--------|----------|-----------------|
| **Vendeur** | Enregistrer une vente | < 2 min, 0 erreur |
| **Client** | Créer demande d'achat | < 1 min, 0 confusion |
| **RH** | Générer bulletin paie | < 3 min, 0 erreur calcul |
| **Comptable** | Consulter statistiques | < 30s, compréhension KPI |

### 8.2 Checklist Ergonomie

- [ ] Navigation intuitive (3 clics max pour action courante)
- [ ] Feedback visuel immédiat (alertes succès/erreur)
- [ ] Labels clairs et concis
- [ ] Formulaires avec validation inline
- [ ] Messages d'erreur explicites
- [ ] Responsive mobile/tablette/desktop
- [ ] Contraste couleurs WCAG AA
- [ ] Temps de chargement < 2s

### 8.3 Signatures

| Rôle | Nom | Signature | Date |
|------|-----|-----------|------|
| **Designer UI/UX** | | | |
| **Lead Développeur** | | | |
| **Chef de Projet** | | | |
| **Expert Métier** | | | |

---

**Fin du document**

**PHASE 3 (Conception) Complète !**
