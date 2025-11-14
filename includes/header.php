<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

requireAuth();

$baseUrl = getBaseUrl();
$appName = defined('APP_NAME') ? APP_NAME : 'PGI Automobile';
$pageTitle = isset($page_title) && $page_title !== '' ? $page_title . ' - ' . $appName : $appName;

$navLinks = [];
$role = $_SESSION['role'] ?? null;

if ($role === 'client') {
    $navLinks[] = ['label' => '🏠 Accueil', 'url' => appUrl('catalogue.php')];

    if (hasPermission('demandes', 'read')) {
        $navLinks[] = ['label' => '📩 Mes demandes', 'url' => appUrl('modules/clients/mes-demandes.php')];
    }
    
    $navLinks[] = ['label' => '✏️ Modifier mes infos', 'url' => appUrl('modules/profil/mon-profil.php')];
} else {
    $navDefinitions = [
        ['permission' => ['vehicules', 'read'], 'url' => 'modules/vehicules/liste.php', 'label' => '🚙 Véhicules'],
        ['permission' => ['clients', 'read'], 'url' => 'modules/clients/liste.php', 'label' => '👥 Clients'],
        ['permission' => ['ventes', 'read'], 'url' => 'modules/ventes/liste.php', 'label' => '💰 Ventes'],
        ['permission' => ['demandes', 'read'], 'url' => 'modules/ventes/demandes-liste.php', 'label' => '📩 Demandes'],
        ['permission' => ['stock', 'read'], 'url' => 'modules/stock/inventaire.php', 'label' => '📦 Stock'],
        ['permission' => ['statistiques', 'read'], 'url' => 'modules/statistiques/dashboard.php', 'label' => '📊 Stats'],
        ['permission' => ['rh', 'read'], 'url' => 'modules/rh/liste.php', 'label' => '🧑‍💼 RH'],
        ['permission' => ['paie', 'read'], 'url' => 'modules/rh/paie.php', 'label' => '💶 Paie'],
        ['permission' => ['conges', 'read'], 'url' => 'modules/rh/conges.php', 'label' => '🌴 Congés'],
        ['permission' => ['utilisateurs', 'read'], 'url' => 'modules/admin/utilisateurs.php', 'label' => '👤 Utilisateurs']
    ];

    foreach ($navDefinitions as $item) {
        [$module, $action] = $item['permission'];
        if (hasPermission($module, $action)) {
            $navLinks[] = [
                'label' => $item['label'],
                'url' => appUrl($item['url'])
            ];
        }
    }

    array_unshift($navLinks, ['label' => '🏠 Accueil', 'url' => appUrl('index.php')]);
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
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">🚗 <?php echo htmlspecialchars($appName); ?></div>
            <ul class="nav-links">
                <?php if (empty($navLinks)): ?>
                    <li><a href="<?php echo appUrl('index.php'); ?>">🏠 Accueil</a></li>
                <?php else: ?>
                    <?php foreach ($navLinks as $link): ?>
                        <li><a href="<?php echo $link['url']; ?>"><?php echo $link['label']; ?></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

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
        </nav>
    </header>
    <main class="container">
