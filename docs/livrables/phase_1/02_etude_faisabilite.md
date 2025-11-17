# Étude de Faisabilité - PGI Automobile

**Projet:** Progiciel de Gestion Intégré pour Concession Automobile (Projet Académique)
**Version:** 1.0 (Projet Académique)
**Date:** Novembre 2025
**Auteurs:** Thibaud THOMAS-LAMOTTE & Melissa BENZIDANE
**Contexte:** Projet de L3 - Période du 27/10/2025 au 17/11/2025 (3 semaines)
**Auteurs:** Thibaud THOMAS-LAMOTTE & Melissa BENZIDANE
**Contexte:** Projet de L3 - Période du 27/10/2025 au 17/11/2025 (3 semaines)

---

## Résumé Exécutif

Cette étude évalue la faisabilité du développement d'un PGI Automobile dans le cadre d'un projet académique de Licence 3, sur une période de **3 semaines**. L'analyse porte sur trois axes : **technique, pédagogique et organisationnel**.

**Conclusion** : Le projet est **FAISABLE** dans les contraintes de temps et de ressources données. Les technologies choisies (PHP/MySQL) sont adaptées au niveau L3, et le périmètre fonctionnel est réaliste pour 2 étudiants en 3 semaines.

---

## 1. Faisabilité Technique

### 1.1 Technologies Retenues

| Composant | Technologie | Justification |
|-----------|-------------|---------------|
| **Backend** | PHP 7.4+ (natif) | Enseigné en L3, pas de framework complexe, documentation riche |
| **Base de données** | MySQL 8.0 | Relationnel adapté au projet, enseigné en cours |
| **Frontend** | HTML5/CSS3/JS | Technologies de base, maîtrise acquise |
| **Serveur web** | Apache 2.4 | Configuration simple, compatible tous environnements |
| **Sécurité** | PDO, bcrypt, sessions PHP | Standards enseignés, implémentation directe |

### 1.2 Choix Technologiques

#### Pourquoi PHP Natif ?

**Avantages** :
- Enseigné durant le cursus L3
- Pas de courbe d'apprentissage d'un framework
- Déploiement simple (pas de dépendances)
- Contrôle total du code
- Documentation exhaustive disponible

**Adaptation au projet** :
- Permet de se concentrer sur la logique métier
- Déploiement facile pour la démonstration
- Pas de configuration complexe

### 1.3 Architecture Technique

**Architecture 3-tiers retenue** :
- **Présentation** : HTML/CSS/JavaScript (interface utilisateur)
- **Logique métier** : PHP (traitement des requêtes, règles de gestion)
- **Données** : MySQL (stockage persistant)

**Justification** :
- Architecture enseignée en cours
- Séparation des préoccupations claire
- Maintenabilité et évolutivité

### 1.4 Sécurité

Les mesures de sécurité implémentées :

| Menace | Protection | Implémentation |
|--------|------------|----------------|
| **Injection SQL** | PDO Prepared Statements | 100% des requêtes utilisent des paramètres bindés |
| **XSS** | Échappement HTML | `htmlspecialchars()` sur toutes les sorties |
| **Mots de passe** | Hachage bcrypt | `password_hash()` avec BCRYPT |
| **Sessions** | Sessions PHP sécurisées | Configuration httponly et secure |
| **Accès** | RBAC | Vérification des permissions à chaque action |

### 1.5 Environnement de Développement

**Configuration requise** :
- WAMP/XAMPP/MAMP (serveur local)
- PHP 7.4+
- MySQL 8.0
- Éditeur de code (VS Code, PHPStorm)
- Git pour le versioning

**Hébergement pour démonstration** :
- Hébergement gratuit (GitHub Pages + base de données externe) OU
- Serveur local pour présentation

---

## 2. Faisabilité Pédagogique

### 2.1 Adéquation avec le Programme L3

Le projet mobilise les compétences enseignées en L3 :

