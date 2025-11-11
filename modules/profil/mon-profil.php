<?php
$page_title = "Mon profil";
include '../../includes/header.php';

$message = '';
$error = '';

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: /pgi-automobile/logout.php");
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_info') {
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        
        if (empty($nom) || empty($prenom) || empty($email)) {
            $error = "Les champs nom, prénom et email sont obligatoires";
        } else {
            try {
                // Vérifier si l'email est déjà utilisé par un autre utilisateur
                $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
                $stmt->execute([$email, $_SESSION['user_id']]);
                if ($stmt->fetch()) {
                    $error = "Cet email est déjà utilisé par un autre utilisateur";
                } else {
                    $sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$nom, $prenom, $email, $telephone, $_SESSION['user_id']]);
                    
                    // Mettre à jour la session
                    $_SESSION['user_nom'] = $nom;
                    $_SESSION['user_prenom'] = $prenom;
                    $_SESSION['user_email'] = $email;
                    
                    $message = "Informations mises à jour avec succès";
                    
                    // Recharger les données
                    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user = $stmt->fetch();
                }
            } catch (PDOException $e) {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    }
    
    if ($action === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $new_password_confirm = $_POST['new_password_confirm'] ?? '';
        
        if (empty($current_password) || empty($new_password) || empty($new_password_confirm)) {
            $error = "Tous les champs du mot de passe sont obligatoires";
        } elseif ($new_password !== $new_password_confirm) {
            $error = "Les nouveaux mots de passe ne correspondent pas";
        } elseif (strlen($new_password) < 8) {
            $error = "Le nouveau mot de passe doit contenir au moins 8 caractères";
        } elseif (!password_verify($current_password, $user['password'])) {
            $error = "Le mot de passe actuel est incorrect";
        } else {
            try {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE utilisateurs SET password = ? WHERE id = ?");
                $stmt->execute([$new_password_hash, $_SESSION['user_id']]);
                
                $message = "Mot de passe modifié avec succès";
            } catch (PDOException $e) {
                $error = "Erreur : " . $e->getMessage();
            }
        }
    }
}

// Récupérer les statistiques de l'utilisateur
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as nb_connexions,
        MAX(created_at) as derniere_connexion
    FROM logs_connexion 
    WHERE utilisateur_id = ? AND action = 'connexion'
");
$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetch();
?>

<h1 style="color: white; text-align: center; margin-bottom: 2rem;">
    👤 Mon Profil
</h1>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Informations générales -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📋 Informations Personnelles</h2>
    </div>
    
    <div style="display: flex; gap: 2rem; margin-bottom: 2rem; align-items: center;">
        <img src="<?php echo getAvatar(); ?>" alt="Avatar" 
             style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid var(--primary);">
        <div>
            <h3 style="margin: 0; color: var(--dark);"><?php echo getFullName(); ?></h3>
            <p style="margin: 0.5rem 0; color: #666;">
                <span class="role-badge role-<?php echo $user['role']; ?>">
                    <?php echo getRoleLabel($user['role']); ?>
                </span>
            </p>
            <p style="margin: 0.5rem 0; color: #666;">
                📧 <?php echo escape($user['email']); ?>
            </p>
            <?php if ($user['telephone']): ?>
                <p style="margin: 0.5rem 0; color: #666;">
                    📞 <?php echo escape($user['telephone']); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="stats-grid" style="margin-top: 2rem;">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stat-icon">🔐</div>
            <div class="stat-value"><?php echo $stats['nb_connexions']; ?></div>
            <div class="stat-label">Connexions</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="stat-icon">⏰</div>
            <div class="stat-value">
                <?php echo $stats['derniere_connexion'] ? timeSince($stats['derniere_connexion']) : 'N/A'; ?>
            </div>
            <div class="stat-label">Dernière connexion</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="stat-icon">📅</div>
            <div class="stat-value"><?php echo formatDate($user['created_at']); ?></div>
            <div class="stat-label">Membre depuis</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="stat-icon">
                <?php echo $user['statut'] === 'actif' ? '✓' : '✗'; ?>
            </div>
            <div class="stat-value"><?php echo ucfirst($user['statut']); ?></div>
            <div class="stat-label">Statut du compte</div>
        </div>
    </div>
</div>

<!-- Modifier les informations -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">✏️ Modifier mes informations</h2>
    </div>
    
    <form method="POST">
        <input type="hidden" name="action" value="update_info">
        
        <div class="form-row">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" class="form-control" required 
                       value="<?php echo escape($user['nom']); ?>">
            </div>
            
            <div class="form-group">
                <label>Prénom *</label>
                <input type="text" name="prenom" class="form-control" required 
                       value="<?php echo escape($user['prenom']); ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" required 
                       value="<?php echo escape($user['email']); ?>">
            </div>
            
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" class="form-control" 
                       value="<?php echo escape($user['telephone']); ?>">
            </div>
        </div>
        
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary">
                💾 Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<!-- Changer le mot de passe -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🔒 Changer mon mot de passe</h2>
    </div>
    
    <form method="POST">
        <input type="hidden" name="action" value="change_password">
        
        <div class="form-group">
            <label>Mot de passe actuel *</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Nouveau mot de passe * (min. 8 caractères)</label>
                <input type="password" name="new_password" class="form-control" required minlength="8">
            </div>
            
            <div class="form-group">
                <label>Confirmer le nouveau mot de passe *</label>
                <input type="password" name="new_password_confirm" class="form-control" required>
            </div>
        </div>
        
        <div style="text-align: center;">
            <button type="submit" class="btn btn-warning">
                🔐 Modifier le mot de passe
            </button>
        </div>
    </form>
</div>

<script>
// Vérifier que les mots de passe correspondent
document.addEventListener('DOMContentLoaded', function() {
    const newPassword = document.querySelector('input[name="new_password"]');
    const newPasswordConfirm = document.querySelector('input[name="new_password_confirm"]');
    
    if (newPassword && newPasswordConfirm) {
        newPasswordConfirm.addEventListener('input', function() {
            if (newPassword.value !== newPasswordConfirm.value) {
                newPasswordConfirm.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                newPasswordConfirm.setCustomValidity('');
            }
        });
    }
});
</script>

<?php include '../../includes/footer.php'; ?>
