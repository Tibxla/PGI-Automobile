<?php
$page_title = "Gestion des utilisateurs";
include '../../includes/header.php';

// Vérifier que c'est un admin
requireRole('admin');

$message = '';
$error = '';

// Gestion des actions
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'toggle_status' && isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        try {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET statut = IF(statut = 'actif', 'inactif', 'actif') WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Statut modifié avec succès";
        } catch (PDOException $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}

// Récupérer tous les utilisateurs
$stmt = $pdo->query("
    SELECT 
        u.*,
        COUNT(l.id) as nb_connexions,
        MAX(l.created_at) as derniere_connexion_log
    FROM utilisateurs u
    LEFT JOIN logs_connexion l ON u.id = l.utilisateur_id AND l.action = 'connexion'
    GROUP BY u.id
    ORDER BY u.created_at DESC
");
$utilisateurs = $stmt->fetchAll();

// Statistiques
$stmt = $pdo->query("SELECT role, COUNT(*) as count FROM utilisateurs GROUP BY role");
$stats_roles = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="color: white; display: flex; align-items: center; gap: 10px;">
        👥 Gestion des Utilisateurs
    </h1>
    <a href="ajouter-utilisateur.php" class="btn btn-primary">
        ➕ Nouvel utilisateur
    </a>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Statistiques des rôles -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">👑</div>
        <div class="stat-value"><?php echo $stats_roles['admin'] ?? 0; ?></div>
        <div class="stat-label">Administrateurs</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="stat-icon">💼</div>
        <div class="stat-value"><?php echo $stats_roles['vendeur'] ?? 0; ?></div>
        <div class="stat-label">Vendeurs</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="stat-icon">📦</div>
        <div class="stat-value"><?php echo $stats_roles['gestionnaire_stock'] ?? 0; ?></div>
        <div class="stat-label">Gestionnaires Stock</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="stat-icon">💰</div>
        <div class="stat-value"><?php echo $stats_roles['comptable'] ?? 0; ?></div>
        <div class="stat-label">Comptables</div>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📋 Liste des Utilisateurs</h2>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Dernière connexion</th>
                    <th>Nb connexions</th>
                    <th>Inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $user): 
                    $role_class = '';
                    $role_icon = '';
                    switch($user['role']) {
                        case 'admin':
                            $role_class = 'role-admin';
                            $role_icon = '👑';
                            break;
                        case 'vendeur':
                            $role_class = 'role-vendeur';
                            $role_icon = '💼';
                            break;
                        case 'gestionnaire_stock':
                            $role_class = 'role-gestionnaire';
                            $role_icon = '📦';
                            break;
                        case 'comptable':
                            $role_class = 'role-comptable';
                            $role_icon = '💰';
                            break;
                    }
                ?>
                <tr>
                    <td><strong>#<?php echo $user['id']; ?></strong></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="<?php echo $user['avatar'] ?? "https://ui-avatars.com/api/?name=" . urlencode($user['prenom'] . ' ' . $user['nom']); ?>" 
                                 alt="Avatar" 
                                 style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--primary);">
                            <strong><?php echo escape($user['nom'] . ' ' . $user['prenom']); ?></strong>
                        </div>
                    </td>
                    <td><?php echo escape($user['email']); ?></td>
                    <td>
                        <span class="role-badge <?php echo $role_class; ?>">
                            <?php echo $role_icon . ' ' . getRoleLabel($user['role']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($user['statut'] === 'actif'): ?>
                            <span class="badge badge-stock">✓ Actif</span>
                        <?php elseif ($user['statut'] === 'inactif'): ?>
                            <span class="badge badge-vendu">✗ Inactif</span>
                        <?php else: ?>
                            <span class="badge badge-reserve">⏸ Suspendu</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($user['derniere_connexion']): ?>
                            <span title="<?php echo formatDate($user['derniere_connexion']); ?>">
                                <?php echo timeSince($user['derniere_connexion']); ?>
                            </span>
                        <?php else: ?>
                            <span style="color: #999;">Jamais</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge badge-stock"><?php echo $user['nb_connexions']; ?></span>
                    </td>
                    <td><?php echo formatDate($user['created_at']); ?></td>
                    <td>
                        <a href="modifier-utilisateur.php?id=<?php echo $user['id']; ?>" 
                           class="btn btn-warning btn-sm" 
                           title="Modifier">
                            ✏️
                        </a>
                        
                        <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                            <a href="?action=toggle_status&id=<?php echo $user['id']; ?>" 
                               class="btn <?php echo $user['statut'] === 'actif' ? 'btn-danger' : 'btn-success'; ?> btn-sm"
                               onclick="return confirm('Changer le statut de cet utilisateur ?')"
                               title="<?php echo $user['statut'] === 'actif' ? 'Désactiver' : 'Activer'; ?>">
                                <?php echo $user['statut'] === 'actif' ? '🔒' : '🔓'; ?>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Dernières connexions -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📊 Dernières Activités</h2>
    </div>
    
    <?php
    $stmt = $pdo->query("
        SELECT l.*, u.nom, u.prenom, u.email
        FROM logs_connexion l
        JOIN utilisateurs u ON l.utilisateur_id = u.id
        ORDER BY l.created_at DESC
        LIMIT 20
    ");
    $logs = $stmt->fetchAll();
    ?>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date/Heure</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Adresse IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($log['created_at'])); ?></td>
                    <td><?php echo escape($log['prenom'] . ' ' . $log['nom']); ?></td>
                    <td>
                        <?php if ($log['action'] === 'connexion'): ?>
                            <span class="badge badge-stock">✓ Connexion</span>
                        <?php elseif ($log['action'] === 'deconnexion'): ?>
                            <span class="badge badge-reserve">→ Déconnexion</span>
                        <?php else: ?>
                            <span class="badge badge-vendu">✗ Échec</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo escape($log['ip_address']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
