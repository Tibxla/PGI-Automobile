<?php
$page_title = "Ajouter un collaborateur";
include '../../includes/header.php';

requirePermission('rh', 'create');

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
            $stmt = $pdo->prepare("INSERT INTO personnel (nom, prenom, poste, salaire, email, telephone, date_embauche, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $nom,
                $prenom,
                $poste,
                (float) $salaire,
                $email,
                $telephone,
                $date_embauche !== '' ? $date_embauche : null,
                $statut
            ]);

            redirectTo('modules/rh/liste.php', ['success' => 1]);
        } catch (PDOException $e) {
            $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ Nouveau collaborateur</h2>
        <a href="liste.php" class="btn btn-warning">← Retour</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" class="form-control" required value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Prénom *</label>
                <input type="text" name="prenom" class="form-control" required value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Poste *</label>
                <input type="text" name="poste" class="form-control" required value="<?php echo htmlspecialchars($_POST['poste'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Salaire mensuel (€) *</label>
                <input type="number" name="salaire" class="form-control" min="0" step="0.01" required value="<?php echo htmlspecialchars($_POST['salaire'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email professionnel *</label>
                <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="telephone" class="form-control" value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date d'embauche</label>
                <input type="date" name="date_embauche" class="form-control" value="<?php echo htmlspecialchars($_POST['date_embauche'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="actif" <?php echo (($_POST['statut'] ?? '') === 'actif') ? 'selected' : ''; ?>>Actif</option>
                    <option value="conge" <?php echo (($_POST['statut'] ?? '') === 'conge') ? 'selected' : ''; ?>>En congé</option>
                    <option value="inactif" <?php echo (($_POST['statut'] ?? '') === 'inactif') ? 'selected' : ''; ?>>Inactif</option>
                </select>
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">✅ Enregistrer</button>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