| Domaine | Compétences Appliquées |
|---------|----------------------|
| **Programmation Web** | PHP, HTML, CSS, JavaScript |
| **Bases de données** | MySQL, modélisation MCD/MLD, requêtes SQL |
| **Architecture** | Architecture 3-tiers, MVC simplifié |
| **Sécurité** | PDO, bcrypt, contrôle d'accès |
| **Gestion de projet** | Planning, versioning Git, documentation |

### 2.2 Objectifs Pédagogiques Atteints

| Objectif | Démonstration |
|----------|---------------|
| **Maîtrise du développement web** | Application complète avec 8 modules fonctionnels |
| **Modélisation de données** | 10 tables avec relations, contraintes d'intégrité |
| **Architecture logicielle** | Code structuré, séparation frontend/backend |
| **Sécurité applicative** | Implémentation OWASP Top 10 |
| **Autonomie** | Gestion complète du projet de A à Z |

### 2.3 Complexité Adaptée

**Périmètre fonctionnel** :
- ✅ 8 modules (ni trop simple, ni trop complexe)
- ✅ Fonctionnalités réalistes (CRUD, calculs, statistiques)
- ✅ Interface complète et ergonomique
- ✅ Documentation professionnelle

**Niveau technique** :
- Adapté à des étudiants de L3
- Challenges techniques variés
- Pas de sur-ingénierie

---

## 3. Faisabilité Organisationnelle

### 3.1 Équipe Projet

| Membre | Rôle Principal | Responsabilités |
|--------|---------------|-----------------|
| **Thibaud THOMAS-LAMOTTE** | Développeur Full-Stack | Backend PHP, architecture, BDD, sécurité |
| **Melissa BENZIDANE** | Développeur Full-Stack | Frontend HTML/CSS/JS, design, tests, documentation |

**Répartition du travail** :
- Conception : ensemble
- Développement : répartition par modules
- Tests : chacun teste le code de l'autre
- Documentation : répartition des livrables

### 3.2 Planning Réaliste

**Durée** : 3 semaines (27 octobre - 17 novembre 2025)

#### Semaine 1 (27/10 - 03/11) : Fondations

| Activité | Durée | Responsable |
|----------|-------|-------------|
| Analyse et conception | 2 jours | Les deux |
| Modélisation BDD (MCD/MLD) | 1 jour | Thibaud |
| Setup environnement + structure projet | 1 jour | Les deux |
| Modules Auth + Véhicules | 3 jours | Thibaud (Auth) + Melissa (UI) |

**Livrable S1** : Base technique opérationnelle + 2 premiers modules

#### Semaine 2 (04/11 - 10/11) : Développement

| Activité | Durée | Responsable |
|----------|-------|-------------|
| Modules Clients + Ventes | 2 jours | Thibaud |
| Modules Demandes + RH | 2 jours | Les deux |
| Module Stock + Statistiques | 2 jours | Melissa |
| Module Administration | 1 jour | Thibaud |

**Livrable S2** : Tous les modules fonctionnels développés

#### Semaine 3 (11/11 - 17/11) : Finalisation

| Activité | Durée | Responsable |
|----------|-------|-------------|
| Tests et corrections bugs | 2 jours | Les deux |
| Design final et responsive | 1 jour | Melissa |
| Documentation technique (20 livrables) | 3 jours | Les deux |
| Préparation présentation | 1 jour | Les deux |

**Livrable S3** : Projet finalisé avec documentation complète

### 3.3 Charge de Travail

**Estimation** :
- Développement : ~80 heures (40h chacun)
- Tests : ~10 heures (5h chacun)
- Documentation : ~30 heures (15h chacun)
- **Total** : ~120 heures sur 3 semaines

**Répartition** : ~20 heures/semaine par personne (réaliste pour un projet académique)

### 3.4 Gestion des Risques

| Risque | Probabilité | Mitigation |
|--------|-------------|------------|
| **Retard sur planning** | Moyenne | Priorisation des modules essentiels, MVP d'abord |
| **Bugs complexes** | Moyenne | Tests réguliers, debugging à deux |
| **Fonctionnalités trop ambitieuses** | Faible | Périmètre clairement défini, pas de scope creep |
| **Problèmes techniques** | Faible | Stack technologique maîtrisée, support cours |

