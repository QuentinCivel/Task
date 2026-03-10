<?php
//Démarer la Session
// session_start();

//IMPORT DE RESSOURCE
// include './Model/UsersModel.php';
// include './utils/functions.php';
// include './View/header.php';
include './View/view_compte.php';
// include './View/footer.php';

class InfoController{
    //ATTRIBUTS
    private string $title = 'Mon Compte Utilisateur';
    private string $style = './src/style/style-info.css';
    private string $message = '';
    private Users $model;
    private Header $header;
    private Footer $footer;
    private InfoView $info;

    //Constructeur
    public function __construct(){
        $this->model = new Users(new PDO('mysql:host=localhost;dbname=task','root','root',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)));
        $this->header = new Header();
        $this->footer = new Footer();
        $this->info = new InfoView();
    }

    //GETTER ET SETTER
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getStyle(): string { return $this->style; }
    public function setStyle(string $style): self { $this->style = $style; return $this; }

    public function getMessage(): string { return $this->message; }
    public function setMessage(string $message): self { $this->message = $message; return $this; }

    public function getModel(): Users { return $this->model; }
    public function setModel(Users $model): self { $this->model = $model; return $this; }

    public function getHeader(): Header { return $this->header; }
    public function setHeader(Header $header): self { $this->header = $header; return $this; }

    public function getFooter(): Footer { return $this->footer; }
    public function setFooter(Footer $footer): self { $this->footer = $footer; return $this; }

    public function getInfo(): InfoView { return $this->info; }
    public function setInfo(InfoView $info): self { $this->info = $info; return $this; }

    //METHODS
    public function isConnected():void{
        //VERIFIER SI LA PERSONNE EST CONNECTE, SINON ON REDIRIGE VERS ACCUEIL
        if(!isset($_SESSION['id'])){
            header('Location:/Projet_task');
        }
    }

    public function updateUser():void{
        //TRAITEMENT DU FORMULAIRE DE MISE A JOUR
        if(isset($_POST['update'])){
            $firstname = '';
            $lastname = '';

            if(!empty($_POST['firstname'])){
                $firstname = sanitize($_POST['firstname']);
            }
            if(!empty($_POST['lastname'])){
                $lastname = sanitize($_POST['lastname']);
            }

            //Récupérer l'objet model user
            $user = $this->getModel();

            //Je remplis mon objet avec les données dont j'ai besoin
            $user->setFirstname($firstname)->setLastname($lastname)->setIdUser($_SESSION['id']);

            //J'utilise la méthode de l'objet updateUser() pour qu'il mette à jour les données de mon utilisateur en BDD
            $this->setMessage($user->updateUser());
        }
    }

    public function displayInfo():void{
        //Vérification si l'utilisateur est bien connecté
        $this->isConnected();

        //Traitement du formulaire de mise à jour
        $this->updateUser();

        //Affichage des Views
        echo $this->getHeader()->setTitle($this->getTitle())->setStyle($this->getStyle())->renderHeader();
        echo $this->getInfo()->setMessage($this->getMessage())->renderInfo();
        echo $this->getFooter()->renderFooter();
    }
}

$info = new InfoController();
$info->displayInfo();



?>

