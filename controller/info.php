<?php


//IMPORT DE RESSOURCE
include './View/view_compte.php';

class InfoController{
    //ATTRIBUTS
    private string $title = 'Mon Compte Utilisateur';
    private string $style = './src/style/style.css';
    private string $message = '';
    private Users $model;
    private Header $header;
    private Footer $footer;
    private InfoView $info;
    private ?PDO $bdd;

    //Constructeur
    public function __construct(?PDO $bdd){
        $this->header = new Header();
        $this->footer = new Footer();
        $this->info = new InfoView();
        $this->bdd = $bdd;
        $this->model = new Users($this->bdd);
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

    public function getBdd(): ?PDO {return $this->bdd;}
    public function setBdd(?PDO $bdd):self {$this->bdd = $bdd; return $this;}

    //METHODS
    public function isConnected():void{
        //VERIFIER SI LA PERSONNE EST CONNECTE, SINON ON REDIRIGE VERS ACCUEIL
        if(!isset($_SESSION['id'])){
            header('Location:/');
        }
    }

    public function updateUser():void{
        //TRAITEMENT DU FORMULAIRE DE MISE A JOUR
        if(isset($_POST['update'])){
            
            
            $newNickname = $_SESSION['nickname'];
            $newEmail = $_SESSION['email'];
            $hasChanged = false;

            // Vérification du Pseudo
            if(!empty($_POST['pseudo']) && $_POST['pseudo'] !== $_SESSION['nickname']){
                $newNickname = sanitize($_POST['pseudo']);
                $hasChanged = true;
            }

            // Vérification de l'Email
            if(!empty($_POST['email']) && $_POST['email'] !== $_SESSION['email']){
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                    $newEmail = sanitize($_POST['email']);
                    $hasChanged = true;
                } else {
                    $this->setMessage("Le format de l'adresse email est invalide.");
                    return; 
                }
            }

            // Mise à jour si un changement a été détecté
            if($hasChanged){
                $user = $this->getModel();
                
                // On met à jour l'objet avec les nouvelles données et l'ID de session
                $user->setNickname($newNickname)
                    ->setEmail($newEmail)
                    ->setIdUser($_SESSION['id']);

                // On lance la requête en BDD 
                $resultMessage = $user->updateUser();
                
                // On met à jour l'affichage des messages
                $this->setMessage($resultMessage);

                // Si la modification en base de données a réussi, on met à jour la session
                
                $_SESSION['nickname'] = $newNickname;
                $_SESSION['email'] = $newEmail;

            } else {
                $this->setMessage("Aucune modification n'a été apportée.");
            }
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

$info = new InfoController($bdd);
$info->displayInfo();



?>