**Stratégie** : Développement incrémental avec validation continue

---

## 4. Analyse des Alternatives

### 4.1 Option 1 : Framework PHP (Laravel/Symfony)

**Avantages** :
- Scaffolding rapide
- ORM pour la base de données
- Sécurité intégrée

**Inconvénients** :
- ❌ Courbe d'apprentissage importante
- ❌ Temps de setup et configuration
- ❌ Surcharge pour le périmètre du projet

**Décision** : ❌ **Rejetée** - Pas adapté au temps disponible

### 4.2 Option 2 : Technologies modernes (React + Node.js)

**Avantages** :
- Technologies actuelles
- Expérience valorisante

**Inconvénients** :
- ❌ Hors programme L3
- ❌ Courbe d'apprentissage trop élevée
- ❌ Pas assez de temps pour maîtriser

**Décision** : ❌ **Rejetée** - Hors périmètre académique

### 4.3 Option 3 : PHP Natif (Choix retenu)

**Avantages** :
- ✅ Enseigné en cours
- ✅ Pas de dépendances complexes
- ✅ Contrôle total du code
- ✅ Déploiement simple
- ✅ Adapté au niveau L3

**Inconvénients** :
- Développement "from scratch"
- Pas de helpers intégrés

**Décision** : ✅ **RETENUE** - Équilibre optimal pour le projet

---

## 5. Conclusion et Recommandation

### 5.1 Synthèse des Faisabilités

| Axe | Verdict | Niveau de Confiance |
|-----|---------|---------------------|
| **Technique** | ✅ FAISABLE | 95% (technologies maîtrisées) |
| **Pédagogique** | ✅ ADAPTÉ | 100% (objectifs L3 atteints) |
| **Organisationnel** | ✅ RÉALISABLE | 85% (planning serré mais réaliste) |

### 5.2 Facteurs de Succès

- ✅ Technologies enseignées et maîtrisées
- ✅ Équipe de 2 : communication facile
- ✅ Périmètre fonctionnel bien défini
- ✅ Planning structuré par semaine
- ✅ Méthodologie itérative (tests continus)

### 5.3 Recommandation Finale

**RECOMMANDATION : GO ✅**

**Justifications** :
1. Stack technique adaptée au niveau L3
2. Périmètre réaliste pour 3 semaines
3. Équipe motivée et compétente
4. Planning structuré et réaliste
5. Valeur pédagogique élevée

### 5.4 Conditions de Succès

1. **Démarrage rapide** : Setup en jour 1, code dès jour 2
2. **Priorisation** : Modules essentiels d'abord (MVP)
3. **Communication** : Coordination quotidienne
4. **Tests continus** : Validation au fur et à mesure
5. **Documentation progressive** : Ne pas tout laisser à la fin

### 5.5 Prochaines Étapes

| Étape | Date |
|-------|------|
| **Setup environnement** | 27/10 (J1) |
| **Modélisation BDD** | 28/10 (J2) |
| **Premiers modules** | 29/10-01/11 (J3-J5) |
| **Développement intensif** | 04/11-10/11 (Semaine 2) |
| **Finalisation** | 11/11-17/11 (Semaine 3) |

---

## Annexes

### A. Technologies et Ressources

**Documentation officielle** :
- PHP : https://www.php.net/
- MySQL : https://dev.mysql.com/doc/
- OWASP : https://owasp.org/

**Outils de développement** :
- WAMP/XAMPP (serveur local)
- VS Code / PHPStorm (IDE)
- Git / GitHub (versioning)
- phpMyAdmin (administration BDD)

### B. Références Pédagogiques

- Cours de PHP avancé (L3)
- Cours de bases de données (L3)
- Cours d'architecture logicielle (L3)
- Cours de sécurité web (L3)

---

**Date de validation** : 27 octobre 2025

**Auteurs** :
- Thibaud THOMAS-LAMOTTE
- Melissa BENZIDANE

**Fin du document**
