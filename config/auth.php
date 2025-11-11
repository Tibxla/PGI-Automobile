<?php
/**
 * Middleware d'authentification
 * À inclure au début de chaque page protégée
 */

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /pgi-automobile/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

/**
 * Vérifier si l'utilisateur a un rôle spécifique
 */
function requireRole($roles) {
    requireAuth();

    if (!is_array($roles)) {
        $roles = [$roles];
    }

    if (!in_array($_SESSION['role'], $roles)) {
        header("Location: /pgi-automobile/acces-refuse.php");
        exit;
    }
}

/**
 * Vérifier si l'utilisateur a la permission pour un module et une action
 */
function hasPermission($module, $action) {
    global $pdo;

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    // L'admin a toutes les permissions
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        return true;
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM permissions WHERE role = ? AND module = ? AND action = ?");
        $stmt->execute([$_SESSION['role'], $module, $action]);
        $result = $stmt->fetch();

        return $result['count'] > 0;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Obtenir le nom complet de l'utilisateur connecté
 */
function getFullName() {
    if (!isset($_SESSION['prenom']) || !isset($_SESSION['nom'])) {
        return 'Utilisateur';
    }
    return $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
}

/**
 * Obtenir le rôle traduit de l'utilisateur
 */
function getRoleLabel($role = null) {
    if ($role === null) {
        $role = $_SESSION['role'] ?? '';
    }

    $labels = [
        'admin' => 'Administrateur',
        'vendeur' => 'Vendeur',
        'gestionnaire_stock' => 'Gestionnaire de Stock',
        'comptable' => 'Comptable',
        'client' => 'Client'
    ];

    return $labels[$role] ?? $role;
}

/**
 * Vérifier si l'utilisateur est admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Obtenir l'avatar de l'utilisateur ou un avatar par défaut
 */
function getAvatar() {
    if (isset($_SESSION['avatar']) && !empty($_SESSION['avatar'])) {
        return $_SESSION['avatar'];
    }

    // Avatar par défaut basé sur les initiales
    $initials = '';
    if (isset($_SESSION['prenom'])) {
        $initials .= strtoupper(substr($_SESSION['prenom'], 0, 1));
    }
    if (isset($_SESSION['nom'])) {
        $initials .= strtoupper(substr($_SESSION['nom'], 0, 1));
    }

    return "https://ui-avatars.com/api/?name=" . urlencode($initials) . "&background=667eea&color=fff&size=128";
}

/**
 * Vérifier la permission et rediriger si refusée
 */
function requirePermission($module, $action) {
    requireAuth();

    if (!hasPermission($module, $action)) {
        header("Location: /pgi-automobile/acces-refuse.php");
        exit;
    }
}

/**
 * Obtenir toutes les permissions de l'utilisateur connecté
 */
function getUserPermissions() {
    global $pdo;

    if (!isset($_SESSION['role'])) {
        return [];
    }

    // L'admin a toutes les permissions
    if ($_SESSION['role'] === 'admin') {
        return ['all' => true];
    }

    try {
        $stmt = $pdo->prepare("SELECT module, action FROM permissions WHERE role = ?");
        $stmt->execute([$_SESSION['role']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Formater la durée depuis la dernière connexion
 */
function timeSince($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d > 0) {
        return $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
    } elseif ($diff->h > 0) {
        return $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
    } elseif ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
    } else {
        return 'À l\'instant';
    }
}
?>