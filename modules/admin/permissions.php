<?php
$page_title = "Gestion des Permissions";
include '../../includes/header.php';

// Vérifier que c'est un admin
requireRole('admin');

$message = '';
$error = '';

// Définition des modules et permissions
$modules = [
    'vehicules' => [
        'label' => '🚙 Véhicules',
        'permissions' => ['read' => 'Consulter', 'create' => 'Créer', 'update' => 'Modifier', 'delete' => 'Supprimer']
    ],
    'clients' => [
        'label' => '👥 Clients',
        'permissions' => ['read' => 'Consulter', 'create' => 'Créer', 'update' => 'Modifier', 'delete' => 'Supprimer']
    ],
    'ventes' => [
        'label' => '💰 Ventes',
        'permissions' => ['read' => 'Consulter', 'create' => 'Créer', 'update' => 'Modifier', 'delete' => 'Supprimer']
    ],
    'stock' => [
        'label' => '📦 Stock',
        'permissions' => ['read' => 'Consulter', 'update' => 'Gérer']
    ],
    'statistiques' => [
        'label' => '📊 Statistiques',
        'permissions' => ['read' => 'Consulter']
    ],
    'admin' => [
        'label' => '⚙️ Administration',
        'permissions' => ['manage' => 'Gérer']
    ]
];

// Définition des rôles
$roles = [
    'admin' => '👑 Administrateur',
    'vendeur' => '💼 Vendeur',
    'gestionnaire_stock' => '📦 Gestionnaire Stock',
    'comptable' => '💰 Comptable'
];

// Permissions par défaut pour chaque rôle
$default_permissions = [
    'admin' => [
        'vehicules' => ['read', 'create', 'update', 'delete'],
        'clients' => ['read', 'create', 'update', 'delete'],
        'ventes' => ['read', 'create', 'update', 'delete'],
        'stock' => ['read', 'update'],
        'statistiques' => ['read'],
        'admin' => ['manage']
    ],
    'vendeur' => [
        'vehicules' => ['read'],
        'clients' => ['read', 'create', 'update'],
        'ventes' => ['read', 'create', 'update'],
        'stock' => ['read'],
        'statistiques' => ['read']
    ],
    'gestionnaire_stock' => [
        'vehicules' => ['read', 'create', 'update', 'delete'],
        'clients' => ['read'],
        'ventes' => ['read'],
        'stock' => ['read', 'update'],
        'statistiques' => ['read']
    ],
    'comptable' => [
        'vehicules' => ['read'],
        'clients' => ['read'],
        'ventes' => ['read'],
        'stock' => ['read'],
        'statistiques' => ['read']
    ]
];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'save_permissions') {
        $message = "Les permissions ont été enregistrées avec succès !";
        // Note : Dans une vraie application, vous sauvegareriez ces permissions en base de données
    }
}

// Statistiques
$stmt = $pdo->query("SELECT role, COUNT(*) as count FROM utilisateurs WHERE statut = 'actif' GROUP BY role");
$user_counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<h1 style="color: white; text-align: center; margin-bottom: 2rem;">
    🔐 Gestion des Permissions
</h1>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Statistiques des rôles -->
<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">👑</div>
        <div class="stat-value"><?php echo $user_counts['admin'] ?? 0; ?></div>
        <div class="stat-label">Administrateurs actifs</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="stat-icon">💼</div>
        <div class="stat-value"><?php echo $user_counts['vendeur'] ?? 0; ?></div>
        <div class="stat-label">Vendeurs actifs</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="stat-icon">📦</div>
        <div class="stat-value"><?php echo $user_counts['gestionnaire_stock'] ?? 0; ?></div>
        <div class="stat-label">Gestionnaires actifs</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="stat-icon">💰</div>
        <div class="stat-value"><?php echo $user_counts['comptable'] ?? 0; ?></div>
        <div class="stat-label">Comptables actifs</div>
    </div>
