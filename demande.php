<?php
session_start();
require_once 'config/database.php';

// VÉRIFIER SI L'UTILISATEUR EST CONNECTÉ EN TANT QUE CLIENT
$est_connecte = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$est_client = $est_connecte && $_SESSION['role'] === 'client';

// Si pas connecté, rediriger vers la page de connexion
if (!$est_connecte) {
    $vehicule_id = isset($_GET['vehicule_id']) ? intval($_GET['vehicule_id']) : 0;
    header('Location: login.php?action=demande&vehicule_id=' . $vehicule_id);
    exit();
}

// Si connecté mais pas client
if (!$est_client) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Accès refusé - PGI Automobile</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .message-box {
                background: white;
                padding: 40px;
                border-radius: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                text-align: center;
                max-width: 500px;
            }
            .message-box h1 { font-size: 48px; margin-bottom: 20px; }
            .message-box h2 { color: #333; margin-bottom: 15px; }
            .message-box p { color: #666; margin-bottom: 30px; line-height: 1.6; }
            .btn {
                display: inline-block;
                padding: 12px 30px;
                background: #667eea;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                margin: 5px;
            }
            .btn:hover { background: #5568d3; }
        </style>
    </head>
    <body>
    <div class="message-box">
        <h1>⚠️</h1>
        <h2>Accès réservé aux clients</h2>
        <p>Vous êtes connecté en tant que <strong><?php echo ucfirst($_SESSION['role']); ?></strong>. Cette page est uniquement accessible aux clients.</p>
        <a href="catalogue.php" class="btn">← Retour au catalogue</a>
        <a href="index.php" class="btn">🏠 Tableau de bord</a>
    </div>
    </body>
    </html>
    <?php
    exit();
}

// Récupérer l'ID du véhicule
$vehicule_id = isset($_GET['vehicule_id']) ? intval($_GET['vehicule_id']) : 0;

// Récupérer les informations du véhicule
$vehicule = null;
if ($vehicule_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ? AND statut = 'stock'");
    $stmt->execute([$vehicule_id]);
    $vehicule = $stmt->fetch();
}

if (!$vehicule) {
    header('Location: catalogue.php');
    exit();
}

// Traitement du formulaire
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);

    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
    $email = $_SESSION['email'];

    // Vérifier si le client a un téléphone
    $stmt = $pdo->prepare("SELECT telephone FROM utilisateurs WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_data = $stmt->fetch();
    $telephone = $user_data['telephone'] ?? '';

    // Si pas de téléphone, prendre celui du formulaire
    if (empty($telephone) && isset($_POST['telephone'])) {
        $telephone = trim($_POST['telephone']);

        // Mettre à jour le téléphone
        $stmt = $pdo->prepare("UPDATE utilisateurs SET telephone = ? WHERE id = ?");
        $stmt->execute([$telephone, $_SESSION['user_id']]);
    }

    if (empty($telephone)) {
        $error = "Le numéro de téléphone est obligatoire.";
    } else {
        // Insérer la demande
        $stmt = $pdo->prepare("INSERT INTO demandes_achat (vehicule_id, client_id, nom, prenom, email, telephone, message, statut) VALUES (?, ?, ?, ?, ?, ?, ?, 'en_attente')");

        if ($stmt->execute([$vehicule_id, $_SESSION['user_id'], $nom, $prenom, $email, $telephone, $message])) {
            $success = true;
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}

// Vérifier si le téléphone existe
$stmt = $pdo->prepare("SELECT telephone FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$phone_data = $stmt->fetch();
$has_phone = !empty($phone_data['telephone']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'achat - <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container { max-width: 900px; margin: 0 auto; }
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
        .back-button:hover { transform: translateX(-5px); }
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .form-header h1 { font-size: 28px; margin-bottom: 10px; }
        .user-info {
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: left;
        }
        .user-info p { font-size: 14px; margin-bottom: 5px; }
        .vehicule-summary {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .vehicule-summary h2 { font-size: 22px; margin-bottom: 10px; }
        .vehicule-summary-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .summary-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        .vehicule-price {
            font-size: 32px;
            font-weight: bold;
            margin-top: 15px;
        }
        .form-content { padding: 40px; }
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group { margin-bottom: 25px; }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        .required { color: #e74c3c; }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
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
            transition: transform 0.3s;
        }
        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .info-box h3 {
            color: #667eea;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .info-box p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        .success-message {
            text-align: center;
            padding: 40px 20px;
        }
        .success-message h2 {
            color: #48bb78;
            font-size: 32px;
            margin-bottom: 15px;
        }
        .success-message p {
            color: #666;
            font-size: 18px;
            margin-bottom: 30px;
        }
        .success-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
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
            <h1>📩 Demande d'Achat</h1>

            <div class="user-info">
                <p><strong>👤 Connecté en tant que:</strong></p>
                <p><?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?></p>
                <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
            </div>

            <div class="vehicule-summary">
                <h2><?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?></h2>
                <div class="vehicule-summary-info">
                    <div class="summary-item">
                        <span>📅</span>
                        <span><?php echo $vehicule['annee']; ?></span>
                    </div>
                    <div class="summary-item">
                        <span>🎨</span>
                        <span><?php echo htmlspecialchars($vehicule['couleur']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>⛽</span>
                        <span><?php echo ucfirst($vehicule['carburant']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>🛣️</span>
                        <span><?php echo number_format($vehicule['kilometrage'], 0, ',', ' '); ?> km</span>
                    </div>
                </div>
                <div class="vehicule-price">
                    <?php echo number_format($vehicule['prix_vente'], 0, ',', ' '); ?> €
                </div>
            </div>
        </div>

        <div class="form-content">
            <?php if ($success): ?>
                <div class="success-message">
                    <h2>✅ Demande envoyée avec succès !</h2>
                    <p>Merci pour votre intérêt. Notre équipe commerciale vous contactera dans les plus brefs délais.</p>
                    <div class="success-buttons">
                        <a href="catalogue.php" class="btn btn-primary">← Retour au catalogue</a>
                        <a href="modules/clients/mes-demandes.php" class="btn btn-secondary">Voir mes demandes</a>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        ⚠️ <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="info-box">
                    <h3>ℹ️ Informations</h3>
                    <p>Votre demande sera envoyée avec vos informations de compte. Notre équipe commerciale vous contactera rapidement.</p>
                </div>

                <form method="POST" action="">
                    <?php if (!$has_phone): ?>
                        <div class="form-group">
                            <label for="telephone">Votre téléphone <span class="required">*</span></label>
                            <input type="tel" id="telephone" name="telephone" required placeholder="Ex: 0612345678">
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="message">Votre message (optionnel)</label>
                        <textarea id="message" name="message" placeholder="Précisez vos questions, disponibilités pour un essai, conditions de financement souhaitées..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    </div>

                    <button type="submit" class="submit-button">
                        📤 Envoyer ma demande
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>