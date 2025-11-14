<?php
$page_title = "Gestion de la paie";
include '../../includes/header.php';

requirePermission('paie', 'read');

$canCreate = hasPermission('paie', 'create');
$canUpdate = hasPermission('paie', 'update');

$personnelFilter = isset($_GET['personnel_id']) ? (int) $_GET['personnel_id'] : null;
$statutFilter = $_GET['statut'] ?? '';

$collaborateurs = $pdo->query("SELECT id, nom, prenom, poste, salaire FROM personnel ORDER BY nom, prenom")->fetchAll();

$creationError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' && $canCreate) {
        $personnel_id = (int) ($_POST['personnel_id'] ?? 0);
        $mois = $_POST['mois_reference'] ?? '';
        $salaire_base = (float) ($_POST['salaire_base'] ?? 0);
        $prime = (float) ($_POST['prime'] ?? 0);
        $deductions = (float) ($_POST['deductions'] ?? 0);
        $notes = trim($_POST['notes'] ?? '');

        if ($personnel_id <= 0 || $mois === '') {
            $creationError = "Merci de sélectionner un collaborateur et un mois de référence.";
        } else {
            $net = $salaire_base + $prime - $deductions;
            try {
                $stmt = $pdo->prepare("INSERT INTO bulletins_paie (personnel_id, mois_reference, salaire_base, prime, deductions, net_a_payer, statut, notes) VALUES (?, ?, ?, ?, ?, ?, 'brouillon', ?)");
                $stmt->execute([$personnel_id, $mois . '-01', $salaire_base, $prime, $deductions, $net, $notes]);
                redirectTo('modules/rh/paie.php', ['success' => 1, 'personnel_id' => $personnel_id]);
            } catch (PDOException $e) {
                $creationError = "Erreur lors de la création : " . $e->getMessage();
            }
        }
    }

    if ($action === 'changer_statut' && $canUpdate) {
        $bulletin_id = (int) ($_POST['bulletin_id'] ?? 0);
        $nouveau_statut = $_POST['statut'] ?? 'brouillon';

        if ($bulletin_id > 0 && in_array($nouveau_statut, ['brouillon', 'valide'], true)) {
            $stmt = $pdo->prepare("UPDATE bulletins_paie SET statut = ? WHERE id = ?");
            $stmt->execute([$nouveau_statut, $bulletin_id]);
            redirectTo('modules/rh/paie.php', ['success' => 1, 'personnel_id' => $personnelFilter, 'statut' => $statutFilter]);
        }
    }
}

$conditions = [];
$params = [];

if ($personnelFilter) {
    $conditions[] = 'b.personnel_id = :pid';
    $params[':pid'] = $personnelFilter;
}

if ($statutFilter !== '' && in_array($statutFilter, ['brouillon', 'valide'], true)) {
    $conditions[] = 'b.statut = :statut';
    $params[':statut'] = $statutFilter;
}

$whereClause = count($conditions) > 0 ? 'WHERE ' . implode(' AND ', $conditions) : '';

