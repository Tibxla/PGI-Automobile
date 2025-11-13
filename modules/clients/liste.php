<?php
$page_title = "Liste des clients";
include '../../includes/header.php';

requirePermission('clients', 'read');

$recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';
$message = '';
$error = '';

// Messages de feedback
if (isset($_GET['success'])) {
    $message = "Client ajouté avec succès !";
}
if (isset($_GET['deleted'])) {
    $message = "Client supprimé avec succès !";
}
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'client_has_sales') {
        $error = "Impossible de supprimer ce client car il a effectué des achats.";
    } else {
        $error = "Une erreur est survenue.";
    }
}

$sql = "SELECT c.*, COUNT(v.id) as nb_achats, SUM(v.prix_vente) as total_achats 
        FROM clients c 
        LEFT JOIN ventes v ON c.id = v.client_id 
        WHERE 1=1";

if ($recherche) {
    $sql .= " AND (c.nom LIKE :recherche OR c.prenom LIKE :recherche OR c.email LIKE :recherche OR c.telephone LIKE :recherche)";
}

$sql .= " GROUP BY c.id ORDER BY c.created_at DESC";

$stmt = $pdo->prepare($sql);
if ($recherche) {
    $stmt->execute([':recherche' => '%' . $recherche . '%']);
} else {
    $stmt->execute();
}
$clients = $stmt->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">👥 Gestion des Clients</h2>
        <?php if (hasPermission('clients', 'create')): ?>
            <a href="ajouter.php" class="btn btn-primary">➕ Ajouter un client</a>
        <?php endif; ?>
    </div>
    
    <!-- Recherche -->
    <form method="GET" style="margin-bottom: 2rem;">
        <div class="form-row">
            <div class="form-group" style="flex: 3;">
                <input type="text" name="recherche" class="form-control" placeholder="🔍 Rechercher un client (nom, email, téléphone...)" value="<?php echo escape($recherche); ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">🔍 Rechercher</button>
                <a href="liste.php" class="btn btn-warning">🔄 Réinitialiser</a>
            </div>
        </div>
    </form>
    
    <!-- Tableau des clients -->
    <?php if (count($clients) > 0): ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Ville</th>
                        <th>Nb Achats</th>
                        <th>Total Achats</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><strong>#<?php echo $client['id']; ?></strong></td>
                            <td>
                                <strong><?php echo escape($client['nom'] . ' ' . $client['prenom']); ?></strong>
                            </td>
                            <td><?php echo escape($client['email']); ?></td>
                            <td><?php echo escape($client['telephone']); ?></td>
                            <td><?php echo escape($client['ville']); ?></td>
                            <td>
                                <span class="badge badge-stock"><?php echo $client['nb_achats']; ?></span>
                            </td>
                            <td>
                                <strong><?php echo formatPrice($client['total_achats'] ?? 0); ?></strong>
                            </td>
                            <td><?php echo formatDate($client['created_at']); ?></td>
                            <td>
                                <?php if (hasPermission('clients', 'update')): ?>
                                    <a href="modifier.php?id=<?php echo $client['id']; ?>" class="btn btn-warning btn-sm">✏️</a>
                                <?php endif; ?>
                                <?php if (hasPermission('clients', 'delete')): ?>
                                    <a href="supprimer.php?id=<?php echo $client['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">🗑️</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <p style="margin-top: 1rem; color: #666;">
            <strong><?php echo count($clients); ?></strong> client(s) trouvé(s)
        </p>
    <?php else: ?>
        <p style="text-align: center; color: #999; padding: 2rem;">
            Aucun client trouvé
        </p>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>