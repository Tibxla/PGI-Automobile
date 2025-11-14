<?php
/**
 * CONFIGURATION EXEMPLE - PGI AUTOMOBILE
 *
 * INSTRUCTIONS :
 * 1. Copier ce fichier : cp config.example.php config.php
 * 2. Remplir les valeurs ci-dessous
 * 3. Ajouter config.php au .gitignore
 */

// Environnement
define('APP_ENV', 'development'); // development, staging, production
define('APP_DEBUG', true);        // false en production !
define('APP_URL', 'http://localhost/PGI-Automobile');

// Base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'pgi_automobile');
define('DB_USER', 'root');
define('DB_PASS', '');

// Sécurité
define('SECURITY_SALT', 'CHANGEZ_CETTE_VALEUR_ALEATOIRE_EN_PRODUCTION');
define('SESSION_LIFETIME', 7200); // 2 heures en secondes

// Email (pour notifications futures)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', '');
define('MAIL_PASSWORD', '');
define('MAIL_FROM', 'noreply@pgi-auto.com');

// Uploads
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_EXTENSIONS', 'jpg,jpeg,png,gif,pdf');
define('UPLOAD_DIR', __DIR__ . '/../assets/images/uploads/');

// Logs
define('LOG_ERRORS', true);
define('LOG_DIR', __DIR__ . '/../logs/');

// Performance
define('ENABLE_CACHE', false); // true en production
define('CACHE_LIFETIME', 3600); // 1 heure

// Sécurité avancée
define('ENABLE_CSRF', true);
define('ENABLE_HTTPS_ONLY', false); // true en production
define('ENABLE_STRICT_MODE', false); // true en production

// Maintenance
define('MAINTENANCE_MODE', false);
define('MAINTENANCE_MESSAGE', 'Site en maintenance. Retour prévu à 14h.');

?>
