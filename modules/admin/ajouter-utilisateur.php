<?php
// Traiter le formulaire AVANT d'inclure le header
session_start();
require_once '../../config/database.php';
require_once '../../config/auth.php';

// Vérifier que c'est un admin
requireRole('admin');

$page_title = "Ajouter un utilisateur";
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $role = $_POST['role'] ?? 'vendeur';
    $telephone = $_POST['telephone'] ?? '';
    
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Tous les champs obligatoires doivent être remplis";
    } elseif ($password !== $password_confirm) {
        $error = "Les mots de passe ne correspondent pas";
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères";
    } else {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé";
            } else {
                // Hasher le mot de passe
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insérer l'utilisateur
                $sql = "INSERT INTO utilisateurs (nom, prenom, email, password, role, telephone, statut) 
                        VALUES (:nom, :prenom, :email, :password, :role, :telephone, 'actif')";
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':email' => $email,
                    ':password' => $password_hash,
                    ':role' => $role,
                    ':telephone' => $telephone
                ]);
                
                // Redirection AVANT tout output HTML
                header("Location: utilisateurs.php?success=1");
                exit;
            }
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
        <h2 class="card-title">➕ Ajouter un Utilisateur</h2>
        <a href="utilisateurs.php" class="btn btn-warning">← Retour à la liste</a>
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
                <label>Email *</label>
                <input type="email" name="email" class="form-control" required placeholder="jean.dupont@pgi-auto.com"
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" class="form-control" placeholder="06 12 34 56 78"
                       value="<?php echo isset($_POST['telephone']) ? htmlspecialchars($_POST['telephone']) : ''; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Mot de passe * (min. 8 caractères)</label>
                <input type="password" name="password" class="form-control" required 
                       placeholder="••••••••" minlength="8">
                <small style="color: #666;">Le mot de passe doit contenir au moins 8 caractères</small>
            </div>
            
            <div class="form-group">
                <label>Confirmer le mot de passe *</label>
                <input type="password" name="password_confirm" class="form-control" required 
                       placeholder="••••••••">
            </div>
        </div>
        
        <div class="form-group">
            <label>Rôle *</label>
            <select name="role" class="form-control" required>
                <option value="vendeur" <?php echo (isset($_POST['role']) && $_POST['role'] === 'vendeur') ? 'selected' : ''; ?>>
                    💼 Vendeur
                </option>
                <option value="gestionnaire_stock" <?php echo (isset($_POST['role']) && $_POST['role'] === 'gestionnaire_stock') ? 'selected' : ''; ?>>
                    📦 Gestionnaire de Stock
                </option>
                <option value="comptable" <?php echo (isset($_POST['role']) && $_POST['role'] === 'comptable') ? 'selected' : ''; ?>>
                    💰 Comptable
                </option>
                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>
                    👑 Administrateur
                </option>
            </select>
            <small style="color: #666;">
                <strong>Vendeur :</strong> Peut gérer clients et ventes<br>
                <strong>Gestionnaire :</strong> Peut gérer les véhicules et le stock<br>
                <strong>Comptable :</strong> Accès lecture aux ventes et statistiques<br>
                <strong>Admin :</strong> Accès total à toutes les fonctionnalités
            </small>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                ✅ Créer l'utilisateur
            </button>
        </div>
    </form>
</div>

<script>
// Vérifier que les mots de passe correspondent en temps réel
document.addEventListener('DOMContentLoaded', function() {
    const password = document.querySelector('input[name="password"]');
    const passwordConfirm = document.querySelector('input[name="password_confirm"]');
    
    passwordConfirm.addEventListener('input', function() {
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    });
    
    password.addEventListener('input', function() {
        if (password.value !== passwordConfirm.value && passwordConfirm.value) {
            passwordConfirm.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            passwordConfirm.setCustomValidity('');
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
