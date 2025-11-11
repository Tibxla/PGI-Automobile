<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

// Vérifier l'authentification
requireAuth();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>PGI Automobile</title>
    <link rel="stylesheet" href="/pgi-automobile/assets/css/style.css">
    <style>
        .user-menu {
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: background 0.3s;
        }
        
        .user-info:hover {
            background: var(--light);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        
        .user-details {
            display: flex;
            flex-direction: column;
        }
        
        .user-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 0.95rem;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: #666;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 0.5rem 0;
            min-width: 220px;
            display: none;
            z-index: 1000;
            margin-top: 0.5rem;
        }
        
        .dropdown-menu.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dropdown-item {
            padding: 0.75rem 1.25rem;
            color: var(--dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: background 0.3s;
        }
        
        .dropdown-item:hover {
            background: var(--light);
        }
        
        .dropdown-divider {
            height: 1px;
            background: var(--light);
            margin: 0.5rem 0;
        }
        
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: auto;
        }
        
        .role-admin {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .role-vendeur {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .role-gestionnaire {
            background: #d1fae5;
            color: #065f46;
        }
        
        .role-comptable {
            background: #fef3c7;
            color: #92400e;
        }
        
        .navbar {
            position: relative;
        }
        
        /* Masquer les liens selon les permissions */
        .nav-links a[data-permission] {
            display: flex;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                🚗 PGI Automobile
            </div>
            <ul class="nav-links">
                <li><a href="/pgi-automobile/index.php">🏠 Accueil</a></li>
                
                <?php if (hasPermission('vehicules', 'read')): ?>
                    <li><a href="/pgi-automobile/modules/vehicules/liste.php">🚙 Véhicules</a></li>
                <?php endif; ?>
                
                <?php if (hasPermission('clients', 'read')): ?>
                    <li><a href="/pgi-automobile/modules/clients/liste.php">👥 Clients</a></li>
                <?php endif; ?>
                
                <?php if (hasPermission('ventes', 'read')): ?>
                    <li><a href="/pgi-automobile/modules/ventes/liste.php">💰 Ventes</a></li>
                <?php endif; ?>
                
                <?php if (hasPermission('stock', 'read')): ?>
                    <li><a href="/pgi-automobile/modules/stock/inventaire.php">📦 Stock</a></li>
                <?php endif; ?>
                
                <?php if (hasPermission('statistiques', 'read')): ?>
                    <li><a href="/pgi-automobile/modules/statistiques/dashboard.php">📊 Stats</a></li>
                <?php endif; ?>
                
                <?php if (isAdmin()): ?>
                    <li><a href="/pgi-automobile/modules/admin/utilisateurs.php">👤 Utilisateurs</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="user-menu">
                <div class="user-info" onclick="toggleDropdown()">
                    <img src="<?php echo getAvatar(); ?>" alt="Avatar" class="user-avatar">
                    <div class="user-details">
                        <span class="user-name"><?php echo getFullName(); ?></span>
                        <span class="user-role"><?php echo getRoleLabel(); ?></span>
                    </div>
                    <span style="color: var(--primary);">▼</span>
                </div>
                
                <div class="dropdown-menu" id="userDropdown">
                    <a href="/pgi-automobile/modules/profil/mon-profil.php" class="dropdown-item">
                        👤 Mon profil
                    </a>
                    
                    <?php if (isAdmin()): ?>
                        <div class="dropdown-divider"></div>
                        <a href="/pgi-automobile/modules/admin/utilisateurs.php" class="dropdown-item">
                            👥 Gestion utilisateurs
                        </a>
                        <a href="/pgi-automobile/modules/admin/permissions.php" class="dropdown-item">
                            🔐 Permissions
                        </a>
                        <a href="/pgi-automobile/modules/admin/logs.php" class="dropdown-item">
                            📋 Logs système
                        </a>
                    <?php endif; ?>
                    
                    <div class="dropdown-divider"></div>
                    <a href="/pgi-automobile/logout.php" class="dropdown-item" style="color: var(--danger);">
                        🚪 Déconnexion
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
    
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }
        
        // Fermer le dropdown en cliquant ailleurs
        window.onclick = function(event) {
            if (!event.target.matches('.user-info') && !event.target.closest('.user-info')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
