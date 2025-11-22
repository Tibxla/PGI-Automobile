# 17. FAQ ET SUPPORT

## Informations du Document

| Élément | Détail |
|---------|--------|
| **Projet** | PGI Automobile - Système de Gestion Intégré |
| **Phase** | PHASE 6 - Maintenance |
| **Livrable** | FAQ et Support |
| **Version** | 1.0 |
| **Date** | 17/11/2025 |
| **Auteur** | Équipe Support PGI Automobile |

---

> **Note:** Ce document a été réalisé dans le cadre d'un projet académique de Licence 3 par **Thibaud** et **Melissa** sur la période du **27/10/2025 au 17/11/2025** (3 semaines).

## Table des Matières

1. [Questions Fréquentes (FAQ)](#1-questions-fréquentes-faq)
2. [Problèmes Techniques Courants](#2-problèmes-techniques-courants)
3. [Procédures de Support](#3-procédures-de-support)
4. [Contacts et Ressources](#4-contacts-et-ressources)

---

## 1. Questions Fréquentes (FAQ)

### 1.1 Connexion et Compte

#### Q1.1 : J'ai oublié mon mot de passe, que faire ?

**Réponse :**

1. Sur la page de connexion, cliquez sur **"Mot de passe oublié ?"**
2. Saisissez votre adresse email professionnelle
3. Vous recevrez un email avec un lien de réinitialisation (valable 1 heure)
4. Cliquez sur le lien et définissez un nouveau mot de passe

⚠️ **Si vous ne recevez pas l'email :**
- Vérifiez vos spams/courrier indésirable
- Vérifiez que l'adresse email est correcte
- Contactez votre administrateur système

💡 **Alternative** : Contactez directement votre administrateur qui peut réinitialiser votre mot de passe manuellement.

---

#### Q1.2 : Mon compte est bloqué après plusieurs tentatives de connexion, que faire ?

**Réponse :**

Le système bloque automatiquement un compte après **10 tentatives de connexion échouées** dans un délai de 15 minutes (protection contre les attaques).

**Solutions :**
1. **Attendre 30 minutes** : Le blocage se lève automatiquement
2. **Contacter l'administrateur** : Il peut débloquer votre compte immédiatement

📝 **Conseil** : Utilisez un gestionnaire de mots de passe pour éviter les erreurs de saisie.

---

#### Q1.3 : Comment changer mon mot de passe ?

**Réponse :**

1. Connectez-vous au système
2. Cliquez sur votre nom en haut à droite
3. Sélectionnez **"Mon profil"**
4. Dans la section "Sécurité", cliquez sur **"Changer le mot de passe"**
5. Saisissez :
   - Ancien mot de passe
   - Nouveau mot de passe (2 fois)
6. Cliquez sur **"Enregistrer"**

📝 **Exigences du nouveau mot de passe :**
- Minimum 8 caractères
- Au moins 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial
- Différent des 3 derniers mots de passe utilisés

---

#### Q1.4 : Ma session se déconnecte tout le temps, pourquoi ?

**Réponse :**

La session expire après **30 minutes d'inactivité** (norme de sécurité).

**Solutions :**
1. **Restez actif** : Toute action dans le système réinitialise le compteur
2. **Ne fermez pas votre navigateur** pendant votre travail
3. **Si vous partez longtemps**, déconnectez-vous manuellement pour éviter une déconnexion forcée en plein travail

⚠️ **Astuce** : Enregistrez régulièrement vos formulaires en cours pour ne pas perdre de données.

---

### 1.2 Module Véhicules

#### Q2.1 : Je ne trouve pas un véhicule dans la liste, où est-il ?

**Réponse :**

**Vérifications :**

1. **Vérifiez les filtres** : Assurez-vous que le filtre "Statut" est sur "Tous" et non sur "Stock" ou "Vendu"
2. **Utilisez la recherche** : Tapez l'immatriculation, la marque ou le modèle dans la barre de recherche
3. **Vérifiez le statut** : Le véhicule a peut-être été vendu ou supprimé
4. **Pagination** : Le véhicule est peut-être sur une autre page (regardez en bas)

💡 **Astuce** : Utilisez **Ctrl+F** (Cmd+F sur Mac) pour rechercher dans la page actuelle.

---

#### Q2.2 : Puis-je modifier un véhicule déjà vendu ?

**Réponse :**

❌ **Non**, par défaut vous ne pouvez pas modifier un véhicule dont le statut est "Vendu". C'est une protection pour garantir l'intégrité des données comptables.

**Exception :** Un Super Admin peut modifier certains champs (comme les notes ou la description) mais pas le prix ou les informations critiques.

**Alternative :** Si vous avez besoin de corriger une erreur, annulez d'abord la vente associée (voir module Ventes).

---

#### Q2.3 : L'upload de photo échoue, que faire ?

**Réponse :**

**Causes possibles :**

1. **Fichier trop volumineux** : Max 10 Mo
   - Solution : Réduisez la taille de l'image avec un outil (https://tinypng.com/)

2. **Format non supporté** : Seuls JPG, JPEG, PNG sont acceptés
   - Solution : Convertissez votre image au bon format

3. **Nom de fichier avec espaces ou accents** : Ex: `Peugeot 308 été.jpg`
   - Solution : Renommez en `peugeot_308.jpg` (sans espaces ni caractères spéciaux)

4. **Problème serveur** : Espace disque plein
   - Solution : Contactez l'administrateur système

💡 **Astuce** : Utilisez toujours des noms de fichiers simples : `marque_modele.jpg`

---

#### Q2.4 : Comment calculer manuellement la marge d'un véhicule ?

**Réponse :**

La marge est calculée automatiquement, mais voici la formule :

```
Prix de vente TTC : 18 900 €
Prix HT = Prix TTC / 1.20 = 15 750 €  (déduction TVA 20%)
Prix d'achat HT : 15 000 €
Marge HT = 15 750 - 15 000 = 750 €
Taux de marge = (750 / 15 000) × 100 = 5%
```

📝 **Note** : Le système affiche toujours la marge HT et le taux.

---

### 1.3 Module Ventes

#### Q3.1 : Puis-je annuler une vente déjà enregistrée ?

**Réponse :**

**Oui, mais sous conditions :**

**Qui peut annuler ?** Directeur ou Super Admin uniquement (pas les Vendeurs)

**Procédure :**
1. Ouvrez la vente
2. Cliquez sur **"Annuler la vente"**
3. Indiquez le motif (obligatoire pour traçabilité)
4. Confirmez

**Conséquences :**
- ✅ Vente marquée comme "Annulée" (conservée pour l'audit)
- ✅ Véhicule repasse automatiquement en statut "Stock"
- ✅ Facture annotée "ANNULÉE"

⚠️ **Attention** : L'annulation ne supprime pas les données, elle les archive.

---

#### Q3.2 : Le véhicule que je veux vendre n'apparaît pas dans la liste déroulante, pourquoi ?

**Réponse :**

Seuls les véhicules avec le statut **"Stock"** (disponibles) apparaissent dans la liste.

**Causes possibles :**
1. **Véhicule déjà vendu** : Vérifiez dans le module Véhicules
2. **Véhicule réservé** : Changez d'abord le statut en "Stock"
3. **Véhicule pas encore dans le système** : Ajoutez-le via le module Véhicules d'abord

💡 **Astuce** : Tapez les premières lettres de l'immatriculation pour filtrer rapidement.

---

#### Q3.3 : Puis-je enregistrer une vente avec un prix inférieur au prix d'achat ?

**Réponse :**

**Oui**, techniquement le système l'autorise (cas de liquidation, véhicule accidenté, etc.), mais vous recevrez un **avertissement** :

```
⚠️ Attention : Le prix de vente (12 000 €) est inférieur au prix d'achat (15 000 €).
Marge négative : -3 000 € (-20%)
Voulez-vous vraiment continuer ?
[Annuler] [Confirmer]
```

Vous devez confirmer explicitement pour valider la vente à perte.

📝 **Note** : Ces ventes sont signalées dans les rapports pour le Directeur.

---

#### Q3.4 : Comment imprimer / envoyer la facture au client ?

**Réponse :**

**Option 1 : Télécharger et imprimer**
1. Ouvrez la vente
2. Cliquez sur **"Télécharger la facture PDF"**
3. Ouvrez le PDF et imprimez (**Ctrl+P** / **Cmd+P**)

**Option 2 : Envoyer par email**
1. Ouvrez la vente
2. Cliquez sur **"Envoyer par email au client"**
3. Vérifiez l'adresse email du client
4. Cliquez sur "Envoyer"
5. Le client reçoit la facture en pièce jointe

💡 **Astuce** : Vous pouvez personnaliser le message d'accompagnement de l'email.

---

### 1.4 Module Clients

#### Q4.1 : Un client existe en double, comment fusionner ?

**Réponse :**

Le système ne permet pas de fusionner automatiquement deux clients (risque d'erreur).

**Procédure manuelle :**

1. **Identifiez le doublon** : Ex: "Jean Dupont" et "J. Dupont"
2. **Choisissez le bon** : Celui avec le plus d'historique
3. **Contactez votre administrateur** : Il fera la fusion manuellement en base de données

⚠️ **Prévention** : Toujours rechercher d'abord si le client existe avant d'en créer un nouveau !

💡 **Astuce** : Utilisez la recherche par email ou téléphone (plus fiable que le nom).

---

#### Q4.2 : Puis-je supprimer un client ?

**Réponse :**

**Restrictions :**
- ❌ Vous ne pouvez **pas supprimer** un client qui a des ventes associées (contrainte métier)
- ✅ Vous pouvez supprimer un client sans historique d'achat

**Alternative pour les clients inactifs :**
- Ajoutez une note : "Client inactif depuis AAAA-MM-JJ"
- Ne le supprimez pas (conservation de l'historique)

**Cas RGPD (droit à l'oubli) :**
Si un client demande la suppression de ses données :
1. Contactez votre administrateur
2. Il anonymisera les données (remplacer nom/email par "Client Anonyme #123")
3. L'historique des ventes est conservé (obligation comptable 10 ans)

---

#### Q4.3 : Comment exporter la liste des clients ?

**Réponse :**

1. Allez dans **Clients**
2. Appliquez les filtres souhaités (optionnel)
3. Cliquez sur **"Exporter"** en haut à droite
4. Choisissez le format :
   - 📊 Excel (.xlsx) : Recommandé pour traitement
   - 📋 CSV : Pour import dans autre logiciel
   - 📄 PDF : Pour archivage
5. Le fichier se télécharge

💡 **Astuce** : L'export respecte les filtres actifs. Pour tout exporter, réinitialisez les filtres d'abord.

---

### 1.5 Module Employés (RH)

#### Q5.1 : Comment générer toutes les paies du mois en une fois ?

**Réponse :**

Au lieu de générer paie par paie :

1. Allez dans **Employés**
2. Cliquez sur **"Générer toutes les paies"** en haut à droite
3. Sélectionnez le mois : `Novembre 2025`
4. Vérifiez la liste des employés actifs
5. Cliquez sur **"Générer"**

**Résultat :**
- Paies créées pour tous les employés actifs
- PDFs générés automatiquement
- Emails envoyés (si configuré)

⏱️ **Durée** : Environ 5 secondes par employé.

📝 **Note** : Vous pouvez ensuite modifier individuellement si nécessaire (heures sup, primes).

---

#### Q5.2 : Une paie contient une erreur, puis-je la modifier ?

**Réponse :**

**Oui**, dans un délai de **7 jours** après génération.

**Procédure :**
1. Ouvrez la paie
2. Cliquez sur **"Modifier"**
3. Corrigez les montants (salaire, heures sup, primes)
4. Le système recalcule automatiquement les cotisations
5. Cliquez sur **"Régénérer"**

**Après 7 jours :**
- ❌ Modification impossible (paie clôturée)
- ✅ Alternative : Générer une paie corrective le mois suivant avec régularisation

⚠️ **Important** : Les modifications sont tracées (audit).

---

#### Q5.3 : Un employé a quitté l'entreprise, dois-je le supprimer ?

**Réponse :**

❌ **Non, ne supprimez pas !** Conservation de l'historique obligatoire.

**Procédure recommandée :**
1. Ouvrez la fiche employé
2. Changez le statut : **"Actif"** → **"Inactif"**
3. Renseignez la date de départ dans les notes
4. Sauvegardez

**Conséquences :**
- L'employé n'apparaît plus dans les listes actives
- Son historique de paies est conservé
- Il n'est plus comptabilisé dans les statistiques RH courantes

💡 **Astuce** : Utilisez le filtre "Statut : Inactif" pour voir les anciens employés.

---

### 1.6 Statistiques

#### Q6.1 : Les chiffres du tableau de bord ne correspondent pas à ma comptabilité, pourquoi ?

**Réponse :**

**Causes possibles :**

1. **Période différente** : Vérifiez que vous comparez la même période
   - Tableau de bord : Par défaut "Mois en cours"
   - Comptabilité : Peut-être "Mois calendaire complet"

2. **Ventes annulées** : Le tableau de bord peut inclure ou exclure les ventes annulées selon le filtre
   - Solution : Filtrez sur "Statut : Validées uniquement"

3. **Délai de synchronisation** : Rafraîchissez la page (F5)

4. **Bug** : Comparez ligne par ligne pour identifier l'écart

💡 **Astuce** : Exportez les données en Excel et comparez avec votre compta.

---

#### Q6.2 : Puis-je créer des rapports personnalisés ?

**Réponse :**

**Dans la version actuelle (v1.0) :** Non, les rapports sont prédéfinis.

**Alternatives :**
1. **Exportez les données brutes** (Excel/CSV) et créez vos propres tableaux croisés
2. **Contactez le support** pour demander un rapport spécifique (peut être ajouté en v2.0)

📝 **À venir (v2.0) :** Module de création de rapports personnalisés avec sélection des champs et filtres.

---

### 1.7 Performance

#### Q7.1 : Le système est lent, que faire ?

**Réponse :**

**Solutions côté utilisateur :**

1. **Rafraîchir la page** : **F5** (ou Ctrl+R / Cmd+R)
2. **Vider le cache** :
   - Chrome : Ctrl+Shift+Suppr → "Vider le cache"
   - Firefox : Ctrl+Shift+Suppr → "Vider le cache"
3. **Fermer les onglets inutiles** : Le navigateur consomme de la RAM
4. **Redémarrer le navigateur**
5. **Vérifier votre connexion internet** : www.speedtest.net

**Si le problème persiste :**
- Testez sur un autre navigateur (Chrome vs Firefox)
- Testez depuis un autre ordinateur
- Contactez le support technique

⚠️ **Problème côté serveur ?** Si tous les utilisateurs sont impactés, c'est un problème serveur → Contactez immédiatement l'administrateur système.

---

#### Q7.2 : Pourquoi les graphiques ne s'affichent pas ?

**Réponse :**

**Causes possibles :**

1. **JavaScript désactivé** : Le système nécessite JavaScript
   - Solution : Vérifiez les paramètres de votre navigateur

2. **Bloqueur de publicités** (AdBlock, uBlock) : Peut bloquer les bibliothèques graphiques
   - Solution : Ajoutez le site à la liste blanche

3. **Données vides** : S'il n'y a aucune vente, le graphique est vide
   - Normal : Attendez d'avoir des données

4. **Navigateur obsolète** : Internet Explorer n'est pas supporté
   - Solution : Utilisez Chrome, Firefox, Edge ou Safari

💡 **Test rapide** : Ouvrez la console JavaScript (F12) et cherchez des erreurs rouges.

---

## 2. Problèmes Techniques Courants

### 2.1 Erreurs et Messages

#### Erreur : "Session expirée"

**Message :**
```
⚠️ Votre session a expiré. Veuillez vous reconnecter.
```

**Cause :** Inactivité de plus de 30 minutes.

**Solution :**
1. Cliquez sur **"OK"**
2. Reconnectez-vous
3. Reprenez votre travail

⚠️ **Données perdues ?** Si vous étiez en train de remplir un formulaire, les données non enregistrées sont perdues. Pensez à enregistrer régulièrement !

---

#### Erreur : "Accès refusé"

**Message :**
```
❌ Vous n'avez pas les permissions nécessaires pour accéder à cette page.
```

**Cause :** Vous tentez d'accéder à un module réservé à un autre rôle.

**Solution :**
1. Vérifiez votre rôle (affiché en haut à droite)
2. Si c'est une erreur, contactez votre administrateur pour ajuster vos permissions
3. Sinon, demandez à un collègue ayant les droits

---

#### Erreur : "Ce véhicule n'est plus disponible"

**Message :**
```
❌ Erreur : Ce véhicule n'est plus disponible (déjà vendu).
```

**Cause :** Un autre vendeur a vendu le véhicule entre-temps (concurrence).

**Solution :**
1. Actualisez la page (F5)
2. Choisissez un autre véhicule
3. Informez le client

💡 **Astuce** : Pour éviter cela, réservez le véhicule (statut "Réservé") avant de finaliser la vente.

---

#### Erreur : "Erreur de connexion à la base de données"

**Message :**
```
❌ Impossible de se connecter à la base de données. Veuillez réessayer.
```

**Cause :** Problème serveur (base de données arrêtée ou en maintenance).

**Solution :**
1. Attendez quelques minutes
2. Rafraîchissez la page
3. Si le problème persiste après 5 minutes, **contactez immédiatement l'administrateur système** (urgence !)

---

### 2.2 Problèmes Navigateur

#### Le site ne s'affiche pas correctement (CSS cassé)

**Symptômes :**
- Pas de couleurs
- Éléments mal alignés
- Texte brut sans mise en forme

**Causes et Solutions :**

1. **Cache navigateur** :
   - Ctrl+Shift+R (ou Cmd+Shift+R) : Rafraîchir en forçant le rechargement des CSS

2. **Connexion internet lente** :
   - Attendez le chargement complet
   - Vérifiez votre débit

3. **Fichiers CSS bloqués** :
   - Désactivez temporairement votre antivirus/pare-feu
   - Vérifiez avec votre service IT

---

#### Impossible de télécharger un PDF

**Symptômes :**
- Le PDF ne se télécharge pas
- Le fichier est corrompu

**Solutions :**

1. **Vérifiez les popups** : Autorisez les popups pour ce site
2. **Vérifiez le bloqueur de téléchargements** : Paramètres navigateur
3. **Essayez un autre navigateur** : Chrome, Firefox
4. **Vérifiez l'espace disque** : Libérez de l'espace sur votre ordinateur

💡 **Alternative** : Demandez à un collègue de télécharger et vous l'envoyer par email.

---

### 2.3 Problèmes de Saisie

#### Les accents ne fonctionnent pas (é, è, à, ç)

**Cause :** Problème de clavier ou encodage.

**Solutions :**
1. **Vérifiez la disposition du clavier** : Français AZERTY
2. **Redémarrez le navigateur**
3. **Copiez-collez** les accents depuis un autre document

---

#### Le formulaire ne se soumet pas (bouton "Enregistrer" ne réagit pas)

**Causes possibles :**

1. **Champs obligatoires manquants** : Cherchez les champs en rouge avec un message d'erreur
2. **JavaScript bloqué** : Vérifiez les paramètres
3. **Double-clic** : Vous avez cliqué trop vite plusieurs fois
   - Solution : Attendez 2-3 secondes, ne cliquez qu'une fois

💡 **Astuce** : Après avoir cliqué sur "Enregistrer", le bouton devient gris et affiche "Enregistrement..." → Soyez patient.

---

## 3. Procédures de Support

### 3.1 Niveaux de Support

| Niveau | Description | Contact | Délai Réponse |
|--------|-------------|---------|---------------|
| **N1 - Utilisateur** | Consulter la documentation, FAQ | - | Immédiat |
| **N2 - Responsable** | Questions métier, formation | Votre manager | < 2 heures |
| **N3 - Support Technique** | Bugs, problèmes techniques | support@votreentreprise.com | < 24 heures |
| **N4 - Administrateur Système** | Problèmes serveur, urgences | admin@votreentreprise.com | < 1 heure (urgence) |

### 3.2 Ouvrir un Ticket de Support

**Quand ouvrir un ticket ?**
- Bug ou erreur système
- Demande de fonctionnalité
- Question technique non résolue par la FAQ
- Problème de performance

**Comment ouvrir un ticket ?**

**Par Email :** support@votreentreprise.com

**Format du message :**

```
Objet : [PGI AUTO] Description courte du problème

Bonjour,

1. INFORMATIONS UTILISATEUR
Nom : Jean Dupont
Rôle : Vendeur
Email : jean.dupont@entreprise.com

2. DESCRIPTION DU PROBLÈME
[Décrivez le problème en détail]

3. ÉTAPES POUR REPRODUIRE
1. Je vais dans le module Véhicules
2. Je clique sur "Ajouter un véhicule"
3. Je remplis le formulaire
4. Je clique sur "Enregistrer"
5. Message d'erreur : "Erreur 500"

4. RÉSULTAT ATTENDU
Le véhicule devrait être enregistré.

5. RÉSULTAT OBTENU
Message d'erreur et pas d'enregistrement.

6. CAPTURES D'ÉCRAN
[Joindre en pièce jointe]

7. NAVIGATEUR ET SYSTÈME
- Navigateur : Chrome 120.0
- OS : Windows 11
- Date et heure : 17/11/2025 à 14:35

8. URGENCE
☐ Bloquant (je ne peux plus travailler)
☑ Important (impact fort mais contournement possible)
☐ Normal (question ou amélioration)

Merci,
Jean Dupont
```

📝 **Plus vous donnez de détails, plus vite nous pourrons résoudre votre problème !**

---

### 3.3 Priorités et Délais

| Priorité | Définition | Exemples | Délai |
|----------|------------|----------|-------|
| **🔴 P0 - Bloquant** | Système inutilisable | Serveur down, impossible de se connecter | < 1h |
| **🟠 P1 - Critique** | Fonctionnalité majeure cassée | Impossible d'enregistrer une vente | < 4h |
| **🟡 P2 - Important** | Fonctionnalité dégradée | Graphique ne s'affiche pas | < 24h |
| **🟢 P3 - Normal** | Question, amélioration | Demande de fonctionnalité, question | < 72h |

---

### 3.4 Suivi de Ticket

Une fois votre ticket ouvert, vous recevrez :

1. **Email de confirmation** avec numéro de ticket : `TICKET-2025-001234`
2. **Mises à jour régulières** sur l'avancement
3. **Email de résolution** quand le problème est corrigé

**Vérifier le statut de votre ticket :**
- Par email : Répondez au fil de discussion
- Par téléphone : Indiquez le numéro de ticket

---

### 3.5 Résolution et Fermeture

**Ticket résolu :**

Vous recevrez un email :
```
Votre ticket TICKET-2025-001234 a été résolu.

Solution appliquée :
[Description de la solution]

Le problème est-il résolu de votre côté ?
[Oui, fermer le ticket] [Non, rouvrir]

Merci,
Équipe Support PGI Automobile
```

**Si le problème n'est pas résolu :**
- Cliquez sur "Non, rouvrir"
- Expliquez pourquoi la solution ne fonctionne pas
- Le ticket reste ouvert jusqu'à résolution complète

---

## 4. Contacts et Ressources

### 4.1 Contacts Support

| Contact | Email | Téléphone | Disponibilité |
|---------|-------|-----------|---------------|
| **Support Technique** | support@votreentreprise.com | 01 23 45 67 89 | Lun-Ven 9h-18h |
| **Administrateur Système** | admin@votreentreprise.com | 01 23 45 67 90 | 24/7 (urgences) |
| **Responsable Projet** | projet@votreentreprise.com | 01 23 45 67 91 | Lun-Ven 9h-17h |

### 4.2 Ressources en Ligne

**Documentation :**
- 📖 **Manuel Utilisateur** : `/docs/livrables/phase_6/16_manuel_utilisateur.md`
- 🛠️ **Guide Administration** : `/docs/livrables/phase_5/15_guide_administration.md`
- 📊 **Spécifications Fonctionnelles** : `/docs/livrables/phase_2/`
- 📝 **Journal des Modifications** : `/docs/livrables/phase_6/18_journal_modifications.md`

**Vidéos de Formation :**
- 🎥 Introduction au système (15 min)
- 🎥 Enregistrer une vente (10 min)
- 🎥 Gestion des clients (8 min)
- *(Liens disponibles sur votre intranet)*

**Tutoriels Écrits :**
- 📄 "Comment ajouter un véhicule en 5 étapes"
- 📄 "Générer une paie rapidement"
- 📄 "Exporter des statistiques en Excel"

### 4.3 Formation

**Formations disponibles :**

| Formation | Durée | Public | Fréquence |
|-----------|-------|--------|-----------|
| **Prise en main** | 2h | Nouveaux utilisateurs | Mensuel |
| **Perfectionnement** | 3h | Utilisateurs confirmés | Trimestriel |
| **Administration** | 4h | Super Admin | Annuel |

**Inscription :** formation@votreentreprise.com

### 4.4 Communauté Utilisateurs

**Forum Interne** : https://forum.votreentreprise.com/pgi-automobile
- Posez vos questions
- Partagez vos astuces
- Consultez les discussions

**Groupe de Travail :**
- Réunion mensuelle des utilisateurs
- Retours d'expérience
- Suggestions d'amélioration

### 4.5 Horaires de Support

**Support Standard :**
- Lundi à Vendredi : 9h - 18h
- Samedi, Dimanche : Fermé (sauf urgence)

**Astreinte Urgence (P0 uniquement) :**
- 24/7 via le numéro d'urgence : **01 23 45 67 90**
- ⚠️ Ne pas abuser : Réservé aux problèmes bloquants (serveur down, données perdues, etc.)

---

## 5. Glossaire

| Terme | Définition |
|-------|------------|
| **CA** | Chiffre d'Affaires |
| **CRUD** | Create, Read, Update, Delete (Créer, Lire, Modifier, Supprimer) |
| **HT** | Hors Taxes |
| **KPI** | Key Performance Indicator (Indicateur Clé de Performance) |
| **Marge** | Différence entre prix de vente et prix d'achat |
| **RBAC** | Role-Based Access Control (Contrôle d'accès basé sur les rôles) |
| **TTC** | Toutes Taxes Comprises |
| **TVA** | Taxe sur la Valeur Ajoutée (20% en France) |

---

## Annexe : Checklist Utilisateur

### Avant de Contacter le Support

☐ J'ai consulté la FAQ
☐ J'ai vérifié le Manuel Utilisateur
☐ J'ai rafraîchi la page (F5)
☐ J'ai vidé le cache du navigateur
☐ J'ai testé sur un autre navigateur
☐ J'ai demandé à un collègue
☐ J'ai pris des captures d'écran du problème
☐ J'ai noté le message d'erreur exact

### Informations à Fournir au Support

☐ Mon nom et rôle
☐ Mon email
☐ Description détaillée du problème
☐ Étapes pour reproduire
☐ Captures d'écran
☐ Navigateur et version
☐ Système d'exploitation
☐ Date et heure du problème
☐ Numéro de version du système (affiché en bas de page)

---

## Conclusion

Cette FAQ couvre les questions les plus fréquentes. Si votre question n'est pas listée :

1. ✅ Consultez le **Manuel Utilisateur** (document 16)
2. ✅ Consultez le **Guide d'Administration** (document 15)
3. ✅ Ouvrez un **ticket de support** : support@votreentreprise.com

**N'hésitez pas à nous contacter, nous sommes là pour vous aider ! 😊**

---

**Document Version :** 1.0
**Dernière mise à jour :** 17/11/2025
**Auteur :** Équipe Support PGI Automobile

**Contact :** support@votreentreprise.com | Tél : 01 23 45 67 89
