<?php
$page_title = "Liste des véhicules";
include '../../includes/header.php';

// Filtres
$filtre_statut = isset($_GET['statut']) ? $_GET['statut'] : '';
$filtre_type = isset($_GET['type']) ? $_GET['type'] : '';
$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';

// Construction de la requête
$sql = "SELECT * FROM vehicules WHERE 1=1";
$params = [];

if ($filtre_statut) {
    $sql .= " AND statut = :statut";
    $params[':statut'] = $filtre_statut;
}

if ($filtre_type) {
    $sql .= " AND type_vehicule = :type";
    $params[':type'] = $filtre_type;
}

if ($recherche) {
    $sql .= " AND (marque LIKE :recherche OR modele LIKE :recherche OR immatriculation LIKE :recherche)";
    $params[':recherche'] = '%' . $recherche . '%';
}

$sql .= " ORDER BY date_arrivee DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehicules = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">🚙 Gestion des Véhicules</h2>
        <a href="ajouter.php" class="btn btn-primary">➕ Ajouter un véhicule</a>
    </div>
    
    <!-- Filtres -->
    <form method="GET" class="form-row" style="margin-bottom: 2rem;">
        <div class="form-group">
            <input type="text" name="recherche" class="form-control" placeholder="🔍 Rechercher (marque, modèle, immat...)" value="<?php echo escape($recherche); ?>">
        </div>
        
        <div class="form-group">
            <select name="statut" class="form-control">
                <option value="">Tous les statuts</option>
                <option value="stock" <?php echo $filtre_statut == 'stock' ? 'selected' : ''; ?>>En stock</option>
                <option value="vendu" <?php echo $filtre_statut == 'vendu' ? 'selected' : ''; ?>>Vendu</option>
                <option value="reserve" <?php echo $filtre_statut == 'reserve' ? 'selected' : ''; ?>>Réservé</option>
            </select>
        </div>
        
        <div class="form-group">
            <select name="type" class="form-control">
                <option value="">Tous les types</option>
                <option value="berline" <?php echo $filtre_type == 'berline' ? 'selected' : ''; ?>>Berline</option>
                <option value="suv" <?php echo $filtre_type == 'suv' ? 'selected' : ''; ?>>SUV</option>
                <option value="sportive" <?php echo $filtre_type == 'sportive' ? 'selected' : ''; ?>>Sportive</option>
                <option value="utilitaire" <?php echo $filtre_type == 'utilitaire' ? 'selected' : ''; ?>>Utilitaire</option>
                <option value="citadine" <?php echo $filtre_type == 'citadine' ? 'selected' : ''; ?>>Citadine</option>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
            <a href="liste.php" class="btn btn-warning">🔄 Réinitialiser</a>
        </div>
    </form>
    
    <!-- Tableau des véhicules -->
    <?php if (count($vehicules) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Véhicule</th>
                        <th>Année</th>
                        <th>Couleur</th>
                        <th>Km</th>
                        <th>Carburant</th>
                        <th>Prix Achat</th>
                        <th>Prix Vente</th>
                        <th>Marge</th>
                        <th>Statut</th>
                        <th>Immatriculation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicules as $vehicule): 
                        $marge = $vehicule['prix_vente'] - $vehicule['prix_achat'];
                        $marge_pct = ($marge / $vehicule['prix_achat']) * 100;
                    ?>
                        <tr>
                            <td><span class="car-type-<?php echo $vehicule['type_vehicule']; ?>"></span></td>
                            <td><strong><?php echo escape($vehicule['marque'] . ' ' . $vehicule['modele']); ?></strong></td>
                            <td><?php echo $vehicule['annee']; ?></td>
                            <td><?php echo escape($vehicule['couleur']); ?></td>
                            <td><?php echo number_format($vehicule['kilometrage'], 0, ',', ' '); ?> km</td>
                            <td><?php echo ucfirst($vehicule['carburant']); ?></td>
                            <td><?php echo formatPrice($vehicule['prix_achat']); ?></td>
                            <td><strong><?php echo formatPrice($vehicule['prix_vente']); ?></strong></td>
                            <td>
                                <span style="color: <?php echo $marge > 0 ? 'green' : 'red'; ?>">
                                    <?php echo formatPrice($marge); ?> (<?php echo number_format($marge_pct, 1); ?>%)
                                </span>
                            </td>
                            <td><span class="badge badge-<?php echo $vehicule['statut']; ?>"><?php echo ucfirst($vehicule['statut']); ?></span></td>
                            <td><?php echo escape($vehicule['immatriculation']); ?></td>
                            <td>
                                <a href="modifier.php?id=<?php echo $vehicule['id']; ?>" class="btn btn-warning btn-sm">✏️</a>
                                <a href="supprimer.php?id=<?php echo $vehicule['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <p style="margin-top: 1rem; color: #666;">
            <strong><?php echo count($vehicules); ?></strong> véhicule(s) trouvé(s)
        </p>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">
            Aucun véhicule trouvé avec ces critères
        </p>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>