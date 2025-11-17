# Étude de Faisabilité - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile
**Version:** 1.0
**Date:** Novembre 2025
**Responsable:** Chef de Projet

---

## Résumé Exécutif

Cette étude évalue la faisabilité du développement d'un PGI Automobile dédié à la gestion de concessions automobiles. L'analyse porte sur **trois axes principaux** : technique, économique et organisationnel.

**Conclusion** : Le projet est **FAISABLE** et présente un **ROI positif** à 18 mois. Les technologies retenues (PHP/MySQL) sont matures, l'équipe compétente, et le besoin métier clairement identifié. Recommandation : **GO**.

---

## 1. Faisabilité Technique

### 1.1 Architecture Technologique Retenue

| Couche | Technologie | Justification |
|--------|-------------|---------------|
| **Backend** | PHP 7.4+ (natif) | Simplicité déploiement, pas de dépendances complexes, maîtrise équipe |
| **Base de données** | MySQL 8.0 | Robustesse éprouvée, compatibilité hébergements mutualisés, gratuit |
| **Frontend** | HTML5/CSS3/JS vanilla | Léger, rapide, responsive, pas de build nécessaire |
| **Serveur web** | Apache 2.4 | Standard industrie, configuration simple, .htaccess supporté |
| **Sécurité** | PDO, bcrypt, sessions PHP | Standards éprouvés, conformité OWASP |

### 1.2 Analyse des Solutions Alternatives

#### Option 1 : Framework PHP (Laravel/Symfony)
- **Avantages** : Scaffolding, ORM, écosystème riche, maintenabilité long terme
- **Inconvénients** : Courbe d'apprentissage, Composer requis, overhead performance
- **Décision** : ❌ **Rejetée** - Complexité inutile pour ce périmètre, équipe non formée Laravel

#### Option 2 : CMS (WordPress, Drupal)
- **Avantages** : Rapide à déployer, plugins existants, communauté
- **Inconvénients** : Personnalisation limitée, plugins métier inexistants, sécurité dépendante plugins tiers
- **Décision** : ❌ **Rejetée** - Inadapté pour gestion métier spécifique

#### Option 3 : PHP Natif (choix retenu)
- **Avantages** : Contrôle total, légèreté, déploiement simple, maîtrise équipe
- **Inconvénients** : Développement from scratch, pas de helpers built-in
- **Décision** : ✅ **Retenue** - Équilibre optimal pour ce projet

### 1.3 Évaluation de la Stack Technique

#### PHP 7.4+

**Points forts** :
- Langage mature (25+ ans)
- PDO natif pour requêtes préparées (sécurité)
- `password_hash()` pour bcrypt (sécurité)
- Hébergement mutualisé large (OVH, Ionos, o2switch)
- Documentation exhaustive
- Équipe maîtrise (2 développeurs confirmés)

**Points de vigilance** :
- Pas de typage strict natif (avant PHP 7.4)
- Nécessite discipline pour architecture MVC propre

**Verdict** : ✅ **VALIDÉ** - Adapté aux exigences projet

#### MySQL 8.0

**Points forts** :
- SGBDR relationnel robuste
- Transactions ACID
- Indexes performants
- UTF-8 complet (utf8mb4)
- phpMyAdmin pour administration graphique
- Compatibilité cloud (AWS RDS, GCP Cloud SQL)

**Points de vigilance** :
- Nécessite optimisation requêtes (indexes, jointures)
- Sauvegardes régulières critiques

**Verdict** : ✅ **VALIDÉ** - Éprouvé pour ce type d'application

#### Responsive Design (HTML5/CSS3)

**Points forts** :
- Flexbox/Grid pour layouts modernes
- Variables CSS pour thème unifié
- Media queries pour adaptation écrans
- Pas de dépendance framework (Bootstrap, Tailwind)

**Points de vigilance** :
- Nécessite tests cross-browser (Chrome, Firefox, Safari, Edge)
- Design custom demande plus de temps qu'un framework CSS

**Verdict** : ✅ **VALIDÉ** - Approche moderne et maîtrisée

