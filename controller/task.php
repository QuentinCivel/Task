<?php

//Initialisation des variables d'affichages
$title = "Mes ToDoes";
$style = "./src/style/style.css";
$message = "";
$checkboxCategories = '';
$todoList = '';

//Traitement de l'affichage des Categories dans le formulaire


// Lancer la requête SELECT via le model

$categoryModel = new Category($bdd);
$taskModel = new Task($bdd);
$data = $categoryModel->readCategories();

// Créer la liste des checkbox pour le formulaire
foreach($data as $category){
    $checkboxCategories = $checkboxCategories."<input id='{$category['category']}' type='checkbox' name='category[]' value='{$category['id_category']}'><label for='{$category['category']}'> {$category['category']} </label>";
}

//Le Traitement le formulaire d'Ajout d'une Task
//1) Vérifier la réception du formulaire
if(isset($_POST['addTask'])){
   //2) Sécurité : Vérifier les champs vides
    if(!empty($_POST['nameTask']) && !empty($_POST['contentTask']) && !empty($_POST['dateTask']) && !empty($_POST['category'])){
        //3) Sécurité : vérifier le format des données -> pas de format à valider (sauf utilisation de REGEX)
        //booléen à true pour gérer le format des checkbox
        $bool = true;
        //boucle sur le tableau de checkbox
        foreach($_POST['category'] as $category){
            //Si au moins une checkbox n'est pas un entier naturel, alors je mets le booléen à false
            if(!filter_var($category, FILTER_VALIDATE_INT)){
                $bool = false;
            }
        }
        //Bool = true si checkbox au bon format
        if($bool){
            //4) Sécurité : nettoyer les données
            $name = sanitize($_POST['nameTask']);
            $content = sanitize($_POST['contentTask']);
            $date = sanitize($_POST['dateTask']);
            //Nettoyage du tableau de checkbox
            $tabCategory = [];
            foreach($_POST['category'] as $category){
                array_push($tabCategory,sanitize($category));
            }

            

            //5) J'appelle le Model pour créer la Task
            //6) J'affiche le message de confirmation
            if($taskModel->createTask($name, $content, $date, $_SESSION['id'], $tabCategory)) {
                $message = "<span style='color:green;'>La tâche a bien été créée !</span>";
            } else {
                $message = "<span style='color:red;'>Erreur lors de la création de la tâche.</span>";
            }
        }else{//Else checkbox pas au bon format
            $message = "Checkbox pas au bon format !";
        }
    }else{
        $message = "Veuillez remplir tous les champs !";
    }
}

if(isset($_POST['updateTask'])) {
    // 1) Vérifications de base (comme pour l'ajout)
    if(!empty($_POST['nameTask']) && !empty($_POST['contentTask']) && !empty($_POST['dateTask']) && !empty($_POST['id_task_to_update']) && !empty($_POST['category'])){
        
        $name = sanitize($_POST['nameTask']);
        $content = sanitize($_POST['contentTask']);
        $date = sanitize($_POST['dateTask']);
        $id_to_update = (int)$_POST['id_task_to_update'];
        
        $tabCategory = [];
        foreach($_POST['category'] as $category){
            array_push($tabCategory, sanitize($category));
        }

        // 2) Appel du modèle pour la mise à jour
        if($taskModel->updateTask($id_to_update,$_SESSION['id'], $name, $content, $date, $tabCategory)) {
            $message = "<span style='color:green;'>La tâche a bien été modifiée !</span>";
        } else {
            $message = "<span style='color:red;'>Erreur lors de la modification.</span>";
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
    }
}

if(isset($_POST['submit_delete_task']) && !empty($_POST['id_task_to_delete'])){
    $id_to_delete = (int)$_POST['id_task_to_delete'];
    
    if($taskModel->deleteTask($id_to_delete,$_SESSION['id'])){
        $message = "<span style='color:green;'>La tâche a bien été supprimée !</span>";
    } else {
        $message = "<span style='color:red;'>Erreur lors de la suppression de la tâche.</span>";
    }
}

$modeEdition = false;
$formIdTask = "";
$formName = "";
$formContent = "";
$formDate = "";
$btnAction = "addTask"; // Nom par défaut du bouton de soumission
$btnText = "Ajouter la Tâche";

if(isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $modeEdition = true;
    $id_to_edit = (int)$_GET['edit_id'];
    
    // On va chercher les infos de la tâche dans la BDD
    $taskToEdit = $taskModel->getTaskById($id_to_edit,$_SESSION['id']);
    
    // Si la tâche existe bien, on prépare les variables pour la vue
    if(!empty($taskToEdit)) {
        $formIdTask = $taskToEdit['id_task'];
        $formName = htmlspecialchars($taskToEdit['name_task']);
        $formContent = htmlspecialchars($taskToEdit['content_task']);
        $formDate = htmlspecialchars($taskToEdit['date_task']);
        
        // On change l'action du bouton
        $btnAction = "updateTask";
        $btnText = "Mettre à jour la Tâche";
    }
}

$data = $taskModel->readTasksByUser($_SESSION['id']);

//Affichage des tâches
foreach($data as $task){
    // On sécurise les données affichées 
    $name = htmlspecialchars($task['name_task']);
    $date = htmlspecialchars($task['date_task']);
    $categories = htmlspecialchars($task['categories'] ?? '');
    $content = htmlspecialchars($task['content_task']);
    $id_task = (int)$task['id_task']; // On s'assure que c'est bien un entier

    $todoList .= "<article>
        <h3> {$name} </h3>
        <h4> DATE : {$date} </h4>
        <h5> Categories : {$categories}</h5>
        <p>{$content}</p>
        
        <a href=\"?edit_id={$id_task}\" style=\"display:inline-block; padding:5px 10px; background:orange; color:white; text-decoration:none; margin-right: 10px;\">Modifier</a>

        <form method='POST' action='' style=\"display:inline-block;\">
            <input type='hidden' name='id_task_to_delete' value='{$id_task}'>
            <button type='submit' name='submit_delete_task'>Supprimer</button>
        </form>
        
    </article>";
}
//Lancer la récupération de la liste des Task en BDD


//Affichage de la vue

$header = new Header();
echo $header->setTitle($title)->setStyle($style)->renderHeader();

include './View/view_task.php';

$footer = new Footer();
echo $footer->renderFooter();