<?php
$page_title = "Ressources Humaines";
include '../../includes/header.php';

requirePermission('rh', 'read');

$filtre_statut = $_GET['statut'] ?? '';
$search = trim($_GET['recherche'] ?? '');

$statsQuery = $pdo->query("
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN statut = 'actif' THEN 1 ELSE 0 END) AS actifs,
        SUM(CASE WHEN statut = 'conge' THEN 1 ELSE 0 END) AS conges,
        SUM(CASE WHEN statut = 'inactif' THEN 1 ELSE 0 END) AS inactifs,
        COALESCE(SUM(salaire), 0) AS masse_salariale
    FROM personnel
");
$stats = $statsQuery->fetch();

$sql = "SELECT * FROM personnel WHERE 1=1";
$params = [];

if ($filtre_statut !== '' && in_array($filtre_statut, ['actif', 'conge', 'inactif'], true)) {
    $sql .= " AND statut = :statut";
    $params[':statut'] = $filtre_statut;
}

if ($search !== '') {
    $sql .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search OR poste LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$employes = $stmt->fetchAll();

$congesRecents = $pdo->query("
    SELECT c.*, p.nom, p.prenom
    FROM conges c
    JOIN personnel p ON c.personnel_id = p.id
    ORDER BY c.created_at DESC
    LIMIT 5
")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">🧑‍💼 Gestion du Personnel</h2>
        <?php if (hasPermission('rh', 'create')): ?>
            <a href="ajouter.php" class="btn btn-primary">➕ Ajouter un collaborateur</a>
        <?php endif; ?>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
            <div class="stat-icon">👥</div>
            <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
            <div class="stat-label">Effectif total</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #34d399 0%, #10b981 100%);">
            <div class="stat-icon">✅</div>
            <div class="stat-value"><?php echo $stats['actifs'] ?? 0; ?></div>
            <div class="stat-label">Actifs</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);">
            <div class="stat-icon">🌴</div>
            <div class="stat-value"><?php echo $stats['conges'] ?? 0; ?></div>
            <div class="stat-label">En congé</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);">
            <div class="stat-icon">⛔</div>
            <div class="stat-value"><?php echo $stats['inactifs'] ?? 0; ?></div>
            <div class="stat-label">Inactifs</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);">
            <div class="stat-icon">💶</div>
            <div class="stat-value"><?php echo number_format($stats['masse_salariale'] ?? 0, 2, ',', ' '); ?> €</div>
            <div class="stat-label">Masse salariale</div>
        </div>
    </div>

    <form method="GET" style="margin-bottom: 1.5rem;">
        <div class="form-row" style="align-items: flex-end;">
            <div class="form-group">
                <label>Rechercher</label>
                <input type="text" name="recherche" class="form-control" placeholder="Nom, poste, email..." value="<?php echo escape($search); ?>">
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="">Tous</option>
                    <option value="actif" <?php echo $filtre_statut === 'actif' ? 'selected' : ''; ?>>Actif</option>
                    <option value="conge" <?php echo $filtre_statut === 'conge' ? 'selected' : ''; ?>>En congé</option>
                    <option value="inactif" <?php echo $filtre_statut === 'inactif' ? 'selected' : ''; ?>>Inactif</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
                <a href="liste.php" class="btn btn-warning">🔄 Réinitialiser</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Collaborateur</th>
                    <th>Poste</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Salaire</th>
                    <th>Statut</th>
                    <th>Date d'embauche</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($employes) === 0): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #666; padding: 2rem;">Aucun collaborateur trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($employes as $employe): ?>
                        <tr>
                            <td>
                                <strong><?php echo escape($employe['prenom'] . ' ' . $employe['nom']); ?></strong><br>
                                <small style="color: #666;">ID #<?php echo $employe['id']; ?></small>
                            </td>
                            <td><?php echo escape($employe['poste']); ?></td>
                            <td><?php echo escape($employe['email']); ?></td>
                            <td><?php echo escape($employe['telephone']); ?></td>
                            <td><strong><?php echo number_format($employe['salaire'], 2, ',', ' '); ?> €</strong></td>
                            <td>
                                <?php
                                $statusClasses = [
                                    'actif' => 'status-badge status-actif',
                                    'conge' => 'status-badge status-conge',
                                    'inactif' => 'status-badge status-inactif'
                                ];
                                $statusLabels = [
                                    'actif' => 'Actif',
                                    'conge' => 'En congé',
                                    'inactif' => 'Inactif'
                                ];
                                $status = $employe['statut'];
                                ?>
                                <span class="<?php echo $statusClasses[$status] ?? 'status-badge'; ?>">
                                    <?php echo $statusLabels[$status] ?? ucfirst($status); ?>
                                </span>
                            </td>
                            <td><?php echo $employe['date_embauche'] ? formatDate($employe['date_embauche']) : '-'; ?></td>
                            <td style="display: flex; gap: 0.5rem;">
                                <?php if (hasPermission('rh', 'update')): ?>
                                    <a href="modifier.php?id=<?php echo $employe['id']; ?>" class="btn btn-warning btn-sm">✏️</a>
                                <?php endif; ?>
                                <?php if (hasPermission('paie', 'read')): ?>
                                    <a href="paie.php?personnel_id=<?php echo $employe['id']; ?>" class="btn btn-primary btn-sm">💶</a>
                                <?php endif; ?>
                                <?php if (hasPermission('conges', 'read')): ?>
                                    <a href="conges.php?personnel_id=<?php echo $employe['id']; ?>" class="btn btn-secondary btn-sm">🌴</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">🌴 Congés récents</h2>
        <a href="conges.php" class="btn btn-secondary">Gérer les congés</a>
    </div>
    
    <?php if (count($congesRecents) === 0): ?>
        <p style="color: #666; text-align: center; padding: 1.5rem;">Aucun congé enregistré récemment.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Collaborateur</th>
                        <th>Période</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($congesRecents as $conge): ?>
                        <tr>
                            <td><?php echo escape($conge['prenom'] . ' ' . $conge['nom']); ?></td>
                            <td>
                                <?php echo formatDate($conge['date_debut']); ?> → <?php echo formatDate($conge['date_fin']); ?><br>
                                <small style="color: #666;">
                                    <?php echo (new DateTime($conge['date_debut']))->diff(new DateTime($conge['date_fin']))->days + 1; ?> jour(s)
                                </small>
                            </td>
                            <td><?php echo ucfirst($conge['type']); ?></td>
                            <td>
                                <?php
                                $statutClasses = [
                                    'en_attente' => 'status-badge status-attente',
                                    'approuve' => 'status-badge status-valide',
                                    'refuse' => 'status-badge status-refuse'
                                ];
                                $statutLabels = [
                                    'en_attente' => 'En attente',
                                    'approuve' => 'Approuvé',
                                    'refuse' => 'Refusé'
                                ];
                                ?>
                                <span class="<?php echo $statutClasses[$conge['statut']] ?? 'status-badge'; ?>">
                                    <?php echo $statutLabels[$conge['statut']] ?? ucfirst($conge['statut']); ?>
                                </span>
                            </td>
                            <td><?php echo escape($conge['commentaire'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