### 1.4 Exigences d'Hébergement

#### Environnement de Production Recommandé

| Ressource | Minimum | Recommandé | Justification |
|-----------|---------|------------|---------------|
| **CPU** | 1 vCore | 2 vCores | Gestion requêtes simultanées (10-20 users) |
| **RAM** | 2 GB | 4 GB | Cache MySQL, sessions PHP |
| **Stockage** | 10 GB | 20 GB SSD | BDD (5 GB) + Images véhicules (10 GB) + Logs |
| **Bande passante** | 100 GB/mois | Illimitée | Chargement images catalogue |
| **PHP** | 7.4 | 8.0+ | Performances optimales |
| **MySQL** | 5.7 | 8.0 | Fonctionnalités modernes (CTE, window functions) |

#### Hébergeurs Compatibles (Exemples France)

| Hébergeur | Offre | Prix/mois | Verdict |
|-----------|-------|-----------|---------|
| **o2switch** | Unique (illimité) | 7€ | ✅ Excellent rapport qualité/prix |
| **OVH** | Perso (100 GB) | 4€ | ✅ Entrée de gamme suffisante |
| **Ionos** | Plus | 8€ | ✅ Bon compromis |
| **AWS EC2** | t3.small | ~15€ | ⚠️ Nécessite expertise DevOps |
| **Infomaniak** | Web Pro | 6€ | ✅ Suisse, écoresponsable |

**Recommandation** : **o2switch Unique** (7€/mois, support français, PHP 8.x, MySQL 8, SSL gratuit)

### 1.5 Performance et Scalabilité

#### Benchmarks Estimés

| Métrique | Objectif | Scénario |
|----------|----------|----------|
| **Temps de chargement** | < 2s | Page catalogue (50 véhicules) |
| **Requêtes/sec** | 50 req/s | Serveur mutualisé standard |
| **Users concurrents** | 20-30 | Équipe + clients web |
| **Taille BDD** | 5 GB | 10 000 véhicules + historique 5 ans |
| **Images** | 10 GB | 10 000 véhicules × 1 image/véhicule (1 MB) |

#### Stratégies d'Optimisation

1. **Base de données** :
   - Indexes sur colonnes fréquemment filtrées (statut, type, marque)
   - Requêtes préparées (PDO) pour cache query plans
   - Pagination des listes (LIMIT/OFFSET)

2. **Cache** :
   - Sessions PHP pour authentification
   - Cache navigateur pour CSS/JS (headers Cache-Control)
   - Compression GZIP activée (Apache mod_deflate)

3. **Images** :
   - Compression WebP (ou JPEG optimisé)
   - Lazy loading (`loading="lazy"`)
   - Resize serveur (max 1920px width)

**Verdict Scalabilité** : ✅ **VALIDÉ** - Architecture supporte 10x croissance données sans refonte

### 1.6 Sécurité

#### Mesures Implémentées

| Menace | Protection | Implémentation |
|--------|------------|----------------|
| **Injection SQL** | PDO Prepared Statements | 100% des requêtes utilisent `$stmt->execute()` |
| **XSS** | Échappement HTML | `htmlspecialchars()` sur toutes sorties |
| **CSRF** | Tokens | Sessions PHP (futur : tokens dédiés) |
| **Brute Force** | Rate limiting | Logs connexions (futur : blocage IP) |
| **Session Hijacking** | Flags sécurisés | `session.cookie_httponly=1`, `session.use_strict_mode=1` |
| **Mots de passe** | Hachage bcrypt | `password_hash(PASSWORD_BCRYPT)` |

#### Conformité OWASP Top 10 (2021)

| Risque | Statut | Commentaire |
|--------|--------|-------------|
| A01 Broken Access Control | ✅ Protégé | RBAC + permissions granulaires |
| A02 Cryptographic Failures | ✅ Protégé | Bcrypt, PDO, HTTPS |
| A03 Injection | ✅ Protégé | PDO prepared statements |
| A07 XSS | ✅ Protégé | htmlspecialchars() systématique |
| A09 Logging Failures | ⚠️ Partiel | Logs connexions OK, logs applicatifs à renforcer |

