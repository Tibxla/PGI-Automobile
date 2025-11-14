<?php
require_once '../../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // Vérifier si le client a des ventes
        $stmt = $pdo->prepare("SELECT COUNT(*) as nb_ventes FROM ventes WHERE client_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        if ($result['nb_ventes'] > 0) {
            header("Location: liste.php?error=client_has_sales");
            exit;
        }
        
        // Vérifier si le client existe
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $client = $stmt->fetch();
        
        if ($client) {
            // Supprimer le client
            $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            
            header("Location: liste.php?deleted=1");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: liste.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

header("Location: liste.php");
exit;
?>