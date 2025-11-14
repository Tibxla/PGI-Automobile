<?php
$page_title = "Ajouter un véhicule";
include '../../includes/header.php';
require_once '../../includes/functions.php';

$message = '';
$error = '';

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
    } elseif (empty($error)) {
        try {
            $sql = "INSERT INTO vehicules (marque, modele, annee, couleur, prix_achat, prix_vente, kilometrage, type_vehicule, carburant, statut, date_arrivee, immatriculation)
                    VALUES (:marque, :modele, :annee, :couleur, :prix_achat, :prix_vente, :kilometrage, :type_vehicule, :carburant, :statut, :date_arrivee, :immatriculation)";

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
                ':immatriculation' => $immatriculation
            ]);

            $message = "Véhicule ajouté avec succès !";
            header("Location: liste.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ Ajouter un Véhicule</h2>
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
                <input type="text" name="marque" class="form-control" required placeholder="Ex: Peugeot">
            </div>
            
            <div class="form-group">
                <label>Modèle *</label>
                <input type="text" name="modele" class="form-control" required placeholder="Ex: 208">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Année *</label>
                <input type="number" name="annee" class="form-control" required min="1990" max="<?php echo date('Y') + 1; ?>" value="<?php echo date('Y'); ?>">
            </div>
            
            <div class="form-group">
                <label>Couleur</label>
                <input type="text" name="couleur" class="form-control" placeholder="Ex: Bleu">
            </div>
            
            <div class="form-group">
                <label>Kilométrage</label>
                <input type="number" name="kilometrage" class="form-control" value="0" min="0">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Type de véhicule</label>
                <select name="type_vehicule" class="form-control">
                    <option value="berline">🚗 Berline</option>
                    <option value="suv">🚙 SUV</option>
                    <option value="sportive">🏎️ Sportive</option>
                    <option value="utilitaire">🚐 Utilitaire</option>
                    <option value="citadine">🚕 Citadine</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Carburant</label>
                <select name="carburant" class="form-control">
                    <option value="essence">Essence</option>
                    <option value="diesel">Diesel</option>
                    <option value="electrique">Électrique</option>
                    <option value="hybride">Hybride</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="stock">En stock</option>
                    <option value="reserve">Réservé</option>
                    <option value="vendu">Vendu</option>
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Prix d'achat (€)</label>
                <input type="number" name="prix_achat" class="form-control" step="0.01" min="0" value="0">
            </div>
            
            <div class="form-group">
                <label>Prix de vente (€)</label>
                <input type="number" name="prix_vente" class="form-control" step="0.01" min="0" value="0">
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Date d'arrivée</label>
                <input type="date" name="date_arrivee" class="form-control" value="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label>Immatriculation</label>
                <input type="text" name="immatriculation" class="form-control" placeholder="Ex: AB-123-CD">
            </div>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                ✅ Enregistrer le véhicule
            </button>
        </div>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>