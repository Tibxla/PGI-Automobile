-- ============================================
-- MISE À JOUR BASE DE DONNÉES - MODULE CLIENTS
-- Ajout des demandes d'achat et rôle client
-- ============================================

USE pgi_automobile;

-- ============================================
-- 1. AJOUTER LE RÔLE CLIENT DANS LA TABLE UTILISATEURS
-- ============================================
ALTER TABLE utilisateurs 
MODIFY COLUMN role ENUM('admin', 'vendeur', 'gestionnaire_stock', 'comptable', 'client') DEFAULT 'vendeur';

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
-- VÉRIFICATION
-- ============================================
SELECT 
    '✅ TABLE DEMANDES_ACHAT CRÉÉE' as 'STATUT',
    COUNT(*) as 'NOMBRE_DEMANDES'
FROM demandes_achat;

SELECT 
    id,
    CONCAT(prenom, ' ', nom) as 'Client',
    email,
    statut,
    created_at as 'Date_demande'
FROM demandes_achat
ORDER BY created_at DESC;

-- ============================================
-- INSTALLATION TERMINÉE !
-- ============================================
