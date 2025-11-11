<?php
session_start();
require_once 'config/database.php';

// Si déjà connecté, rediriger
if (isset($_SESSION['user_id'])) {
    // Vérifier s'il y a une redirection demandée
    if (isset($_GET['redirect']) && $_GET['redirect'] === 'demande' && isset($_GET['vehicule_id'])) {
        header('Location: demande.php?vehicule_id=' . intval($_GET['vehicule_id']));
    } else {
        header('Location: catalogue.php');
    }
    exit();
}

// Récupérer les paramètres de redirection
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
$vehicule_id = isset($_GET['vehicule_id']) ? intval($_GET['vehicule_id']) : 0;

$success = false;
$error = '';

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
        $query = "SELECT id FROM utilisateurs WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Cette adresse email est déjà utilisée.";
        } else {
            // Hasher le mot de passe
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insérer le nouveau client
            $query = "INSERT INTO utilisateurs (nom, prenom, email, password, role, statut) 
                      VALUES (?, ?, ?, ?, 'client', 'actif')";
            $stmt = $pdo->prepare($query);

            if ($stmt->execute([$nom, $prenom, $email, $password_hash])) {
                $success = true;
                $new_user_id = $pdo->lastInsertId();

                // Connexion automatique
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['nom'] = $nom;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'client';

                // Log de connexion
                $ip = $_SERVER['REMOTE_ADDR'];
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
                $log_query = "INSERT INTO logs_connexion (utilisateur_id, action, ip_address, user_agent) 
                             VALUES (?, 'connexion', ?, ?)";
                $log_stmt = $pdo->prepare($log_query);
                $log_stmt->execute([$new_user_id, $ip, $user_agent]);

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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 500px;
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .back-button:hover {
            transform: translateX(-5px);
        }

        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .form-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .form-content {
            padding: 40px 30px;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }

        .info-box p {
            color: #0c5ba8;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .required {
            color: #e74c3c;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .submit-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .login-link p {
            color: #666;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .success-message {
            text-align: center;
            padding: 20px;
        }

        .success-message h2 {
            color: #48bb78;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .success-message p {
            color: #666;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .success-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-secondary {
            background: #48bb78;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="catalogue.php" class="back-button">← Retour au catalogue</a>

    <div class="form-container">
        <div class="form-header">
            <h1>📝 Créer un compte</h1>
            <p>Rejoignez-nous pour faire vos demandes</p>
        </div>

        <div class="form-content">
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
                    <div class="alert alert-error">
                        ⚠️ <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($redirect === 'demande' && $vehicule_id > 0): ?>
                    <div class="info-box">
                        <p>🔒 <strong>Compte requis</strong><br>
                            Créez un compte gratuit pour faire une demande d'achat. Vous serez ensuite redirigé automatiquement.</p>
                    </div>
                <?php else: ?>
                    <div class="info-box">
                        <p>ℹ️ Créez un compte gratuit pour suivre vos demandes d'achat et être contacté plus rapidement.</p>
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
                        <div class="password-requirements">
                            Minimum 6 caractères
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirmer le mot de passe <span class="required">*</span></label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="submit-button">
                        🚀 Créer mon compte
                    </button>

                    <div class="login-link">
                        <p>Vous avez déjà un compte ?
                            <a href="login.php<?php echo ($redirect === 'demande' && $vehicule_id > 0) ? '?action=demande&vehicule_id=' . $vehicule_id : ''; ?>">
                                Se connecter
                            </a>
                        </p>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>