**Verdict Sécurité** : ✅ **VALIDÉ** - Niveau de sécurité conforme bonnes pratiques

### 1.7 Tests de Faisabilité Technique

#### Prototype Développé

Un **prototype fonctionnel** a été développé incluant :
- Module véhicules (CRUD complet)
- Module ventes (enregistrement vente)
- Authentification + RBAC
- Dashboard statistiques basique

**Résultats** :
- ✅ CRUD opérationnel en 2 semaines
- ✅ Temps de chargement < 1.5s (50 véhicules)
- ✅ Design responsive validé (Chrome, Firefox, Safari)
- ✅ PDO + sessions fonctionnels

**Conclusion Technique** : **FAISABILITÉ VALIDÉE** ✅

---

## 2. Faisabilité Économique

### 2.1 Estimation des Coûts de Développement

#### Décomposition par Phase

| Phase | Durée | Charge (h) | Coût Unitaire | Total |
|-------|-------|-----------|---------------|-------|
| **1. Analyse & Conception** | 3 semaines | 120h | 55€/h | 6 600€ |
| **2. Développement** | 7 semaines | 280h | 50€/h | 14 000€ |
| **3. Tests & Recette** | 2 semaines | 80h | 40€/h | 3 200€ |
| **4. Déploiement** | 1 semaine | 40h | 50€/h | 2 000€ |
| **5. Documentation** | 1 semaine | 40h | 45€/h | 1 800€ |
| **6. Formation** | 1 semaine | 20h | 60€/h | 1 200€ |
| **TOTAL Développement** | **15 semaines** | **580h** | - | **28 800€** |

#### Coûts Additionnels

