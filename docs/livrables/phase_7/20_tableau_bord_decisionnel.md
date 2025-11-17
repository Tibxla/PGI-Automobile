# 20. TABLEAU DE BORD DÉCISIONNEL

## Informations du Document

| Élément | Détail |
|---------|--------|
| **Projet** | PGI Automobile - Système de Gestion Intégré |
| **Phase** | PHASE 7 - Aide à la Décision |
| **Livrable** | Tableau de Bord Décisionnel |
| **Version** | 1.0 |
| **Date** | 17/11/2025 |
| **Auteur** | Direction PGI Automobile |

---

## Table des Matières

1. [Introduction](#1-introduction)
2. [Tableau de Bord Direction Générale](#2-tableau-de-bord-direction-générale)
3. [Tableau de Bord Commercial](#3-tableau-de-bord-commercial)
4. [Tableau de Bord Financier](#4-tableau-de-bord-financier)
5. [Tableau de Bord RH](#5-tableau-de-bord-rh)
6. [Analyses Prédictives](#6-analyses-prédictives)
7. [Alertes et Signaux Faibles](#7-alertes-et-signaux-faibles)

---

## 1. Introduction

### 1.1 Objectif

Ce document présente les **tableaux de bord décisionnels** du système PGI Automobile, conçus pour fournir une vision stratégique temps réel à la direction et aux managers.

### 1.2 Principes de Design

**Les tableaux de bord respectent les bonnes pratiques :**

- 📊 **Visuels intuitifs** : Graphiques clairs et compréhensibles en un coup d'œil
- 🎯 **Focalisés** : 5-7 KPIs maximum par tableau
- 🚦 **Code couleur** : Rouge (alerte), Orange (attention), Vert (OK)
- ⏱️ **Temps réel** : Données actualisées automatiquement
- 📱 **Responsive** : Consultables sur desktop, tablette, smartphone

### 1.3 Profils Utilisateurs

| Profil | Tableau de Bord | Fréquence Consultation |
|--------|-----------------|------------------------|
| **Directeur Général** | Vision 360° consolidée | Quotidien |
| **Directeur Commercial** | Performance ventes et équipe | Quotidien |
| **Directeur Financier** | Trésorerie, marges, rentabilité | Quotidien |
| **Directeur RH** | Effectifs, paies, productivité | Hebdomadaire |
| **Vendeurs** | Performance individuelle | Quotidien |

---

## 2. Tableau de Bord Direction Générale

### 2.1 Vue d'Ensemble Exécutive

**🎯 COCKPIT DIRECTION - NOVEMBRE 2025**

```
┌────────────────────────────────────────────────────────────────────┐
│  📊 PGI AUTOMOBILE - TABLEAU DE BORD DIRECTION                    │
│  Période : Novembre 2025                    Mis à jour : 17/11 14h│
├────────────────────────────────────────────────────────────────────┤
│                                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌──────────┐ │
│  │ 125 200 €   │  │    45       │  │   18.5%     │  │   23     │ │
│  │ Chiffre     │  │  Ventes     │  │   Marge     │  │  Stock   │ │
│  │ d'Affaires  │  │             │  │             │  │          │ │
│  │             │  │             │  │             │  │          │ │
│  │ ↗️ +5.7%     │  │ ↗️ +7.1%     │  │ → 0%        │  │ ↘️ -8.0%  │ │
│  │ vs Oct      │  │ vs Oct      │  │ vs Oct      │  │ vs Oct   │ │
│  └─────────────┘  └─────────────┘  └─────────────┘  └──────────┘ │
│                                                                     │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌──────────┐ │
│  │  28 320 €   │  │    22%      │  │    4.6/5    │  │    8     │ │
│  │ Trésorerie  │  │  Taux Conv. │  │ Satisfaction│  │ Employés │ │
│  │             │  │             │  │             │  │          │ │
│  │             │  │             │  │             │  │          │ │
│  │ ⚠️ -17 280€  │  │ ✅ +2pts     │  │ ✅ +0.1pt    │  │ → 0      │ │
│  │ vs début    │  │ vs Oct      │  │ vs Oct      │  │ vs Oct   │ │
│  └─────────────┘  └─────────────┘  └─────────────┘  └──────────┘ │
│                                                                     │
└────────────────────────────────────────────────────────────────────┘
```

**🚦 ÉTAT GLOBAL : 🟢 BON**

- ✅ CA en progression constante (+5.7%)
- ✅ Volume ventes en hausse (+7.1%)
- ✅ Marge stable et au-dessus objectif
- ⚠️ Trésorerie en baisse (attention)
- ✅ Satisfaction client élevée

---

### 2.2 Évolution Mensuelle

**📈 CHIFFRE D'AFFAIRES - 6 DERNIERS MOIS**

```
140 000€ ┤
130 000€ ┤                                        ┌──┐
120 000€ ┤                               ┌──┐    │██│
110 000€ ┤                       ┌──┐    │██│    │██│
100 000€ ┤              ┌──┐    │██│    │██│    │██│
 90 000€ ┤     ┌──┐    │██│    │██│    │██│    │██│
 80 000€ ┤     │██│    │██│    │██│    │██│    │██│
        └┴─────┴──┴────┴──┴────┴──┴────┴──┴────┴──┴──
         Juin  Juil   Août  Sept   Oct    Nov

         106K€  90K€   98K€  112K€  118K€  125K€

Tendance : ↗️ Croissance +18.4% sur 6 mois
Objectif Déc : 145 000€ (croissance +15.8%)
```

**📊 VENTES - 6 DERNIERS MOIS**

```
50 ┤
45 ┤                                        ┌──┐
40 ┤                               ┌──┐    │45│
35 ┤                       ┌──┐    │42│    │  │
30 ┤              ┌──┐    │40│    │  │    │  │
25 ┤     ┌──┐    │35│    │  │    │  │    │  │
20 ┤     │38│    │  │    │  │    │  │    │  │
   └┴─────┴──┴────┴──┴────┴──┴────┴──┴────┴──┴──
    Juin  Juil   Août  Sept   Oct    Nov

Tendance : ↗️ +18.4% sur 6 mois
Moyenne mobile 3 mois : 42.3 ventes/mois
```

---

### 2.3 Objectifs vs Réalisé

**🎯 PERFORMANCE PAR RAPPORT AUX OBJECTIFS**

| Indicateur | Objectif Annuel | Réalisé Nov | Réalisé Cumulé | % Avancement | Prévision Fin Année |
|------------|-----------------|-------------|----------------|--------------|---------------------|
| **CA** | 1 800 000 € | 125 200 € | 675 340 € | 37.5% | ✅ 1 890 000 € |
| **Ventes** | 600 | 45 | 243 | 40.5% | ✅ 630 |
| **Marge** | 324 000 € | 23 164 € | 124 913 € | 38.6% | ✅ 349 000 € |

**Analyse :** Sur la bonne trajectoire pour atteindre les objectifs annuels (+5% vs budget)

**📅 SUIVI TRIMESTRIEL**

```
Objectifs Trimestriels 2025

Q1 (Jan-Mar) :    CA : 405 000€    Réalisé : 398 500€  ⚠️  -1.6%
Q2 (Avr-Jui) :    CA : 450 000€    Réalisé : 441 000€  ⚠️  -2.0%
Q3 (Jul-Sep) :    CA : 495 000€    Réalisé : 500 840€  ✅  +1.2%
Q4 (Oct-Déc) :    CA : 450 000€    Prév. : 468 000€    ✅  +4.0%

Année 2025 :      CA : 1 800 000€  Prév. : 1 890 000€  ✅  +5.0%
```

---

### 2.4 Scorecard Balanced Scorecard

**⚖️ BALANCED SCORECARD - 4 PERSPECTIVES**

| Perspective | KPI | Valeur | Objectif | Score |
|-------------|-----|--------|----------|-------|
| **📊 Financière** | | | | **85%** 🟢 |
| | CA Mensuel | 125 200 € | 120 000 € | ✅ 104% |
| | Marge | 18.5% | 18.0% | ✅ 103% |
| | Rentabilité | -19 336 € | 0 € | ⚠️ - |
| | | | | |
| **👥 Clients** | | | | **92%** 🟢 |
| | Satisfaction | 4.6/5 | 4.5/5 | ✅ 102% |
| | Taux conversion | 22% | 20% | ✅ 110% |
| | Récurrence | 51.2% | 50% | ✅ 102% |
| | | | | |
| **⚙️ Processus** | | | | **88%** 🟢 |
| | Rotation stock | 23.9x | 18x | ✅ 133% |
| | Délai vente | 12j | 15j | ✅ 125% |
| | Taux défaut | 0% | < 2% | ✅ 100% |
| | | | | |
| **🎓 Apprentissage** | | | | **75%** 🟡 |
| | Formation | 2.6% MS | 3.5% MS | ⚠️ 74% |
| | Turnover | 0% | < 5% | ✅ 100% |
| | Satisfaction RH | 4.2/5 | 4.0/5 | ✅ 105% |

**Score Global : 85%** 🟢 Très Bon

---

## 3. Tableau de Bord Commercial

### 3.1 Performance des Ventes

**📈 DASHBOARD COMMERCIAL - NOVEMBRE 2025**

```
┌──────────────────────────────────────────────────────────────┐
│  PERFORMANCE COMMERCIALE                                      │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  Ventes du Mois                                              │
│  ████████████████████ 45 / 40 (objectif)  ✅ +12.5%         │
│                                                               │
│  Chiffre d'Affaires                                          │
│  █████████████████    125 200€ / 120 000€  ✅ +4.3%         │
│                                                               │
│  Panier Moyen                                                 │
│  ███████████████      2 782€ / 3 000€      ⚠️ -7.3%          │
│                                                               │
│  Taux de Conversion                                           │
│  ████████████████████ 22% / 20%            ✅ +10%          │
│                                                               │
│  Délai Vente Moyen                                           │
│  ████████████████████ 12j / 15j            ✅ -20%          │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

---

### 3.2 Entonnoir de Vente (Funnel)

**🔽 PIPELINE COMMERCIAL - NOVEMBRE**

```
                    PROSPECTS
                ┌───────────────┐
                │  205 Contacts │
                └───────┬───────┘
                        │ Taux qualification : 68%
                        ↓
                  QUALIFIÉS
                ┌───────────────┐
                │ 139 Prospects │
                └───────┬───────┘
                        │ Taux proposition : 85%
                        ↓
                 PROPOSITIONS
                ┌───────────────┐
                │ 118 Devis     │
                └───────┬───────┘
                        │ Taux négociation : 54%
                        ↓
                 NÉGOCIATION
                ┌───────────────┐
                │  64 En cours  │
                └───────┬───────┘
                        │ Taux closing : 70%
                        ↓
                   VENTES
                ┌───────────────┐
                │  45 Vendus    │
                └───────────────┘

Taux conversion global : 22% (205 → 45)
Amélioration vs Oct : +2 points
```

**💡 INSIGHTS**

- ✅ Excellent taux qualification (68%)
- ✅ Fort taux de proposition (85%)
- ⚠️ Perte au stade négociation (46% abandons)
  → **Action** : Formation techniques de closing

---

### 3.3 Performance par Vendeur

**🏆 CLASSEMENT VENDEURS - NOVEMBRE**

```
┌────────────────────────────────────────────────────────────────────┐
│ Rang │ Vendeur         │ Ventes │  CA      │ Marge    │ Taux Conv.│
├──────┼─────────────────┼────────┼──────────┼──────────┼───────────┤
│  1🥇 │ Jean Dupont     │   15   │ 41 730€  │ 7 720€   │   25%     │
│      │ ████████████████████████████████████████████████████       │
│      │                                                             │
│  2🥈 │ Marie Martin    │   12   │ 33 384€  │ 6 177€   │   22%     │
│      │ ████████████████████████████████████████                   │
│      │                                                             │
│  3🥉 │ Luc Moreau      │   10   │ 27 820€  │ 5 147€   │   20%     │
│      │ ████████████████████████████████████                       │
│      │                                                             │
│  4   │ Sophie Bernard  │    8   │ 22 266€  │ 4 120€   │   18%     │
│      │ ████████████████████████████                               │
└────────────────────────────────────────────────────────────────────┘

Performance Équipe :
• Moyenne : 11.25 ventes/vendeur
• Écart-type : 2.87 (homogénéité correcte)
• Top performer : Jean Dupont (33% des ventes)
```

**📊 ÉVOLUTION INDIVIDUELLE (vs Octobre)**

| Vendeur | Oct | Nov | Évolution |
|---------|-----|-----|-----------|
| Jean Dupont | 14 | 15 | ↗️ +7.1% |
| Marie Martin | 11 | 12 | ↗️ +9.1% |
| Luc Moreau | 10 | 10 | → 0% |
| Sophie Bernard | 7 | 8 | ↗️ +14.3% |

**💡 ACTIONS**
- 🏆 Prime mois : Jean Dupont
- 📚 Coaching : Luc Moreau (stagnation)
- 📈 Encouragements : Sophie Bernard (belle progression)

---

### 3.4 Mix Produit

**📦 RÉPARTITION DES VENTES**

**Par Type**
```
Neufs (70%)     ████████████████████████████  31 ventes
Occasions (30%) ████████████                  14 ventes
```

**Par Marque (Top 5)**
```
Peugeot (36%)   ████████████████████          16 ventes
Renault (24%)   ████████████                  11 ventes
Citroën (16%)   ████████                       7 ventes
Volkswagen (11%)██████                         5 ventes
Toyota (9%)     ████                           4 ventes
Autres (4%)     ██                             2 ventes
```

**Par Mode de Paiement**
```
Cash (60%)      ████████████████████████      27 ventes
Crédit (31%)    ████████████                  14 ventes
Leasing (9%)    ████                           4 ventes
```

**💡 RECOMMANDATIONS**

- ✅ Bon équilibre neufs/occasions
- 📈 Développer Toyota (marge 19%, demande forte)
- 💰 Promouvoir leasing (commissions intéressantes)

---

### 3.5 Prévisions des Ventes

**🔮 FORECAST DÉCEMBRE 2025**

**Méthode : Moyenne mobile + saisonnalité**

```
Historique 6 mois : 38, 32, 35, 40, 42, 45
Moyenne mobile 3 mois : (40+42+45)/3 = 42.3
Coefficient saisonnalité Décembre : +15% (fêtes)
Pipeline actuel : 18 ventes avancées

Prévision Décembre : 42.3 × 1.15 + 18 = 67 ventes 🎯

CA prévu : 67 × 2 800€ = 187 600€ ✅ Record !
```

**Scénarios :**
- 😊 **Optimiste (+10%)** : 74 ventes / 207 200€
- 😐 **Réaliste** : 67 ventes / 187 600€
- 😟 **Pessimiste (-10%)** : 60 ventes / 168 000€

**Probabilité scénario réaliste : 75%**

---

## 4. Tableau de Bord Financier

### 4.1 Trésorerie et Cash Flow

**💰 DASHBOARD TRÉSORERIE - NOVEMBRE 2025**

```
┌──────────────────────────────────────────────────────────────┐
│  SITUATION DE TRÉSORERIE                                     │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  Solde Trésorerie                                            │
│  █████████████        28 320€                ⚠️ -17 280€     │
│                       (vs début mois)                         │
│                                                               │
│  Encaissements                                               │
│  ████████████████████ 98 400€                                │
│  • Ventes cash : 75 120€                                     │
│  • Acomptes :    23 280€                                     │
│                                                               │
│  Décaissements                                               │
│  ███████████████████████ -115 680€                           │
│  • Achats véh. : -82 500€                                    │
│  • Salaires :    -18 200€                                    │
│  • Charges :     -12 400€                                    │
│  • Autres :       -2 580€                                    │
│                                                               │
│  Flux Net                                                     │
│  ████              -17 280€               ⚠️ Négatif         │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

**📊 ÉVOLUTION TRÉSORERIE (6 mois)**

```
60 000€ ┤                 ●
50 000€ ┤              ●
40 000€ ┤           ●
30 000€ ┤                                         ●
20 000€ ┤        ●
10 000€ ┤     ●
       └┴──────┴──────┴──────┴──────┴──────┴──────
        Juin  Juil   Août  Sept   Oct    Nov

Tendance : ↘️ Baisse préoccupante
Seuil alerte : 20 000€
Action requise : Améliorer BFR
```

---

### 4.2 Analyse de la Marge

**📈 MARGES - NOVEMBRE 2025**

```
┌──────────────────────────────────────────────────────────────┐
│  ANALYSE DES MARGES                                          │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  Marge Brute Totale                                          │
│  ████████████████████ 23 164€                                │
│  Taux : 18.5%         Objectif : 18%             ✅ +0.5pt   │
│                                                               │
│  Marge par Type                                              │
│  • Neufs      : 17.6%  ████████████████           15 440€   │
│  • Occasions  : 20.6%  ████████████████████████    7 724€   │
│                                                               │
│  Évolution                                                    │
│  • vs Oct : +5.7%      ↗️ Progression                         │
│  • vs Budget : +5.3%   ✅ Au-dessus objectif                 │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

**💡 INDICATEURS COMPLÉMENTAIRES**

| Indicateur | Valeur | Cible | Écart |
|------------|--------|-------|-------|
| **Marge unitaire moyenne** | 515 € | 500 € | ✅ +3% |
| **Taux de remise moyen** | 3.2% | < 5% | ✅ |
| **Marge nette (après charges)** | -15.4% | 0% | ⚠️ |

---

### 4.3 Rentabilité

**📊 SEUIL DE RENTABILITÉ**

```
┌──────────────────────────────────────────────────────────────┐
│  POINT MORT (BREAK-EVEN)                                     │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  Charges Fixes Mensuelles : 42 500€                          │
│  Taux de Marge : 18.5%                                       │
│                                                               │
│  CA Break-Even = 42 500 / 0.185 = 229 730€/mois             │
│                                                               │
│  ┌────────────────────────────────────────────────┐          │
│  │                                     │          │          │
│  │                                     │ 104 530€ │ Manque  │
│  │        125 200€                     │          │          │
│  │        Réalisé                      │ Seuil    │          │
│  └─────────────────────────────────────┴──────────┘          │
│    0€              125K€            229K€                     │
│                                                               │
│  Progression nécessaire : +83.5%                             │
│  Avec croissance actuelle (+5.7%/mois) :                    │
│  → Seuil atteint dans 9-10 mois (Août 2026)                 │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

---

### 4.4 Ratios Financiers

**📐 INDICATEURS CLÉS**

| Ratio | Formule | Valeur | Norme | Interprétation |
|-------|---------|--------|-------|----------------|
| **Marge brute / CA** | MB / CA × 100 | 18.5% | 15-20% | ✅ Bon |
| **Charges / CA** | Charges / CA × 100 | 33.9% | 30-35% | 📊 Normal |
| **BFR / CA** | BFR / CA × 100 | 35.2% | 25-40% | 📊 À surveiller |
| **Délai clients** | Créances / CA × 30 | 18j | 30j | ✅ Bon |
| **Délai fournisseurs** | Dettes / Achats × 30 | 30j | 45j | ⚠️ Court |
| **Rotation crédit client** | CA / Créances | 20.3x | > 12x | ✅ Excellent |

**💡 SYNTHÈSE**

- ✅ Bonne gestion crédit client (paiement rapide)
- ⚠️ Négocier délais fournisseurs (+15j)
- 📊 BFR élevé dû au stock important

---

## 5. Tableau de Bord RH

### 5.1 Effectifs et Masse Salariale

**👥 DASHBOARD RH - NOVEMBRE 2025**

```
┌──────────────────────────────────────────────────────────────┐
│  RESSOURCES HUMAINES                                         │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  Effectif Total                                              │
│  ████████         8 employés                 → Stable        │
│                                                               │
│  Masse Salariale Brute                                       │
│  ████████████████  18 200€/mois                              │
│  Coût total employeur : 29 844€ (charges incluses)           │
│                                                               │
│  Ratio MS / CA                                               │
│  ██████████████    14.5%                     ✅ < 15%        │
│                                                               │
│  Productivité par Vendeur                                    │
│  ████████████████████ 31 300€ CA/vendeur    ✅ > 30K€       │
│                                                               │
│  Absentéisme                                                 │
│  ██                2.5%                      ✅ < 3%         │
│                                                               │
│  Turnover (12 mois)                                          │
│  ░                 0%                        ✅ Excellent     │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

---

### 5.2 Performance Individuelle

**📊 MATRICE PERFORMANCE / POTENTIEL**

```
Potentiel
    ↑
    │
 4  │       [Marie M.]
    │
 3  │  [Jean D.]
    │
 2  │               [Luc M.]
    │
 1  │                      [Sophie B.]
    │
    └────────────────────────────────→ Performance
      1        2        3        4

Légende :
• Jean Dupont : Haute performance, Haut potentiel → Talent clé (rétention)
• Marie Martin : Haute performance, Potentiel élevé → Future leader
• Luc Moreau : Performance moyenne, Potentiel moyen → Maintien
• Sophie Bernard : Performance faible, Potentiel à développer → Formation
```

**💡 PLAN D'ACTION RH**

| Employé | Statut | Action Prioritaire |
|---------|--------|--------------------|
| Jean Dupont | ⭐ Talent | Rétention : Augmentation +10%, responsabilités |
| Marie Martin | 🌟 Potentiel | Développement : Formation management |
| Luc Moreau | 📊 Stable | Maintien : Suivi régulier |
| Sophie Bernard | 📚 Développement | Formation : Techniques vente avancées |

---

### 5.3 Indicateurs de Bien-Être

**😊 SATISFACTION ET ENGAGEMENT**

```
┌──────────────────────────────────────────────────────────────┐
│  BAROMÈTRE SOCIAL (Enquête interne Oct. 2025)               │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  Satisfaction Globale                     4.2/5   ✅         │
│  ████████████████████                     (84%)              │
│                                                               │
│  Ambiance de Travail                      4.5/5   ✅         │
│  ██████████████████████                   (90%)              │
│                                                               │
│  Équilibre Vie Pro/Perso                  3.8/5   📊         │
│  ███████████████                          (76%)              │
│                                                               │
│  Reconnaissance                           3.5/5   ⚠️          │
│  ██████████████                           (70%)              │
│                                                               │
│  Perspectives d'Évolution                 3.2/5   ⚠️          │
│  █████████████                            (64%)              │
│                                                               │
│  Management                               4.0/5   ✅         │
│  ████████████████████                     (80%)              │
│                                                               │
└──────────────────────────────────────────────────────────────┘

Points forts : Ambiance, Management
Points d'amélioration : Reconnaissance, Perspectives
```

**📈 ACTIONS AMÉLIORATION**

1. **Reconnaissance** : Mise en place primes mensuelles (done ✅)
2. **Perspectives** : Définir parcours carrière (Q1 2026)
3. **Équilibre** : Télétravail 1j/semaine pour admin (à tester)

---

## 6. Analyses Prédictives

### 6.1 Prévisions Financières

**🔮 PROJECTION 12 MOIS (Déc 2025 - Nov 2026)**

**Hypothèses :**
- Croissance mensuelle : +5% (conservateur)
- Recrutement 2 vendeurs : Avril 2026
- Ouverture atelier : Septembre 2026

```
┌────────────────────────────────────────────────────────────────┐
│  CA PRÉVISIONNEL (€)                                          │
│                                                                │
│  240K ┤                                          ●            │
│  220K ┤                                    ●                   │
│  200K ┤                              ●                         │
│  180K ┤                        ●                               │
│  160K ┤                  ●                                     │
│  140K ┤            ●                                           │
│  120K ┤      ●                                                 │
│      └┴──────┴──────┴──────┴──────┴──────┴──────┴──────       │
│       Déc   Fév   Avr   Juin  Août  Oct   Déc                │
│       2025                2026                                 │
│                                                                │
│  Point mort (229K€) atteint : Août 2026                       │
│  CA Année 2026 (prév.) : 2.4 M€                               │
└────────────────────────────────────────────────────────────────┘
```

**📊 PROJECTION RENTABILITÉ**

| Trimestre | CA Prév. | Marge | Charges | Résultat |
|-----------|----------|-------|---------|----------|
| Q1 2026 | 420 000 € | 77 700 € | -130 000 € | **-52 300 €** |
| Q2 2026 | 510 000 € | 94 350 € | -140 000 € | **-45 650 €** |
| Q3 2026 | 630 000 € | 116 550 € | -150 000 € | **-33 450 €** |
| Q4 2026 | 840 000 € | 155 400 € | -155 000 € | **+400 €** ✅ |

**Premier trimestre bénéficiaire : Q4 2026**

---

### 6.2 Analyse de Scénarios

**🎲 SIMULATIONS STRATÉGIQUES**

**Scénario 1 : Status Quo (croissance organique +5%/mois)**

| Indicateur | 2026 | 2027 | 2028 |
|------------|------|------|------|
| CA | 2.4 M€ | 3.1 M€ | 3.9 M€ |
| Résultat Net | -131 K€ | +180 K€ | +390 K€ |
| Rentabilité | Q4 2026 | - | - |

**Scénario 2 : Accélération (recrutement + marketing)**

| Indicateur | 2026 | 2027 | 2028 |
|------------|------|------|------|
| CA | 3.2 M€ | 4.8 M€ | 6.5 M€ |
| Résultat Net | -80 K€ | +420 K€ | +910 K€ |
| Rentabilité | Q3 2026 | - | - |
| Investissement | 180 K€ | 120 K€ | 80 K€ |

**Scénario 3 : Diversification (atelier + location)**

| Indicateur | 2026 | 2027 | 2028 |
|------------|------|------|------|
| CA | 3.8 M€ | 5.9 M€ | 8.2 M€ |
| Résultat Net | -45 K€ | +680 K€ | +1.3 M€ |
| Rentabilité | Q2 2026 | - | - |
| Investissement | 280 K€ | 150 K€ | 100 K€ |

**💡 RECOMMANDATION : Scénario 3**
- Rentabilité la plus rapide
- Meilleure diversification des risques
- ROI élevé sur services annexes

---

### 6.3 Modèle Prédictif Ventes

**🤖 MACHINE LEARNING - PRÉVISION VENTES**

**Modèle :** Régression linéaire + facteurs saisonniers

**Variables prédictives :**
- Historique ventes (poids 40%)
- Tendance marché (poids 20%)
- Saisonnalité (poids 15%)
- Actions marketing (poids 15%)
- Météo / événements (poids 10%)

**Prévisions Décembre 2025 - Février 2026**

```
Ventes
 75 ┤
    │        ●  67 (Déc)     IC 95%: [61-73]  🎄 Fêtes
 60 ┤     ●  52 (Jan)        IC 95%: [48-56]  ❄️ Creux
    │  ●  58 (Fév)           IC 95%: [54-62]  📈 Reprise
 45 ┤
    │
 30 ┤
    └┴──────┴──────┴──────
     Nov    Déc    Jan    Fév
```

**Fiabilité du modèle : 87%** (testé sur 12 mois historiques)

**Alertes :**
- ⚠️ Janvier faible : Anticiper avec campagne promo
- ✅ Décembre record : Préparer stock (+10 véhicules)

---

## 7. Alertes et Signaux Faibles

### 7.1 Système d'Alertes Automatiques

**🚨 ALERTES ACTIVES - 17 NOVEMBRE 2025**

```
┌──────────────────────────────────────────────────────────────┐
│  ALERTES CRITIQUES                                           │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  🔴 URGENT                                                   │
│  • Trésorerie < 30 000€    Actuel: 28 320€   ⚠️ -5.6%       │
│    → Action: Réunion trésorerie ASAP                         │
│                                                               │
│  🟠 IMPORTANT                                                │
│  • 2 véhicules > 90 jours stock   Valeur: 42 590€            │
│    → Action: Opération déstockage ce week-end                │
│                                                               │
│  🟡 ATTENTION                                                │
│  • Panier moyen en baisse  -7.3% vs objectif                 │
│    → Action: Formation upselling vendeurs                     │
│                                                               │
│  • Sophie Bernard sous objectif  8 ventes (obj: 10)          │
│    → Action: Coaching individuel                              │
│                                                               │
└──────────────────────────────────────────────────────────────┘

🟢 PAS D'ALERTE : Marge, Satisfaction client, Absentéisme
```

---

### 7.2 Indicateurs Avancés (Leading Indicators)

**📡 SIGNAUX FAIBLES - DÉTECTION PRÉCOCE**

| Signal | Valeur | Tendance | Impact Potentiel |
|--------|--------|----------|------------------|
| **Pipeline prospects** | 139 qualifiés | ↗️ +8% | ✅ Bonnes ventes à venir |
| **Trafic web** | 1 240 visites | ↘️ -12% | ⚠️ Baisse prospects futurs |
| **Demandes devis** | 118 | → 0% | 📊 Stable |
| **Taux abandon devis** | 46% | ↗️ +3pts | ⚠️ Perte compétitivité prix ? |
| **Avis Google** | 4.6/5 (28 avis) | ✅ +0.2pt | ✅ E-réputation positive |
| **Temps moyen négo** | 8.5 jours | ↗️ +1.2j | ⚠️ Closing moins efficace |
| **Stock jours** | 52 jours | ↗️ +7j | ⚠️ Rotation ralentit |

**💡 ACTIONS PRÉVENTIVES**

1. **Trafic web en baisse** → Booster SEO + campagne Google Ads (budget 2K€)
2. **Abandon devis élevé** → Analyser grille tarifaire concurrence
3. **Temps négo allongé** → Formation techniques closing rapide

---

### 7.3 Tableau de Bord Risques

**⚠️ MATRICE DES RISQUES**

```
Probabilité
    ↑
 5  │  [R5]
    │              [R2]
 4  │
    │
 3  │         [R1]
    │                   [R3]
 2  │
    │                        [R4]
 1  │
    └────────────────────────────────→ Impact
      1    2    3    4    5

Risques :
R1 : Défaillance fournisseur principal (P3, I3) → Risque Moyen
R2 : Concurrent agressif prix (P4, I4) → Risque Élevé
R3 : Départ Jean Dupont (P3, I4) → Risque Élevé
R4 : Pénurie véhicules neufs (P2, I5) → Risque Moyen
R5 : Crise économique (P5, I2) → Risque Moyen
```

**📋 PLAN DE MITIGATION**

| Risque | Probabilité | Impact | Plan Mitigation | Responsable |
|--------|-------------|--------|-----------------|-------------|
| **R2 : Prix concurrent** | 80% | Élevé | Veille hebdo + ajustement prix | Commercial |
| **R3 : Départ top performer** | 60% | Élevé | Rétention (prime) + succession | RH |
| **R1 : Fournisseur** | 40% | Moyen | 2ème fournisseur backup | Achats |
| **R4 : Pénurie** | 30% | Élevé | Stock sécurité + diversification | Stock |
| **R5 : Crise** | 70% | Faible | Réduction coûts variables | Finance |

---

### 7.4 Early Warning System

**🔔 SYSTÈME D'ALERTE PRÉCOCE**

**Déclencheurs automatiques (envoi email direction) :**

| Indicateur | Seuil Alerte | Niveau | Dernière Alerte |
|------------|--------------|--------|-----------------|
| Trésorerie | < 25 000 € | 🔴 Critique | - |
| CA quotidien | < 3 000 € | 🟠 Important | - |
| Ventes jour | 0 | 🟡 Attention | 3 fois/mois |
| Stock > 90j | > 2 véhicules | 🟠 Important | 15/11/2025 ✅ |
| Satisfaction | < 4.0/5 | 🔴 Critique | Jamais |
| Absentéisme | > 5% | 🟡 Attention | Jamais |
| Pipeline | < 100 prospects | 🟠 Important | Jamais |

**Alertes SMS (urgence uniquement) :**
- Trésorerie < 15 000 € → DG + DAF
- Satisfaction < 3.5/5 → DG + Commercial
- 0 vente pendant 3 jours → DG

---

## 8. Conclusion

### 8.1 Dashboard Personnalisés

**🎛️ ACCÈS PAR PROFIL**

**Directeur Général**
- ✅ Vue 360° complète
- ✅ Tous les tableaux de bord
- ✅ Alertes temps réel
- ✅ Analyses prédictives

**Directeur Commercial**
- ✅ Performance ventes
- ✅ Entonnoir commercial
- ✅ Performance vendeurs
- ✅ Prévisions CA

**Directeur Financier**
- ✅ Trésorerie
- ✅ Marges
- ✅ Rentabilité
- ✅ Ratios financiers

**Directeur RH**
- ✅ Effectifs
- ✅ Masse salariale
- ✅ Performance individuelle
- ✅ Bien-être

**Vendeurs**
- ✅ Performance personnelle
- ✅ Objectifs / Réalisé
- ✅ Classement équipe
- ✅ Commission du mois

---

### 8.2 Fréquence de Consultation Recommandée

| Dashboard | Direction | Managers | Vendeurs |
|-----------|-----------|----------|----------|
| **Vue 360°** | Quotidien | - | - |
| **Commercial** | Quotidien | Quotidien | Quotidien |
| **Financier** | Quotidien | Hebdo | - |
| **RH** | Hebdo | Hebdo | - |
| **Prédictif** | Hebdo | Mensuel | - |
| **Alertes** | Temps réel | Temps réel | - |

---

### 8.3 Intégration dans PGI Automobile

**📱 ACCÈS AUX TABLEAUX DE BORD**

**Via l'application web :**
1. Se connecter à PGI Automobile
2. Cliquer sur **"Statistiques"** → **"Tableaux de Bord"**
3. Sélectionner le tableau souhaité
4. Filtrer par période (jour, semaine, mois, année)
5. Exporter en PDF ou Excel si nécessaire

**Actualisation :**
- Automatique toutes les 5 minutes
- Bouton "Rafraîchir" pour mise à jour instantanée

**Notifications push :**
- Alertes critiques : Notification navigateur + Email
- Rapport quotidien : Email à 8h
- Rapport hebdo : Email lundi 9h
- Rapport mensuel : Email le 5 du mois

---

**✅ Les tableaux de bord sont opérationnels et prêts à l'emploi !**

---

**Document Version :** 1.0
**Dernière mise à jour :** 17/11/2025
**Auteur :** Direction PGI Automobile

**Prochaine mise à jour :** Mensuelle (Dashboard mis à jour en temps réel)
