<?php
session_start();

require_once 'config/database.php';

// Logger la déconnexion
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("INSERT INTO logs_connexion (utilisateur_id, action, ip_address, user_agent) VALUES (?, 'deconnexion', ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '']);
    } catch (PDOException $e) {
        // Ignorer les erreurs de log
    }
}

// Détruire la session
session_destroy();
session_unset();

// Rediriger vers la page de connexion
header("Location: login.php?logout=success");
exit;
?>
