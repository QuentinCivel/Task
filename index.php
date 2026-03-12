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

// 1) Calculer le chemin de base (ex: "/Projet_task" ou "/Mon_Dossier")
$basePath = dirname($_SERVER['SCRIPT_NAME']);

// 2) Récupérer l'URL exacte tapée par l'utilisateur
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 3) Soustraire le dossier de base de l'URL pour ne garder que la "Route" pure
$route = str_replace($basePath, '', $uri);

// 4) Si l'utilisateur tape juste le nom du dossier, on le redirige sur l'accueil par défaut
if ($route === '' || $route === '/') {
    $route = '/accueil';
}

// 5) Le Routeur (Switch)
// Plus besoin de mettre "/Projet_task/" partout, on utilise juste les noms de nos pages !
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
        include './View/view_task.php';
        break;
    
    default : 
        // La bonne pratique est d'afficher une belle erreur 404
        echo "<h1>Erreur 404</h1><p>Cette page n'existe pas.</p>";
        break;
}
?>