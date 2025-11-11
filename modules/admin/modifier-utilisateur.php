<?php
$page_title = "Modifier un utilisateur";
include '../../includes/header.php';

// Vérifier que c'est un admin
requireRole('admin');

$message = '';
$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: utilisateurs.php");
    exit;
}

// Récupération de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: utilisateurs.php");
    exit;
}

// Empêcher de se modifier soi-même (sauf pour certains champs)
$is_self = ($user['id'] === $_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? $user['role'];
    $statut = $_POST['statut'] ?? $user['statut'];
    $nouveau_password = trim($_POST['nouveau_password'] ?? '');
    $confirmer_password = trim($_POST['confirmer_password'] ?? '');
    
    // Validations
    if (empty($nom) || empty($prenom)) {
        $error = "Le nom et le prénom sont obligatoires";
    } elseif (empty($email)) {
        $error = "L'email est obligatoire";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'email n'est pas valide";
    } elseif ($is_self && $statut !== 'actif') {
        $error = "Vous ne pouvez pas désactiver votre propre compte";
    } elseif ($is_self && $role !== 'admin') {
        $error = "Vous ne pouvez pas changer votre propre rôle";
    } else {
        try {
            // Vérifier si l'email existe déjà pour un autre utilisateur
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            if ($stmt->fetch()) {
                $error = "Cet email est déjà utilisé par un autre utilisateur";
            } else {
                // Mise à jour de l'utilisateur
                $sql = "UPDATE utilisateurs SET 
                        nom = :nom, 
                        prenom = :prenom, 
                        email = :email, 
                        role = :role, 
                        statut = :statut";
                
                $params = [
                    ':nom' => $nom,
                    ':prenom' => $prenom,
                    ':email' => $email,
                    ':role' => $role,
                    ':statut' => $statut,
                    ':id' => $id
                ];
                
                // Si un nouveau mot de passe est fourni
                if (!empty($nouveau_password)) {
                    if ($nouveau_password !== $confirmer_password) {
                        $error = "Les mots de passe ne correspondent pas";
                    } elseif (strlen($nouveau_password) < 6) {
                        $error = "Le mot de passe doit contenir au moins 6 caractères";
                    } else {
                        $sql .= ", password = :password";
                        $params[':password'] = password_hash($nouveau_password, PASSWORD_DEFAULT);
                    }
                }
                
                if (empty($error)) {
                    $sql .= " WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    
                    $message = "Utilisateur modifié avec succès !";
                    
                    // Recharger les données
                    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
                    $stmt->execute([$id]);
                    $user = $stmt->fetch();
                    
                    // Si c'est l'utilisateur actuel qui modifie ses propres infos, mettre à jour la session
                    if ($is_self) {
                        $_SESSION['user_nom'] = $nom;
                        $_SESSION['user_prenom'] = $prenom;
                        $_SESSION['user_email'] = $email;
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}

// Statistiques de l'utilisateur
$stmt = $pdo->prepare("
    SELECT 
        COUNT(DISTINCT CASE WHEN action = 'connexion' THEN id END) as nb_connexions,
        MAX(CASE WHEN action = 'connexion' THEN created_at END) as derniere_connexion
    FROM logs_connexion
    WHERE utilisateur_id = ?
");
$stmt->execute([$id]);
$stats = $stmt->fetch();

// Dernières activités
$stmt = $pdo->prepare("
    SELECT * FROM logs_connexion 
    WHERE utilisateur_id = ? 
    ORDER BY created_at DESC 
    LIMIT 10
");
$stmt->execute([$id]);
$activites = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1 style="color: white; display: flex; align-items: center; gap: 10px;">
        ✏️ Modifier l'Utilisateur
    </h1>
    <a href="utilisateurs.php" class="btn btn-warning">
        ← Retour à la liste
    </a>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($is_self): ?>
    <div class="alert alert-warning">
        <strong>⚠️ Attention !</strong> Vous modifiez votre propre compte. 
        Vous ne pouvez pas changer votre rôle ni désactiver votre compte.
    </div>
<?php endif; ?>

<!-- Statistiques de l'utilisateur -->
<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="stat-icon">👤</div>
        <div class="stat-value"><?php echo escape($user['prenom'] . ' ' . $user['nom']); ?></div>
        <div class="stat-label">Utilisateur #<?php echo $user['id']; ?></div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">
            <?php
            $role_icons = [
                'admin' => '👑',
                'vendeur' => '💼',
                'gestionnaire_stock' => '📦',
                'comptable' => '💰'
            ];
            echo $role_icons[$user['role']] ?? '👤';
            ?>
        </div>
        <div class="stat-value"><?php echo getRoleLabel($user['role']); ?></div>
        <div class="stat-label">Rôle actuel</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="stat-icon">🔑</div>
        <div class="stat-value"><?php echo $stats['nb_connexions']; ?></div>
        <div class="stat-label">Connexions</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="stat-icon">
            <?php echo $user['statut'] === 'actif' ? '✅' : '❌'; ?>
        </div>
        <div class="stat-value">
            <?php 
            if ($user['statut'] === 'actif') echo 'Actif';
            elseif ($user['statut'] === 'inactif') echo 'Inactif';
            else echo 'Suspendu';
            ?>
        </div>
        <div class="stat-label">Statut</div>
    </div>
</div>

<!-- Formulaire de modification -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📝 Informations de l'Utilisateur</h2>
    </div>
    
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom" class="form-control" required 
                       value="<?php echo escape($user['nom']); ?>" placeholder="Dupont">
            </div>
            
            <div class="form-group">
                <label>Prénom *</label>
                <input type="text" name="prenom" class="form-control" required 
                       value="<?php echo escape($user['prenom']); ?>" placeholder="Jean">
            </div>
        </div>
        
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" required 
                   value="<?php echo escape($user['email']); ?>" placeholder="jean.dupont@example.com">
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Rôle</label>
                <select name="role" class="form-control" <?php echo $is_self ? 'disabled' : ''; ?>>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>👑 Administrateur</option>
                    <option value="vendeur" <?php echo $user['role'] === 'vendeur' ? 'selected' : ''; ?>>💼 Vendeur</option>
                    <option value="gestionnaire_stock" <?php echo $user['role'] === 'gestionnaire_stock' ? 'selected' : ''; ?>>📦 Gestionnaire Stock</option>
                    <option value="comptable" <?php echo $user['role'] === 'comptable' ? 'selected' : ''; ?>>💰 Comptable</option>
                </select>
                <?php if ($is_self): ?>
                    <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                    <small style="color: #f59e0b;">Vous ne pouvez pas modifier votre propre rôle</small>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control" <?php echo $is_self ? 'disabled' : ''; ?>>
                    <option value="actif" <?php echo $user['statut'] === 'actif' ? 'selected' : ''; ?>>✅ Actif</option>
                    <option value="inactif" <?php echo $user['statut'] === 'inactif' ? 'selected' : ''; ?>>❌ Inactif</option>
                    <option value="suspendu" <?php echo $user['statut'] === 'suspendu' ? 'selected' : ''; ?>>⏸ Suspendu</option>
                </select>
                <?php if ($is_self): ?>
                    <input type="hidden" name="statut" value="<?php echo $user['statut']; ?>">
                    <small style="color: #f59e0b;">Vous ne pouvez pas modifier votre propre statut</small>
                <?php endif; ?>
            </div>
        </div>
        
        <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e5e7eb;">
        
        <h3 style="color: var(--primary); margin-bottom: 1rem;">🔒 Modifier le Mot de Passe (Optionnel)</h3>
        
        <div class="form-row">
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" name="nouveau_password" class="form-control" 
                       placeholder="Laisser vide pour ne pas changer">
                <small style="color: #666;">Minimum 6 caractères</small>
            </div>
            
            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="confirmer_password" class="form-control" 
                       placeholder="Confirmer le nouveau mot de passe">
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                ✅ Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<!-- Dernières activités -->
<?php if (count($activites) > 0): ?>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📊 Dernières Activités</h2>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Date/Heure</th>
                    <th>Action</th>
                    <th>Adresse IP</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activites as $activite): ?>
                <tr>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            <strong><?php echo date('d/m/Y', strtotime($activite['created_at'])); ?></strong>
                            <small style="color: #666;"><?php echo date('H:i:s', strtotime($activite['created_at'])); ?></small>
                        </div>
                    </td>
                    <td>
                        <?php
                        $action_class = 'badge-stock';
                        $action_text = ucfirst($activite['action']);
                        
                        if ($activite['action'] === 'connexion') {
                            $action_class = 'badge-stock';
                            $action_text = '✅ Connexion';
                        } elseif ($activite['action'] === 'deconnexion') {
                            $action_class = 'badge-reserve';
                            $action_text = '↩ Déconnexion';
                        } elseif ($activite['action'] === 'echec_connexion') {
                            $action_class = 'badge-vendu';
                            $action_text = '❌ Échec';
                        }
                        ?>
                        <span class="badge <?php echo $action_class; ?>">
                            <?php echo $action_text; ?>
                        </span>
                    </td>
                    <td><code style="font-size: 0.85rem;"><?php echo escape($activite['ip_address']); ?></code></td>
                    <td>
                        <small style="color: #666; max-width: 300px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                               title="<?php echo escape($activite['user_agent']); ?>">
                            <?php echo escape($activite['user_agent']); ?>
                        </small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Informations complémentaires -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">ℹ️ Informations Complémentaires</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <div>
            <strong style="color: var(--primary);">Inscription :</strong><br>
            <span style="color: #666;"><?php echo formatDate($user['created_at']); ?></span>
        </div>
        
        <div>
            <strong style="color: var(--primary);">Dernière connexion :</strong><br>
            <span style="color: #666;">
                <?php 
                if ($stats['derniere_connexion']) {
                    echo formatDate($stats['derniere_connexion']) . ' (' . timeSince($stats['derniere_connexion']) . ')';
                } else {
                    echo 'Jamais connecté';
                }
                ?>
            </span>
        </div>
        
        <div>
            <strong style="color: var(--primary);">Nombre de connexions :</strong><br>
            <span style="color: #666;"><?php echo $stats['nb_connexions']; ?> connexion(s)</span>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