| Poste | Coût Annuel | Coût Triennal |
|-------|-------------|---------------|
| **Hébergement** (o2switch Unique) | 84€ | 252€ |
| **Nom de domaine** (.fr) | 10€ | 30€ |
| **SSL** (Let's Encrypt) | 0€ | 0€ |
| **Maintenance corrective** (20h/an) | 1 000€ | 3 000€ |
| **Sauvegardes externes** (Backblaze) | 60€ | 180€ |
| **TOTAL Exploitation** | **1 154€/an** | **3 462€** |

#### Budget Global Projet

| Catégorie | Montant |
|-----------|---------|
| Développement initial | 28 800€ |
| Exploitation 3 ans | 3 462€ |
| **TOTAL 3 ans** | **32 262€** |

### 2.2 Analyse Coût-Bénéfice

#### Coûts de la Situation Actuelle (Sans PGI)

| Poste | Coût Annuel | Justification |
|-------|-------------|---------------|
| **Licences logiciels métier** (3 outils) | 3 000€ | Facturation, Stock, Paie (3×1000€) |
| **Temps perdu saisies multiples** (10h/semaine) | 12 000€ | 10h × 50€ × 48 semaines |
| **Erreurs de gestion** (marges, stock) | 5 000€ | Sous-facturation, ruptures stock |
| **Absence pilotage** | 8 000€ | Décisions sous-optimales (estimé) |
| **TOTAL Coûts Actuels/an** | **28 000€** | |

#### Bénéfices Attendus du PGI (Annuels)

| Bénéfice | Gain Annuel | Justification |
|----------|-------------|---------------|
| **Suppression licences** | 3 000€ | Outils métier remplacés par PGI |
| **Gain productivité** (10h → 2h/semaine) | 9 600€ | 8h économisées × 50€ × 48 semaines |
| **Réduction erreurs** | 3 000€ | Calculs automatisés (marges, paie) |
| **Meilleure rotation stock** | 4 000€ | Alertes véhicules longue durée |
| **Augmentation ventes** (portail client) | 6 000€ | +10 ventes/an via demandes en ligne |
| **Optimisation RH** | 2 000€ | Automatisation paie/congés |
| **TOTAL Gains/an** | **27 600€** | |

#### Calcul du ROI

**Formule** : ROI = (Gains cumulés - Coûts totaux) / Coûts totaux × 100

| Période | Coûts Cumulés | Gains Cumulés | ROI |
|---------|---------------|---------------|-----|
| **Année 1** | 29 954€ (dev + exploit) | 27 600€ | -8% (normal phase investissement) |
| **Année 2** | 30 138€ | 55 200€ | **+83%** |
| **Année 3** | 31 308€ | 82 800€ | **+164%** |

**Point mort (Break-even)** : **13 mois** après mise en production

**Gain net sur 3 ans** : **82 800€ - 31 308€ = 51 492€**

### 2.3 Analyse de Sensibilité

#### Scénario Pessimiste (Gains -30%)

| Période | Gains Annuels | ROI 3 ans |
|---------|---------------|-----------|
| Scénario pessimiste | 19 320€/an | **+85%** |

#### Scénario Optimiste (Gains +30%)

| Période | Gains Annuels | ROI 3 ans |
|---------|---------------|-----------|
| Scénario optimiste | 35 880€/an | **+244%** |

**Conclusion** : Même en scénario pessimiste, **ROI positif à 3 ans** (+85%)

### 2.4 Comparaison avec Solutions du Marché

#### Solutions SaaS Concurrentes

| Solution | Prix/mois | Coût 3 ans | Limitations |
|----------|-----------|------------|-------------|
| **Autosoft DMS** | 150€ | 5 400€ | Modules limités, pas de personnalisation |
| **AutoRaptor** | 200€ | 7 200€ | US-centric, pas RH intégré |
| **VinSolutions** | 180€ | 6 480€ | Lock-in fournisseur, coûts additionnels |
| **PGI Automobile (custom)** | ~30€ | 3 462€ | - |

**Économie vs SaaS** : **2 000€ à 4 000€/an** + maîtrise complète du code

### 2.5 Financement et Amortissement

#### Plan de Financement

| Source | Montant | Modalité |
|--------|---------|----------|
| **Fonds propres** | 15 000€ | Apport entreprise |
| **Crédit BPI France** | 15 000€ | Prêt innovation (taux 0%) |
| **TOTAL** | **30 000€** | - |

#### Amortissement Comptable

- **Durée** : 3 ans (logiciel)
- **Dotation annuelle** : 9 600€
- **Valeur résiduelle** : 0€ (logiciel libre, évolutif)

**Verdict Économique** : ✅ **PROJET RENTABLE** (ROI +164% à 3 ans, break-even 13 mois)

---

## 3. Faisabilité Organisationnelle

### 3.1 Compétences de l'Équipe Projet

#### Équipe Interne

| Rôle | Nom | Compétences | Disponibilité |
|------|-----|-------------|---------------|
| **Chef de Projet** | Jean MARTIN | PMP, 10 ans SI automobile | 50% (4 mois) |
| **Dev Full-Stack Senior** | Sophie DURAND | PHP 12 ans, MySQL, sécurité | 100% (4 mois) |
| **Dev Full-Stack Junior** | Thomas BERNARD | PHP 3 ans, HTML/CSS/JS | 100% (4 mois) |
| **Designer UI/UX** | Marie CLAIRE | Figma, design systems | 25% (2 mois) |
| **Testeur QA** | Luc PETIT | Tests manuels/auto, Selenium | 50% (2 mois) |
| **Expert Métier** | Pierre GARAGE | Gérant concession 20 ans | 10% (validation) |

#### Analyse des Compétences

| Compétence Requise | Niveau Équipe | Gap | Action |
|-------------------|---------------|-----|--------|
| **PHP avancé** | ✅ Expert | Aucun | - |
| **MySQL** | ✅ Confirmé | Aucun | - |
| **Sécurité Web** | ✅ Confirmé | Mineur | Formation OWASP (1 jour) |
| **Responsive Design** | ✅ Confirmé | Aucun | - |
| **Gestion Projet Agile** | ⚠️ Intermédiaire | Mineur | Mentoring chef de projet |
| **Tests Automatisés** | ⚠️ Basique | Moyen | Formation PHPUnit (2 jours) |

**Conclusion** : ✅ **Équipe compétente**, gaps mineurs comblés par formation ciblée

### 3.2 Organisation du Travail

#### Méthodologie Agile (Scrum Adapté)

- **Sprints** : 2 semaines (8 sprints au total)
- **Daily Standup** : 15 min/jour (Lun-Ven)
- **Sprint Planning** : 2h début de sprint
- **Sprint Review** : 1h fin de sprint (démo)
- **Sprint Retrospective** : 1h fin de sprint

#### Répartition des Tâches par Sprint

| Sprint | Semaines | Modules | Objectif |
|--------|----------|---------|----------|
| **S1** | 1-2 | Setup, Auth, Véhicules | Base technique + CRUD principal |
| **S2** | 3-4 | Clients, Ventes | Cycle de vente complet |
| **S3** | 5-6 | Demandes, RH | Portail client + gestion employés |
| **S4** | 7-8 | Congés, Paie | RH avancé |
| **S5** | 9-10 | Stock, Stats | Tableaux de bord |
| **S6** | 11-12 | Admin, Permissions | Sécurité avancée |
| **S7** | 13-14 | Tests, Corrections | QA intensive |
| **S8** | 15-16 | Déploiement, Formation | Mise en production |

### 3.3 Gestion du Changement

#### Impacts Organisationnels

| Utilisateur | Changement | Impact | Mesure d'accompagnement |
|-------------|------------|--------|------------------------|
| **Vendeurs** | Nouvel outil ventes | Moyen | Formation 4h + support 2 semaines |
| **Comptable** | Extraction données PGI | Faible | Formation 2h |
| **RH** | Saisie paie dans PGI | Élevé | Formation 4h + hotline dédiée |
| **Gestionnaire Stock** | Nouvel inventaire | Moyen | Formation 3h |
| **Clients** | Portail en ligne | Faible | Tutoriel vidéo 3 min |

#### Plan de Formation

| Session | Public | Durée | Contenu |
|---------|--------|-------|---------|
| **Formation Admin** | Admin système | 2h | Installation, configuration, backups |
| **Formation Vendeurs** | 5 vendeurs | 4h | Véhicules, clients, ventes, demandes |
| **Formation RH** | 1 RH | 4h | Personnel, congés, paie |
| **Formation Comptable** | 1 comptable | 2h | Statistiques, exports |
| **Formation Clients** | Tous clients | Vidéo | Inscription, catalogue, demandes |

**Budget Formation** : 2 000€ (formateur interne, supports)

### 3.4 Résistance au Changement

#### Risques Identifiés

| Risque | Probabilité | Stratégie Mitigation |
|--------|-------------|---------------------|
| **Rejet par vendeurs** ("trop complexe") | Moyenne | Impliquer vendeur pilote dès sprint 2 |
| **RH préfère Excel** | Faible | Démontrer gain temps (10 min/paie vs 30 min) |
| **Manque adoption portail client** | Moyenne | Campagne email + incentive (réduction 50€) |
| **Scepticisme direction** | Faible | Démos régulières + KPI visibles |

#### Stratégie Communication

- **Kick-off projet** : Présentation enjeux à toute l'entreprise (1h)
- **Newsletter mensuelle** : Avancement, captures d'écran
- **Bêta testeurs** : 2 vendeurs + 1 RH testent en pré-prod (2 semaines)
- **Champions** : 1 utilisateur avancé par département (support niveau 1)

### 3.5 Ressources Matérielles

#### Équipement Développement

| Ressource | Quantité | Coût Unitaire | Total |
|-----------|----------|---------------|-------|
| **PC développeurs** (déjà possédés) | 2 | - | 0€ |
| **Licence IDE** (PHPStorm) | 2 | 200€ | 400€ |
| **Serveur de test** (VPS OVH) | 1 | 7€/mois × 4 mois | 28€ |
| **Licence Figma** (Design) | 1 | 12€/mois × 2 mois | 24€ |
| **TOTAL** | - | - | **452€** |

#### Équipement Production (Utilisateurs)

| Ressource | Quantité | Statut |
|-----------|----------|--------|
| **PC bureaux** | 8 | ✅ Déjà possédés |
| **Tablettes vendeurs** | 3 | ✅ Déjà possédées (iPad) |
| **Connexion Internet** | - | ✅ Fibre 500 Mbps existante |

**Investissement matériel** : **~450€** (négligeable)

### 3.6 Planning et Disponibilité

#### Contraintes Planning

| Contrainte | Impact | Solution |
|------------|--------|----------|
| **Période fêtes** (15 déc - 5 jan) | Développeurs indisponibles | Décaler début projet ou sprint buffer |
| **Salon automobile** (mars) | Vendeurs indisponibles formation | Former en février |
| **Clôture comptable** (juin) | Comptable indisponible | Formation en mai |

#### Planning Recommandé

- **Début projet** : 1er septembre 2025
- **Fin développement** : 31 décembre 2025
- **Mise en production** : 15 janvier 2026
- **Fin garantie** : 15 mars 2026

**Verdict Organisationnel** : ✅ **FAISABLE** avec équipe disponible et compétente

---

## 4. Analyse des Risques

### 4.1 Matrice des Risques

| ID | Risque | Probabilité | Impact | Criticité | Mitigation |
|----|--------|-------------|--------|-----------|------------|
| R1 | Dérive fonctionnelle (scope creep) | Moyenne | Élevé | 🟠 Majeur | Périmètre gelé après sprint 1 |
| R2 | Indisponibilité dev senior | Faible | Critique | 🔴 Critique | Binômage permanent, doc code |
| R3 | Bug sécurité majeur | Faible | Critique | 🔴 Critique | Audit code sprint 6, pentest |
| R4 | Refus utilisateurs | Faible | Élevé | 🟠 Majeur | Bêta test, formation, support |
| R5 | Performance insuffisante | Faible | Moyen | 🟡 Mineur | Benchmark sprint 3, optimisations |
| R6 | Crash serveur prod | Faible | Élevé | 🟠 Majeur | Backups quotidiens, plan reprise |
| R7 | Budget dépassé (>10%) | Moyenne | Moyen | 🟠 Majeur | Suivi hebdo, alerte à +5% |
| R8 | Délai dépassé (>2 semaines) | Moyenne | Moyen | 🟠 Majeur | Sprint planning rigoureux, buffer |

### 4.2 Plan de Contingence

#### Scénario 1 : Indisponibilité Développeur Senior

**Déclencheur** : Absence > 1 semaine
**Actions** :
1. Développeur junior prend lead (formation accélérée)
2. Freelance PHP senior (backup identifié, 500€/jour)
3. Réduction périmètre si nécessaire (module stats reporté)

#### Scénario 2 : Bug Sécurité Critique Post-Production

**Déclencheur** : Vulnérabilité OWASP Top 10 découverte
**Actions** :
1. Mise hors ligne immédiate (page maintenance)
2. Patch développé en urgence (< 24h)
3. Audit externe complet (prestataire)
4. Communication transparente utilisateurs

#### Scénario 3 : Refus Utilisateurs Massif

**Déclencheur** : < 30% adoption après 1 mois
**Actions** :
1. Enquête satisfaction (interviews)
2. Ajustements ergonomie rapides
3. Formation complémentaire individuelle
4. Mode hybride temporaire (ancien outil + PGI)

---

## 5. Recommandations GO/NO-GO

### 5.1 Synthèse des Faisabilités

| Axe | Verdict | Niveau de Confiance |
|-----|---------|---------------------|
| **Technique** | ✅ FAISABLE | 95% |
| **Économique** | ✅ RENTABLE | 90% (ROI +164% à 3 ans) |
| **Organisationnel** | ✅ RÉALISABLE | 85% (équipe compétente) |

### 5.2 Critères de Décision GO

- [x] Technologies matures et maîtrisées
- [x] Équipe compétente disponible
- [x] ROI positif à 18 mois
- [x] Besoin métier fort et documenté
- [x] Risques identifiés et maîtrisables
- [x] Budget validé (34 300€)
- [x] Sponsor projet identifié (Direction)

**Tous les critères sont remplis** : ✅

### 5.3 Conditions de Succès

1. **Engagement Direction** : Sponsor actif, budget garanti
2. **Périmètre Gelé** : Aucun ajout fonctionnel après sprint 1
3. **Disponibilité Équipe** : Développeurs full-time 4 mois
4. **Implication Utilisateurs** : Bêta testeurs disponibles sprint 6
5. **Formation Prioritaire** : 2 semaines dédiées avant production

### 5.4 Facteurs de Risque Résiduels

- ⚠️ Dépendance forte au développeur senior (mitigation : binômage)
- ⚠️ Adoption portail client incertaine (mitigation : campagne comm)
- ⚠️ Évolution future nécessite compétences PHP maintenues (mitigation : doc)

### 5.5 Plan B (NO-GO)

**Si décision NO-GO**, alternatives :

1. **Solution SaaS** : AutoSoft DMS (5 400€/3 ans)
   - ❌ Coût supérieur
   - ❌ Dépendance fournisseur
   - ✅ Mise en œuvre rapide (1 semaine)

2. **Externalisation développement** : ESN spécialisée
   - ❌ Coût +50% (45 000€)
   - ✅ Expertise garantie
   - ❌ Transfert compétences limité

3. **Développement par phases** : Seulement modules critiques
   - ✅ Réduction coûts (-40%)
   - ❌ Bénéfices limités
   - ⚠️ Migration progressive complexe

---

## 6. Conclusion et Recommandation

### 6.1 Synthèse

Le projet **PGI Automobile** présente une **faisabilité élevée** sur les trois axes évalués :

- **Technique** : Stack PHP/MySQL mature, prototype validé, équipe compétente
- **Économique** : ROI de +164% à 3 ans, break-even à 13 mois, gain net 51 492€
- **Organisationnel** : Équipe disponible, méthodologie agile éprouvée, résistance au changement maîtrisable

Les **risques identifiés** (dérive fonctionnelle, sécurité, adoption) disposent de **plans de mitigation** robustes.

### 6.2 Recommandation Finale

**RECOMMANDATION : GO ✅**

**Justifications** :
1. Besoin métier fort et documenté (gain 27 600€/an)
2. Solution technique adaptée et maîtrisée
3. Investissement raisonnable (32 262€ sur 3 ans)
4. ROI attractif (+164% à 3 ans)
5. Équipe compétente et motivée
6. Risques maîtrisables

### 6.3 Prochaines Étapes

| Étape | Responsable | Deadline |
|-------|-------------|----------|
| **Validation décision GO** | Direction | J+7 |
| **Signature budget** | DAF | J+14 |
| **Constitution équipe** | Chef de Projet | J+21 |
| **Kick-off projet** | Chef de Projet | J+30 |
| **Sprint 1 (démarrage dev)** | Équipe | J+30 |

---

## Annexes

### A. Références Techniques

- PHP 8.0 Documentation : https://www.php.net/
- MySQL 8.0 Reference Manual : https://dev.mysql.com/doc/
- OWASP Top 10 : https://owasp.org/www-project-top-ten/

### B. Benchmark Solutions SaaS

| Solution | Site | Essai Gratuit |
|----------|------|---------------|
| AutoSoft DMS | autosoft-dms.com | 30 jours |
| VinSolutions | vinsolutions.com | Démo |
| AutoRaptor | autoraptor.com | 14 jours |

### C. Prestataires de Secours

| Type | Entreprise | Contact | Tarif |
|------|-----------|---------|-------|
| **Freelance PHP** | Jean DUPONT | jean@example.com | 500€/jour |
| **Audit Sécurité** | SecurIT Consulting | contact@securit.fr | 3 000€ |
| **Hébergement Backup** | AWS France | - | Variable |

---

**Date de validation** : _____________________

**Signature Direction** : _____________________

**Fin du document**
