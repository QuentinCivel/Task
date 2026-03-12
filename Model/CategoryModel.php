<?php
class Category{
    //ATTRIBUTS
    private ?int $id_category;
    private ?string $category;
    private ?PDO $bdd;

    //CONSTRUCTEUR
    public function __construct(?PDO $bdd){
        $this->bdd = $bdd;
    }

    //GETTER ET SETTER
    public function getIdCategory(): ?int {return $this->id_category;}
    public function setIdCategory(?int $id_category): self {$this->id_category = $id_category;return $this;}

    public function getCategory(): ?string {return $this->category;}
    public function setCategory(?string $category): self {$this->category = $category;return $this;}

    public function getBdd(): ?PDO {return $this->bdd;}
    public function setBdd(?PDO $bdd):self {$this->bdd = $bdd; return $this;}
    

    public function readCategories():array{
        try {
            //Préparation de la requête
            $req = $this->bdd->prepare('SELECT c.id_category, c.category FROM category c');

           //Executer la requête
            $req->execute();

            //Retourne la réponse de la BDD
            return $req->fetchAll();

        } catch(Exception $error) {
            // 1. On fabrique message avec la date, l'heure et l'endroit du bug
            $date = date('Y-m-d H:i:s');
            $logMessage = "[{$date}] Erreur SQL dans CategoryModel->readCategories() : " . $error->getMessage() . PHP_EOL;

            // 2. On écrit dans le fichier log caché sur le serveur
            // __DIR__ représente le dossier actuel (Model). Le /../ permet de remonter d'un cran pour aller dans logs/
            error_log($logMessage, 3, __DIR__ . '/../logs/errors.log');

            // 3. On retourne un tableau vide au lieu de faire crasher la page
            return [];
        }
    }
}   