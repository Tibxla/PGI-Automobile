<?php
require_once '../../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // Vérifier si le véhicule existe
        $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
        $stmt->execute([$id]);
        $vehicule = $stmt->fetch();
        
        if ($vehicule) {
            // Supprimer le véhicule
            $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id = ?");
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