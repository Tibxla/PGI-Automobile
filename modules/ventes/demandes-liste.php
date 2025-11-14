<?php
$page_title = "Demandes d'achat";
$additional_css = ['assets/css/demandes-liste.css'];
include '../../includes/header.php';

requirePermission('demandes', 'read');

// Récupérer les filtres
$filtre_statut = isset($_GET['statut']) ? $_GET['statut'] : '';

// Construire la requête
$query = "SELECT da.*, v.marque, v.modele, v.prix_vente, v.annee,
          u.nom as gestionnaire_nom, u.prenom as gestionnaire_prenom
          FROM demandes_achat da
          INNER JOIN vehicules v ON da.vehicule_id = v.id
          LEFT JOIN utilisateurs u ON da.traitee_par = u.id";

if (!empty($filtre_statut)) {
    $query .= " WHERE da.statut = ?";
}

$query .= " ORDER BY 
    CASE da.statut
        WHEN 'en_attente' THEN 1
        WHEN 'en_cours' THEN 2
        WHEN 'acceptee' THEN 3
        WHEN 'finalisee' THEN 4
        WHEN 'refusee' THEN 5
    END,
    da.created_at DESC";

if (!empty($filtre_statut)) {
    $stmt = $pdo->prepare($query);
    $stmt->execute([$filtre_statut]);
} else {
    $stmt = $pdo->query($query);
}

$demandes = $stmt->fetchAll();

// Compter les demandes par statut
$stats_query = "SELECT statut, COUNT(*) as count FROM demandes_achat GROUP BY statut";
$stats_result = $pdo->query($stats_query);
$stats = [];
while ($row = $stats_result->fetch()) {
    $stats[$row['statut']] = $row['count'];
}
?>

<h1 style="color: white; text-align: center; margin-bottom: 2rem;">
    📋 Gestion des Demandes d'Achat
</h1>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);" data-filter="en_attente">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?php echo isset($stats['en_attente']) ? $stats['en_attente'] : 0; ?></div>
        <div class="stat-label">En attente</div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);" data-filter="en_cours">
        <div class="stat-icon">🔄</div>
        <div class="stat-value"><?php echo isset($stats['en_cours']) ? $stats['en_cours'] : 0; ?></div>
        <div class="stat-label">En cours</div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);" data-filter="acceptee">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?php echo isset($stats['acceptee']) ? $stats['acceptee'] : 0; ?></div>
        <div class="stat-label">Acceptées</div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);" data-filter="refusee">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?php echo isset($stats['refusee']) ? $stats['refusee'] : 0; ?></div>
        <div class="stat-label">Refusées</div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);" data-filter="finalisee">
        <div class="stat-icon">🏁</div>
        <div class="stat-value"><?php echo isset($stats['finalisee']) ? $stats['finalisee'] : 0; ?></div>
        <div class="stat-label">Finalisées</div>
    </div>
</div>

<!-- Filtres -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🔍 Filtrer par statut</h2>
    </div>

    <div class="filter-buttons">
        <a href="demandes-liste.php" class="filter-btn <?php echo empty($filtre_statut) ? 'active' : ''; ?>">
            Toutes
        </a>
        <a href="?statut=en_attente" class="filter-btn <?php echo $filtre_statut === 'en_attente' ? 'active' : ''; ?>">
            En attente
        </a>
        <a href="?statut=en_cours" class="filter-btn <?php echo $filtre_statut === 'en_cours' ? 'active' : ''; ?>">
            En cours
        </a>
        <a href="?statut=acceptee" class="filter-btn <?php echo $filtre_statut === 'acceptee' ? 'active' : ''; ?>">
            Acceptées
        </a>
        <a href="?statut=refusee" class="filter-btn <?php echo $filtre_statut === 'refusee' ? 'active' : ''; ?>">
            Refusées
        </a>
        <a href="?statut=finalisee" class="filter-btn <?php echo $filtre_statut === 'finalisee' ? 'active' : ''; ?>">
            Finalisées
        </a>
    </div>
</div>

<!-- Tableau des demandes -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📋 Liste des Demandes d'Achat</h2>
    </div>

    <?php if (count($demandes) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Véhicule</th>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Traité par</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($demandes as $demande): ?>
                    <tr>
                        <td><strong>#<?php echo $demande['id']; ?></strong></td>
                        <td>
                            <div class="vehicule-info">
                                <?php echo htmlspecialchars($demande['marque'] . ' ' . $demande['modele']); ?>
                            </div>
                            <div class="date-info">
                                <?php echo $demande['annee']; ?> • <?php echo number_format($demande['prix_vente'], 0, ',', ' '); ?> €
                            </div>
                        </td>
                        <td>
                            <div class="vehicule-info">
                                <?php echo htmlspecialchars($demande['prenom'] . ' ' . $demande['nom']); ?>
                            </div>
                        </td>
                        <td>
                            <div class="client-info">
                                📧 <?php echo htmlspecialchars($demande['email']); ?><br>
                                📱 <?php echo htmlspecialchars($demande['telephone']); ?>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <?php echo date('d/m/Y', strtotime($demande['created_at'])); ?><br>
                                <?php echo date('H:i', strtotime($demande['created_at'])); ?>
                            </div>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <?php if ($demande['gestionnaire_nom']): ?>
                                <div class="client-info">
                                    <?php echo htmlspecialchars($demande['gestionnaire_prenom'] . ' ' . $demande['gestionnaire_nom']); ?>
                                </div>
                            <?php else: ?>
                                <div class="date-info">Non assigné</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="demandes-detail.php?id=<?php echo $demande['id']; ?>" class="action-btn">
                                👁️ Détails
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">Aucune demande avec ce filtre</p>
    <?php endif; ?>
</div>

<script>
// Gestion des clics sur les stat-cards pour filtrer les demandes
document.querySelectorAll('.stat-card[data-filter]').forEach(function(card) {
    card.addEventListener('click', function() {
        var filter = this.getAttribute('data-filter');
        window.location.href = '?statut=' + filter;
    });
});
</script>

<?php include '../../includes/footer.php'; ?>