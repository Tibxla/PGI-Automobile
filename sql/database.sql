-- ============================================
-- BASE DE DONNÉES PGI AUTOMOBILE - VERSION COMPLÈTE & FINALE
-- ============================================
--
-- 📦 CONTENU COMPLET :
--    ✅ 10 tables (vehicules, clients, ventes, personnel, conges, bulletins_paie, fournisseurs, utilisateurs, logs_connexion, permissions, demandes_achat)
--    ✅ Données de test pour tous les modules
--    ✅ 6 utilisateurs de test (admin, vendeurs, gestionnaire, comptable, RH, clients)
--    ✅ Permissions par rôle
--    ✅ Colonnes adresse/ville/code_postal pour utilisateurs
--    ✅ Système de demandes d'achat clients
--
-- 🔑 MOT DE PASSE POUR TOUS LES COMPTES : password123
--
-- 📝 INSTALLATION :
--    1. Créer la base : mysql -u root -p < database.sql
--    2. Configurer config/database.php avec vos identifiants
--    3. Accéder à : http://localhost/PGI-Automobile/
--    4. Connexion avec admin@pgi-auto.com / password123
--
-- ⚠️  IMPORTANT : Changez les mots de passe en production !
--
-- ============================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS pgi_automobile;
USE pgi_automobile;

-- ============================================
-- TABLE DES VÉHICULES
-- ============================================
CREATE TABLE IF NOT EXISTS vehicules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    annee INT NOT NULL,
    couleur VARCHAR(30),
    prix_achat DECIMAL(10,2),
    prix_vente DECIMAL(10,2),
    kilometrage INT,
    type_vehicule ENUM('berline', 'suv', 'sportive', 'utilitaire', 'citadine') DEFAULT 'berline',
    carburant ENUM('essence', 'diesel', 'electrique', 'hybride') DEFAULT 'essence',
    statut ENUM('stock', 'vendu', 'reserve') DEFAULT 'stock',
    date_arrivee DATE,
    immatriculation VARCHAR(20),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE DES CLIENTS
-- ============================================
CREATE TABLE IF NOT EXISTS clients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telephone VARCHAR(20),
    adresse TEXT,
    ville VARCHAR(50),
    code_postal VARCHAR(10),
    date_naissance DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE DES VENTES
-- ============================================
CREATE TABLE IF NOT EXISTS ventes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    vehicule_id INT,
    client_id INT,
    prix_vente DECIMAL(10,2) NOT NULL,
    mode_paiement ENUM('comptant', 'credit', 'leasing') DEFAULT 'comptant',
    date_vente DATE NOT NULL,
    marge DECIMAL(10,2),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE DU PERSONNEL (RH simplifié)
