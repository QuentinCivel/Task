<?php
// config.php

// 1. Charger le fichier .env
$envFile = __DIR__ . '/.env';

if (file_exists($envFile)) {
    // La fonction parse_ini_file transforme le fichier .env en tableau PHP !
    $env = parse_ini_file($envFile);
} else {
    die("Fichier .env introuvable. Veuillez le créer à la racine du projet.");
}

// 2. Définition des identifiants à partir du tableau $env
define('DB_HOST', $env['DB_HOST']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);

// 3. Création de la connexion PDO
try {
    $bdd = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', 
        DB_USER, 
        DB_PASS, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (Exception $e) {
    $date = date('Y-m-d H:i:s');
    error_log("[{$date}] Erreur FATALE de connexion BDD : " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/logs/errors.log');
    
    die("<h1>Erreur critique</h1><p>Impossible de se connecter à la base de données pour le moment.</p>");
}
?>