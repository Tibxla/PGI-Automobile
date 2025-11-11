<?php
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';

// Vérifier l'authentification
requireAuth();

// Rediriger selon le rôle
switch ($_SESSION['role']) {
    case 'admin':
        header('Location: /pgi-automobile/modules/admin/utilisateurs.php');
        break;
    
    case 'vendeur':
        header('Location: /pgi-automobile/modules/ventes/liste.php');
        break;
    
    case 'gestionnaire_stock':
        header('Location: /pgi-automobile/modules/stock/inventaire.php');
        break;
    
    case 'comptable':
        header('Location: /pgi-automobile/modules/statistiques/dashboard.php');
        break;
    
    case 'client':
        header('Location: /pgi-automobile/catalogue.php');
        break;
    
    default:
        // Si le rôle est inconnu, déconnecter
        session_destroy();
        header('Location: /pgi-automobile/login.php?error=role_inconnu');
        break;
}
exit();
?>
