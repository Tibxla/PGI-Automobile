/**
 * CATALOGUE.JS - JavaScript pour le catalogue de véhicules
 * Fonctions : Filtrage, recherche, et redirection vers demande
 */

/**
 * Filtrer les véhicules selon les critères sélectionnés
 */
function filterVehicules() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
    const carburantFilter = document.getElementById('carburantFilter').value.toLowerCase();
    const prixMax = document.getElementById('prixMax').value;

    const cards = document.querySelectorAll('.vehicule-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const marque = card.dataset.marque;
        const modele = card.dataset.modele;
        const type = card.dataset.type;
        const carburant = card.dataset.carburant;
        const prix = parseFloat(card.dataset.prix);

        let show = true;

        // Filtre recherche (marque ou modèle)
        if (searchInput && !marque.includes(searchInput) && !modele.includes(searchInput)) {
            show = false;
        }

        // Filtre type
        if (typeFilter && type !== typeFilter) {
            show = false;
        }

        // Filtre carburant
        if (carburantFilter && carburant !== carburantFilter) {
            show = false;
        }

        // Filtre prix maximum
        if (prixMax && prix > parseFloat(prixMax)) {
            show = false;
        }

        card.style.display = show ? 'block' : 'none';
        if (show) visibleCount++;
    });

    // Afficher un message si aucun résultat
    const grid = document.getElementById('vehiculesGrid');
    const existingMessage = document.getElementById('no-results-message');
    
    if (visibleCount === 0 && !existingMessage) {
        const message = document.createElement('div');
        message.id = 'no-results-message';
        message.className = 'no-vehicules';
        message.innerHTML = `
            <h2>Aucun véhicule ne correspond à vos critères</h2>
            <p>Essayez de modifier vos filtres pour obtenir plus de résultats</p>
        `;
        message.style.gridColumn = '1 / -1';
        grid.appendChild(message);
    } else if (visibleCount > 0 && existingMessage) {
        existingMessage.remove();
    }

    console.log(`🔍 Filtrage: ${visibleCount} véhicule(s) affiché(s)`);
}

/**
 * Faire une demande d'achat pour un véhicule
 */
function faireDemandeAchat(vehiculeId) {
    window.location.href = 'demande.php?vehicule_id=' + vehiculeId;
}

/**
 * Réinitialiser tous les filtres
 */
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('carburantFilter').value = '';
    document.getElementById('prixMax').value = '';
    filterVehicules();
}

/**
 * Animation au chargement
 */
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.vehicule-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });

    console.log('🚗 Catalogue chargé avec succès');
});
