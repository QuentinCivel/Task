<?php
class InfoView{
    //ATTRIBUTS
    private string $message = '';

    //CONSTRUCTEUR
    public function __construct(){}

    //GETTER ET SETTER
    public function getMessage():string{
        return $this->message;
    }

    public function setMessage(string $newMessage):InfoView{
        $this->message = $newMessage;
        return $this;
    }

    public function renderInfo(){
        // On rajoute la balise <section> pour hériter du CSS "carte"
        return '<section>
            <h1>Mon Profil</h1>
            <div style="background:#FAFAFA; padding:15px; border-radius:8px; margin-bottom:30px; border-left: 5px solid var(--primary);">
                <p><strong>Pseudo :</strong> '.htmlspecialchars($_SESSION['nickname']).'</p>
                <p><strong>Email :</strong> '.htmlspecialchars($_SESSION['email']).'</p>
                <p><strong>Rôle :</strong> '.htmlspecialchars($_SESSION['role']).'</p>
            </div>

            <h2>Mise à jour Utilisateur</h2>
            <form action="" method="post">
                <label for="firstname">Prenom :</label>
                <input id="firstname" type="text" name="firstname">
                
                <label for="lastname">Nom :</label>
                <input id="lastname" type="text" name="lastname">
                
                <input type="submit" name="update" value="Mettre à Jour">
            </form>
            <p>'.htmlspecialchars($this->getMessage()).'</p>
        </section>';
    } 
}
?>