<?php
/**
 * Fichier de fonctions utilitaires pour PGI Automobile
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
 * Calculer l'âge d'un véhicule
 */
function calculerAgeVehicule($annee) {
    return date('Y') - $annee;
}

/**
 * Formater le kilométrage
 */
function formatKilometrage($km) {
    return number_format($km, 0, ',', ' ') . ' km';
}

/**
 * Obtenir l'icône d'un type de véhicule
 */
function getIconeTypeVehicule($type) {
    $icones = [
        'berline' => '🚗',
        'suv' => '🚙',
        'sportive' => '🏎️',
        'utilitaire' => '🚚',
        'citadine' => '🚕'
    ];
    return $icones[$type] ?? '🚗';
}

/**
 * Obtenir la couleur d'un badge de statut
 */
function getStatutBadgeClass($statut) {
    $classes = [
        'stock' => 'badge-stock',
        'vendu' => 'badge-vendu',
        'reserve' => 'badge-reserve'
    ];
    return $classes[$statut] ?? 'badge-stock';
}

/**
 * Calculer le taux de marge
 */
function calculerTauxMarge($prix_achat, $prix_vente) {
    if ($prix_achat <= 0) return 0;
    return (($prix_vente - $prix_achat) / $prix_achat) * 100;
}

/**
 * Formater un pourcentage
 */
function formatPourcentage($valeur, $decimales = 1) {
    return number_format($valeur, $decimales, ',', ' ') . '%';
}

/**
 * Valider un email
 */
function validerEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valider un numéro de téléphone français
 */
function validerTelephone($telephone) {
    // Accepte les formats: 0612345678, 06 12 34 56 78, 06.12.34.56.78, 06-12-34-56-78
    $telephone = preg_replace('/[^0-9]/', '', $telephone);
    return preg_match('/^0[1-9][0-9]{8}$/', $telephone);
}

/**
 * Formater un numéro de téléphone
 */
function formatTelephone($telephone) {
    $telephone = preg_replace('/[^0-9]/', '', $telephone);
    if (strlen($telephone) === 10) {
        return substr($telephone, 0, 2) . ' ' .
            substr($telephone, 2, 2) . ' ' .
            substr($telephone, 4, 2) . ' ' .
            substr($telephone, 6, 2) . ' ' .
            substr($telephone, 8, 2);
    }
    return $telephone;
}

/**
 * Générer un mot de passe aléatoire sécurisé
 */
function genererMotDePasse($longueur = 12) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    $max = strlen($caracteres) - 1;

    for ($i = 0; $i < $longueur; $i++) {
        $password .= $caracteres[random_int(0, $max)];
    }

    return $password;
}

/**
 * Obtenir le mois en français
 */
function getMoisFrancais($mois) {
    $mois_fr = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    return $mois_fr[(int)$mois] ?? '';
}

/**
 * Formater une période (mois/année)
 */
function formatPeriode($date) {
    $timestamp = strtotime($date . '-01');
    return getMoisFrancais(date('n', $timestamp)) . ' ' . date('Y', $timestamp);
}

/**
 * Calculer la différence en jours entre deux dates
 */
function joursEntre($date1, $date2 = null) {
    $date2 = $date2 ?? date('Y-m-d');
    $diff = strtotime($date2) - strtotime($date1);
    return floor($diff / (60 * 60 * 24));
}

/**
 * Vérifier si une date est dans le passé
 */
function estDansLePasse($date) {
    return strtotime($date) < strtotime(date('Y-m-d'));
}

/**
 * Obtenir le premier jour du mois
 */
function premierJourMois($mois = null, $annee = null) {
    $mois = $mois ?? date('m');
    $annee = $annee ?? date('Y');
    return date('Y-m-d', strtotime("$annee-$mois-01"));
}

/**
 * Obtenir le dernier jour du mois
 */
function dernierJourMois($mois = null, $annee = null) {
    $mois = $mois ?? date('m');
    $annee = $annee ?? date('Y');
    return date('Y-m-t', strtotime("$annee-$mois-01"));
}

/**
 * Tronquer un texte
 */
function tronquer($texte, $longueur = 50, $suffixe = '...') {
    if (strlen($texte) <= $longueur) {
        return $texte;
    }
    return substr($texte, 0, $longueur) . $suffixe;
}

/**
 * Obtenir l'initiale d'un nom
 */
function getInitiales($prenom, $nom) {
    return strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
}

/**
 * Formater un nombre
 */
function formatNombre($nombre, $decimales = 0) {
    return number_format($nombre, $decimales, ',', ' ');
}

/**
 * Convertir un statut en texte français
 */
function getStatutTexte($statut) {
    $statuts = [
        'stock' => 'En stock',
        'vendu' => 'Vendu',
        'reserve' => 'Réservé',
        'actif' => 'Actif',
        'inactif' => 'Inactif',
        'suspendu' => 'Suspendu'
    ];
    return $statuts[$statut] ?? ucfirst($statut);
}

/**
 * Logger une action dans un fichier
 */
function loggerAction($action, $details = '') {
    $log_file = __DIR__ . '/../logs/actions.log';
    $log_dir = dirname($log_file);

    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $timestamp = date('Y-m-d H:i:s');
    $user = $_SESSION['user_email'] ?? 'Système';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

    $log_entry = "[$timestamp] [$user] [$ip] $action";
    if ($details) {
        $log_entry .= " - $details";
    }
    $log_entry .= "\n";

    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

/**
 * Envoyer un email (fonction de base)
 */
function envoyerEmail($destinataire, $sujet, $message) {
    // Configuration basique - À adapter selon vos besoins
    $headers = "From: noreply@pgi-auto.com\r\n";
    $headers .= "Reply-To: contact@pgi-auto.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($destinataire, $sujet, $message, $headers);
}

/**
 * Générer un token sécurisé
 */
function genererToken($longueur = 32) {
    return bin2hex(random_bytes($longueur));
}

/**
 * Vérifier si un fichier est une image
 */
function estImage($fichier) {
    $extensions_valides = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($fichier, PATHINFO_EXTENSION));
    return in_array($extension, $extensions_valides);
}

/**
 * Redimensionner une image (fonction basique)
 */
function redimensionnerImage($source, $destination, $largeur_max, $hauteur_max) {
    list($largeur_orig, $hauteur_orig, $type) = getimagesize($source);

    $ratio = $largeur_orig / $hauteur_orig;

    if ($largeur_max / $hauteur_max > $ratio) {
        $largeur_nouvelle = $hauteur_max * $ratio;
        $hauteur_nouvelle = $hauteur_max;
    } else {
        $hauteur_nouvelle = $largeur_max / $ratio;
        $largeur_nouvelle = $largeur_max;
    }

    $image_nouvelle = imagecreatetruecolor($largeur_nouvelle, $hauteur_nouvelle);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $image_orig = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $image_orig = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $image_orig = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    imagecopyresampled($image_nouvelle, $image_orig, 0, 0, 0, 0,
        $largeur_nouvelle, $hauteur_nouvelle,
        $largeur_orig, $hauteur_orig);

    imagejpeg($image_nouvelle, $destination, 90);
    imagedestroy($image_orig);
    imagedestroy($image_nouvelle);

    return true;
}

/**
 * Pagination
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