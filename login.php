<?php
session_start();
require_once 'config/database.php';
require_once 'config/auth.php';

// Si déjà connecté, rediriger selon le rôle
if (isset($_SESSION['user_id'])) {
    if (isset($_GET['action']) && $_GET['action'] === 'demande' && isset($_GET['vehicule_id'])) {
        redirectTo('demande.php', ['vehicule_id' => (int) $_GET['vehicule_id']]);
    }

    if ($_SESSION['role'] === 'client') {
        redirectTo('catalogue.php');
    }

    redirectTo('dashboard.php');
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$vehicule_id = isset($_GET['vehicule_id']) ? intval($_GET['vehicule_id']) : 0;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND statut = 'actif'");
        $stmt->execute([$email]);

        if ($user = $stmt->fetch()) {
            if (password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Log de connexion
                $ip = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $stmt = $pdo->prepare("INSERT INTO logs_connexion (utilisateur_id, action, ip_address, user_agent) VALUES (?, 'connexion', ?, ?)");
                $stmt->execute([$user['id'], $ip, $user_agent]);

                // Mettre à jour la dernière connexion
                $stmt = $pdo->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);

                // Redirection intelligente
                if ($action === 'demande' && $vehicule_id > 0) {
                    if ($user['role'] === 'client') {
                        redirectTo('demande.php', ['vehicule_id' => $vehicule_id]);
                    }

                    redirectTo('dashboard.php');
                }

                if ($user['role'] === 'client') {
                    redirectTo('catalogue.php');
                }

                redirectTo('dashboard.php');
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - PGI Automobile</title>
    <link rel="stylesheet" href="assets/css/public.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body class="auth-page">
<div class="auth-wrapper">
    <a href="catalogue.php" class="back-button">← Retour au catalogue</a>

    <div class="auth-card">
        <div class="auth-header">
            <h1>🔐 Connexion</h1>
            <p>Accédez à votre espace personnel</p>
        </div>

        <div class="auth-body">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($action === 'demande' && $vehicule_id > 0): ?>
                <div class="auth-meta">
                    🔒 <strong>Connexion requise</strong><br>
                    Pour faire une demande d'achat, vous devez être connecté avec un compte client.
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="submit-button">
                    🚀 Se connecter
                </button>
            </form>

            <div class="auth-divider">
                <span>ou</span>
            </div>

            <div class="auth-signup">
                <h3>📝 Pas encore de compte ?</h3>
                <p>Créez un compte client gratuit en quelques secondes</p>
                <a href="client-inscription.php<?php echo ($action === 'demande' && $vehicule_id > 0) ? '?redirect=demande&vehicule_id=' . $vehicule_id : ''; ?>" class="btn btn-secondary">
                    Créer un compte client
                </a>
            </div>

            <?php if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false): ?>
                <div class="auth-test-accounts">
                    <h4>🧪 Comptes de test (développement)</h4>
                    <ul>
                        <li><strong>Admin:</strong> admin@pgi-auto.com / password123</li>
                        <li><strong>Vendeur:</strong> julie@pgi-auto.com / password123</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <div class="auth-footer">
            <p>Besoin d'un accès employé ? Contactez votre administrateur PGI Automobile.</p>
        </div>
    </div>
</div>
</body>
</html>