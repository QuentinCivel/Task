<?php
session_start();

// Importer les ressources 
include "./config.php";
include "./utils/functions.php";
include "./Model/UsersModel.php";
include "./View/header.php";
include "./View/footer.php";
include './Model/TaskModel.php';
include './Model/CategoryModel.php';

// Récupérer la route pure tapée par l'utilisateur
$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Si l'utilisateur tape juste le nom de domaine, on le redirige sur l'accueil
if ($route === '' || $route === '/') {
    $route = '/accueil';
}

// Le Routeur (Switch)
switch ($route) {
    case '/accueil' :
        include './controller/accueil.php';
        break;
    
    case '/Se_deconnecter' :
        include './controller/deco.php';
        break;

    case '/Info' :
        include './controller/info.php';
        break;

    case '/Mes_taches' :
        include './controller/task.php';
        break;
    
    default : 
        echo "<h1>Erreur 404</h1><p>Cette page n'existe pas.</p>";
        break;
}


?>