</div>

<!-- Matrice des permissions -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🔒 Matrice des Permissions par Rôle</h2>
    </div>
    
    <div style="background: #fff3cd; padding: 1rem; margin-bottom: 1.5rem; border-radius: 10px; border-left: 4px solid #ffc107;">
        <strong>ℹ️ Information :</strong> Cette page affiche la configuration actuelle des permissions. 
        Les permissions sont définies dans le code (config/auth.php) pour plus de sécurité.
    </div>
    
    <form method="POST">
        <input type="hidden" name="action" value="save_permissions">
        
        <div class="table-responsive">
            <table style="width: 100%;">
                <thead>
                    <tr style="background: var(--light);">
                        <th style="width: 200px; position: sticky; left: 0; background: var(--light); z-index: 10;">Module</th>
                        <?php foreach ($roles as $role_key => $role_label): ?>
                            <th style="text-align: center; min-width: 150px;">
                                <?php echo $role_label; ?>
                                <br>
                                <small style="color: #666; font-weight: normal;">
                                    (<?php echo $user_counts[$role_key] ?? 0; ?> utilisateur(s))
                                </small>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modules as $module_key => $module_info): ?>
                        <tr>
                            <td colspan="<?php echo count($roles) + 1; ?>" style="background: #f8f9fa; font-weight: bold; padding: 1rem;">
                                <?php echo $module_info['label']; ?>
                            </td>
                        </tr>
                        <?php foreach ($module_info['permissions'] as $perm_key => $perm_label): ?>
                            <tr>
                                <td style="padding-left: 2rem; position: sticky; left: 0; background: white;">
                                    <?php echo $perm_label; ?>
                                    <small style="color: #666; display: block;">(<?php echo $perm_key; ?>)</small>
                                </td>
                                <?php foreach ($roles as $role_key => $role_label): ?>
                                    <?php 
                                    $has_permission = isset($default_permissions[$role_key][$module_key]) && 
                                                     in_array($perm_key, $default_permissions[$role_key][$module_key]);
                                    ?>
                                    <td style="text-align: center;">
                                        <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                                            <input type="checkbox" 
                                                   name="permissions[<?php echo $role_key; ?>][<?php echo $module_key; ?>][<?php echo $perm_key; ?>]"
                                                   <?php echo $has_permission ? 'checked' : ''; ?>
                                                   <?php echo $role_key === 'admin' ? 'disabled' : ''; ?>
                                                   style="width: 18px; height: 18px; cursor: pointer;">
                                            <span style="font-size: 1.2rem;">
                                                <?php echo $has_permission ? '✅' : '❌'; ?>
                                            </span>
                                        </label>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div style="text-align: center; margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 10px;">
            <p style="color: #666; margin-bottom: 1rem;">
                <strong>Note :</strong> Les permissions de l'administrateur ne peuvent pas être modifiées.
            </p>
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                💾 Enregistrer les permissions
            </button>
        </div>
    </form>
</div>

