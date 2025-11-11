<?php
/**
 * Script de diagnostic - Système d'authentification
 * À exécuter dans le navigateur : http://localhost/PGI-Automobile/test-auth.php
 */

require_once 'config/database.php';

echo "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>Diagnostic Authentification</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 900px; 
            margin: 50px auto; 
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .success { color: #10b981; font-weight: bold; }
        .error { color: #ef4444; font-weight: bold; }
        .warning { color: #f59e0b; font-weight: bold; }
        h1 { color: #2563eb; }
        h2 { color: #1e40af; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f3f4f6; font-weight: bold; }
        .code { 
            background: #1f2937; 
            color: #10b981; 
            padding: 15px; 
            border-radius: 5px; 
            font-family: monospace;
            margin: 10px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover { background: #1e40af; }
    </style>
</head>
<body>
    <h1>🔍 Diagnostic du Système d'Authentification</h1>";

// Test 1 : Connexion à la base de données
echo "<div class='section'>";
echo "<h2>1️⃣ Test de connexion à la base de données</h2>";
try {
    $pdo->query("SELECT 1");
    echo "<p class='success'>✅ Connexion à la base de données réussie</p>";
} catch (PDOException $e) {
    echo "<p class='error'>❌ Erreur de connexion : " . $e->getMessage() . "</p>";
    echo "</div></body></html>";
    exit;
}
echo "</div>";

// Test 2 : Vérifier si la table utilisateurs existe
echo "<div class='section'>";
echo "<h2>2️⃣ Vérification de la table 'utilisateurs'</h2>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'utilisateurs'");
    if ($stmt->rowCount() > 0) {
        echo "<p class='success'>✅ La table 'utilisateurs' existe</p>";
        
        // Compter les utilisateurs
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM utilisateurs");
        $count = $stmt->fetch()['count'];
        echo "<p>👥 Nombre d'utilisateurs : <strong>$count</strong></p>";
        
    } else {
        echo "<p class='error'>❌ La table 'utilisateurs' n'existe pas</p>";
        echo "<p class='warning'>⚠️ Vous devez exécuter le script SQL 'auth_system.sql'</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Erreur : " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 3 : Lister les utilisateurs
echo "<div class='section'>";
echo "<h2>3️⃣ Liste des utilisateurs dans la base</h2>";
try {
    $stmt = $pdo->query("SELECT id, nom, prenom, email, role, statut FROM utilisateurs");
    $users = $stmt->fetchAll();
    
    if (count($users) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th>Statut</th></tr>";
        foreach ($users as $user) {
            $statusColor = $user['statut'] === 'actif' ? 'success' : 'error';
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['nom']}</td>";
            echo "<td>{$user['prenom']}</td>";
            echo "<td><strong>{$user['email']}</strong></td>";
            echo "<td>{$user['role']}</td>";
            echo "<td class='$statusColor'>{$user['statut']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='warning'>⚠️ Aucun utilisateur trouvé dans la base</p>";
        echo "<p>Vous devez créer au moins un utilisateur admin.</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Erreur : " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 4 : Tester le hash d'un mot de passe
echo "<div class='section'>";
echo "<h2>4️⃣ Test du système de hash des mots de passe</h2>";

$test_password = "password123";
$test_hash = password_hash($test_password, PASSWORD_DEFAULT);

echo "<p>🔐 Mot de passe de test : <code>$test_password</code></p>";
echo "<p>🔑 Hash généré : <code style='word-break: break-all;'>$test_hash</code></p>";

// Vérifier le hash
if (password_verify($test_password, $test_hash)) {
    echo "<p class='success'>✅ La fonction password_verify() fonctionne correctement</p>";
} else {
    echo "<p class='error'>❌ Problème avec password_verify()</p>";
}
echo "</div>";

// Test 5 : Vérifier le hash des utilisateurs existants
echo "<div class='section'>";
echo "<h2>5️⃣ Vérification des mots de passe dans la base</h2>";
try {
    $stmt = $pdo->query("SELECT email, password FROM utilisateurs LIMIT 5");
    $users = $stmt->fetchAll();
    
    if (count($users) > 0) {
        echo "<p>Test de vérification du mot de passe 'password123' pour chaque utilisateur :</p>";
        echo "<table>";
        echo "<tr><th>Email</th><th>Hash (début)</th><th>Test avec 'password123'</th></tr>";
        
        foreach ($users as $user) {
            $hash_preview = substr($user['password'], 0, 30) . "...";
            $test_result = password_verify('password123', $user['password']);
            $resultClass = $test_result ? 'success' : 'error';
            $resultText = $test_result ? '✅ OK' : '❌ ÉCHEC';
            
            echo "<tr>";
            echo "<td><strong>{$user['email']}</strong></td>";
            echo "<td><code>$hash_preview</code></td>";
            echo "<td class='$resultClass'>$resultText</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Erreur : " . $e->getMessage() . "</p>";
}
echo "</div>";

// Test 6 : Simuler une connexion
echo "<div class='section'>";
echo "<h2>6️⃣ Simulation de connexion</h2>";

$test_email = "admin@pgi-auto.com";
$test_password = "password123";

echo "<p>Test de connexion avec :</p>";
echo "<ul>";
echo "<li>📧 Email : <strong>$test_email</strong></li>";
echo "<li>🔐 Mot de passe : <strong>$test_password</strong></li>";
echo "</ul>";

try {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$test_email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<p class='success'>✅ Utilisateur trouvé dans la base</p>";
        echo "<p>👤 Nom : {$user['nom']} {$user['prenom']}</p>";
        echo "<p>🎭 Rôle : {$user['role']}</p>";
        echo "<p>📊 Statut : {$user['statut']}</p>";
        
        if ($user['statut'] !== 'actif') {
            echo "<p class='warning'>⚠️ ATTENTION : Le compte n'est pas actif !</p>";
        }
        
        if (password_verify($test_password, $user['password'])) {
            echo "<p class='success'>✅ Le mot de passe est correct !</p>";
            echo "<p class='success'>🎉 La connexion devrait fonctionner !</p>";
        } else {
            echo "<p class='error'>❌ Le mot de passe ne correspond pas</p>";
            echo "<p class='warning'>⚠️ Le hash dans la base est peut-être incorrect</p>";
        }
    } else {
        echo "<p class='error'>❌ Aucun utilisateur trouvé avec cet email</p>";
        echo "<p class='warning'>⚠️ Vous devez créer cet utilisateur</p>";
    }
} catch (PDOException $e) {
    echo "<p class='error'>❌ Erreur : " . $e->getMessage() . "</p>";
}
echo "</div>";

// Solution : Script SQL pour créer un admin
echo "<div class='section'>";
echo "<h2>🔧 Solution : Créer un utilisateur admin</h2>";
echo "<p>Si aucun utilisateur ne fonctionne, exécutez ce script SQL dans phpMyAdmin :</p>";

$new_hash = password_hash('password123', PASSWORD_DEFAULT);

echo "<div class='code'>";
echo "-- Supprimer l'ancien admin s'il existe<br>";
echo "DELETE FROM utilisateurs WHERE email = 'admin@pgi-auto.com';<br><br>";
echo "-- Créer un nouvel admin<br>";
echo "INSERT INTO utilisateurs (nom, prenom, email, password, role, statut)<br>";
echo "VALUES ('Admin', 'Super', 'admin@pgi-auto.com', '$new_hash', 'admin', 'actif');<br><br>";
echo "-- Vérifier<br>";
echo "SELECT id, nom, prenom, email, role FROM utilisateurs WHERE email = 'admin@pgi-auto.com';";
echo "</div>";

echo "<p><a href='reinitialiser-admin.php' class='btn'>🔄 Créer automatiquement un admin</a></p>";
echo "</div>";

echo "<div class='section'>";
echo "<h2>🔗 Actions suivantes</h2>";
echo "<p><a href='login.php' class='btn'>🔐 Essayer de se connecter</a></p>";
echo "<p><a href='reinitialiser-admin.php' class='btn'>🔄 Réinitialiser l'admin</a></p>";
echo "</div>";

echo "</body></html>";
?>
