<?php
session_start();
require_once '../../config/auth.php';
require_once '../../config/database.php';

// Vérifier les permissions (vendeur ou admin)
if (!in_array($_SESSION['role'], ['vendeur', 'admin'])) {
    header('Location: ../../acces-refuse.php');
    exit();
}

$demande_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Traitement du formulaire de mise à jour
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_statut = $_POST['statut'];
    $notes_gestionnaire = trim($_POST['notes_gestionnaire']);

    // Mettre à jour la demande
    $update_query = "UPDATE demandes_achat 
                     SET statut = ?, 
                         notes_gestionnaire = ?, 
                         traitee_par = ?,
                         date_traitement = NOW()
                     WHERE id = ?";
    $stmt = $pdo->prepare($update_query);

    if ($stmt->execute([$nouveau_statut, $notes_gestionnaire, $_SESSION['user_id'], $demande_id])) {
        $success = true;
    } else {
        $error = "Erreur lors de la mise à jour.";
    }
}

// Récupérer les détails de la demande
$query = "SELECT da.*, 
          v.marque, v.modele, v.annee, v.couleur, v.kilometrage, 
          v.prix_vente, v.carburant, v.type_vehicule, v.immatriculation,
          u.nom as gestionnaire_nom, u.prenom as gestionnaire_prenom,
          c.nom as client_nom, c.prenom as client_prenom
          FROM demandes_achat da
          INNER JOIN vehicules v ON da.vehicule_id = v.id
          LEFT JOIN utilisateurs u ON da.traitee_par = u.id
          LEFT JOIN utilisateurs c ON da.client_id = c.id
          WHERE da.id = ?";

$stmt = $pdo->prepare($query);
$stmt->execute([$demande_id]);
$demande = $stmt->fetch();

