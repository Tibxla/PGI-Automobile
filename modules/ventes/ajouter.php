<?php
$page_title = "Nouvelle vente";
include '../../includes/header.php';

$message = '';
$error = '';

// Récupérer les véhicules disponibles (stock ou réservé)
$stmt = $pdo->query("SELECT * FROM vehicules WHERE statut IN ('stock', 'reserve') ORDER BY marque, modele");
$vehicules_dispo = $stmt->fetchAll();

// Récupérer tous les clients
$stmt = $pdo->query("SELECT * FROM clients ORDER BY nom, prenom");
$clients = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicule_id = $_POST['vehicule_id'] ?? 0;
    $client_id = $_POST['client_id'] ?? 0;
    $prix_vente = $_POST['prix_vente'] ?? 0;
    $mode_paiement = $_POST['mode_paiement'] ?? 'comptant';
    $date_vente = $_POST['date_vente'] ?? date('Y-m-d');
    $notes = $_POST['notes'] ?? '';
    
    if ($vehicule_id <= 0 || $client_id <= 0) {
        $error = "Veuillez sélectionner un véhicule et un client";
    } elseif ($prix_vente <= 0) {
        $error = "Le prix de vente doit être supérieur à 0";
    } else {
        try {
            // Récupérer le prix d'achat du véhicule
            $stmt = $pdo->prepare("SELECT prix_achat FROM vehicules WHERE id = ?");
            $stmt->execute([$vehicule_id]);
            $vehicule = $stmt->fetch();
            
            $marge = $prix_vente - $vehicule['prix_achat'];
            
            // Insérer la vente
            $sql = "INSERT INTO ventes (vehicule_id, client_id, prix_vente, mode_paiement, date_vente, marge, notes) 
                    VALUES (:vehicule_id, :client_id, :prix_vente, :mode_paiement, :date_vente, :marge, :notes)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':vehicule_id' => $vehicule_id,
                ':client_id' => $client_id,
                ':prix_vente' => $prix_vente,
                ':mode_paiement' => $mode_paiement,
                ':date_vente' => $date_vente,
                ':marge' => $marge,
                ':notes' => $notes
            ]);
            
            // Mettre à jour le statut du véhicule
            $stmt = $pdo->prepare("UPDATE vehicules SET statut = 'vendu' WHERE id = ?");
            $stmt->execute([$vehicule_id]);
            
            header("Location: liste.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}
?>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">➕ Enregistrer une Vente</h2>
        <a href="liste.php" class="btn btn-warning">← Retour à la liste</a>
    </div>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (count($vehicules_dispo) == 0): ?>
        <div class="alert alert-warning">
            ⚠️ Aucun véhicule disponible à la vente. <a href="../vehicules/ajouter.php">Ajouter un véhicule</a>
        </div>
    <?php elseif (count($clients) == 0): ?>
        <div class="alert alert-warning">
            ⚠️ Aucun client enregistré. <a href="../clients/ajouter.php">Ajouter un client</a>
        </div>
    <?php else: ?>
        <form method="POST" id="venteForm">
            <div class="form-row">
                <div class="form-group">
                    <label>Véhicule *</label>
                    <select name="vehicule_id" id="vehicule_id" class="form-control" required onchange="updatePrix()">
                        <option value="">Sélectionner un véhicule</option>
                        <?php foreach ($vehicules_dispo as $vehicule): ?>
                            <option value="<?php echo $vehicule['id']; ?>" 
                                    data-prix="<?php echo $vehicule['prix_vente']; ?>"
                                    data-prix-achat="<?php echo $vehicule['prix_achat']; ?>">
                                <?php 
                                $icon = '';
                                switch($vehicule['type_vehicule']) {
                                    case 'berline': $icon = '🚗'; break;
                                    case 'suv': $icon = '🚙'; break;
                                    case 'sportive': $icon = '🏎️'; break;
                                    case 'utilitaire': $icon = '🚐'; break;
                                    case 'citadine': $icon = '🚕'; break;
                                }
                                echo $icon . ' ' . escape($vehicule['marque'] . ' ' . $vehicule['modele'] . ' (' . $vehicule['annee'] . ') - ' . formatPrice($vehicule['prix_vente'])); 
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Client *</label>
                    <select name="client_id" class="form-control" required>
                        <option value="">Sélectionner un client</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?php echo $client['id']; ?>">
                                <?php echo escape($client['nom'] . ' ' . $client['prenom'] . ' - ' . $client['email']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small><a href="../clients/ajouter.php" target="_blank">➕ Nouveau client</a></small>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Prix de vente (€) *</label>
                    <input type="number" name="prix_vente" id="prix_vente" class="form-control" step="0.01" min="0" required onchange="calculMarge()">
                    <small id="info_prix" style="color: #666;"></small>
                </div>
                
                <div class="form-group">
                    <label>Mode de paiement</label>
                    <select name="mode_paiement" class="form-control">
                        <option value="comptant">💵 Comptant</option>
                        <option value="credit">💳 Crédit</option>
                        <option value="leasing">📄 Leasing</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Date de vente</label>
                    <input type="date" name="date_vente" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Notes / Observations</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Notes complémentaires..."></textarea>
            </div>
            
            <div id="marge_info" style="background: #f0f9ff; padding: 1.5rem; border-radius: 10px; margin: 1rem 0; display: none;">
                <h3 style="margin: 0 0 1rem 0; color: #1e40af;">💰 Informations Financières</h3>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    <div>
                        <strong>Prix d'achat:</strong><br>
                        <span id="prix_achat_display" style="font-size: 1.2rem;">-</span>
                    </div>
                    <div>
                        <strong>Prix de vente:</strong><br>
                        <span id="prix_vente_display" style="font-size: 1.2rem;">-</span>
                    </div>
                    <div>
                        <strong>Marge:</strong><br>
                        <span id="marge_display" style="font-size: 1.2rem; font-weight: bold;">-</span>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">
                    ✅ Enregistrer la vente
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
function updatePrix() {
    const select = document.getElementById('vehicule_id');
    const option = select.options[select.selectedIndex];
    const prix = option.getAttribute('data-prix');
    
    if (prix) {
        document.getElementById('prix_vente').value = prix;
        calculMarge();
    }
}

function calculMarge() {
    const select = document.getElementById('vehicule_id');
    const option = select.options[select.selectedIndex];
    const prixAchat = parseFloat(option.getAttribute('data-prix-achat')) || 0;
    const prixVente = parseFloat(document.getElementById('prix_vente').value) || 0;
    
    if (prixAchat > 0 && prixVente > 0) {
        const marge = prixVente - prixAchat;
        const margePct = (marge / prixAchat) * 100;
        
        document.getElementById('marge_info').style.display = 'block';
        document.getElementById('prix_achat_display').textContent = prixAchat.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'});
        document.getElementById('prix_vente_display').textContent = prixVente.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'});
        document.getElementById('marge_display').textContent = marge.toLocaleString('fr-FR', {style: 'currency', currency: 'EUR'}) + ' (' + margePct.toFixed(1) + '%)';
        document.getElementById('marge_display').style.color = marge > 0 ? 'green' : 'red';
        
        if (marge < 0) {
            document.getElementById('info_prix').textContent = '⚠️ Attention: Prix de vente inférieur au prix d\'achat !';
            document.getElementById('info_prix').style.color = 'red';
        } else {
            document.getElementById('info_prix').textContent = '✅ Marge: ' + margePct.toFixed(1) + '%';
            document.getElementById('info_prix').style.color = 'green';
        }
    }
}
</script>

<?php include '../../includes/footer.php'; ?>