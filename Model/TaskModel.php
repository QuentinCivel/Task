<?php

class Task{
    //ATTRIBUTS
    private ?int $id_task;
    private ?string $name_task;
    private ?string $content_task;
    private ?string $date_task;
    private ?int $id_user;

    private ?PDO $bdd;

    //CONSTRUCTEUR
    public function __construct(?PDO $bdd){
        $this->bdd = $bdd;
    }

    //GETTER SETTER
    public function getIdTask(): ?int {return $this->id_task;}
    public function setIdTask(?int $id_task): self {$this->id_task = $id_task;return $this;}

    public function getNameTask(): ?string {return $this->name_task;}
    public function setNameTask(?string $name_task): self {$this->name_task = $name_task;return $this;}

    public function getContentTask(): ?string {return $this->content_task;}
    public function setContentTask(?string $content_task): self {$this->content_task = $content_task;return $this;}

    public function getDateTask(): ?string {return $this->date_task;}
    public function setDateTask(?string $date_task): self {$this->date_task = $date_task;return $this;}

    public function getIdUser(): ?int {return $this->id_user;}
    public function setIdUser(?int $id_user): self {$this->id_user = $id_user;return $this;}


//Function pour enregistrer une Task
public function createTask(string $name, string $content, string $date, int $id, array $tabCategory):bool{
    try{
        //Preparation de la requête
        $req = $this->bdd->prepare('INSERT INTO task (name_task, content_task, date_task, id_user) VALUES (?,?,?,?)');

        //Binding de Paramètre
        $req->bindParam(1,$name,PDO::PARAM_STR);
        $req->bindParam(2,$content,PDO::PARAM_STR);
        $req->bindParam(3,$date,PDO::PARAM_STR);
        $req->bindParam(4,$id,PDO::PARAM_INT);

        //Executer la requête
        $req->execute();

        //2)On récupérer l'id de la task enregistrée
        $idTask = $this->bdd->lastInsertId();


        //3)Boucle d'INSERT INTO pour remplir la table task_category
        foreach($tabCategory as $idCategory){
            $req = $this->bdd->prepare('INSERT INTO task_category (id_task, id_category) VALUES (?,?)');

            $req->bindParam(1,$idTask,PDO::PARAM_INT);
            $req->bindParam(2,$idCategory,PDO::PARAM_INT);

            $req->execute();
        }

        //Retourner un message de confirmation
        return true;

    }catch(Exception $error) {
        // 1. On fabrique message avec la date, l'heure et l'endroit du bug
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] Erreur SQL dans TaskModel->createTask() : " . $error->getMessage() . PHP_EOL;

        // 2. On écrit dans le fichier log caché sur le serveur
        // __DIR__ représente le dossier actuel (Model). Le /../ permet de remonter d'un cran pour aller dans logs/
        error_log($logMessage, 3, __DIR__ . '/../logs/errors.log');

        // 3. On retourne un tableau vide au lieu de faire crasher la page
        return false;
    }
}

public function readTasksByUser(int $id):array{
    try{
        //Preparation de la requête
        $req = $this->bdd->prepare('SELECT t.id_task, t.name_task, t.content_task, t.date_task, GROUP_CONCAT(c.category) categories FROM task t LEFT JOIN task_category tc ON t.id_task = tc.id_task LEFT JOIN category c ON tc.id_category = c.id_category WHERE t.id_user = ? GROUP BY t.id_task');

        //Binding de Param
        $req->bindParam(1,$id,PDO::PARAM_INT);

        //Execution de la requête
        $req->execute();

        //Récupérer la réponse de la bdd
        $data = $req->fetchAll();

        //Retourner les données
        return $data;
    }catch(Exception $error) {
        // 1. On fabrique message avec la date, l'heure et l'endroit du bug
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] Erreur SQL dans TaskModel->readTasksByUser() : " . $error->getMessage() . PHP_EOL;

        // 2. On écrit dans le fichier log caché sur le serveur
        // __DIR__ représente le dossier actuel (Model). Le /../ permet de remonter d'un cran pour aller dans logs/
        error_log($logMessage, 3, __DIR__ . '/../logs/errors.log');

        // 3. On retourne un tableau vide au lieu de faire crasher la page
        return [];
    }
}

