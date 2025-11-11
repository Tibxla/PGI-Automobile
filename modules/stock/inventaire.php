<?php
$page_title = "Inventaire Stock";
include '../../includes/header.php';

// Statistiques globales
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN statut = 'stock' THEN 1 ELSE 0 END) as en_stock,
        SUM(CASE WHEN statut = 'reserve' THEN 1 ELSE 0 END) as reserve,
        SUM(CASE WHEN statut = 'vendu' THEN 1 ELSE 0 END) as vendu,
        SUM(prix_achat) as valeur_achat,
        SUM(prix_vente) as valeur_vente,
        SUM(prix_vente - prix_achat) as marge_potentielle
    FROM vehicules
");
$stats = $stmt->fetch();

// Stock par type
$stmt = $pdo->query("
    SELECT 
        type_vehicule,
        COUNT(*) as nombre,
        SUM(prix_vente) as valeur_totale,
        AVG(prix_vente) as prix_moyen
    FROM vehicules
    WHERE statut = 'stock'
    GROUP BY type_vehicule
");
$stock_par_type = $stmt->fetchAll();

// Stock par carburant
$stmt = $pdo->query("
    SELECT 
        carburant,
        COUNT(*) as nombre,
        SUM(prix_vente) as valeur_totale
    FROM vehicules
    WHERE statut = 'stock'
    GROUP BY carburant
");
$stock_par_carburant = $stmt->fetchAll();

// Véhicules anciens (plus de 6 mois en stock)
$stmt = $pdo->query("
    SELECT *, DATEDIFF(CURDATE(), date_arrivee) as jours_stock
    FROM vehicules
    WHERE statut = 'stock' AND DATEDIFF(CURDATE(), date_arrivee) > 180
    ORDER BY date_arrivee ASC
");
$vehicules_anciens = $stmt->fetchAll();
?>

<h1 style="color: white; text-align: center; margin-bottom: 2rem;">
    📦 Inventaire et Gestion du Stock
</h1>

<!-- Statistiques globales -->
<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="stat-icon">🚙</div>
        <div class="stat-value"><?php echo $stats['en_stock']; ?></div>
        <div class="stat-label">Véhicules en stock</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">🔒</div>
        <div class="stat-value"><?php echo $stats['reserve']; ?></div>
        <div class="stat-label">Véhicules réservés</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="stat-icon">💰</div>
        <div class="stat-value"><?php echo formatPrice($stats['valeur_vente']); ?></div>
        <div class="stat-label">Valeur stock (vente)</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="stat-icon">📈</div>
        <div class="stat-value"><?php echo formatPrice($stats['marge_potentielle']); ?></div>
        <div class="stat-label">Marge potentielle</div>
    </div>
</div>

<!-- Répartition par type -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📊 Stock par Type de Véhicule</h2>
    </div>
    
    <?php if (count($stock_par_type) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Nombre</th>
                        <th>Valeur Totale</th>
                        <th>Prix Moyen</th>
                        <th>% du Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stock_par_type as $type): 
                        $pourcentage = ($type['nombre'] / $stats['en_stock']) * 100;
                        $icon = '';
                        switch($type['type_vehicule']) {
                            case 'berline': $icon = '🚗'; break;
                            case 'suv': $icon = '🚙'; break;
                            case 'sportive': $icon = '🏎️'; break;
                            case 'utilitaire': $icon = '🚐'; break;
                            case 'citadine': $icon = '🚕'; break;
                        }
                    ?>
                        <tr>
                            <td><strong><?php echo $icon . ' ' . ucfirst($type['type_vehicule']); ?></strong></td>
                            <td><?php echo $type['nombre']; ?></td>
                            <td><?php echo formatPrice($type['valeur_totale']); ?></td>
                            <td><?php echo formatPrice($type['prix_moyen']); ?></td>
                            <td>
                                <div style="background: #e5e7eb; border-radius: 10px; height: 20px; position: relative;">
                                    <div style="background: linear-gradient(90deg, #667eea, #764ba2); width: <?php echo $pourcentage; ?>%; height: 100%; border-radius: 10px;"></div>
                                    <span style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); font-size: 0.8rem; font-weight: bold;">
                                        <?php echo number_format($pourcentage, 1); ?>%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">Aucun véhicule en stock</p>
    <?php endif; ?>
</div>

<!-- Répartition par carburant -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">⚡ Stock par Type de Carburant</h2>
    </div>
    
    <?php if (count($stock_par_carburant) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Carburant</th>
                        <th>Nombre</th>
                        <th>Valeur Totale</th>
                        <th>% du Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stock_par_carburant as $carb): 
                        $pourcentage = ($carb['nombre'] / $stats['en_stock']) * 100;
                    ?>
                        <tr>
                            <td><strong><?php echo ucfirst($carb['carburant']); ?></strong></td>
                            <td><?php echo $carb['nombre']; ?></td>
                            <td><?php echo formatPrice($carb['valeur_totale']); ?></td>
                            <td>
                                <span class="badge badge-stock"><?php echo number_format($pourcentage, 1); ?>%</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Alertes - Véhicules anciens -->
<?php if (count($vehicules_anciens) > 0): ?>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">⚠️ Alertes - Véhicules en Stock Longue Durée</h2>
    </div>
    
    <div class="alert alert-warning">
        <strong>⚠️ Attention !</strong> <?php echo count($vehicules_anciens); ?> véhicule(s) en stock depuis plus de 6 mois
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Véhicule</th>
                    <th>Date d'arrivée</th>
                    <th>Jours en stock</th>
                    <th>Prix</th>
                    <th>Recommandation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicules_anciens as $veh): ?>
                    <tr>
                        <td><strong><?php echo escape($veh['marque'] . ' ' . $veh['modele']); ?></strong></td>
                        <td><?php echo formatDate($veh['date_arrivee']); ?></td>
                        <td>
                            <span class="badge badge-danger">
                                <?php echo $veh['jours_stock']; ?> jours
                            </span>
                        </td>
                        <td><?php echo formatPrice($veh['prix_vente']); ?></td>
                        <td>
                            <?php if ($veh['jours_stock'] > 270): ?>
                                🔴 Envisager une remise importante
                            <?php elseif ($veh['jours_stock'] > 180): ?>
                                🟠 Proposer une promotion
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include '../../includes/footer.php'; ?>