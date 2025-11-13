<?php
/**
 * INDEX.PHP - Point d'entrée principal (Dispatcher)
 * Redirige intelligemment selon l'état de connexion
 */

// Démarrer la session
session_start();

// Si l'utilisateur est connecté
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    // Rediriger vers le dashboard approprié selon le rôle
    header('Location: dashboard.php');
    exit();
}

// Si l'utilisateur n'est pas connecté, afficher la page d'accueil
header('Location: accueil.php');
exit();
?>