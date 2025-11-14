<?php
require_once '../../config/database.php';

// Fonctions nécessaires si elles ne sont pas chargées
if (!function_exists('formatPrice')) {
    function formatPrice($price) {
        return number_format($price, 2, ',', ' ') . ' €';
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date) {
        return date('d/m/Y', strtotime($date));
    }
}

if (!function_exists('escape')) {
    function escape($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: liste.php");
    exit;
}

// Récupérer les informations de la vente
$stmt = $pdo->prepare("
    SELECT 
        v.*,
        ve.marque, ve.modele, ve.annee, ve.couleur, ve.immatriculation, 
        ve.kilometrage, ve.type_vehicule, ve.carburant,
        c.nom as client_nom, c.prenom as client_prenom, c.email as client_email,
        c.telephone as client_telephone, c.adresse as client_adresse,
        c.ville as client_ville, c.code_postal as client_code_postal
    FROM ventes v
    JOIN vehicules ve ON v.vehicule_id = ve.id
    JOIN clients c ON v.client_id = c.id
    WHERE v.id = ?
");
$stmt->execute([$id]);
$vente = $stmt->fetch();

if (!$vente) {
    header("Location: liste.php");
    exit;
}

// Numéro de facture
$numero_facture = 'FACT-' . date('Y') . '-' . str_pad($vente['id'], 5, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture <?php echo $numero_facture; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .facture-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: #2563eb;
        }
        
        .facture-info {
            text-align: right;
        }
        
        .facture-numero {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .facture-date {
            color: #666;
        }
        
        .parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .partie {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        
        .partie h3 {
            color: #2563eb;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .partie p {
            margin: 5px 0;
            color: #333;
        }
        
        .vehicule-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .vehicule-info h3 {
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .vehicule-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .vehicule-detail {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 5px;
        }
        
        .vehicule-detail strong {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th {
            background: #2563eb;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .totaux {
            text-align: right;
            margin-top: 30px;
        }
        
        .total-line {
            display: flex;
            justify-content: flex-end;
            padding: 10px 0;
            font-size: 1.1rem;
        }
        
        .total-line .label {
            margin-right: 50px;
            color: #666;
        }
        
        .total-line .value {
            min-width: 150px;
            font-weight: bold;
        }
        
        .total-final {
            background: #2563eb;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 1.3rem;
        }
        
        .conditions {
            margin-top: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-left: 4px solid #2563eb;
        }
        
        .conditions h4 {
            color: #2563eb;
            margin-bottom: 10px;
        }
        
        .conditions p {
            color: #666;
            line-height: 1.6;
            margin: 5px 0;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #999;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .buttons {
            text-align: center;
            margin: 30px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 0 10px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1e40af;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .facture-container {
                box-shadow: none;
                padding: 20px;
            }
            
            .buttons {
                display: none;
            }
        }
        
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            background: #10b981;
            color: white;
        }
    </style>
</head>
<body>
    <div class="facture-container">
        <!-- Boutons d'action -->
        <div class="buttons">
            <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimer la facture</button>
            <a href="liste.php" class="btn btn-secondary">← Retour aux ventes</a>
        </div>
        
        <!-- En-tête -->
        <div class="header">
            <div>
                <div class="logo">🚗 PGI Automobile</div>
                <p style="margin-top: 10px; color: #666;">Concessionnaire Automobile</p>
                <p style="color: #666;">123 Avenue des Champs-Élysées</p>
                <p style="color: #666;">75008 Paris, France</p>
                <p style="color: #666;">Tél: 01 23 45 67 89</p>
                <p style="color: #666;">Email: contact@pgi-auto.fr</p>
            </div>
            <div class="facture-info">
                <div class="facture-numero"><?php echo $numero_facture; ?></div>
                <div class="facture-date">Date: <?php echo formatDate($vente['date_vente']); ?></div>
                <div style="margin-top: 10px;">
                    <span class="badge"><?php echo ucfirst($vente['mode_paiement']); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Parties (Vendeur / Client) -->
        <div class="parties">
            <div class="partie">
                <h3>📍 Vendeur</h3>
                <p><strong>PGI Automobile SARL</strong></p>
                <p>SIRET: 123 456 789 00012</p>
                <p>TVA: FR12345678900</p>
                <p>123 Avenue des Champs-Élysées</p>
                <p>75008 Paris, France</p>
            </div>
            
            <div class="partie">
                <h3>👤 Client</h3>
                <p><strong><?php echo escape($vente['client_nom'] . ' ' . $vente['client_prenom']); ?></strong></p>
                <p><?php echo escape($vente['client_adresse'] ?? ''); ?></p>
                <p><?php echo escape($vente['client_code_postal'] . ' ' . $vente['client_ville']); ?></p>
                <p>📧 <?php echo escape($vente['client_email'] ?? ''); ?></p>
                <p>📱 <?php echo escape($vente['client_telephone'] ?? ''); ?></p>
            </div>
        </div>
        
        <!-- Informations du véhicule -->
        <div class="vehicule-info">
            <h3>🚗 Détails du Véhicule Vendu</h3>
            <div class="vehicule-details">
                <div class="vehicule-detail">
                    <strong>Marque & Modèle</strong>
                    <?php echo escape($vente['marque'] . ' ' . $vente['modele']); ?>
                </div>
                <div class="vehicule-detail">
                    <strong>Année</strong>
                    <?php echo $vente['annee']; ?>
                </div>
                <div class="vehicule-detail">
                    <strong>Couleur</strong>
                    <?php echo escape($vente['couleur']); ?>
                </div>
                <div class="vehicule-detail">
                    <strong>Kilométrage</strong>
                    <?php echo number_format($vente['kilometrage'], 0, ',', ' '); ?> km
                </div>
                <div class="vehicule-detail">
                    <strong>Type</strong>
                    <?php echo ucfirst($vente['type_vehicule']); ?>
                </div>
                <div class="vehicule-detail">
                    <strong>Carburant</strong>
                    <?php echo ucfirst($vente['carburant']); ?>
                </div>
                <div class="vehicule-detail">
                    <strong>Immatriculation</strong>
                    <?php echo escape($vente['immatriculation']); ?>
                </div>
                <div class="vehicule-detail">
                    <strong>Date de vente</strong>
                    <?php echo formatDate($vente['date_vente']); ?>
                </div>
            </div>
        </div>
        
        <!-- Tableau des détails -->
        <table>
            <thead>
                <tr>
                    <th>Désignation</th>
                    <th style="text-align: center;">Quantité</th>
                    <th style="text-align: right;">Prix Unitaire</th>
                    <th style="text-align: right;">Total HT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong><?php echo escape($vente['marque'] . ' ' . $vente['modele'] . ' (' . $vente['annee'] . ')'); ?></strong><br>
                        <small style="color: #666;">
                            <?php echo ucfirst($vente['type_vehicule']); ?> - 
                            <?php echo ucfirst($vente['carburant']); ?> - 
                            <?php echo number_format($vente['kilometrage'], 0, ',', ' '); ?> km
                        </small>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;"><?php echo formatPrice($vente['prix_vente']); ?></td>
                    <td style="text-align: right;"><strong><?php echo formatPrice($vente['prix_vente']); ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Totaux -->
        <div class="totaux">
            <div class="total-line">
                <span class="label">Total HT:</span>
                <span class="value"><?php echo formatPrice($vente['prix_vente']); ?></span>
            </div>
            <div class="total-line">
                <span class="label">TVA (20%):</span>
                <span class="value"><?php echo formatPrice($vente['prix_vente'] * 0.20); ?></span>
            </div>
            <div class="total-final">
                <div class="total-line">
                    <span class="label">TOTAL TTC:</span>
                    <span class="value"><?php echo formatPrice($vente['prix_vente'] * 1.20); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        <?php if (!empty($vente['notes'])): ?>
        <div class="conditions">
            <h4>📝 Notes</h4>
            <p><?php echo nl2br(escape($vente['notes'])); ?></p>
        </div>
        <?php endif; ?>
        
        <!-- Conditions générales -->
        <div class="conditions">
            <h4>📋 Conditions Générales de Vente</h4>
            <p>• Le véhicule est vendu dans l'état où il se trouve.</p>
            <p>• Garantie légale de conformité de 2 ans applicable.</p>
            <p>• Mode de paiement: <?php echo ucfirst($vente['mode_paiement']); ?></p>
            <p>• Cette facture est payable à réception.</p>
            <p>• En cas de retard de paiement, des pénalités de 3% par mois seront appliquées.</p>
        </div>
        
        <!-- Signatures -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 60px;">
            <div style="text-align: center;">
                <p style="margin-bottom: 80px; color: #666;">Signature du vendeur</p>
                <div style="border-top: 2px solid #333; padding-top: 10px;">
                    <strong>PGI Automobile</strong>
                </div>
            </div>
            <div style="text-align: center;">
                <p style="margin-bottom: 80px; color: #666;">Signature du client</p>
                <div style="border-top: 2px solid #333; padding-top: 10px;">
                    <strong><?php echo escape($vente['client_nom'] . ' ' . $vente['client_prenom']); ?></strong>
                </div>
            </div>
        </div>
        
        <!-- Pied de page -->
        <div class="footer">
            <p>PGI Automobile - SARL au capital de 50 000€</p>
            <p>SIRET: 123 456 789 00012 - RCS Paris - TVA: FR12345678900</p>
            <p>www.pgi-automobile.fr - contact@pgi-automobile.fr</p>
        </div>
    </div>
</body>
</html>