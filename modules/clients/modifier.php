<?php
/**
 * MODIFIER.PHP - Modifier les informations d'un client
 * Module: Clients
 * Accessible par: Admin, Vendeurs (avec permission)
 */

// Traiter le formulaire AVANT d'inclure le header
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

// Vérifier authentification et permissions
requireAuth();
requirePermission('clients', 'update');

$page_title = "Modifier un client";
$message = '';
$error = '';

// Récupérer l'ID du client
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: liste.php?error=id_invalid");
    exit;
}

// Récupérer les données du client
$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) {
    header("Location: liste.php?error=client_not_found");
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $code_postal = $_POST['code_postal'] ?? '';
    $date_naissance = $_POST['date_naissance'] ?? null;

    if (empty($nom) || empty($prenom)) {
        $error = "Le nom et le prénom sont obligatoires";
    } else {
        try {
            $sql = "UPDATE clients SET 
                    nom = :nom, 
                    prenom = :prenom, 
                    email = :email, 
                    telephone = :telephone, 
                    adresse = :adresse, 
                    ville = :ville, 
                    code_postal = :code_postal, 
                    date_naissance = :date_naissance 
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':adresse' => $adresse,
                ':ville' => $ville,
                ':code_postal' => $code_postal,
                ':date_naissance' => $date_naissance ?: null,
                ':id' => $id
            ]);

            // Redirection AVANT tout output
            header("Location: liste.php?updated=1");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}

// Inclure le header
include '../../includes/header.php';
?>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">✏️ Modifier un Client</h2>
            <a href="liste.php" class="btn btn-warning">← Retour à la liste</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="nom" class="form-control" required
                           value="<?php echo htmlspecialchars($client['nom']); ?>">
                </div>

                <div class="form-group">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" class="form-control" required
                           value="<?php echo htmlspecialchars($client['prenom']); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo htmlspecialchars($client['email']); ?>">
                </div>

                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="tel" name="telephone" class="form-control"
                           value="<?php echo htmlspecialchars($client['telephone']); ?>">
                </div>

                <div class="form-group">
                    <label>Date de naissance</label>
                    <input type="date" name="date_naissance" class="form-control"
                           value="<?php echo htmlspecialchars($client['date_naissance']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Adresse</label>
                <textarea name="adresse" class="form-control" rows="3"><?php echo htmlspecialchars($client['adresse']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ville</label>
                    <input type="text" name="ville" class="form-control"
                           value="<?php echo htmlspecialchars($client['ville']); ?>">
                </div>

                <div class="form-group">
                    <label>Code postal</label>
                    <input type="text" name="code_postal" class="form-control"
                           value="<?php echo htmlspecialchars($client['code_postal']); ?>">
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                    ✅ Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

<?php include '../../includes/footer.php'; ?>