<?php
session_start();

//Importer les ressources 

include "./utils/functions.php";
include "./Model/UsersModel.php";
include "./View/header.php";
include "./View/footer.php";
include './Model/TaskModel.php';
include './Model/CategoryModel.php';



//1) Récupérer l'url entré par l'utilisateur
$url = parse_url($_SERVER['REQUEST_URI']);

var_dump($url);

$path='';

if(isset($url['path'])){
    $path = $url['path'];
}else{
    $path = '/Projet_task-MVC-POO-Part02/Projet_task/';
}

switch($path){
    case '/Projet_task-MVC-POO-Part02/Projet_task/' :
    case '/Projet_task-MVC-POO-Part02/Projet_task/accueil' :
        include './controller/accueil.php';
        break;
    
    case '/Projet_task-MVC-POO-Part02/Projet_task/Se_deconnecter' :
        include './controller/deco.php';
        break;

    case '/Projet_task-MVC-POO-Part02/Projet_task/Info' :
        include './controller/info.php';
        break;


    case '/Projet_task-MVC-POO-Part02/Projet_task/Mes_taches' :
        
        include './View/view_task.php';
        include './controller/task.php';
        break;
    
    default : echo "erreur" ;
    break;
}

?>