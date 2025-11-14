<?php
/**
 * Fichier de fonctions utilitaires pour PGI Automobile
 * Version nettoyée - Fonctions réellement utilisées
 */

/**
 * Générer un code de référence unique pour une vente
 */
function genererCodeVente($id) {
    return 'VT-' . date('Y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
}

/**
 * Générer un code de référence unique pour un véhicule
 */
function genererCodeVehicule($id) {
    return 'VH-' . date('Y') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);
}

/**
 * Formater un nombre
 * Utilisé pour afficher les prix, kilométrages, etc.
 */
function formatNombre($nombre, $decimales = 0) {
    return number_format($nombre, $decimales, ',', ' ');
}

/**
 * Générer un token sécurisé
 * Utilisé pour CSRF, sessions, etc.
 */
function genererToken($longueur = 32) {
    return bin2hex(random_bytes($longueur));
}

/**
 * Pagination
 * Génère les liens de pagination HTML
 */
function pagination($page_actuelle, $total_items, $items_par_page = 20) {
    $total_pages = ceil($total_items / $items_par_page);
    $html = '<div class="pagination">';

    if ($page_actuelle > 1) {
        $html .= '<a href="?page=' . ($page_actuelle - 1) . '">« Précédent</a>';
    }

    for ($i = 1; $i <= $total_pages; $i++) {
        $active = ($i == $page_actuelle) ? ' class="active"' : '';
        $html .= '<a href="?page=' . $i . '"' . $active . '>' . $i . '</a>';
    }

    if ($page_actuelle < $total_pages) {
        $html .= '<a href="?page=' . ($page_actuelle + 1) . '">Suivant »</a>';
    }

    $html .= '</div>';
    return $html;
}
?>