$stats = $pdo->query("
    SELECT 
        COUNT(*) AS total,
        SUM(net_a_payer) AS masse,
        SUM(CASE WHEN statut = 'valide' THEN net_a_payer ELSE 0 END) AS valides
    FROM bulletins_paie
")->fetch();

$sql = "
    SELECT b.*, p.nom, p.prenom, p.poste
    FROM bulletins_paie b
    JOIN personnel p ON b.personnel_id = p.id
    $whereClause
    ORDER BY b.mois_reference DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bulletins = $stmt->fetchAll();

$statusClasses = [
    'brouillon' => 'status-badge status-brouillon',
    'valide' => 'status-badge status-valide'
];
$statusLabels = [
    'brouillon' => 'Brouillon',
    'valide' => 'Validé'
];
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">💶 Bulletins de paie</h2>
        <div style="display: flex; gap: 0.75rem;">
            <a href="liste.php" class="btn btn-secondary">👥 Retour RH</a>
            <?php if ($canCreate): ?><a href="#form-paie" class="btn btn-primary">➕ Nouveau bulletin</a><?php endif; ?>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✅ Opération réalisée avec succès.</div>
    <?php endif; ?>

    <div class="stats-grid" style="margin-bottom: 1.5rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);">
            <div class="stat-icon">📄</div>
            <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
            <div class="stat-label">Bulletins générés</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);">
            <div class="stat-icon">💸</div>
            <div class="stat-value"><?php echo number_format($stats['masse'] ?? 0, 2, ',', ' '); ?> €</div>
            <div class="stat-label">Montant cumulé</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #34d399 0%, #059669 100%);">
            <div class="stat-icon">✅</div>
            <div class="stat-value"><?php echo number_format($stats['valides'] ?? 0, 2, ',', ' '); ?> €</div>
            <div class="stat-label">Validés</div>
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
                    <option value="brouillon" <?php echo $statutFilter === 'brouillon' ? 'selected' : ''; ?>>Brouillon</option>
                    <option value="valide" <?php echo $statutFilter === 'valide' ? 'selected' : ''; ?>>Validé</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
                <a href="paie.php" class="btn btn-warning">🔄 Réinitialiser</a>
            </div>
        </div>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Mois</th>
                    <th>Collaborateur</th>
                    <th>Salaire base</th>
                    <th>Prime</th>
                    <th>Retenues</th>
                    <th>Net à payer</th>
                    <th>Statut</th>
                    <?php if ($canUpdate): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (count($bulletins) === 0): ?>
                    <tr>
                        <td colspan="<?php echo $canUpdate ? 8 : 7; ?>" style="text-align:center; color:#666; padding:2rem;">Aucun bulletin de paie trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bulletins as $bulletin): ?>
                        <tr>
                            <td>
                                <strong><?php echo date('m/Y', strtotime($bulletin['mois_reference'])); ?></strong><br>
                                <small style="color:#666;">Créé le <?php echo formatDate($bulletin['created_at']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo escape($bulletin['prenom'] . ' ' . $bulletin['nom']); ?></strong><br>
                                <small style="color:#666;">Poste : <?php echo escape($bulletin['poste']); ?></small>
                            </td>
                            <td><?php echo number_format($bulletin['salaire_base'], 2, ',', ' '); ?> €</td>
                            <td><?php echo number_format($bulletin['prime'], 2, ',', ' '); ?> €</td>
                            <td><?php echo number_format($bulletin['deductions'], 2, ',', ' '); ?> €</td>
                            <td><strong><?php echo number_format($bulletin['net_a_payer'], 2, ',', ' '); ?> €</strong></td>
                            <td><span class="<?php echo $statusClasses[$bulletin['statut']] ?? 'status-badge'; ?>"><?php echo $statusLabels[$bulletin['statut']] ?? ucfirst($bulletin['statut']); ?></span></td>
                            <?php if ($canUpdate): ?>
                            <td>
                                <form method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                                    <input type="hidden" name="action" value="changer_statut">
                                    <input type="hidden" name="bulletin_id" value="<?php echo $bulletin['id']; ?>">
                                    <select name="statut" class="form-control" style="max-width: 140px;">
                                        <option value="brouillon" <?php echo $bulletin['statut'] === 'brouillon' ? 'selected' : ''; ?>>Brouillon</option>
                                        <option value="valide" <?php echo $bulletin['statut'] === 'valide' ? 'selected' : ''; ?>>Validé</option>
                                    </select>
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
<div class="card" id="form-paie" style="margin-top: 2rem;">
    <div class="card-header">
        <h2 class="card-title">➕ Nouveau bulletin de paie</h2>
    </div>

    <?php if ($creationError): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($creationError); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="action" value="create">
        <div class="form-row">
            <div class="form-group">
                <label>Collaborateur *</label>
                <select name="personnel_id" class="form-control" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($collaborateurs as $collaborateur): ?>
                        <option value="<?php echo $collaborateur['id']; ?>" <?php echo ($personnelFilter === (int) $collaborateur['id']) ? 'selected' : ''; ?>>
                            <?php echo escape($collaborateur['prenom'] . ' ' . $collaborateur['nom'] . ' (' . $collaborateur['poste'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Mois de référence *</label>
                <input type="month" name="mois_reference" class="form-control" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Salaire de base *</label>
                <input type="number" name="salaire_base" class="form-control" min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Prime</label>
                <input type="number" name="prime" class="form-control" min="0" step="0.01" value="0">
            </div>
            <div class="form-group">
                <label>Retenues</label>
                <input type="number" name="deductions" class="form-control" min="0" step="0.01" value="0">
            </div>
        </div>

        <div class="form-group">
            <label>Notes internes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Détail sur primes / retenues"></textarea>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">✅ Enregistrer le bulletin</button>
        </div>
    </form>
</div>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>