public function deleteTask(int $id_task, int $id_user): bool { // Ajout de $id_user
    try {
        // Ajout du contrôle AND id_user = ?
        $req = $this->bdd->prepare('DELETE FROM task WHERE id_task = ? AND id_user = ?');
        $req->bindParam(1, $id_task, PDO::PARAM_INT);
        $req->bindParam(2, $id_user, PDO::PARAM_INT);
        $req->execute();
        return true;

    }catch(Exception $error) {
        // 1. On fabrique message avec la date, l'heure et l'endroit du bug
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] Erreur SQL dans TaskModel->deleteTask() : " . $error->getMessage() . PHP_EOL;

        // 2. On écrit dans le fichier log caché sur le serveur
        // __DIR__ représente le dossier actuel (Model). Le /../ permet de remonter d'un cran pour aller dans logs/
        error_log($logMessage, 3, __DIR__ . '/../logs/errors.log');

        // 3. On retourne un tableau vide au lieu de faire crasher la page
        return false;
    }
}

// 1. Récupérer les infos d'une seule tâche (pour pré-remplir le formulaire)
public function getTaskById(int $id_task, int $id_user): array {
    try {
        $req = $this->bdd->prepare('SELECT t.id_task, t.name_task, t.content_task, t.date_task, t.id_user FROM task t WHERE t.id_task = ? AND t.id_user = ?');
        $req->bindParam(1, $id_task, PDO::PARAM_INT);
        $req->bindParam(2, $id_user, PDO::PARAM_INT);
        $req->execute();
            
            $data = $req->fetch(PDO::FETCH_ASSOC);
            
            // On retourne la tâche si elle existe, sinon un tableau vide
            return $data ? $data : []; 
            
        } catch(Exception $error) {
            $date = date('Y-m-d H:i:s');
            $logMessage = "[{$date}] Erreur SQL dans TaskModel->getTaskById() : " . $error->getMessage() . PHP_EOL;
            error_log($logMessage, 3, __DIR__ . '/../logs/errors.log');
            return [];
        }
    }

    // 2. Mettre à jour la tâche 
public function updateTask(int $id_task, int $id_user, string $name, string $content, string $date, array $tabCategory): bool {
    try {
        // Ajout du AND id_user = ?
        $req = $this->bdd->prepare('UPDATE task SET name_task = ?, content_task = ?, date_task = ? WHERE id_task = ? AND id_user = ?');
        $req->bindParam(1, $name, PDO::PARAM_STR);
        $req->bindParam(2, $content, PDO::PARAM_STR);
        $req->bindParam(3, $date, PDO::PARAM_STR);
        $req->bindParam(4, $id_task, PDO::PARAM_INT);
        $req->bindParam(5, $id_user, PDO::PARAM_INT);
        $req->execute();

            // B. Mise à jour des catégories (On supprime les anciennes et on insère les nouvelles)
            $reqDel = $this->bdd->prepare('DELETE FROM task_category WHERE id_task = ?');
            $reqDel->bindParam(1, $id_task, PDO::PARAM_INT);
            $reqDel->execute();

            foreach($tabCategory as $idCategory) {
                $reqCat = $this->bdd->prepare('INSERT INTO task_category (id_task, id_category) VALUES (?, ?)');
                $reqCat->bindParam(1, $id_task, PDO::PARAM_INT);
                $reqCat->bindParam(2, $idCategory, PDO::PARAM_INT);
                $reqCat->execute();
            }

            return true;
        } catch(Exception $error) {
            $date = date('Y-m-d H:i:s');
            $logMessage = "[{$date}] Erreur SQL dans TaskModel->updateTask() : " . $error->getMessage() . PHP_EOL;
            error_log($logMessage, 3, __DIR__ . '/../logs/errors.log');
            return false;
        }
    }

}