<?php
$page_title = "Modifier un véhicule";
include '../../includes/header.php';

$message = '';
$error = '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: liste.php");
    exit;
}

// Récupération du véhicule
$stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
$stmt->execute([$id]);
$vehicule = $stmt->fetch();

if (!$vehicule) {
    header("Location: liste.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marque = $_POST['marque'] ?? '';
    $modele = $_POST['modele'] ?? '';
    $annee = $_POST['annee'] ?? '';
    $couleur = $_POST['couleur'] ?? '';
    $prix_achat = $_POST['prix_achat'] ?? 0;
    $prix_vente = $_POST['prix_vente'] ?? 0;
    $kilometrage = $_POST['kilometrage'] ?? 0;
    $type_vehicule = $_POST['type_vehicule'] ?? 'berline';
    $carburant = $_POST['carburant'] ?? 'essence';
    $statut = $_POST['statut'] ?? 'stock';
    $date_arrivee = $_POST['date_arrivee'] ?? date('Y-m-d');
    $immatriculation = $_POST['immatriculation'] ?? '';
    
    if (empty($marque) || empty($modele) || empty($annee)) {
        $error = "Veuillez remplir tous les champs obligatoires";
    } else {
        try {
            $sql = "UPDATE vehicules SET 
                    marque = :marque, 
                    modele = :modele, 
                    annee = :annee, 
                    couleur = :couleur, 
                    prix_achat = :prix_achat, 
                    prix_vente = :prix_vente, 
                    kilometrage = :kilometrage, 
                    type_vehicule = :type_vehicule, 
                    carburant = :carburant, 
                    statut = :statut, 
                    date_arrivee = :date_arrivee, 
                    immatriculation = :immatriculation
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':marque' => $marque,
                ':modele' => $modele,
                ':annee' => $annee,
                ':couleur' => $couleur,
                ':prix_achat' => $prix_achat,
                ':prix_vente' => $prix_vente,
                ':kilometrage' => $kilometrage,
                ':type_vehicule' => $type_vehicule,
                ':carburant' => $carburant,
                ':statut' => $statut,
                ':date_arrivee' => $date_arrivee,
                ':immatriculation' => $immatriculation,
                ':id' => $id
            ]);
            
            $message = "Véhicule modifié avec succès !";
            // Recharger les données
            $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
            $stmt->execute([$id]);
            $vehicule = $stmt->fetch();
        } catch (PDOException $e) {
            $error = "Erreur lors de la modification : " . $e->getMessage();
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">✏️ Modifier le Véhicule</h2>
        <a href="liste.php" class="btn btn-warning">← Retour à la liste</a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Marque *</label>
                <input type="text" name="marque" class="form-control" required value="<?php echo escape($vehicule['marque']); ?>">
            </div>
            
            <div class="form-group">
                <label>Modèle *</label>
                <input type="text" name="modele" class="form-control" required value="<?php echo escape($vehicule['modele']); ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Année *</label>
                <input type="number" name="annee" class="form-control" required min="1990" max="<?php echo date('Y') + 1; ?>" value="<?php echo $vehicule['annee']; ?>">
            </div>
            
            <div class="form-group">
                <label>Couleur</label>
                <input type="text" name="couleur" class="form-control" value="<?php echo escape($vehicule['couleur']); ?>">
            </div>
            
            <div class="form-group">
                <label>Kilométrage</label>
                <input type="number" name="kilometrage" class="form-control" min="0" value="<?php echo $vehicule['kilometrage']; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Type de véhicule</label>
                <select name="type_vehicule" class="form-control">
                    <option value="berline" <?php echo $vehicule['type_vehicule'] == 'berline' ? 'selected' : ''; ?>>🚗 Berline</option>
                    <option value="suv" <?php echo $vehicule['type_vehicule'] == 'suv' ? 'selected' : ''; ?>>🚙 SUV</option>
                    <option value="sportive" <?php echo $vehicule['type_vehicule'] == 'sportive' ? 'selected' : ''; ?>>🏎️ Sportive</option>
                    <option value="utilitaire" <?php echo $vehicule['type_vehicule'] == 'utilitaire' ? 'selected' : ''; ?>>🚐 Utilitaire</option>
                    <option value="citadine" <?php echo $vehicule['type_vehicule'] == 'citadine' ? 'selected' : ''; ?>>🚕 Citadine</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Carburant</label>
                <select name="carburant" class="form-control">
                    <option value="essence" <?php echo $vehicule['carburant'] == 'essence' ? 'selected' : ''; ?>>Essence</option>
                    <option value="diesel" <?php echo $vehicule['carburant'] == 'diesel' ? 'selected' : ''; ?>>Diesel</option>
                    <option value="electrique" <?php echo $vehicule['carburant'] == 'electrique' ? 'selected' : ''; ?>>Électrique</option>
                    <option value="hybride" <?php echo $vehicule['carburant'] == 'hybride' ? 'selected' : ''; ?>>Hybride</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="stock" <?php echo $vehicule['statut'] == 'stock' ? 'selected' : ''; ?>>En stock</option>
                    <option value="reserve" <?php echo $vehicule['statut'] == 'reserve' ? 'selected' : ''; ?>>Réservé</option>
                    <option value="vendu" <?php echo $vehicule['statut'] == 'vendu' ? 'selected' : ''; ?>>Vendu</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Prix d'achat (€)</label>
                <input type="number" name="prix_achat" class="form-control" step="0.01" min="0" value="<?php echo $vehicule['prix_achat']; ?>">
            </div>
            
            <div class="form-group">
                <label>Prix de vente (€)</label>
                <input type="number" name="prix_vente" class="form-control" step="0.01" min="0" value="<?php echo $vehicule['prix_vente']; ?>">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Date d'arrivée</label>
                <input type="date" name="date_arrivee" class="form-control" value="<?php echo $vehicule['date_arrivee']; ?>">
            </div>
            
            <div class="form-group">
                <label>Immatriculation</label>
                <input type="text" name="immatriculation" class="form-control" value="<?php echo escape($vehicule['immatriculation']); ?>">
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                ✅ Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>