<?php
$page_title = "Accès refusé";
require_once 'config/auth.php';
requireAuth();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès refusé - PGI Automobile</title>
    <link rel="stylesheet" href="/pgi-automobile/assets/css/style.css">
    <style>
        .error-container {
            min-height: calc(100vh - 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .error-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
        }
        
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }
        
        .error-title {
            font-size: 2rem;
            color: var(--danger);
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .error-details {
            background: #f3f4f6;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: left;
        }
        
        .error-details h4 {
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .error-details p {
            color: #666;
            margin: 0.25rem 0;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">🚫</div>
            <h1 class="error-title">Accès Refusé</h1>
            <p class="error-message">
                Vous n'avez pas les permissions nécessaires pour accéder à cette page.
            </p>
            
            <div class="error-details">
                <h4>Informations de votre compte :</h4>
                <p><strong>Nom :</strong> <?php echo getFullName(); ?></p>
                <p><strong>Rôle :</strong> <?php echo getRoleLabel(); ?></p>
                <p><strong>Email :</strong> <?php echo $_SESSION['user_email'] ?? 'N/A'; ?></p>
            </div>
            
            <p style="color: #666; margin-bottom: 2rem;">
                Si vous pensez qu'il s'agit d'une erreur, veuillez contacter votre administrateur système.
            </p>
            
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="/pgi-automobile/index.php" class="btn btn-primary">
                    🏠 Retour à l'accueil
                </a>
                <a href="javascript:history.back()" class="btn btn-warning">
                    ← Page précédente
                </a>
            </div>
        </div>
    </div>
</body>
</html>