<!-- Légende et explications -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📖 Légende des Permissions</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <div style="padding: 1.5rem; background: #e3f2fd; border-radius: 10px; border-left: 4px solid #2196f3;">
            <h3 style="color: #1976d2; margin-bottom: 1rem;">🔵 Read (Consulter)</h3>
            <p style="color: #666; line-height: 1.6;">
                Permet de <strong>visualiser</strong> les données du module. 
                L'utilisateur peut voir les listes et les détails, mais ne peut pas modifier.
            </p>
        </div>
        
        <div style="padding: 1.5rem; background: #e8f5e9; border-radius: 10px; border-left: 4px solid #4caf50;">
            <h3 style="color: #388e3c; margin-bottom: 1rem;">🟢 Create (Créer)</h3>
            <p style="color: #666; line-height: 1.6;">
                Permet de <strong>créer de nouvelles entrées</strong> dans le module. 
                Par exemple, ajouter un nouveau client ou véhicule.
            </p>
        </div>
        
        <div style="padding: 1.5rem; background: #fff3e0; border-radius: 10px; border-left: 4px solid #ff9800;">
            <h3 style="color: #f57c00; margin-bottom: 1rem;">🟠 Update (Modifier)</h3>
            <p style="color: #666; line-height: 1.6;">
                Permet de <strong>modifier les données existantes</strong>. 
                L'utilisateur peut éditer les informations, changer les statuts, etc.
            </p>
        </div>
        
        <div style="padding: 1.5rem; background: #ffebee; border-radius: 10px; border-left: 4px solid #f44336;">
            <h3 style="color: #d32f2f; margin-bottom: 1rem;">🔴 Delete (Supprimer)</h3>
            <p style="color: #666; line-height: 1.6;">
                Permet de <strong>supprimer des entrées</strong>. 
                Permission sensible à accorder avec précaution.
            </p>
        </div>
        
        <div style="padding: 1.5rem; background: #f3e5f5; border-radius: 10px; border-left: 4px solid #9c27b0;">
            <h3 style="color: #7b1fa2; margin-bottom: 1rem;">🟣 Manage (Gérer)</h3>
            <p style="color: #666; line-height: 1.6;">
                Permission complète incluant toutes les actions possibles. 
                Réservée généralement aux administrateurs.
            </p>
        </div>
    </div>
</div>

<!-- Description des rôles -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">👥 Description des Rôles</h2>
    </div>
    
    <div style="display: grid; gap: 1rem;">
        <div style="padding: 1.5rem; background: linear-gradient(to right, #f093fb20, #f5576c20); border-radius: 10px; border-left: 4px solid #f5576c;">
            <h3 style="color: #f5576c; margin-bottom: 0.5rem;">👑 Administrateur</h3>
            <p style="color: #666; line-height: 1.6; margin: 0;">
                <strong>Accès complet</strong> à tous les modules et fonctionnalités. 
                Peut gérer les utilisateurs, les permissions, et accéder aux logs système.
            </p>
        </div>
        
        <div style="padding: 1.5rem; background: linear-gradient(to right, #4facfe20, #00f2fe20); border-radius: 10px; border-left: 4px solid #00f2fe;">
            <h3 style="color: #00f2fe; margin-bottom: 0.5rem;">💼 Vendeur</h3>
            <p style="color: #666; line-height: 1.6; margin: 0;">
                Peut <strong>gérer les clients et les ventes</strong>. 
                Accès en lecture seule aux véhicules et au stock. Peut créer et modifier les ventes.
            </p>
        </div>
        
        <div style="padding: 1.5rem; background: linear-gradient(to right, #43e97b20, #38f9d720); border-radius: 10px; border-left: 4px solid #38f9d7;">
            <h3 style="color: #38f9d7; margin-bottom: 0.5rem;">📦 Gestionnaire Stock</h3>
            <p style="color: #666; line-height: 1.6; margin: 0;">
                Gère l'<strong>inventaire des véhicules</strong>. 
                Peut ajouter, modifier et supprimer des véhicules. Accès en lecture aux clients et ventes.
            </p>
        </div>
        
        <div style="padding: 1.5rem; background: linear-gradient(to right, #fa709a20, #fee14020); border-radius: 10px; border-left: 4px solid #fee140;">
            <h3 style="color: #fa709a; margin-bottom: 0.5rem;">💰 Comptable</h3>
            <p style="color: #666; line-height: 1.6; margin: 0;">
                Accès en <strong>lecture seule</strong> à tous les modules. 
                Peut consulter les statistiques, les ventes et générer des rapports financiers.
            </p>
        </div>
    </div>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    table input[type="checkbox"] {
        accent-color: var(--primary);
    }
    
    table input[type="checkbox"]:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<?php include '../../includes/footer.php'; ?>
