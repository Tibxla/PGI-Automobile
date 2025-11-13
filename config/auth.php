<?php
/**
 * Middleware d'authentification
 * À inclure au début de chaque page protégée
 */

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('getBaseUrl')) {
    function getBaseUrl(): string
    {
        static $baseUrl;
        if ($baseUrl !== null) {
            return $baseUrl;
        }

        if (defined('APP_BASE_URL')) {
            $baseUrl = rtrim(APP_BASE_URL, '/');
            return $baseUrl === '' ? '/' : $baseUrl;
        }

        $env = getenv('PGI_BASE_URL');
        if ($env !== false && $env !== '') {
            $baseUrl = rtrim($env, '/');
            return $baseUrl === '' ? '/' : $baseUrl;
        }

        // Valeur par défaut adaptée au dépôt actuel
        $baseUrl = '/pgi-automobile';
        return $baseUrl;
    }
}

if (!function_exists('appUrl')) {
    function appUrl(string $path = ''): string
    {
        $base = rtrim(getBaseUrl(), '/');
        $normalizedPath = '/' . ltrim($path, '/');

        if ($base === '' || $base === '/') {
            return $normalizedPath;
        }

        return $base . $normalizedPath;
    }
}

if (!function_exists('redirectTo')) {
    function redirectTo(string $path, array $query = []): void
    {
        $url = appUrl($path);
        if (!empty($query)) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
        }

        header('Location: ' . $url);
        exit;
    }
}

const DEFAULT_ROLE_PERMISSIONS = [
    'admin' => ['*' => ['*']],
    'vendeur' => [
        'vehicules' => ['read'],
        'clients' => ['read', 'create', 'update'],
        'ventes' => ['read', 'create', 'update'],
        'demandes' => ['read', 'update'],
        'stock' => ['read'],
        'statistiques' => ['read']
    ],
    'gestionnaire_stock' => [
        'vehicules' => ['read', 'create', 'update', 'delete'],
        'stock' => ['read', 'update'],
        'demandes' => ['read'],
        'statistiques' => ['read']
    ],
    'comptable' => [
        'ventes' => ['read'],
        'comptabilite' => ['read', 'update'],
        'statistiques' => ['read']
    ],
    'rh' => [
        'rh' => ['read', 'create', 'update'],
        'conges' => ['read', 'create', 'update'],
        'paie' => ['read', 'create', 'update'],
        'statistiques' => ['read']
    ],
    'client' => [
        'catalogue' => ['read'],
        'demandes' => ['create', 'read']
    ]
];

function roleHasDefaultPermission(string $role, string $module, string $action): bool {
    if ($role === 'admin') {
        return true;
    }

    if (!isset(DEFAULT_ROLE_PERMISSIONS[$role])) {
        return false;
    }

    $permissions = DEFAULT_ROLE_PERMISSIONS[$role];

    if (isset($permissions['*'])) {
        if (in_array('*', $permissions['*'], true) || in_array($action, $permissions['*'], true)) {
            return true;
        }
    }

    if (!isset($permissions[$module])) {
        return false;
    }

    return in_array('*', $permissions[$module], true) || in_array($action, $permissions[$module], true);
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        redirectTo('login.php', ['redirect' => $_SERVER['REQUEST_URI'] ?? appUrl()]);
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

    if (!in_array($_SESSION['role'], $roles, true)) {
        redirectTo('acces-refuse.php');
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

    $role = $_SESSION['role'] ?? null;

    if ($role === 'admin') {
        return true;
    }

    if ($role === null) {
        return false;
    }

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM permissions WHERE role = ? AND module = ? AND action = ?");
        $stmt->execute([$role, $module, $action]);
        $result = $stmt->fetch();

        if (!empty($result['count'])) {
            return true;
        }
    } catch (PDOException $e) {
        // En cas d'erreur, on se rabattra sur la configuration par défaut
    }

    return roleHasDefaultPermission($role, $module, $action);
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
        'rh' => 'Responsable RH',
        'client' => 'Client'
    ];

    return $labels[$role] ?? $role;
}

function getRoleIcon(string $role): string
{
    $icons = [
        'admin' => '👑',
        'vendeur' => '💼',
        'gestionnaire_stock' => '📦',
        'comptable' => '💰',
        'rh' => '🧑‍💼',
        'client' => '🙋'
    ];

    return $icons[$role] ?? '👤';
}

function getRoleOptions(): array
{
    return [
        'admin' => getRoleLabel('admin'),
        'vendeur' => getRoleLabel('vendeur'),
        'gestionnaire_stock' => getRoleLabel('gestionnaire_stock'),
        'comptable' => getRoleLabel('comptable'),
        'rh' => getRoleLabel('rh')
    ];
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
        redirectTo('acces-refuse.php');
    }
}

/**
 * Obtenir toutes les permissions de l'utilisateur connecté
 */
function getUserPermissions() {
    global $pdo;

    $role = $_SESSION['role'] ?? null;

    if ($role === null) {
        return [];
    }

    if ($role === 'admin') {
        return ['all' => true];
    }

    try {
        $stmt = $pdo->prepare("SELECT module, action FROM permissions WHERE role = ?");
        $stmt->execute([$role]);
        $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($permissions && count($permissions) > 0) {
            return $permissions;
        }
    } catch (PDOException $e) {
        // On utilisera la configuration par défaut
    }

    $defaults = DEFAULT_ROLE_PERMISSIONS[$role] ?? [];
    $flatten = [];

    foreach ($defaults as $module => $actions) {
        if ($module === '*') {
            continue;
        }
        foreach ($actions as $action) {
            $flatten[] = ['module' => $module, 'action' => $action];
        }
    }

    return $flatten;
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