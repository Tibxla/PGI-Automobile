<?php
$page_title = "Demandes d'achat";
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

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: transform 0.3s;
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-card.en_attente {
        border-left-color: #f59e0b;
    }

    .stat-card.en_cours {
        border-left-color: #3b82f6;
    }

    .stat-card.acceptee {
        border-left-color: #10b981;
    }

    .stat-card.refusee {
        border-left-color: #ef4444;
    }

    .stat-card.finalisee {
        border-left-color: #6b7280;
    }

    .stat-card h3 {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .stat-card .number {
        font-size: 36px;
        font-weight: bold;
        color: #333;
    }

    .filters {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .filters h3 {
        margin-bottom: 15px;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 20px;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }

    .filter-btn:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }

    .filter-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .demandes-table {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
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

    .action-btn {
        padding: 6px 15px;
        background: #667eea;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 13px;
        transition: background 0.3s;
    }

    .action-btn:hover {
        background: #5568d3;
    }

    .vehicule-info {
        font-weight: 600;
        color: #333;
    }

    .client-info {
        color: #666;
        font-size: 14px;
    }

    .date-info {
        color: #999;
        font-size: 13px;
    }

    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .no-data h3 {
        font-size: 20px;
        margin-bottom: 10px;
    }
</style>

<div class="container">
    <div class="page-header">
        <h1>📋 Gestion des Demandes d'Achat</h1>
        <p>Visualisez et traitez toutes les demandes de vos clients</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card en_attente" onclick="window.location.href='?statut=en_attente'">
            <h3>⏳ En attente</h3>
            <div class="number"><?php echo isset($stats['en_attente']) ? $stats['en_attente'] : 0; ?></div>
        </div>

        <div class="stat-card en_cours" onclick="window.location.href='?statut=en_cours'">
            <h3>🔄 En cours</h3>
            <div class="number"><?php echo isset($stats['en_cours']) ? $stats['en_cours'] : 0; ?></div>
        </div>

        <div class="stat-card acceptee" onclick="window.location.href='?statut=acceptee'">
            <h3>✅ Acceptées</h3>
            <div class="number"><?php echo isset($stats['acceptee']) ? $stats['acceptee'] : 0; ?></div>
        </div>

        <div class="stat-card refusee" onclick="window.location.href='?statut=refusee'">
            <h3>❌ Refusées</h3>
            <div class="number"><?php echo isset($stats['refusee']) ? $stats['refusee'] : 0; ?></div>
        </div>

        <div class="stat-card finalisee" onclick="window.location.href='?statut=finalisee'">
            <h3>🏁 Finalisées</h3>
            <div class="number"><?php echo isset($stats['finalisee']) ? $stats['finalisee'] : 0; ?></div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters">
        <h3>🔍 Filtrer par statut</h3>
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
    <div class="demandes-table">
        <?php if (count($demandes) > 0): ?>
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
        <?php else: ?>
            <div class="no-data">
                <h3>Aucune demande trouvée</h3>
                <p>Il n'y a pas de demandes avec ce filtre.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>