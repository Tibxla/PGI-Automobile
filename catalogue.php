<?php
// Page publique - Accessible sans connexion
require_once 'config/database.php';

// Récupérer tous les véhicules disponibles à la vente (stock)
$query = "SELECT * FROM vehicules WHERE statut = 'stock' ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$vehicules = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Véhicules - PGI Automobile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-secondary {
            background: #48bb78;
            color: white;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .filters h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        .vehicules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }

        .vehicule-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .vehicule-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0,0,0,0.2);
        }

        .vehicule-image {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }

        .vehicule-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .vehicule-details {
            padding: 20px;
        }

        .vehicule-title {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .vehicule-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 15px 0;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #666;
        }

        .info-item strong {
            color: #333;
        }

        .vehicule-price {
            font-size: 28px;
            font-weight: bold;
            color: #48bb78;
            margin: 15px 0;
        }

        .vehicule-tags {
            display: flex;
            gap: 10px;
            margin: 15px 0;
        }

        .tag {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .tag-type {
            background: #e6f3ff;
            color: #0066cc;
        }

        .tag-carburant {
            background: #f0f9ff;
            color: #0891b2;
        }

        .tag-annee {
            background: #fef3c7;
            color: #d97706;
        }

        .btn-demande {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-demande:hover {
            background: #5568d3;
        }

        .no-vehicules {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .no-vehicules h2 {
            color: #666;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .count-badge {
            background: #48bb78;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-left: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- En-tête -->
    <div class="header">
        <div>
            <h1>🚗 Catalogue Véhicules
                <span class="count-badge"><?php echo count($vehicules); ?> disponibles</span>
            </h1>
            <p style="color: #666; margin-top: 5px;">Découvrez notre sélection de véhicules</p>
        </div>
        <div class="header-buttons">
            <a href="client-inscription.php" class="btn btn-secondary">📝 Créer un compte</a>
            <a href="login.php" class="btn btn-primary">🔐 Se connecter</a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters">
        <h3>🔍 Filtrer les véhicules</h3>
        <div class="filter-group">
            <input type="text" id="searchInput" placeholder="🔎 Rechercher (marque, modèle...)" onkeyup="filterVehicules()">
            <select id="typeFilter" onchange="filterVehicules()">
                <option value="">Tous les types</option>
                <option value="berline">Berline</option>
                <option value="suv">SUV</option>
                <option value="sportive">Sportive</option>
                <option value="citadine">Citadine</option>
                <option value="utilitaire">Utilitaire</option>
            </select>
            <select id="carburantFilter" onchange="filterVehicules()">
                <option value="">Tous les carburants</option>
                <option value="essence">Essence</option>
                <option value="diesel">Diesel</option>
                <option value="electrique">Électrique</option>
                <option value="hybride">Hybride</option>
            </select>
            <input type="number" id="prixMax" placeholder="💰 Prix maximum" onkeyup="filterVehicules()">
        </div>
    </div>

    <!-- Grille des véhicules -->
    <div class="vehicules-grid" id="vehiculesGrid">
        <?php if (count($vehicules) > 0): ?>
            <?php foreach ($vehicules as $vehicule): ?>
                <div class="vehicule-card"
                     data-marque="<?php echo strtolower($vehicule['marque']); ?>"
                     data-modele="<?php echo strtolower($vehicule['modele']); ?>"
                     data-type="<?php echo $vehicule['type_vehicule']; ?>"
                     data-carburant="<?php echo $vehicule['carburant']; ?>"
                     data-prix="<?php echo $vehicule['prix_vente']; ?>">

                    <div class="vehicule-image">
                        <?php if (!empty($vehicule['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($vehicule['image_url']); ?>" alt="<?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?>">
                        <?php else: ?>
                            🚗
                        <?php endif; ?>
                    </div>

                    <div class="vehicule-details">
                        <div class="vehicule-title">
                            <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?>
                        </div>

                        <div class="vehicule-tags">
                            <span class="tag tag-type"><?php echo ucfirst($vehicule['type_vehicule']); ?></span>
                            <span class="tag tag-carburant"><?php echo ucfirst($vehicule['carburant']); ?></span>
                            <span class="tag tag-annee"><?php echo $vehicule['annee']; ?></span>
                        </div>

                        <div class="vehicule-info">
                            <div class="info-item">
                                <span>🎨</span>
                                <strong><?php echo htmlspecialchars($vehicule['couleur']); ?></strong>
                            </div>
                            <div class="info-item">
                                <span>🛣️</span>
                                <strong><?php echo number_format($vehicule['kilometrage'], 0, ',', ' '); ?> km</strong>
                            </div>
                            <div class="info-item">
                                <span>📅</span>
                                <strong><?php echo date('d/m/Y', strtotime($vehicule['date_arrivee'])); ?></strong>
                            </div>
                            <div class="info-item">
                                <span>🔖</span>
                                <strong><?php echo htmlspecialchars($vehicule['immatriculation']); ?></strong>
                            </div>
                        </div>

                        <div class="vehicule-price">
                            <?php echo number_format($vehicule['prix_vente'], 0, ',', ' '); ?> €
                        </div>

                        <button class="btn-demande" onclick="faireDemandeAchat(<?php echo $vehicule['id']; ?>, '<?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?>')">
                            📩 Faire une demande d'achat
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-vehicules">
                <h2>Aucun véhicule disponible pour le moment</h2>
                <p>Revenez bientôt pour découvrir nos nouvelles arrivées !</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function filterVehicules() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
        const carburantFilter = document.getElementById('carburantFilter').value.toLowerCase();
        const prixMax = document.getElementById('prixMax').value;

        const cards = document.querySelectorAll('.vehicule-card');

        cards.forEach(card => {
            const marque = card.dataset.marque;
            const modele = card.dataset.modele;
            const type = card.dataset.type;
            const carburant = card.dataset.carburant;
            const prix = parseFloat(card.dataset.prix);

            let show = true;

            if (searchInput && !marque.includes(searchInput) && !modele.includes(searchInput)) {
                show = false;
            }

            if (typeFilter && type !== typeFilter) {
                show = false;
            }

            if (carburantFilter && carburant !== carburantFilter) {
                show = false;
            }

            if (prixMax && prix > parseFloat(prixMax)) {
                show = false;
            }

            card.style.display = show ? 'block' : 'none';
        });
    }

    function faireDemandeAchat(vehiculeId, vehiculeNom) {
        window.location.href = 'demande.php?vehicule_id=' + vehiculeId;
    }
</script>
</body>
</html>