<?php
/**
 * CATALOGUE.PHP - Version Moderne
 * Design glassmorphism avec icônes animées
 * Accessible par : Visiteurs et Clients
 */

session_start();

// Contrôle d'accès : Employés redirigés vers dashboard
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'client') {
    header('Location: dashboard.php');
    exit();
}

require_once 'config/database.php';

// Récupérer tous les véhicules en stock
$query = "SELECT * FROM vehicules WHERE statut = 'stock' ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$vehicules = $stmt->fetchAll();

$est_connecte = isset($_SESSION['user_id']);

// Mapper les icônes par type
$icons = [
    'berline' => '🚗',
    'suv' => '🚙',
    'sportive' => '🏎️',
    'citadine' => '🚕',
    'utilitaire' => '🚚'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Véhicules - PGI Automobile</title>

    <!-- CSS Externes -->
    <link rel="stylesheet" href="assets/css/public.css">
    <link rel="stylesheet" href="assets/css/catalogue-moderne.css">
</head>
<body>
<div class="container">
    <!-- En-tête -->
    <div class="header">
        <div>
            <h1>🚗 Catalogue Véhicules
                <span class="count-badge"><?php echo count($vehicules); ?> disponibles</span>
            </h1>
            <p style="color: #666; margin-top: 5px;">Découvrez notre sélection premium</p>
        </div>
        <div class="header-buttons">
            <?php if ($est_connecte): ?>
                <a href="modules/clients/mes-demandes.php" class="btn btn-secondary">📋 Mes Demandes</a>
                <a href="logout.php" class="btn btn-warning">🚪 Déconnexion</a>
            <?php else: ?>
                <a href="client-inscription.php" class="btn btn-secondary">📝 Créer un compte</a>
                <a href="login.php" class="btn btn-primary">🔐 Se connecter</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters">
        <h3>🔎 Filtrer les véhicules</h3>
        <div class="filter-group">
            <input type="text" id="searchInput" placeholder="🔍 Rechercher (marque, modèle...)" onkeyup="filterVehicules()">
            <select id="typeFilter" onchange="filterVehicules()">
                <option value="">🚗 Tous les types</option>
                <option value="berline">🚗 Berline</option>
                <option value="suv">🚙 SUV</option>
                <option value="sportive">🏎️ Sportive</option>
                <option value="citadine">🚕 Citadine</option>
                <option value="utilitaire">🚚 Utilitaire</option>
            </select>
            <select id="carburantFilter" onchange="filterVehicules()">
                <option value="">⛽ Tous les carburants</option>
                <option value="essence">⛽ Essence</option>
                <option value="diesel">🛢️ Diesel</option>
                <option value="electrique">⚡ Électrique</option>
                <option value="hybride">🔋 Hybride</option>
            </select>
            <input type="number" id="prixMax" placeholder="💰 Prix maximum (€)" onkeyup="filterVehicules()">
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

                    <!-- Header avec icône -->
                    <div class="vehicule-header">
                        <div class="status-badge">✨ Disponible</div>
                        <div class="vehicule-icon" data-type="<?php echo $vehicule['type_vehicule']; ?>"></div>
                        <div class="vehicule-header-title">
                            <?php echo htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']); ?>
                        </div>
                    </div>

                    <!-- Corps de la carte -->
                    <div class="vehicule-body">
                        <!-- Tags -->
                        <div class="vehicule-tags">
                            <span class="tag tag-type">
                                <?php echo $icons[$vehicule['type_vehicule']] ?? '🚗'; ?>
                                <?php echo ucfirst($vehicule['type_vehicule']); ?>
                            </span>
                            <span class="tag tag-carburant">
                                <?php
                                $carburant_icons = [
                                    'essence' => '⛽',
                                    'diesel' => '🛢️',
                                    'electrique' => '⚡',
                                    'hybride' => '🔋'
                                ];
                                echo $carburant_icons[$vehicule['carburant']] ?? '⛽';
                                ?>
                                <?php echo ucfirst($vehicule['carburant']); ?>
                            </span>
                            <span class="tag tag-annee">
                                📅 <?php echo $vehicule['annee']; ?>
                            </span>
                        </div>

                        <!-- Grille d'informations -->
                        <div class="vehicule-info-grid">
                            <div class="info-card">
                                <div class="info-icon">🎨</div>
                                <div class="info-content">
                                    <div class="info-label">Couleur</div>
                                    <div class="info-value"><?php echo htmlspecialchars($vehicule['couleur']); ?></div>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">🛣️</div>
                                <div class="info-content">
                                    <div class="info-label">Kilométrage</div>
                                    <div class="info-value"><?php echo number_format($vehicule['kilometrage'], 0, ',', ' '); ?> km</div>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">📅</div>
                                <div class="info-content">
                                    <div class="info-label">Arrivée</div>
                                    <div class="info-value"><?php echo date('d/m/Y', strtotime($vehicule['date_arrivee'])); ?></div>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">📖</div>
                                <div class="info-content">
                                    <div class="info-label">Immatriculation</div>
                                    <div class="info-value"><?php echo htmlspecialchars($vehicule['immatriculation']); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="vehicule-price-section">
                            <div class="price-label">Prix TTC</div>
                            <div class="vehicule-price">
                                <?php echo number_format($vehicule['prix_vente'], 0, ',', ' '); ?> €
                            </div>
                        </div>

                        <!-- Bouton demande -->
                        <div class="btn-demande-wrapper">
                            <button class="btn-demande" onclick="faireDemandeAchat(<?php echo $vehicule['id']; ?>)">
                                <span>📩</span>
                                <span>Faire une demande d'achat</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-vehicules">
                <h2>Aucun véhicule disponible</h2>
                <p>Revenez bientôt pour découvrir nos nouvelles arrivées !</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript -->
<script src="assets/js/catalogue.js"></script>

<script>
    // Animation au scroll
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.vehicule-card');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 100);
                }
            });
        }, {
            threshold: 0.1
        });

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease-out';
            observer.observe(card);
        });
    });
</script>
</body>
</html>