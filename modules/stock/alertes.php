<?php
$page_title = "Alertes Stock";
include '../../includes/header.php';

// Véhicules en stock depuis longtemps (plus de 6 mois)
$stmt = $pdo->query("
    SELECT *, DATEDIFF(CURDATE(), date_arrivee) as jours_stock
    FROM vehicules
    WHERE statut = 'stock' AND DATEDIFF(CURDATE(), date_arrivee) > 180
    ORDER BY date_arrivee ASC
");
$vehicules_anciens = $stmt->fetchAll();

// Véhicules à prix élevé sans vente
$stmt = $pdo->query("
    SELECT *, DATEDIFF(CURDATE(), date_arrivee) as jours_stock
    FROM vehicules
    WHERE statut = 'stock' AND prix_vente > 40000
    ORDER BY prix_vente DESC
");
$vehicules_premium = $stmt->fetchAll();

// Statistiques des alertes
$total_alertes = count($vehicules_anciens);
$valeur_bloquee = array_sum(array_column($vehicules_anciens, 'prix_vente'));
?>

    <h1 style="color: white; text-align: center; margin-bottom: 2rem;">
        ⚠️ Alertes et Gestion du Stock
    </h1>

    <!-- Résumé des alertes -->
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <div class="stat-icon">⚠️</div>
            <div class="stat-value"><?php echo $total_alertes; ?></div>
            <div class="stat-label">Alertes actives</div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="stat-icon">📅</div>
            <div class="stat-value">
                <?php
                if ($total_alertes > 0) {
                    $jours_moyen = array_sum(array_column($vehicules_anciens, 'jours_stock')) / $total_alertes;
                    echo round($jours_moyen) . ' j';
                } else {
                    echo '0 j';
                }
                ?>
            </div>
            <div class="stat-label">Durée moyenne en stock</div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <div class="stat-icon">💰</div>
            <div class="stat-value"><?php echo formatPrice($valeur_bloquee); ?></div>
            <div class="stat-label">Valeur bloquée</div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
            <div class="stat-icon">🏷️</div>
            <div class="stat-value"><?php echo count($vehicules_premium); ?></div>
            <div class="stat-label">Véhicules premium</div>
        </div>
    </div>

    <!-- Véhicules en stock longue durée -->
<?php if (count($vehicules_anciens) > 0): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">🚨 Véhicules en Stock Longue Durée (+6 mois)</h2>
        </div>

        <div class="alert alert-warning">
            <strong>⚠️ Attention !</strong> <?php echo count($vehicules_anciens); ?> véhicule(s) en stock depuis plus de 6 mois.
            Action recommandée : Réduction de prix ou promotion.
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Véhicule</th>
                    <th>Date d'arrivée</th>
                    <th>Jours en stock</th>
                    <th>Prix actuel</th>
                    <th>Niveau d'alerte</th>
                    <th>Recommandation</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vehicules_anciens as $veh):
                    $niveau_alerte = '';
                    $couleur_alerte = '';
                    $recommandation = '';

                    if ($veh['jours_stock'] > 365) {
                        $niveau_alerte = '🔴 CRITIQUE';
                        $couleur_alerte = 'red';
                        $recommandation = 'Réduction importante (-20% à -30%)';
                    } elseif ($veh['jours_stock'] > 270) {
                        $niveau_alerte = '🟠 ÉLEVÉ';
                        $couleur_alerte = 'orange';
                        $recommandation = 'Promotion urgente (-15% à -20%)';
                    } else {
                        $niveau_alerte = '🟡 MOYEN';
                        $couleur_alerte = '#f59e0b';
                        $recommandation = 'Envisager une promotion (-10%)';
                    }
                    ?>
                    <tr>
                        <td><span class="car-type-<?php echo $veh['type_vehicule']; ?>"></span></td>
                        <td>
                            <strong><?php echo escape($veh['marque'] . ' ' . $veh['modele']); ?></strong><br>
                            <small><?php echo $veh['annee']; ?> - <?php echo escape($veh['immatriculation']); ?></small>
                        </td>
                        <td><?php echo formatDate($veh['date_arrivee']); ?></td>
                        <td>
                            <span class="badge badge-danger">
                                <?php echo $veh['jours_stock']; ?> jours
                            </span>
                        </td>
                        <td><strong><?php echo formatPrice($veh['prix_vente']); ?></strong></td>
                        <td>
                            <span style="color: <?php echo $couleur_alerte; ?>; font-weight: bold;">
                                <?php echo $niveau_alerte; ?>
                            </span>
                        </td>
                        <td style="color: #666;">
                            <?php echo $recommandation; ?>
                        </td>
                        <td>
                            <a href="../vehicules/modifier.php?id=<?php echo $veh['id']; ?>" class="btn btn-warning btn-sm">
                                ✏️ Modifier prix
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">✅ Stock Sain</h2>
        </div>
        <div class="alert alert-success">
            <strong>✅ Excellent !</strong> Aucun véhicule en stock depuis plus de 6 mois.
        </div>
    </div>
<?php endif; ?>

    <!-- Véhicules premium -->
<?php if (count($vehicules_premium) > 0): ?>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">💎 Véhicules Premium (+ 40 000€)</h2>
        </div>

        <p style="color: #666; margin-bottom: 1rem;">
            Ces véhicules haut de gamme nécessitent une attention particulière en marketing.
        </p>

        <div class="table-responsive">
            <table>
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Véhicule</th>
                    <th>Année</th>
                    <th>Prix</th>
                    <th>Jours en stock</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($vehicules_premium as $veh): ?>
                    <tr>
                        <td><span class="car-type-<?php echo $veh['type_vehicule']; ?>"></span></td>
                        <td>
                            <strong><?php echo escape($veh['marque'] . ' ' . $veh['modele']); ?></strong>
                        </td>
                        <td><?php echo $veh['annee']; ?></td>
                        <td><strong style="color: #8b5cf6;"><?php echo formatPrice($veh['prix_vente']); ?></strong></td>
                        <td>
                            <span class="badge badge-stock">
                                <?php echo $veh['jours_stock']; ?> jours
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $veh['statut']; ?>">
                                <?php echo ucfirst($veh['statut']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="../vehicules/modifier.php?id=<?php echo $veh['id']; ?>" class="btn btn-warning btn-sm">✏️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

    <!-- Recommandations -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">💡 Recommandations</h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div style="background: #fef3c7; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #f59e0b;">
                <h3 style="color: #92400e; margin-bottom: 0.5rem;">📢 Actions Marketing</h3>
                <ul style="color: #92400e; margin: 0; padding-left: 1.5rem;">
                    <li>Mettre en avant les véhicules anciens</li>
                    <li>Créer des promotions ciblées</li>
                    <li>Utiliser les réseaux sociaux</li>
                </ul>
            </div>

            <div style="background: #dbeafe; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #3b82f6;">
                <h3 style="color: #1e40af; margin-bottom: 0.5rem;">💰 Ajustements Prix</h3>
                <ul style="color: #1e40af; margin: 0; padding-left: 1.5rem;">
                    <li>Réviser les prix des véhicules anciens</li>
                    <li>Proposer des facilités de paiement</li>
                    <li>Offrir des garanties étendues</li>
                </ul>
            </div>

            <div style="background: #dcfce7; padding: 1.5rem; border-radius: 10px; border-left: 4px solid #10b981;">
                <h3 style="color: #065f46; margin-bottom: 0.5rem;">📊 Analyse</h3>
                <ul style="color: #065f46; margin: 0; padding-left: 1.5rem;">
                    <li>Analyser la concurrence</li>
                    <li>Étudier la demande du marché</li>
                    <li>Optimiser le mix produits</li>
                </ul>
            </div>
        </div>
    </div>

<?php include '../../includes/footer.php'; ?>