if (!$demande) {
    header('Location: demandes-liste.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Demande #<?php echo $demande_id; ?> - PGI Automobile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .detail-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        .detail-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .detail-card h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .info-group {
            margin-bottom: 25px;
        }

        .info-group h3 {
            font-size: 14px;
            color: #999;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-en_attente {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-en_cours {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-acceptee {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-refusee {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-finalisee {
            background: #e5e7eb;
            color: #374151;
        }

        .vehicule-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .vehicule-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .vehicule-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .vehicule-detail-item {
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

        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #333;
        }

        .contact-item:last-child {
            margin-bottom: 0;
        }

        .message-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .message-box h3 {
            font-size: 14px;
            color: #999;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .message-content {
            color: #333;
            line-height: 1.6;
            font-style: italic;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }

        .submit-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
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

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 32px;
            color: #333;
        }

        .timeline {
            margin-top: 20px;
        }

        .timeline-item {
            padding-left: 25px;
            position: relative;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-left: 2px solid #e0e0e0;
        }

        .timeline-item:last-child {
            border-left: none;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
        }

        .timeline-date {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
        }

        .timeline-content {
            color: #333;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .detail-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<?php include '../../includes/header.php'; ?>

<div class="container">
    <a href="demandes-liste.php" class="back-button">← Retour à la liste</a>

    <div class="page-header">
        <h1>📋 Demande #<?php echo $demande_id; ?></h1>
        <span class="badge badge-<?php echo $demande['statut']; ?>">
                <?php
                $statuts = [
                    'en_attente' => '⏳ En attente',
                    'en_cours' => '🔄 En cours',
                    'acceptee' => '✅ Acceptée',
                    'refusee' => '❌ Refusée',
                    'finalisee' => '🏁 Finalisée'
                ];
                echo $statuts[$demande['statut']];
                ?>
            </span>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">
            ✅ La demande a été mise à jour avec succès !
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error">
            ⚠️ <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="detail-container">
        <!-- Colonne principale -->
        <div>
            <!-- Informations du véhicule -->
            <div class="detail-card">
                <h2>🚗 Véhicule demandé</h2>
                <div class="vehicule-card">
                    <h3><?php echo htmlspecialchars($demande['marque'] . ' ' . $demande['modele']); ?></h3>
                    <div class="vehicule-details">
                        <div class="vehicule-detail-item">
                            <span>📅</span>
                            <span><?php echo $demande['annee']; ?></span>
                        </div>
                        <div class="vehicule-detail-item">
                            <span>🎨</span>
                            <span><?php echo htmlspecialchars($demande['couleur']); ?></span>
                        </div>
                        <div class="vehicule-detail-item">
                            <span>⛽</span>
                            <span><?php echo ucfirst($demande['carburant']); ?></span>
                        </div>
                        <div class="vehicule-detail-item">
                            <span>🛣️</span>
                            <span><?php echo number_format($demande['kilometrage'], 0, ',', ' '); ?> km</span>
                        </div>
                        <div class="vehicule-detail-item">
                            <span>🚙</span>
                            <span><?php echo ucfirst($demande['type_vehicule']); ?></span>
                        </div>
                        <div class="vehicule-detail-item">
                            <span>🔖</span>
                            <span><?php echo htmlspecialchars($demande['immatriculation']); ?></span>
                        </div>
                    </div>
                    <div class="vehicule-price">
                        <?php echo number_format($demande['prix_vente'], 0, ',', ' '); ?> €
                    </div>
                </div>
            </div>

            <!-- Informations du client -->
            <div class="detail-card">
                <h2>👤 Informations Client</h2>
                <div class="info-group">
                    <h3>Nom complet</h3>
                    <div class="info-value">
                        <?php echo htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']); ?>
                    </div>
                </div>

                <div class="contact-info">
                    <div class="contact-item">
                        <span>📧</span>
                        <strong>Email:</strong>
                        <a href="mailto:<?php echo htmlspecialchars($demande['email']); ?>">
                            <?php echo htmlspecialchars($demande['email']); ?>
                        </a>
                    </div>
                    <div class="contact-item">
                        <span>📱</span>
                        <strong>Téléphone:</strong>
                        <a href="tel:<?php echo htmlspecialchars($demande['telephone']); ?>">
                            <?php echo htmlspecialchars($demande['telephone']); ?>
                        </a>
                    </div>
                </div>

                <?php if ($demande['client_id']): ?>
                    <div class="info-group">
                        <h3>Compte client</h3>
                        <div class="info-value">
                            ✅ Client inscrit sur la plateforme
                        </div>
                    </div>
                <?php else: ?>
                    <div class="info-group">
                        <h3>Compte client</h3>
                        <div class="info-value">
                            ℹ️ Visiteur non inscrit
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($demande['message'])): ?>
                    <div class="message-box">
                        <h3>💬 Message du client</h3>
                        <div class="message-content">
                            "<?php echo nl2br(htmlspecialchars($demande['message'])); ?>"
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Colonne latérale -->
        <div>
            <!-- Gestion de la demande -->
            <div class="detail-card">
                <h2>⚙️ Gestion</h2>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="statut">Statut de la demande</label>
                        <select id="statut" name="statut" required>
                            <option value="en_attente" <?php echo $demande['statut'] === 'en_attente' ? 'selected' : ''; ?>>
                                ⏳ En attente
                            </option>
                            <option value="en_cours" <?php echo $demande['statut'] === 'en_cours' ? 'selected' : ''; ?>>
                                🔄 En cours
                            </option>
                            <option value="acceptee" <?php echo $demande['statut'] === 'acceptee' ? 'selected' : ''; ?>>
                                ✅ Acceptée
                            </option>
                            <option value="refusee" <?php echo $demande['statut'] === 'refusee' ? 'selected' : ''; ?>>
                                ❌ Refusée
                            </option>
                            <option value="finalisee" <?php echo $demande['statut'] === 'finalisee' ? 'selected' : ''; ?>>
                                🏁 Finalisée
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes_gestionnaire">Notes internes</label>
                        <textarea id="notes_gestionnaire" name="notes_gestionnaire" placeholder="Notes privées visibles uniquement par l'équipe..."><?php echo htmlspecialchars($demande['notes_gestionnaire']); ?></textarea>
                    </div>

                    <button type="submit" class="submit-button">
                        💾 Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Informations de traitement -->
            <div class="detail-card">
                <h2>📊 Informations</h2>

                <div class="info-group">
                    <h3>Date de création</h3>
                    <div class="info-value">
                        <?php echo date('d/m/Y à H:i', strtotime($demande['created_at'])); ?>
                    </div>
                </div>

                <?php if ($demande['date_traitement']): ?>
                    <div class="info-group">
                        <h3>Dernière mise à jour</h3>
                        <div class="info-value">
                            <?php echo date('d/m/Y à H:i', strtotime($demande['date_traitement'])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($demande['gestionnaire_nom']): ?>
                    <div class="info-group">
                        <h3>Traité par</h3>
                        <div class="info-value">
                            <?php echo htmlspecialchars($demande['gestionnaire_prenom'] . ' ' . $demande['gestionnaire_nom']); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>