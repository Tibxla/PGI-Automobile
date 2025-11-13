<?php
$page_title = "Gestion des congés";
include '../../includes/header.php';

requirePermission('conges', 'read');

$canManage = hasPermission('conges', 'update');
$canCreate = hasPermission('conges', 'create');

$creationError = '';
$personnelFilter = isset($_GET['personnel_id']) ? (int) $_GET['personnel_id'] : null;
$statutFilter = $_GET['statut'] ?? '';

$collaborateurs = $pdo->query("SELECT id, nom, prenom, poste FROM personnel ORDER BY nom, prenom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' && $canCreate) {
        $personnel_id = (int) ($_POST['personnel_id'] ?? 0);
        $type = $_POST['type'] ?? 'CP';
        $date_debut = $_POST['date_debut'] ?? '';
        $date_fin = $_POST['date_fin'] ?? '';
        $commentaire = trim($_POST['commentaire'] ?? '');

        if ($personnel_id <= 0 || $date_debut === '' || $date_fin === '') {
            $creationError = "Merci de renseigner un collaborateur et une période valide.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO conges (personnel_id, type, date_debut, date_fin, statut, commentaire) VALUES (?, ?, ?, ?, 'en_attente', ?)");
                $stmt->execute([$personnel_id, $type, $date_debut, $date_fin, $commentaire]);
                redirectTo('modules/rh/conges.php', ['success' => 1, 'personnel_id' => $personnel_id]);
            } catch (PDOException $e) {
                $creationError = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    }

    if ($action === 'update_statut' && $canManage) {
        $conge_id = (int) ($_POST['conge_id'] ?? 0);
        $nouveau_statut = $_POST['statut'] ?? 'en_attente';
        $notes = trim($_POST['notes'] ?? '');

        if ($conge_id > 0 && in_array($nouveau_statut, ['en_attente', 'approuve', 'refuse'], true)) {
            $stmt = $pdo->prepare("UPDATE conges SET statut = ?, commentaire_gestion = ? WHERE id = ?");
            $stmt->execute([$nouveau_statut, $notes !== '' ? $notes : null, $conge_id]);
            redirectTo('modules/rh/conges.php', ['success' => 1, 'personnel_id' => $personnelFilter, 'statut' => $statutFilter]);
        }
    }
}

$conditions = [];
$params = [];

if ($personnelFilter) {
    $conditions[] = 'c.personnel_id = :pid';
    $params[':pid'] = $personnelFilter;
}

if ($statutFilter !== '' && in_array($statutFilter, ['en_attente', 'approuve', 'refuse'], true)) {
    $conditions[] = 'c.statut = :statut';
    $params[':statut'] = $statutFilter;
}

$whereClause = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

$stats = $pdo->query("
    SELECT 
        SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) AS attente,
        SUM(CASE WHEN statut = 'approuve' THEN 1 ELSE 0 END) AS approuve,
        SUM(CASE WHEN statut = 'refuse' THEN 1 ELSE 0 END) AS refuse,
        COUNT(*) AS total
    FROM conges
")->fetch();

$sql = "
    SELECT c.*, p.nom, p.prenom, p.poste
    FROM conges c
    JOIN personnel p ON c.personnel_id = p.id
    $whereClause
    ORDER BY c.date_debut DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$conges = $stmt->fetchAll();

