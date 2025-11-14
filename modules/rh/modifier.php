<?php
$page_title = "Modifier un collaborateur";
include '../../includes/header.php';

requirePermission('rh', 'update');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    redirectTo('modules/rh/liste.php');
}

$stmt = $pdo->prepare("SELECT * FROM personnel WHERE id = ?");
$stmt->execute([$id]);
$employe = $stmt->fetch();

if (!$employe) {
    redirectTo('modules/rh/liste.php');
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $poste = trim($_POST['poste'] ?? '');
    $salaire = $_POST['salaire'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $date_embauche = $_POST['date_embauche'] ?? '';
    $statut = $_POST['statut'] ?? 'actif';

    if ($nom === '' || $prenom === '' || $poste === '' || $salaire === '' || $email === '') {
        $error = "Merci de renseigner tous les champs obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } elseif (!in_array($statut, ['actif', 'conge', 'inactif'], true)) {
        $error = "Le statut sélectionné est invalide.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE personnel SET nom = ?, prenom = ?, poste = ?, salaire = ?, email = ?, telephone = ?, date_embauche = ?, statut = ? WHERE id = ?");
            $stmt->execute([
                $nom,
                $prenom,
                $poste,
                (float) $salaire,
                $email,
                $telephone,
                $date_embauche !== '' ? $date_embauche : null,
                $statut,
                $id
            ]);

            $message = "Collaborateur mis à jour avec succès.";

            $stmt = $pdo->prepare("SELECT * FROM personnel WHERE id = ?");
            $stmt->execute([$id]);
            $employe = $stmt->fetch();
        } catch (PDOException $e) {
            $error = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}

$anciennete = null;
if (!empty($employe['date_embauche'])) {
    $anciennete = (new DateTime($employe['date_embauche']))->diff(new DateTime())->y;
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">🧑‍💼 <?php echo escape($employe['prenom'] . ' ' . $employe['nom']); ?></h2>
        <a href="liste.php" class="btn btn-warning">← Retour</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="stats-grid" style="margin-bottom: 1.5rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #34d399 0%, #059669 100%);">
            <div class="stat-icon">💼</div>
            <div class="stat-value"><?php echo escape($employe['poste']); ?></div>
            <div class="stat-label">Poste actuel</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);">
            <div class="stat-icon">💶</div>
            <div class="stat-value"><?php echo number_format($employe['salaire'], 2, ',', ' '); ?> €</div>
            <div class="stat-label">Salaire mensuel</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%);">
            <div class="stat-icon">📅</div>
            <div class="stat-value"><?php echo $employe['date_embauche'] ? formatDate($employe['date_embauche']) : '—'; ?></div>
            <div class="stat-label">Date d'embauche</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);">
            <div class="stat-icon">⏳</div>
            <div class="stat-value"><?php echo $anciennete !== null ? $anciennete . ' an(s)' : '—'; ?></div>
            <div class="stat-label">Ancienneté estimée</div>
        </div>
    </div>

    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" class="form-control" required value="<?php echo escape($employe['nom']); ?>">
            </div>
            <div class="form-group">
                <label>Prénom *</label>
                <input type="text" name="prenom" class="form-control" required value="<?php echo escape($employe['prenom']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Poste *</label>
                <input type="text" name="poste" class="form-control" required value="<?php echo escape($employe['poste']); ?>">
            </div>
            <div class="form-group">
                <label>Salaire mensuel (€) *</label>
                <input type="number" name="salaire" class="form-control" min="0" step="0.01" required value="<?php echo escape($employe['salaire']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email professionnel *</label>
                <input type="email" name="email" class="form-control" required value="<?php echo escape($employe['email']); ?>">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="<?php echo escape($employe['telephone']); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date d'embauche</label>
                <input type="date" name="date_embauche" class="form-control" value="<?php echo escape($employe['date_embauche']); ?>">
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="actif" <?php echo $employe['statut'] === 'actif' ? 'selected' : ''; ?>>Actif</option>
                    <option value="conge" <?php echo $employe['statut'] === 'conge' ? 'selected' : ''; ?>>En congé</option>
                    <option value="inactif" <?php echo $employe['statut'] === 'inactif' ? 'selected' : ''; ?>>Inactif</option>
                </select>
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-start; margin: 1.5rem 0;">
            <?php if (hasPermission('paie', 'read')): ?>
                <a href="paie.php?personnel_id=<?php echo $employe['id']; ?>" class="btn btn-primary">💶 Historique paie</a>
            <?php endif; ?>
            <?php if (hasPermission('conges', 'read')): ?>
                <a href="conges.php?personnel_id=<?php echo $employe['id']; ?>" class="btn btn-secondary">🌴 Congés</a>
            <?php endif; ?>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">✅ Mettre à jour</button>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
