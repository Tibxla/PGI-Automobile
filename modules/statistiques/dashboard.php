<?php
$page_title = "Statistiques";
include '../../includes/header.php';

// Statistiques mensuelles des 6 derniers mois
$stmt = $pdo->query("
    SELECT 
        DATE_FORMAT(date_vente, '%Y-%m') as mois,
        COUNT(*) as nb_ventes,
        SUM(prix_vente) as ca,
        SUM(marge) as marge_totale,
        AVG(prix_vente) as prix_moyen
    FROM ventes
    WHERE date_vente >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(date_vente, '%Y-%m')
    ORDER BY mois DESC
");
$stats_mensuelles = $stmt->fetchAll();

// Top 5 des véhicules les plus vendus (marques)
$stmt = $pdo->query("
    SELECT 
        ve.marque,
        COUNT(*) as nb_ventes,
        SUM(v.prix_vente) as ca_total,
        AVG(v.prix_vente) as prix_moyen
    FROM ventes v
    JOIN vehicules ve ON v.vehicule_id = ve.id
    GROUP BY ve.marque
    ORDER BY nb_ventes DESC
    LIMIT 5
");
$top_marques = $stmt->fetchAll();

// Performance commerciale
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total_ventes,
        SUM(prix_vente) as ca_total,
        AVG(prix_vente) as panier_moyen,
        SUM(marge) as marge_totale,
        AVG(marge) as marge_moyenne
    FROM ventes
    WHERE YEAR(date_vente) = YEAR(CURDATE())
");
$perf_annee = $stmt->fetch();

// Taux de rotation du stock
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total_vendus,
        AVG(DATEDIFF(v.date_vente, ve.date_arrivee)) as duree_moyenne_stock
    FROM ventes v
    JOIN vehicules ve ON v.vehicule_id = ve.id
    WHERE v.date_vente >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
");
$rotation_stock = $stmt->fetch();

// Top clients
$stmt = $pdo->query("
    SELECT 
        c.nom,
        c.prenom,
        COUNT(v.id) as nb_achats,
        SUM(v.prix_vente) as total_depense
    FROM clients c
    JOIN ventes v ON c.id = v.client_id
    GROUP BY c.id
    HAVING nb_achats > 0
    ORDER BY total_depense DESC
    LIMIT 5
");
$top_clients = $stmt->fetchAll();
?>

<h1 style="color: white; text-align: center; margin-bottom: 2rem;">
    📊 Tableau de Bord Statistiques
</h1>

<!-- Performances de l'année -->
<div class="stats-grid">
    <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?php echo $perf_annee['total_ventes'] ?? 0; ?></div>
        <div class="stat-label">Ventes <?php echo date('Y'); ?></div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <div class="stat-icon">💰</div>
        <div class="stat-value"><?php echo formatPrice($perf_annee['ca_total'] ?? 0); ?></div>
        <div class="stat-label">CA <?php echo date('Y'); ?></div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <div class="stat-icon">🛒</div>
        <div class="stat-value"><?php echo formatPrice($perf_annee['panier_moyen'] ?? 0); ?></div>
        <div class="stat-label">Panier moyen</div>
    </div>
    
    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <div class="stat-icon">📈</div>
        <div class="stat-value"><?php echo formatPrice($perf_annee['marge_totale'] ?? 0); ?></div>
        <div class="stat-label">Marge totale</div>
    </div>
</div>

<!-- Évolution mensuelle -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📈 Évolution des Ventes (6 derniers mois)</h2>
    </div>
    
    <?php if (count($stats_mensuelles) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Mois</th>
                        <th>Nb Ventes</th>
                        <th>Chiffre d'Affaires</th>
                        <th>Marge</th>
                        <th>Prix Moyen</th>
                        <th>Marge Moyenne</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats_mensuelles as $stat): 
                        $mois_label = strftime('%B %Y', strtotime($stat['mois'] . '-01'));
                        $marge_pct = $stat['ca'] > 0 ? ($stat['marge_totale'] / ($stat['ca'] - $stat['marge_totale'])) * 100 : 0;
                    ?>
                        <tr>
                            <td><strong><?php echo $mois_label; ?></strong></td>
                            <td><?php echo $stat['nb_ventes']; ?></td>
                            <td><strong style="color: green;"><?php echo formatPrice($stat['ca']); ?></strong></td>
                            <td>
                                <span style="color: green;">
                                    <?php echo formatPrice($stat['marge_totale']); ?>
                                    <small>(<?php echo number_format($marge_pct, 1); ?>%)</small>
                                </span>
                            </td>
                            <td><?php echo formatPrice($stat['prix_moyen']); ?></td>
                            <td><?php echo formatPrice($stat['marge_totale'] / $stat['nb_ventes']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">Aucune donnée disponible</p>
    <?php endif; ?>
</div>

<!-- Top marques -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🏆 Top 5 des Marques Vendues</h2>
    </div>
    
    <?php if (count($top_marques) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Marque</th>
                        <th>Nb Ventes</th>
                        <th>CA Total</th>
                        <th>Prix Moyen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rang = 1;
                    foreach ($top_marques as $marque): 
                        $medal = '';
                        if ($rang == 1) $medal = '🥇';
                        elseif ($rang == 2) $medal = '🥈';
                        elseif ($rang == 3) $medal = '🥉';
                    ?>
                        <tr>
                            <td><strong><?php echo $medal . ' #' . $rang; ?></strong></td>
                            <td><strong><?php echo escape($marque['marque']); ?></strong></td>
                            <td><span class="badge badge-stock"><?php echo $marque['nb_ventes']; ?></span></td>
                            <td><?php echo formatPrice($marque['ca_total']); ?></td>
                            <td><?php echo formatPrice($marque['prix_moyen']); ?></td>
                        </tr>
                    <?php 
                        $rang++;
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">Aucune donnée disponible</p>
    <?php endif; ?>
</div>

<!-- Top clients -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">👑 Top 5 des Meilleurs Clients</h2>
    </div>
    
    <?php if (count($top_clients) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Client</th>
                        <th>Nb Achats</th>
                        <th>Total Dépensé</th>
                        <th>Panier Moyen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rang = 1;
                    foreach ($top_clients as $client): 
                        $medal = '';
                        if ($rang == 1) $medal = '🥇';
                        elseif ($rang == 2) $medal = '🥈';
                        elseif ($rang == 3) $medal = '🥉';
                    ?>
                        <tr>
                            <td><strong><?php echo $medal . ' #' . $rang; ?></strong></td>
                            <td><strong><?php echo escape($client['nom'] . ' ' . $client['prenom']); ?></strong></td>
                            <td><span class="badge badge-stock"><?php echo $client['nb_achats']; ?></span></td>
                            <td><strong style="color: green;"><?php echo formatPrice($client['total_depense']); ?></strong></td>
                            <td><?php echo formatPrice($client['total_depense'] / $client['nb_achats']); ?></td>
                        </tr>
                    <?php 
                        $rang++;
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">Aucun client avec achats</p>
    <?php endif; ?>
</div>

<!-- Indicateurs de performance -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">🎯 Indicateurs de Performance</h2>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; padding: 1rem;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 15px;">
            <h3 style="margin: 0 0 1rem 0;">⚡ Rotation du Stock</h3>
            <div style="font-size: 2rem; font-weight: bold;">
                <?php echo round($rotation_stock['duree_moyenne_stock'] ?? 0); ?> jours
            </div>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">
                Durée moyenne de stockage avant vente
            </p>
        </div>
        
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 2rem; border-radius: 15px;">
            <h3 style="margin: 0 0 1rem 0;">💰 Taux de Marge Moyen</h3>
            <div style="font-size: 2rem; font-weight: bold;">
                <?php 
                $taux_marge = $perf_annee['ca_total'] > 0 ? 
                    ($perf_annee['marge_totale'] / ($perf_annee['ca_total'] - $perf_annee['marge_totale'])) * 100 : 0;
                echo number_format($taux_marge, 1); 
                ?>%
            </div>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">
                Sur l'année <?php echo date('Y'); ?>
            </p>
        </div>
        
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 2rem; border-radius: 15px;">
            <h3 style="margin: 0 0 1rem 0;">🎯 Véhicules Vendus</h3>
            <div style="font-size: 2rem; font-weight: bold;">
                <?php echo $rotation_stock['total_vendus'] ?? 0; ?>
            </div>
            <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">
                Sur les 12 derniers mois
            </p>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>