<?php
session_start();
require_once 'config/database.php';

// Si déjà connecté, rediriger selon le rôle
if (isset($_SESSION['user_id'])) {
    if (isset($_GET['action']) && $_GET['action'] === 'demande' && isset($_GET['vehicule_id'])) {
        header('Location: demande.php?vehicule_id=' . intval($_GET['vehicule_id']));
    } else {
        if ($_SESSION['role'] === 'client') {
            header('Location: catalogue.php');
        } else {
            header('Location: index.php');
        }
    }
    exit();
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
                        header('Location: demande.php?vehicule_id=' . $vehicule_id);
                    } else {
                        // Les non-clients ne peuvent pas faire de demande, redirection vers leur dashboard
                        header('Location: dashboard.php');
                    }
                } elseif ($user['role'] === 'client') {
                    header('Location: catalogue.php');
                } else {
                    // Tous les autres rôles vont vers dashboard.php qui redirige selon le rôle
                    header('Location: dashboard.php');
                }
                exit();
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

        .back-link {
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

        .back-link:hover {
            transform: translateX(-5px);
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .login-content {
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

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #999;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        .signup-section {
            text-align: center;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .signup-section h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .signup-section p {
            color: #666;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .btn-signup {
            display: inline-block;
            padding: 12px 30px;
            background: #48bb78;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            background: #38a169;
        }

        .test-accounts {
            margin-top: 20px;
            padding: 20px;
            background: #fff9e6;
            border-radius: 8px;
            border: 1px solid #ffd700;
        }

        .test-accounts h4 {
            color: #d97706;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .test-accounts ul {
            list-style: none;
            padding: 0;
        }

        .test-accounts li {
            color: #666;
            font-size: 13px;
            margin-bottom: 5px;
            padding-left: 20px;
            position: relative;
        }

        .test-accounts li::before {
            content: '👤';
            position: absolute;
            left: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="catalogue.php" class="back-link">← Retour au catalogue</a>

    <div class="login-container">
        <div class="login-header">
            <h1>🔐 Connexion</h1>
            <p>Accédez à votre espace personnel</p>
        </div>

        <div class="login-content">
            <?php if ($error): ?>
                <div class="alert alert-error">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($action === 'demande' && $vehicule_id > 0): ?>
                <div class="info-box">
                    <p>🔒 <strong>Connexion requise</strong><br>
                        Pour faire une demande d'achat, vous devez être connecté avec un compte client.</p>
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

            <div class="divider">
                <span>OU</span>
            </div>

            <div class="signup-section">
                <h3>📝 Pas encore de compte ?</h3>
                <p>Créez un compte client gratuit en quelques secondes</p>
                <a href="client-inscription.php<?php echo ($action === 'demande' && $vehicule_id > 0) ? '?redirect=demande&vehicule_id=' . $vehicule_id : ''; ?>" class="btn-signup">
                    Créer un compte client
                </a>
            </div>

            <?php if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false): ?>
                <div class="test-accounts">
                    <h4>🧪 Comptes de test (développement)</h4>
                    <ul>
                        <li><strong>Admin:</strong> admin@pgi-auto.com / password123</li>
                        <li><strong>Vendeur:</strong> julie@pgi-auto.com / password123</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>