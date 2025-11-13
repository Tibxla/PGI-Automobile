<?php
/**
 * DEMANDE.PHP - Formulaire de demande d'achat de véhicule
 * Accessible uniquement par : Clients connectés
 * Redirige : Non-connectés vers login, Employés vers message d'erreur
 */

session_start();
require_once 'config/database.php';

// VÉRIFIER L'AUTHENTIFICATION
$est_connecte = isset($_SESSION['user_id']) && isset($_SESSION['role']);
$est_client = $est_connecte && $_SESSION['role'] === 'client';

// Si pas connecté, rediriger vers login
if (!$est_connecte) {
    $vehicule_id = isset($_GET['vehicule_id']) ? intval($_GET['vehicule_id']) : 0;
    header('Location: login.php?action=demande&vehicule_id=' . $vehicule_id);
    exit();
}

// Si connecté mais pas client, afficher message d'erreur
if (!$est_client) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Accès refusé - PGI Automobile</title>
        <link rel="stylesheet" href="assets/css/public.css">
    </head>
    <body>
    <div class="container">
        <div class="public-card public-card--centered">
            <h1>⚠️</h1>
            <h2>Accès réservé aux clients</h2>
            <p>Vous êtes connecté en tant que <strong><?php echo ucfirst($_SESSION['role']); ?></strong>.
                Cette page est uniquement accessible aux clients.</p>
            <div class="success-buttons">
                <a href="catalogue.php" class="btn btn-primary">← Retour au catalogue</a>
                <a href="index.php" class="btn btn-secondary">🏠 Tableau de bord</a>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit();
}

// Récupérer le véhicule
$vehicule_id = isset($_GET['vehicule_id']) ? intval($_GET['vehicule_id']) : 0;
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

    // Vérifier le téléphone
    $stmt = $pdo->prepare("SELECT telephone FROM utilisateurs WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_data = $stmt->fetch();
    $telephone = $user_data['telephone'] ?? '';

    // Si pas de téléphone, prendre celui du formulaire
    if (empty($telephone) && isset($_POST['telephone'])) {
        $telephone = trim($_POST['telephone']);

        // Mettre à jour le téléphone dans la base
        $stmt = $pdo->prepare("UPDATE utilisateurs SET telephone = ? WHERE id = ?");
        $stmt->execute([$telephone, $_SESSION['user_id']]);
    }

    if (empty($telephone)) {
        $error = "Le numéro de téléphone est obligatoire.";
    } else {
        // Insérer la demande
        $stmt = $pdo->prepare("INSERT INTO demandes_achat (vehicule_id, client_id, nom, prenom, email, telephone, message, statut) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, 'en_attente')");

        if ($stmt->execute([$vehicule_id, $_SESSION['user_id'], $nom, $prenom, $email, $telephone, $message])) {
            $success = true;
        } else {
            $error = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}

// Vérifier si téléphone existe
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

    <!-- CSS Externes -->
    <link rel="stylesheet" href="assets/css/public.css">
    <link rel="stylesheet" href="assets/css/demande.css">
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
                    <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="info-box">
                    <h3>ℹ️ Informations</h3>
                    <p>Votre demande sera envoyée avec vos informations de compte.
                        Notre équipe commerciale vous contactera rapidement.</p>
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