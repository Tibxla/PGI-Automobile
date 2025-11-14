<?php
/**
 * HEADER UNIFIÉ POUR LES PAGES PUBLIQUES/CLIENTS
 * Même design que header.php mais sans forcer l'authentification
 * Compatible avec visiteurs et clients connectés
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

$baseUrl = getBaseUrl();
$appName = defined('APP_NAME') ? APP_NAME : 'PGI Automobile';
$pageTitle = isset($page_title) && $page_title !== '' ? $page_title . ' - ' . $appName : $appName;

$est_connecte = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$est_client = $est_connecte && $_SESSION['role'] === 'client';

// Construction de la navigation pour les clients connectés
$navLinks = [];

if ($est_client) {
    $navLinks[] = ['label' => '🏠 Catalogue', 'url' => appUrl('catalogue.php')];

    if (hasPermission('demandes', 'read')) {
        $navLinks[] = ['label' => '📩 Mes demandes', 'url' => appUrl('modules/clients/mes-demandes.php')];
    }

    $navLinks[] = ['label' => '👤 Mon profil', 'url' => appUrl('modules/profil/mon-profil.php')];
} elseif ($est_connecte) {
    // Pour les employés, rediriger vers leur dashboard
    $navLinks[] = ['label' => '🏠 Dashboard', 'url' => appUrl('index.php')];
    $navLinks[] = ['label' => '👤 Mon profil', 'url' => appUrl('modules/profil/mon-profil.php')];
} else {
    // Pour les visiteurs non connectés
    $navLinks[] = ['label' => '🏠 Catalogue', 'url' => appUrl('catalogue.php')];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo appUrl('assets/css/style.css'); ?>">
    <?php if (isset($additional_css) && is_array($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo appUrl($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <script src="<?php echo appUrl('assets/js/script.js'); ?>" defer></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">🚗 <?php echo htmlspecialchars($appName); ?></div>

            <ul class="nav-links">
                <?php if (empty($navLinks)): ?>
                    <li><a href="<?php echo appUrl('catalogue.php'); ?>">🏠 Catalogue</a></li>
                <?php else: ?>
                    <?php foreach ($navLinks as $link): ?>
                        <li><a href="<?php echo $link['url']; ?>"><?php echo $link['label']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <?php if ($est_connecte): ?>
                <!-- Menu utilisateur connecté -->
                <div class="nav-user-menu">
                    <button class="nav-user-toggle" type="button" aria-expanded="false" aria-controls="userDropdown">
                        <img src="<?php echo getAvatar(); ?>" alt="Avatar" class="nav-user-avatar">
                        <div class="nav-user-details">
                            <span class="nav-user-name"><?php echo htmlspecialchars(getFullName()); ?></span>
                            <span class="nav-user-role"><?php echo htmlspecialchars(getRoleLabel()); ?></span>
                        </div>
                        <span class="nav-user-arrow">▼</span>
                    </button>

                    <div class="dropdown-menu" id="userDropdown" role="menu">
                        <a href="<?php echo appUrl('modules/profil/mon-profil.php'); ?>" class="dropdown-item" role="menuitem">
                            👤 Mon profil
                        </a>

                        <?php if (isAdmin()): ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo appUrl('modules/admin/utilisateurs.php'); ?>" class="dropdown-item" role="menuitem">
                                👥 Gestion utilisateurs
                            </a>
                            <a href="<?php echo appUrl('modules/admin/permissions.php'); ?>" class="dropdown-item" role="menuitem">
                                🔐 Permissions
                            </a>
                            <a href="<?php echo appUrl('modules/admin/logs.php'); ?>" class="dropdown-item" role="menuitem">
                                📋 Logs système
                            </a>
                        <?php endif; ?>

                        <div class="dropdown-divider"></div>
                        <a href="<?php echo appUrl('logout.php'); ?>" class="dropdown-item dropdown-item-danger" role="menuitem">
                            🚪 Déconnexion
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Boutons pour visiteurs non connectés -->
                <div class="nav-auth-buttons">
                    <a href="<?php echo appUrl('client-inscription.php'); ?>" class="btn btn-secondary">📝 S'inscrire</a>
                    <a href="<?php echo appUrl('login.php'); ?>" class="btn btn-primary">🔐 Connexion</a>
                </div>
            <?php endif; ?>
        </nav>
    </header>
    <main class="container">
