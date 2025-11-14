<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/auth.php';
require_once '../../config/database.php';

// Vérifier que c'est bien un client
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
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

// Configuration pour le header unifié
$page_title = 'Mes Demandes';
$additional_css = ['assets/css/mes-demandes.css'];
include '../../includes/header-client.php';
?>

    <!-- En-tête de page -->
    <div class="page-header" style="background: white; border-radius: 15px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div>
            <h1 style="color: var(--dark); margin: 0;">📋 Mes Demandes d'Achat</h1>
            <p style="color: #666; margin-top: 0.5rem; margin-bottom: 0;">Suivez l'état de vos demandes de véhicules</p>
        </div>
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

<?php include '../../includes/footer.php'; ?>