$statusClasses = [
    'en_attente' => 'status-badge status-attente',
    'approuve' => 'status-badge status-valide',
    'refuse' => 'status-badge status-refuse'
];
$statusLabels = [
    'en_attente' => 'En attente',
    'approuve' => 'Approuvé',
    'refuse' => 'Refusé'
];
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">🌴 Suivi des congés</h2>
        <div style="display: flex; gap: 0.75rem;">
            <a href="liste.php" class="btn btn-secondary">👥 Retour RH</a>
            <?php if ($canCreate): ?>
                <a href="#form-conge" class="btn btn-primary">➕ Nouveau congé</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✅ Mise à jour effectuée.</div>
    <?php endif; ?>

    <div class="stats-grid" style="margin-bottom: 1.5rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);">
            <div class="stat-icon">📋</div>
            <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
            <div class="stat-label">Demandes totales</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);">
            <div class="stat-icon">⏳</div>
            <div class="stat-value"><?php echo $stats['attente'] ?? 0; ?></div>
            <div class="stat-label">En attente</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #34d399 0%, #059669 100%);">
            <div class="stat-icon">✅</div>
            <div class="stat-value"><?php echo $stats['approuve'] ?? 0; ?></div>
            <div class="stat-label">Approuvés</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);">
            <div class="stat-icon">❌</div>
            <div class="stat-value"><?php echo $stats['refuse'] ?? 0; ?></div>
            <div class="stat-label">Refusés</div>
        </div>
    </div>

    <form method="GET" style="margin-bottom: 1.5rem;">
        <div class="form-row" style="align-items: flex-end;">
            <div class="form-group">
                <label>Collaborateur</label>
                <select name="personnel_id" class="form-control">
                    <option value="">Tous</option>
                    <?php foreach ($collaborateurs as $collaborateur): ?>
                        <option value="<?php echo $collaborateur['id']; ?>" <?php echo $personnelFilter === (int) $collaborateur['id'] ? 'selected' : ''; ?>>
                            <?php echo escape($collaborateur['prenom'] . ' ' . $collaborateur['nom'] . ' (' . $collaborateur['poste'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="">Tous</option>
                    <option value="en_attente" <?php echo $statutFilter === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                    <option value="approuve" <?php echo $statutFilter === 'approuve' ? 'selected' : ''; ?>>Approuvé</option>
                    <option value="refuse" <?php echo $statutFilter === 'refuse' ? 'selected' : ''; ?>>Refusé</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
                <a href="conges.php" class="btn btn-warning">🔄 Réinitialiser</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Collaborateur</th>
                    <th>Période</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Commentaire</th>
                    <?php if ($canManage): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (count($conges) === 0): ?>
                    <tr>
                        <td colspan="<?php echo $canManage ? 6 : 5; ?>" style="text-align: center; color: #666; padding: 2rem;">Aucun congé trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($conges as $conge): ?>
                        <tr>
                            <td>
                                <strong><?php echo escape($conge['prenom'] . ' ' . $conge['nom']); ?></strong><br>
                                <small style="color: #666;">Poste : <?php echo escape($conge['poste']); ?></small>
                            </td>
                            <td>
                                <?php echo formatDate($conge['date_debut']); ?> → <?php echo formatDate($conge['date_fin']); ?><br>
                                <small style="color: #666;">
                                    <?php echo (new DateTime($conge['date_debut']))->diff(new DateTime($conge['date_fin']))->days + 1; ?> jour(s)
                                </small>
                            </td>
                            <td><?php echo strtoupper($conge['type']); ?></td>
                            <td>
                                <span class="<?php echo $statusClasses[$conge['statut']] ?? 'status-badge'; ?>">
                                    <?php echo $statusLabels[$conge['statut']] ?? ucfirst($conge['statut']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $conge['commentaire'] ? escape($conge['commentaire']) : '<span style="color:#999;">—</span>'; ?><br>
                                <?php if (!empty($conge['commentaire_gestion'])): ?>
                                    <small style="color:#2563eb; display:block; margin-top:0.25rem;">RH : <?php echo escape($conge['commentaire_gestion']); ?></small>
                                <?php endif; ?>
                            </td>
                            <?php if ($canManage): ?>
                            <td>
                                <form method="POST" style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    <input type="hidden" name="action" value="update_statut">
                                    <input type="hidden" name="conge_id" value="<?php echo $conge['id']; ?>">
                                    <select name="statut" class="form-control" style="min-width: 140px;">
                                        <option value="en_attente" <?php echo $conge['statut'] === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                        <option value="approuve" <?php echo $conge['statut'] === 'approuve' ? 'selected' : ''; ?>>Approuver</option>
                                        <option value="refuse" <?php echo $conge['statut'] === 'refuse' ? 'selected' : ''; ?>>Refuser</option>
                                    </select>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Note RH..."><?php echo htmlspecialchars($conge['commentaire_gestion'] ?? ''); ?></textarea>
                                    <button type="submit" class="btn btn-primary btn-sm">Mettre à jour</button>
                                </form>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($canCreate): ?>
<div class="card" id="form-conge" style="margin-top: 2rem;">
    <div class="card-header">
        <h2 class="card-title">➕ Nouvelle demande de congé</h2>
    </div>

    <?php if (!empty($creationError)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($creationError); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="action" value="create">
        <div class="form-row">
            <div class="form-group">
                <label>Collaborateur *</label>
                <select name="personnel_id" class="form-control" required>
                    <option value="">Sélectionnez</option>
                    <?php foreach ($collaborateurs as $collaborateur): ?>
                        <option value="<?php echo $collaborateur['id']; ?>" <?php echo ($personnelFilter === (int) $collaborateur['id']) ? 'selected' : ''; ?>>
                            <?php echo escape($collaborateur['prenom'] . ' ' . $collaborateur['nom'] . ' (' . $collaborateur['poste'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Type *</label>
                <select name="type" class="form-control">
                    <option value="CP">Congés payés</option>
                    <option value="RTT">RTT</option>
                    <option value="Maladie">Maladie</option>
                    <option value="Sans solde">Sans solde</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date de début *</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Date de fin *</label>
                <input type="date" name="date_fin" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label>Commentaire</label>
            <textarea name="commentaire" class="form-control" rows="3" placeholder="Informations complémentaires..."></textarea>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">✅ Créer la demande</button>
        </div>
    </form>
</div>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>
