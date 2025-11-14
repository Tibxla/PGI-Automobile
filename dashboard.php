<?php
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';

requireAuth();

switch ($_SESSION['role'] ?? null) {
    case 'admin':
        redirectTo('modules/admin/utilisateurs.php');
        break;

    case 'vendeur':
        redirectTo('modules/ventes/liste.php');
        break;

    case 'gestionnaire_stock':
        redirectTo('modules/stock/inventaire.php');
        break;

    case 'comptable':
        redirectTo('modules/statistiques/dashboard.php');
        break;

    case 'rh':
        redirectTo('modules/rh/liste.php');
        break;

    case 'client':
        redirectTo('catalogue.php');
        break;

    default:
        session_destroy();
        redirectTo('login.php', ['error' => 'role_inconnu']);
}
?>
