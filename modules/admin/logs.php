<?php
$page_title = "Logs Système";
include '../../includes/header.php';

// Vérifier que c'est un admin
requireRole('admin');

// Paramètres de pagination et filtres
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 50;
$offset = ($page - 1) * $per_page;

$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$user_filter = isset($_GET['user']) ? $_GET['user'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Construction de la requête
$where_clauses = [];
$params = [];

if ($type_filter) {
    $where_clauses[] = "l.action = :type";
    $params[':type'] = $type_filter;
}

if ($user_filter) {
    $where_clauses[] = "l.utilisateur_id = :user_id";
    $params[':user_id'] = $user_filter;
}

if ($date_filter) {
    $where_clauses[] = "DATE(l.created_at) = :date";
    $params[':date'] = $date_filter;
}

$where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

// Compter le total
$count_sql = "SELECT COUNT(*) FROM logs_connexion l $where_sql";
$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_logs = $stmt->fetchColumn();
$total_pages = ceil($total_logs / $per_page);

// Récupérer les logs
$sql = "
    SELECT l.*, u.nom, u.prenom, u.email, u.role
    FROM logs_connexion l
    LEFT JOIN utilisateurs u ON l.utilisateur_id = u.id
    $where_sql
    ORDER BY l.created_at DESC
    LIMIT :limit OFFSET :offset
";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll();

// Statistiques
$stats_sql = "
    SELECT 
        action,
        COUNT(*) as count,
        COUNT(DISTINCT utilisateur_id) as unique_users
    FROM logs_connexion
    WHERE DATE(created_at) >= CURDATE() - INTERVAL 30 DAY
    GROUP BY action
";
$stats = $pdo->query($stats_sql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

// Récupérer la liste des utilisateurs pour le filtre
$users = $pdo->query("SELECT id, nom, prenom FROM utilisateurs ORDER BY nom, prenom")->fetchAll();
?>

<h1 style="color: white; text-align: center; margin-bottom: 2rem;">
    📋 Logs Système
</h1>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?php echo number_format($total_logs); ?></div>
        <div class="stat-label">Total des logs</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="stat-icon">✅</div>
        <div class="stat-value">
            <?php echo isset($stats['connexion']) ? number_format($stats['connexion'][0]['count']) : 0; ?>
        </div>
        <div class="stat-label">Connexions (30j)</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">❌</div>
        <div class="stat-value">
            <?php echo isset($stats['echec_connexion']) ? number_format($stats['echec_connexion'][0]['count']) : 0; ?>
        </div>
        <div class="stat-label">Échecs (30j)</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
        <div class="stat-icon">🔒</div>
        <div class="stat-value">
            <?php 
            $echecs = isset($stats['echec_connexion']) ? $stats['echec_connexion'][0]['count'] : 0;
            $connexions = isset($stats['connexion']) ? $stats['connexion'][0]['count'] : 1;
            echo number_format(($echecs / ($echecs + $connexions)) * 100, 1) . '%';
            ?>
        </div>
        <div class="stat-label">Taux d'échec</div>
    </div>
</div>

<!-- Filtres -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🔍 Filtres</h2>
    </div>
    
    <form method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
        <div class="form-group">
            <label>Type d'action</label>
            <select name="type" class="form-control">
                <option value="">-- Tous --</option>
                <option value="connexion" <?php echo $type_filter === 'connexion' ? 'selected' : ''; ?>>✅ Connexion</option>
                <option value="deconnexion" <?php echo $type_filter === 'deconnexion' ? 'selected' : ''; ?>>↩ Déconnexion</option>
                <option value="echec_connexion" <?php echo $type_filter === 'echec_connexion' ? 'selected' : ''; ?>>❌ Échec</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Utilisateur</label>
            <select name="user" class="form-control">
                <option value="">-- Tous --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo $user_filter == $user['id'] ? 'selected' : ''; ?>>
                        <?php echo escape($user['prenom'] . ' ' . $user['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="<?php echo escape($date_filter); ?>">
        </div>
        
        <div class="form-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
            <a href="logs.php" class="btn btn-warning">🔄 Réinitialiser</a>
        </div>
    </form>
</div>

<!-- Liste des logs -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📜 Historique des Logs</h2>
        <div>
            <span style="color: #666;">
                Page <?php echo $page; ?> sur <?php echo $total_pages; ?> 
                (<?php echo number_format($total_logs); ?> log(s))
            </span>
        </div>
    </div>
    
    <?php if (count($logs) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date/Heure</th>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>Action</th>
                        <th>Adresse IP</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><strong>#<?php echo $log['id']; ?></strong></td>
                            <td>
                                <div style="display: flex; flex-direction: column;">
                                    <strong><?php echo date('d/m/Y', strtotime($log['created_at'])); ?></strong>
                                    <small style="color: #666;"><?php echo date('H:i:s', strtotime($log['created_at'])); ?></small>
                                </div>
                            </td>
                            <td>
                                <?php if ($log['nom']): ?>
                                    <div style="display: flex; flex-direction: column;">
                                        <strong><?php echo escape($log['prenom'] . ' ' . $log['nom']); ?></strong>
                                        <small style="color: #666;"><?php echo escape($log['email']); ?></small>
                                    </div>
                                <?php else: ?>
                                    <span style="color: #999;">Utilisateur supprimé</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($log['role']): ?>
                                    <span class="badge badge-stock"><?php echo getRoleLabel($log['role']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $action_class = 'badge-stock';
                                $action_icon = '📝';
                                $action_text = ucfirst($log['action']);
                                
                                if ($log['action'] === 'connexion') {
                                    $action_class = 'badge-stock';
                                    $action_icon = '✅';
                                    $action_text = 'Connexion';
                                } elseif ($log['action'] === 'deconnexion') {
                                    $action_class = 'badge-reserve';
                                    $action_icon = '↩';
                                    $action_text = 'Déconnexion';
                                } elseif ($log['action'] === 'echec_connexion') {
                                    $action_class = 'badge-vendu';
                                    $action_icon = '❌';
                                    $action_text = 'Échec connexion';
                                }
                                ?>
                                <span class="badge <?php echo $action_class; ?>">
                                    <?php echo $action_icon . ' ' . $action_text; ?>
                                </span>
                            </td>
                            <td>
                                <code style="font-size: 0.85rem;"><?php echo escape($log['ip_address']); ?></code>
                            </td>
                            <td>
                                <small style="color: #666; max-width: 200px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo escape($log['user_agent']); ?>">
                                    <?php echo escape($log['user_agent']); ?>
                                </small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 2rem; flex-wrap: wrap;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&type=<?php echo $type_filter; ?>&user=<?php echo $user_filter; ?>&date=<?php echo $date_filter; ?>" 
                       class="btn btn-warning">« Précédent</a>
                <?php endif; ?>
                
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);
                
                if ($start_page > 1): ?>
                    <a href="?page=1&type=<?php echo $type_filter; ?>&user=<?php echo $user_filter; ?>&date=<?php echo $date_filter; ?>" 
                       class="btn btn-secondary">1</a>
                    <?php if ($start_page > 2): ?>
                        <span style="padding: 0.5rem;">...</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&type=<?php echo $type_filter; ?>&user=<?php echo $user_filter; ?>&date=<?php echo $date_filter; ?>" 
                       class="btn <?php echo $i === $page ? 'btn-primary' : 'btn-secondary'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <span style="padding: 0.5rem;">...</span>
                    <?php endif; ?>
                    <a href="?page=<?php echo $total_pages; ?>&type=<?php echo $type_filter; ?>&user=<?php echo $user_filter; ?>&date=<?php echo $date_filter; ?>" 
                       class="btn btn-secondary"><?php echo $total_pages; ?></a>
                <?php endif; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&type=<?php echo $type_filter; ?>&user=<?php echo $user_filter; ?>&date=<?php echo $date_filter; ?>" 
                       class="btn btn-warning">Suivant »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">
            Aucun log trouvé avec ces critères
        </p>
    <?php endif; ?>
</div>

<!-- Informations sur la table logs_connexion -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">ℹ️ Informations</h2>
    </div>
    
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 10px;">
        <h3 style="margin-bottom: 1rem; color: var(--primary);">À propos des logs</h3>
        <ul style="line-height: 2; color: #666;">
            <li><strong>Connexion :</strong> Enregistrée lorsqu'un utilisateur se connecte avec succès</li>
            <li><strong>Déconnexion :</strong> Enregistrée lorsqu'un utilisateur se déconnecte manuellement</li>
            <li><strong>Échec connexion :</strong> Enregistrée lors d'une tentative de connexion échouée</li>
            <li><strong>Adresse IP :</strong> L'adresse IP de l'utilisateur au moment de l'action</li>
            <li><strong>User Agent :</strong> Informations sur le navigateur et le système d'exploitation</li>
        </ul>
        
        <div style="margin-top: 1.5rem; padding: 1rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 5px;">
            <strong>💡 Conseil :</strong> Surveillez régulièrement les échecs de connexion répétés depuis la même IP pour détecter d'éventuelles tentatives d'intrusion.
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
