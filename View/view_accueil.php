<?php
class AccueilView{
    //Attribut
    private string $message = '';
    private string $messageCo = '';

    //Constructeur
    public function __construct(){}

    //GETTER ET SETTER
    public function getMessage(): string { return $this->message; }
    public function setMessage(string $message): self { $this->message = $message; return $this; }

    public function getMessageCo(): string { return $this->messageCo; }
    public function setMessageCo(string $messageCo): self { $this->messageCo = $messageCo; return $this; }

    //METHOD
    public function renderAccueil(){
        if(!isset($_SESSION['role'])){
            $accueil = "
                <div class='auth-container'>
                    <section class='auth-card'>
                        <h2>Inscription Utilisateur</h2>
                        <form action='' method='post'>
                            <label for='nickname'>Pseudo</label><input type='text' id='nickname' name='nickname'>
                            <label for='email'>Email</label><input type='email' id='email' name='email'>
                            <label for='password'>Mot de Passe</label><input type='password' id='password' name='password'>
                            <label for='passwordVerify'>Retappez le Mot de Passe</label><input type='password' id='passwordVerify' name='passwordVerify'>
                            <input type='submit' name='signIn' value=\"S'inscrire\">
                        </form>
                        <p>".htmlspecialchars($this->getMessage())."</p>
                    </section>

                    <section class='auth-card'>
                        <h2>Connexion Utilisateur</h2>
                        <form action='' method='post'>
                            <label for='nicknameSignUp'>Pseudo</label><input type='text' id='nicknameSignUp' name='nicknameSignUp'>
                            <label for='passwordSignUp'>Password</label><input type='password' id='passwordSignUp' name='passwordSignUp'>
                            <input type='submit' name='signUp' value='Se Connecter'>
                        </form>
                        <p>".htmlspecialchars($this->getMessageCo())."</p>
                    </section>
                </div>";

        }else{ 
            $accueil =  "
                <div class='auth-container'>
                    <section class='auth-card' style='max-width: 500px;'>
                        <h2>Bienvenue " . htmlspecialchars($_SESSION['nickname']) . "</h2>
                        <div style='background:#FAFAFA; padding:15px; border-radius:8px; border-left: 5px solid var(--primary);'>
                            <p><strong>Pseudo :</strong> " . htmlspecialchars($_SESSION['nickname']) . " </p>
                            <p><strong>Email :</strong> " . htmlspecialchars($_SESSION['email']) . " </p>
                            <p><strong>Rôle :</strong> " . htmlspecialchars($_SESSION['role']) . " </p>
                        </div>
                    </section>
                </div>
            ";
        }

        return "<h1>Bienvenue sur le Projet Task</h1>".$accueil;
    }
}
?>
