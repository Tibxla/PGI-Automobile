<?php
$page_title = "Liste des ventes";
include '../../includes/header.php';

// Filtres
$filtre_mois = isset($_GET['mois']) ? $_GET['mois'] : date('Y-m');

// Statistiques du mois
$stmt = $pdo->prepare("
    SELECT 
        COUNT(*) as nb_ventes,
        SUM(prix_vente) as ca_total,
        SUM(marge) as marge_totale,
        AVG(prix_vente) as prix_moyen
    FROM ventes 
    WHERE DATE_FORMAT(date_vente, '%Y-%m') = ?
");
$stmt->execute([$filtre_mois]);
$stats = $stmt->fetch();

// Liste des ventes
$stmt = $pdo->prepare("
    SELECT v.*, ve.marque, ve.modele, ve.type_vehicule, c.nom, c.prenom, c.email, c.telephone
    FROM ventes v
    JOIN vehicules ve ON v.vehicule_id = ve.id
    JOIN clients c ON v.client_id = c.id
    WHERE DATE_FORMAT(v.date_vente, '%Y-%m') = ?
    ORDER BY v.date_vente DESC
");
$stmt->execute([$filtre_mois]);
$ventes = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">💰 Gestion des Ventes</h2>
        <a href="ajouter.php" class="btn btn-primary">➕ Nouvelle vente</a>
    </div>
    
    <!-- Filtre par mois -->
    <form method="GET" style="margin-bottom: 2rem;">
        <div class="form-row">
            <div class="form-group">
                <label>Mois</label>
                <input type="month" name="mois" class="form-control" value="<?php echo $filtre_mois; ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
                <a href="liste.php" class="btn btn-warning">🔄 Mois actuel</a>
            </div>
        </div>
    </form>
    
    <!-- Statistiques du mois -->
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stat-icon">📊</div>
            <div class="stat-value"><?php echo $stats['nb_ventes'] ?? 0; ?></div>
            <div class="stat-label">Ventes réalisées</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stat-icon">💵</div>
            <div class="stat-value"><?php echo formatPrice($stats['ca_total'] ?? 0); ?></div>
            <div class="stat-label">Chiffre d'affaires</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="stat-icon">📈</div>
            <div class="stat-value"><?php echo formatPrice($stats['marge_totale'] ?? 0); ?></div>
            <div class="stat-label">Marge totale</div>
        </div>
        
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="stat-icon">💰</div>
            <div class="stat-value"><?php echo formatPrice($stats['prix_moyen'] ?? 0); ?></div>
            <div class="stat-label">Prix moyen</div>
        </div>
    </div>
</div>

<!-- Liste des ventes -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title">📋 Détail des Ventes</h2>
    </div>
    
    <?php if (count($ventes) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Véhicule</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Prix Vente</th>
                        <th>Marge</th>
                        <th>Mode Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventes as $vente): ?>
                        <tr>
                            <td><strong>#<?php echo $vente['id']; ?></strong></td>
                            <td><?php echo formatDate($vente['date_vente']); ?></td>
                            <td>
                                <span class="car-type-<?php echo $vente['type_vehicule']; ?>"></span>
                                <strong><?php echo escape($vente['marque'] . ' ' . $vente['modele']); ?></strong>
                            </td>
                            <td><?php echo escape($vente['nom'] . ' ' . $vente['prenom']); ?></td>
                            <td>
                                <?php echo escape($vente['telephone']); ?><br>
                                <small><?php echo escape($vente['email']); ?></small>
                            </td>
                            <td><strong style="color: green;"><?php echo formatPrice($vente['prix_vente']); ?></strong></td>
                            <td>
                                <?php 
                                $marge_pct = $vente['marge'] > 0 ? ($vente['marge'] / ($vente['prix_vente'] - $vente['marge'])) * 100 : 0;
                                ?>
                                <span style="color: <?php echo $vente['marge'] > 0 ? 'green' : 'red'; ?>">
                                    <?php echo formatPrice($vente['marge']); ?>
                                    <br><small>(<?php echo number_format($marge_pct, 1); ?>%)</small>
                                </span>
                            </td>
                            <td><span class="badge badge-stock"><?php echo ucfirst($vente['mode_paiement']); ?></span></td>
                            <td>
                                <a href="facture.php?id=<?php echo $vente['id']; ?>" class="btn btn-primary btn-sm">📄 Facture</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <p style="margin-top: 1rem; color: #666;">
            <strong><?php echo count($ventes); ?></strong> vente(s) pour le mois sélectionné
        </p>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">
            Aucune vente pour ce mois
        </p>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>