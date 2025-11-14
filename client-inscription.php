<?php
/**
 * CLIENT-INSCRIPTION.PHP - Création de compte client
 * Accessible par : Visiteurs non connectés
 * Redirige : Clients connectés vers catalogue
 */

session_start();
require_once 'config/database.php';
require_once 'config/auth.php';

// Si déjà connecté, rediriger
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'client') {
        redirectTo('catalogue.php');
    }

    redirectTo('dashboard.php');
}

// Paramètres de redirection
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
$vehicule_id = isset($_GET['vehicule_id']) ? intval($_GET['vehicule_id']) : 0;

$success = false;
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Cette adresse email est déjà utilisée.";
        } else {
            // Créer le compte
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, password, role, statut) 
                                  VALUES (?, ?, ?, ?, 'client', 'actif')");

            if ($stmt->execute([$nom, $prenom, $email, $password_hash])) {
                $success = true;
                $new_user_id = $pdo->lastInsertId();

                // Connecter automatiquement l'utilisateur
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'client';

                // Logger la connexion
                $ip = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $stmt = $pdo->prepare("INSERT INTO logs_connexion (utilisateur_id, action, ip_address, user_agent) 
                                      VALUES (?, 'connexion', ?, ?)");
                $stmt->execute([$new_user_id, $ip, $user_agent]);
            } else {
                $error = "Une erreur est survenue lors de la création du compte.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte client - PGI Automobile</title>
    <link rel="stylesheet" href="assets/css/public.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-page">
<div class="auth-wrapper">
    <a href="catalogue.php" class="back-button">← Retour au catalogue</a>

    <div class="auth-card">
        <div class="auth-header">
            <h1>📝 Créer un compte</h1>
            <p>Rejoignez-nous pour faire vos demandes</p>
        </div>

        <div class="auth-body">
            <?php if ($success): ?>
                <div class="success-message">
                    <h2>✅ Compte créé avec succès !</h2>
                    <p>Bienvenue <?php echo htmlspecialchars($_SESSION['prenom']); ?> ! Vous êtes maintenant connecté.</p>
                    <div class="success-buttons">
                        <?php if ($redirect === 'demande' && $vehicule_id > 0): ?>
                            <a href="demande.php?vehicule_id=<?php echo $vehicule_id; ?>" class="btn btn-primary">
                                Continuer ma demande
                            </a>
                        <?php else: ?>
                            <a href="catalogue.php" class="btn btn-primary">Voir le catalogue</a>
                            <a href="modules/clients/mes-demandes.php" class="btn btn-secondary">Mon espace</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if ($redirect === 'demande' && $vehicule_id > 0): ?>
                    <div class="auth-meta">
                        <p>🔒 <strong>Compte requis</strong><br>
                        Créez un compte gratuit pour faire une demande d'achat.
                        Vous serez ensuite redirigé automatiquement.</p>
                    </div>
                <?php else: ?>
                    <div class="info-box">
                        <p>ℹ️ Créez un compte gratuit pour suivre vos demandes d'achat
                        et être contacté plus rapidement.</p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nom">Nom <span class="required">*</span></label>
                        <input type="text" id="nom" name="nom" required value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom <span class="required">*</span></label>
                        <input type="text" id="prenom" name="prenom" required value="<?php echo isset($_POST['prenom']) ? htmlspecialchars($_POST['prenom']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe <span class="required">*</span></label>
                        <input type="password" id="password" name="password" required>
                        <div class="password-requirements">Minimum 6 caractères</div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe <span class="required">*</span></label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="submit-button">
                        🚀 Créer mon compte
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="auth-footer">
            <?php if ($success): ?>
                <p>Vous pouvez désormais accéder à votre espace et suivre vos demandes.</p>
            <?php else: ?>
                <p>Déjà inscrit ? <a href="login.php<?php echo ($redirect === 'demande' && $vehicule_id > 0) ? '?action=demande&vehicule_id=' . $vehicule_id : ''; ?>">Se connecter</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>