-- ============================================
CREATE TABLE IF NOT EXISTS personnel (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    poste VARCHAR(50),
    salaire DECIMAL(10,2),
    email VARCHAR(100),
    telephone VARCHAR(20),
    date_embauche DATE,
    statut ENUM('actif', 'conge', 'inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE DES CONGÉS (RH)
-- ============================================
CREATE TABLE IF NOT EXISTS conges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    personnel_id INT NOT NULL,
    type VARCHAR(30) DEFAULT 'CP',
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('en_attente', 'approuve', 'refuse') DEFAULT 'en_attente',
    commentaire TEXT NULL,
    commentaire_gestion TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (personnel_id) REFERENCES personnel(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE DES BULLETINS DE PAIE
-- ============================================
CREATE TABLE IF NOT EXISTS bulletins_paie (
    id INT PRIMARY KEY AUTO_INCREMENT,
    personnel_id INT NOT NULL,
    mois_reference DATE NOT NULL,
    salaire_base DECIMAL(10,2) NOT NULL,
    prime DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    net_a_payer DECIMAL(10,2) NOT NULL,
    statut ENUM('brouillon', 'valide') DEFAULT 'brouillon',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (personnel_id) REFERENCES personnel(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE DES FOURNISSEURS
-- ============================================
CREATE TABLE IF NOT EXISTS fournisseurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom_entreprise VARCHAR(100) NOT NULL,
    contact VARCHAR(100),
    email VARCHAR(100),
    telephone VARCHAR(20),
    adresse TEXT,
    specialite VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE DES UTILISATEURS (Authentification)
-- ============================================
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'vendeur', 'gestionnaire_stock', 'comptable', 'rh', 'client') DEFAULT 'vendeur',
    statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif',
    avatar VARCHAR(255),
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    ville VARCHAR(100),
    code_postal VARCHAR(10),
    derniere_connexion DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE DES LOGS DE CONNEXION
-- ============================================
CREATE TABLE IF NOT EXISTS logs_connexion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT,
    action ENUM('connexion', 'deconnexion', 'tentative_echec') NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE DES PERMISSIONS
-- ============================================
CREATE TABLE IF NOT EXISTS permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role VARCHAR(50) NOT NULL,
    module VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_permission (role, module, action)
);

-- ============================================
-- INSERTION DES DONNÉES DE TEST - VÉHICULES
-- ============================================
INSERT INTO vehicules (marque, modele, annee, couleur, prix_achat, prix_vente, kilometrage, type_vehicule, carburant, statut, date_arrivee, immatriculation) VALUES
('Peugeot', '208', 2022, 'Bleu', 15000, 18500, 25000, 'citadine', 'essence', 'stock', '2024-01-15', 'AB-123-CD'),
('Renault', 'Clio', 2021, 'Blanc', 13500, 16800, 32000, 'citadine', 'diesel', 'stock', '2024-02-10', 'EF-456-GH'),
('BMW', 'Serie 3', 2023, 'Noir', 35000, 42000, 15000, 'berline', 'essence', 'stock', '2024-03-05', 'IJ-789-KL'),
('Tesla', 'Model 3', 2023, 'Blanc', 42000, 48000, 8000, 'berline', 'electrique', 'stock', '2024-03-20', 'MN-012-OP'),
('Volkswagen', 'Tiguan', 2022, 'Gris', 28000, 33500, 18000, 'suv', 'diesel', 'stock', '2024-02-28', 'QR-345-ST'),
('Mercedes', 'Classe A', 2023, 'Rouge', 32000, 38000, 12000, 'berline', 'hybride', 'reserve', '2024-04-01', 'UV-678-WX'),
('Audi', 'Q5', 2022, 'Noir', 38000, 45000, 20000, 'suv', 'diesel', 'stock', '2024-01-25', 'YZ-901-AB'),
('Porsche', '911', 2023, 'Jaune', 95000, 110000, 5000, 'sportive', 'essence', 'stock', '2024-04-10', 'CD-234-EF');

-- ============================================
-- INSERTION DES DONNÉES DE TEST - CLIENTS
-- ============================================
INSERT INTO clients (nom, prenom, email, telephone, adresse, ville, code_postal, date_naissance) VALUES
('Dupont', 'Jean', 'jean.dupont@email.com', '0612345678', '12 rue de la Paix', 'Paris', '75001', '1985-05-15'),
('Martin', 'Sophie', 'sophie.martin@email.com', '0623456789', '34 avenue Victor Hugo', 'Lyon', '69002', '1990-08-22'),
('Bernard', 'Pierre', 'pierre.bernard@email.com', '0634567890', '56 boulevard Haussmann', 'Marseille', '13001', '1978-12-10'),
('Dubois', 'Marie', 'marie.dubois@email.com', '0645678901', '78 rue du Commerce', 'Toulouse', '31000', '1995-03-18');

-- ============================================
-- INSERTION DES DONNÉES DE TEST - PERSONNEL
-- ============================================
INSERT INTO personnel (nom, prenom, poste, salaire, email, telephone, date_embauche, statut) VALUES
('Leclerc', 'Thomas', 'Directeur Commercial', 4500, 'thomas.leclerc@pgi-auto.com', '0656789012', '2020-01-10', 'actif'),
('Moreau', 'Julie', 'Vendeuse', 2500, 'julie.moreau@pgi-auto.com', '0667890123', '2021-03-15', 'actif'),
('Rousseau', 'Marc', 'Mécanicien', 2800, 'marc.rousseau@pgi-auto.com', '0678901234', '2019-06-20', 'actif'),
('Petit', 'Claire', 'Comptable', 3200, 'claire.petit@pgi-auto.com', '0689012345', '2020-09-01', 'actif');

-- ============================================
-- DONNÉES DE TEST - CONGÉS & PAIE
-- ============================================
INSERT INTO conges (personnel_id, type, date_debut, date_fin, statut, commentaire) VALUES
(1, 'CP', '2024-07-15', '2024-07-19', 'approuve', 'Vacances été'),
(2, 'RTT', '2024-08-02', '2024-08-02', 'en_attente', 'RTT ponctuel'),
(3, 'Maladie', '2024-06-10', '2024-06-12', 'refuse', 'Certificat incomplet');

INSERT INTO bulletins_paie (personnel_id, mois_reference, salaire_base, prime, deductions, net_a_payer, statut, notes) VALUES
(1, '2024-05-01', 4500, 300, 150, 4650, 'valide', 'Prime objectifs Q1'),
(2, '2024-05-01', 2500, 150, 0, 2650, 'valide', 'Prime sur ventes'),
(3, '2024-05-01', 2800, 0, 120, 2680, 'brouillon', 'Retenue équipement');

-- ============================================
-- INSERTION DES DONNÉES DE TEST - FOURNISSEURS
-- ============================================
INSERT INTO fournisseurs (nom_entreprise, contact, email, telephone, adresse, specialite) VALUES
('Auto Import France', 'M. Durand', 'contact@autoimport.fr', '0140506070', '123 rue de Rivoli, Paris', 'Importation véhicules neufs'),
('Pièces Auto Pro', 'Mme Lefort', 'info@piecesauto.fr', '0141516171', '45 avenue de Clichy, Paris', 'Pièces détachées'),
('Garage Excellence', 'M. Blanc', 'garage@excellence.fr', '0142526272', '67 rue de Lyon, Lyon', 'Réparation et entretien');

-- ============================================
-- INSERTION DES UTILISATEURS - AUTHENTIFICATION
-- Hash du mot de passe : password123
-- ============================================

-- Supprimer les anciens utilisateurs s'ils existent
DELETE FROM utilisateurs;

-- Réinitialiser l'auto-increment
ALTER TABLE utilisateurs AUTO_INCREMENT = 1;

-- 1. ADMINISTRATEUR - Accès complet
INSERT INTO utilisateurs (nom, prenom, email, password, role, statut, telephone, created_at) VALUES
('Admin', 'Super', 'admin@pgi-auto.com', '$2y$10$.mIgK9IWFLkQztOzzFgnL.VpOfjUUQ9NkKX7jRN.KlmcfSWIIgGLe', 'admin', 'actif', '0600000001', NOW());

-- 2. VENDEUR 1 - Julie Moreau
INSERT INTO utilisateurs (nom, prenom, email, password, role, statut, telephone, created_at) VALUES
('Moreau', 'Julie', 'julie@pgi-auto.com', '$2y$10$.mIgK9IWFLkQztOzzFgnL.VpOfjUUQ9NkKX7jRN.KlmcfSWIIgGLe', 'vendeur', 'actif', '0600000002', NOW());

-- 3. VENDEUR 2 - Thomas Leclerc
INSERT INTO utilisateurs (nom, prenom, email, password, role, statut, telephone, created_at) VALUES
('Leclerc', 'Thomas', 'thomas@pgi-auto.com', '$2y$10$.mIgK9IWFLkQztOzzFgnL.VpOfjUUQ9NkKX7jRN.KlmcfSWIIgGLe', 'vendeur', 'actif', '0600000003', NOW());

-- 4. GESTIONNAIRE STOCK - Marc Rousseau
INSERT INTO utilisateurs (nom, prenom, email, password, role, statut, telephone, created_at) VALUES
('Rousseau', 'Marc', 'marc@pgi-auto.com', '$2y$10$.mIgK9IWFLkQztOzzFgnL.VpOfjUUQ9NkKX7jRN.KlmcfSWIIgGLe', 'gestionnaire_stock', 'actif', '0600000004', NOW());

-- 5. COMPTABLE - Claire Petit
INSERT INTO utilisateurs (nom, prenom, email, password, role, statut, telephone, created_at) VALUES
('Petit', 'Claire', 'claire@pgi-auto.com', '$2y$10$.mIgK9IWFLkQztOzzFgnL.VpOfjUUQ9NkKX7jRN.KlmcfSWIIgGLe', 'comptable', 'actif', '0600000005', NOW());

-- 6. RESPONSABLE RH - Emma Durand
INSERT INTO utilisateurs (nom, prenom, email, password, role, statut, telephone, created_at) VALUES
('Durand', 'Emma', 'emma@pgi-auto.com', '$2y$10$.mIgK9IWFLkQztOzzFgnL.VpOfjUUQ9NkKX7jRN.KlmcfSWIIgGLe', 'rh', 'actif', '0600000006', NOW());

-- ============================================
-- INSERTION DES PERMISSIONS
-- ============================================

-- Supprimer les anciennes permissions
DELETE FROM permissions;

-- PERMISSIONS ADMIN (Accès complet)
INSERT INTO permissions (role, module, action) VALUES
('admin', 'vehicules', 'create'),
('admin', 'vehicules', 'read'),
('admin', 'vehicules', 'update'),
('admin', 'vehicules', 'delete'),
('admin', 'clients', 'create'),
('admin', 'clients', 'read'),
('admin', 'clients', 'update'),
('admin', 'clients', 'delete'),
('admin', 'ventes', 'create'),
('admin', 'ventes', 'read'),
('admin', 'ventes', 'update'),
('admin', 'ventes', 'delete'),
('admin', 'statistiques', 'read'),
('admin', 'stock', 'read'),
('admin', 'stock', 'update'),
('admin', 'utilisateurs', 'create'),
('admin', 'utilisateurs', 'read'),
('admin', 'utilisateurs', 'update'),
('admin', 'utilisateurs', 'delete'),
('admin', 'rh', 'read'),
('admin', 'rh', 'create'),
('admin', 'rh', 'update'),
('admin', 'conges', 'read'),
('admin', 'conges', 'create'),
('admin', 'conges', 'update'),
('admin', 'paie', 'read'),
('admin', 'paie', 'create'),
('admin', 'paie', 'update');

-- PERMISSIONS VENDEUR
INSERT INTO permissions (role, module, action) VALUES
('vendeur', 'vehicules', 'read'),
('vendeur', 'clients', 'create'),
('vendeur', 'clients', 'read'),
('vendeur', 'clients', 'update'),
('vendeur', 'ventes', 'create'),
('vendeur', 'ventes', 'read');

-- PERMISSIONS GESTIONNAIRE STOCK
INSERT INTO permissions (role, module, action) VALUES
('gestionnaire_stock', 'vehicules', 'create'),
('gestionnaire_stock', 'vehicules', 'read'),
('gestionnaire_stock', 'vehicules', 'update'),
('gestionnaire_stock', 'vehicules', 'delete'),
('gestionnaire_stock', 'stock', 'read'),
('gestionnaire_stock', 'stock', 'update');

-- PERMISSIONS COMPTABLE
INSERT INTO permissions (role, module, action) VALUES
('comptable', 'ventes', 'read'),
('comptable', 'statistiques', 'read'),
('comptable', 'clients', 'read');

-- PERMISSIONS RH
INSERT INTO permissions (role, module, action) VALUES
('rh', 'rh', 'read'),
('rh', 'rh', 'create'),
('rh', 'rh', 'update'),
('rh', 'conges', 'read'),
('rh', 'conges', 'create'),
('rh', 'conges', 'update'),
('rh', 'paie', 'read'),
('rh', 'paie', 'create'),
('rh', 'paie', 'update');

-- ============================================
-- VÉRIFICATION DES UTILISATEURS CRÉÉS
-- ============================================
SELECT 
    '✅ UTILISATEURS CRÉÉS AVEC SUCCÈS' as 'STATUT',
    COUNT(*) as 'NOMBRE'
FROM utilisateurs;

SELECT 
    id,
    CONCAT(prenom, ' ', nom) as 'Nom Complet',
    email as 'Email',
    role as 'Rôle',
    statut as 'Statut'
FROM utilisateurs
ORDER BY id;

-- ============================================
-- 2. CRÉER LA TABLE DES DEMANDES D'ACHAT
-- ============================================
CREATE TABLE IF NOT EXISTS demandes_achat (
                                              id INT PRIMARY KEY AUTO_INCREMENT,
                                              vehicule_id INT NOT NULL,
                                              client_id INT NULL,  -- NULL si demande par visiteur non inscrit
                                              nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    message TEXT,
    statut ENUM('en_attente', 'en_cours', 'acceptee', 'refusee', 'finalisee') DEFAULT 'en_attente',
    notes_gestionnaire TEXT,  -- Notes privées du gestionnaire
    traitee_par INT NULL,  -- ID du gestionnaire qui traite
    date_traitement DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicule_id) REFERENCES vehicules(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    FOREIGN KEY (traitee_par) REFERENCES utilisateurs(id) ON DELETE SET NULL
    );

-- ============================================
-- 3. AJOUTER DES PERMISSIONS POUR LE RÔLE CLIENT
-- ============================================
INSERT INTO permissions (role, module, action) VALUES
                                                   ('client', 'vehicules', 'read'),
                                                   ('client', 'demandes', 'create'),
                                                   ('client', 'demandes', 'read');

-- ============================================
-- 4. AJOUTER DES PERMISSIONS POUR GÉRER LES DEMANDES
-- ============================================
INSERT INTO permissions (role, module, action) VALUES
                                                   ('vendeur', 'demandes', 'read'),
                                                   ('vendeur', 'demandes', 'update'),
                                                   ('admin', 'demandes', 'create'),
                                                   ('admin', 'demandes', 'read'),
                                                   ('admin', 'demandes', 'update'),
                                                   ('admin', 'demandes', 'delete');

-- ============================================
-- 5. DONNÉES DE TEST - DEMANDES D'ACHAT
-- ============================================
INSERT INTO demandes_achat (vehicule_id, nom, prenom, email, telephone, message, statut, created_at) VALUES
                                                                                                         (1, 'Dubois', 'Antoine', 'antoine.dubois@email.com', '0612345678', 'Je suis très intéressé par ce véhicule. Possibilité de négocier le prix ?', 'en_attente', NOW() - INTERVAL 2 DAY),
                                                                                                         (3, 'Lambert', 'Sarah', 'sarah.lambert@email.com', '0623456789', 'Bonjour, j\'aimerais faire un essai de ce véhicule. Disponible ce week-end ?', 'en_cours', NOW() - INTERVAL 1 DAY),
(5, 'Robert', 'Michel', 'michel.robert@email.com', '0634567890', 'Véhicule toujours disponible ? Possibilité de reprise ?', 'en_attente', NOW() - INTERVAL 3 HOUR);


-- ============================================
-- INFORMATIONS DE CONNEXION
-- ============================================
-- 
-- MOT DE PASSE POUR TOUS LES COMPTES : password123
-- 
-- 👑 ADMINISTRATEUR :
--    Email : admin@pgi-auto.com
--    Accès : Complet (toutes les fonctionnalités)
-- 
-- 💼 VENDEUR (Julie) :
--    Email : julie@pgi-auto.com
--    Accès : Véhicules (lecture), Clients, Ventes
-- 
-- 💼 VENDEUR (Thomas) :
--    Email : thomas@pgi-auto.com
--    Accès : Véhicules (lecture), Clients, Ventes
-- 
-- 📦 GESTIONNAIRE STOCK (Marc) :
--    Email : marc@pgi-auto.com
--    Accès : Véhicules (CRUD), Stock, Inventaire
-- 
-- 💰 COMPTABLE (Claire) :
--    Email : claire@pgi-auto.com
--    Accès : Ventes (lecture), Statistiques
-- 
-- ============================================
-- INSTALLATION TERMINÉE !
-- ============================================
-- 
-- 1. Connectez-vous sur : http://localhost/PGI-Automobile/login.php
-- 2. Utilisez l'un des comptes ci-dessus
-- 3. Mot de passe : password123
-- 
-- ⚠️ N'oubliez pas de changer les mots de passe !
-- 
-- ============================================
