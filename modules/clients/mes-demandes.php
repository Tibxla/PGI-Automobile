<?php
session_start();
require_once '../../config/auth.php';
require_once '../../config/database.php';

// Vérifier que c'est bien un client
if ($_SESSION['role'] !== 'client') {
    header('Location: ../../acces-refuse.php');
    exit();
}

// Récupérer toutes les demandes du client connecté
$query = "SELECT da.*, v.marque, v.modele, v.annee, v.prix_vente, v.couleur, v.kilometrage
          FROM demandes_achat da
          INNER JOIN vehicules v ON da.vehicule_id = v.id
          WHERE da.client_id = ?
          ORDER BY da.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$demandes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Demandes - PGI Automobile</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
        }

        .demandes-grid {
            display: grid;
            gap: 25px;
        }

        .demande-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .demande-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .demande-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .demande-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .demande-id {
            font-size: 14px;
            opacity: 0.9;
        }

        .demande-date {
            font-size: 13px;
            opacity: 0.8;
        }

        .demande-vehicule {
            font-size: 24px;
            font-weight: bold;
        }

        .demande-body {
            padding: 20px;
        }

        .demande-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .demande-price {
            font-size: 28px;
            font-weight: bold;
            color: #48bb78;
            text-align: center;
            margin: 20px 0;
        }

        .demande-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
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

        .message-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .message-label {
            font-size: 12px;
            color: #999;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .message-content {
            font-size: 14px;
            color: #333;
            font-style: italic;
        }

        .no-demandes {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .no-demandes h2 {
            color: #666;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .no-demandes p {
            color: #999;
            margin-bottom: 25px;
        }

        .btn-primary {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .status-description {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .status-description h3 {
            font-size: 16px;
            color: #333;
            margin-bottom: 15px;
        }

        .status-list {
            display: grid;
            gap: 10px;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<?php include '../../includes/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1>📋 Mes Demandes d'Achat</h1>
        <p>Suivez l'état de vos demandes de véhicules</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total</h3>
            <div class="number"><?php echo count($demandes); ?></div>
        </div>
        <?php
        $stats = ['en_attente' => 0, 'en_cours' => 0, 'acceptee' => 0];
        foreach ($demandes as $row) {
            if (isset($stats[$row['statut']])) {
                $stats[$row['statut']]++;
            }
        }
        ?>
        <div class="stat-card">
            <h3>⏳ En attente</h3>
            <div class="number"><?php echo $stats['en_attente']; ?></div>
        </div>
        <div class="stat-card">
            <h3>🔄 En cours</h3>
            <div class="number"><?php echo $stats['en_cours']; ?></div>
        </div>
        <div class="stat-card">
            <h3>✅ Acceptées</h3>
            <div class="number"><?php echo $stats['acceptee']; ?></div>
        </div>
    </div>

    <!-- Explications des statuts -->
    <div class="status-description">
        <h3>ℹ️ Signification des statuts</h3>
        <div class="status-list">
            <div class="status-item">
                <span class="badge badge-en_attente">⏳ En attente</span>
                <span>Votre demande a été reçue et sera traitée prochainement</span>
            </div>
            <div class="status-item">
                <span class="badge badge-en_cours">🔄 En cours</span>
                <span>Notre équipe est en train de traiter votre demande</span>
            </div>
            <div class="status-item">
                <span class="badge badge-acceptee">✅ Acceptée</span>
                <span>Votre demande a été acceptée, nous vous contacterons bientôt</span>
            </div>
            <div class="status-item">
                <span class="badge badge-refusee">❌ Refusée</span>
                <span>Malheureusement, nous ne pouvons pas donner suite à cette demande</span>
            </div>
            <div class="status-item">
                <span class="badge badge-finalisee">🏁 Finalisée</span>
                <span>La transaction a été finalisée avec succès</span>
            </div>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="demandes-grid">
        <?php if (count($demandes) > 0): ?>
            <?php foreach ($demandes as $demande): ?>
                <div class="demande-card">
                    <div class="demande-header">
                        <div class="demande-header-top">
                            <span class="demande-id">Demande #<?php echo $demande['id']; ?></span>
                            <span class="demande-date">
                                    📅 <?php echo date('d/m/Y', strtotime($demande['created_at'])); ?>
                                </span>
                        </div>
                        <div class="demande-vehicule">
                            🚗 <?php echo htmlspecialchars($demande['marque'] . ' ' . $demande['modele']); ?>
                        </div>
                    </div>

                    <div class="demande-body">
                        <div class="demande-info-grid">
                            <div class="info-item">
                                <div class="info-label">Année</div>
                                <div class="info-value"><?php echo $demande['annee']; ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Couleur</div>
                                <div class="info-value"><?php echo htmlspecialchars($demande['couleur']); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kilométrage</div>
                                <div class="info-value"><?php echo number_format($demande['kilometrage'], 0, ',', ' '); ?> km</div>
                            </div>
                        </div>

                        <div class="demande-price">
                            <?php echo number_format($demande['prix_vente'], 0, ',', ' '); ?> €
                        </div>

                        <?php if (!empty($demande['message'])): ?>
                            <div class="message-box">
                                <div class="message-label">Votre message</div>
                                <div class="message-content">
                                    "<?php echo nl2br(htmlspecialchars($demande['message'])); ?>"
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="demande-footer">
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
                            <?php if ($demande['date_traitement']): ?>
                                <span style="font-size: 13px; color: #999;">
                                        Mis à jour le <?php echo date('d/m/Y', strtotime($demande['date_traitement'])); ?>
                                    </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-demandes">
                <h2>Aucune demande pour le moment</h2>
                <p>Vous n'avez pas encore fait de demande d'achat.</p>
                <a href="../../catalogue.php" class="btn-primary">📋 Voir le catalogue</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
</body>
</html>