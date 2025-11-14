<?php
// Traiter le formulaire AVANT d'inclure le header
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

// Vérifier l'authentification et permissions
requireAuth();
requirePermission('clients', 'create');

$page_title = "Ajouter un client";
$message = '';
$error = '';

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
            $sql = "INSERT INTO clients (nom, prenom, email, telephone, adresse, ville, code_postal, date_naissance) 
                    VALUES (:nom, :prenom, :email, :telephone, :adresse, :ville, :code_postal, :date_naissance)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone,
                ':adresse' => $adresse,
                ':ville' => $ville,
                ':code_postal' => $code_postal,
                ':date_naissance' => $date_naissance ?: null
            ]);
            
            // Redirection AVANT tout output
            header("Location: liste.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}

// Maintenant on peut inclure le header
include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ Ajouter un Client</h2>
        <a href="liste.php" class="btn btn-warning">← Retour à la liste</a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" class="form-control" required placeholder="Dupont"
                       value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Prénom *</label>
                <input type="text" name="prenom" class="form-control" required placeholder="Jean"
                       value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="jean.dupont@email.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" class="form-control" placeholder="06 12 34 56 78"
                       value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" class="form-control"
                       value="<?php echo isset($_POST['date_naissance']) ? htmlspecialchars($_POST['date_naissance']) : ''; ?>">
            </div>
        </div>
        
        <div class="form-group">
            <label>Adresse</label>
            <textarea name="adresse" class="form-control" rows="3" placeholder="12 rue de la Paix"><?php echo isset($_POST['adresse']) ? htmlspecialchars($_POST['adresse']) : ''; ?></textarea>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Ville</label>
                <input type="text" name="ville" class="form-control" placeholder="Paris"
                       value="<?php echo isset($_POST['ville']) ? htmlspecialchars($_POST['ville']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Code postal</label>
                <input type="text" name="code_postal" class="form-control" placeholder="75001"
                       value="<?php echo isset($_POST['code_postal']) ? htmlspecialchars($_POST['code_postal']) : ''; ?>">
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                ✅ Enregistrer le client
            </button>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>