<?php
class Header {
    //ATTRIBUT
    private string $title = '';
    private string $style = '';

    //CONSTRUCTEUR
    public function __construct(){}

    //GETTER ET SETTER
    public function getTitle():string{
        return $this->title;
    }

    public function setTitle(string $newTitle):Header{
        $this->title=$newTitle;
        return $this;
    }

    public function getStyle():string{
        return $this->style;
    }

    public function setStyle(string $newStyle):Header{
        $this->style=$newStyle;
        return $this;
    }

    //METHODE
    public function renderHeader():string{
        $link = '';
        $linkSession= '';
        $span = '';

        switch($this->getTitle()){
            case 'Mon Compte Utilisateur' :
            case 'Mes ToDoes' :
                $link = "<li><a href='/Projet_task-MVC-POO-Part02/Projet_task/'>Accueil TODO LIST</a></li>";
                break;
            case 'accueil TODO LIST' :
                $link = "<li><a href='/Projet_task-MVC-POO-Part02/Projet_task/Info'>Vos Infos</a></li>";
                break;
        }

        if(isset($_SESSION['nickname'])){
            $linkSession= "<li><a href='/Projet_task-MVC-POO-Part02/Projet_task/Mes_taches'>My ToDoes</a></li>
                        <li><a href='/Projet_task-MVC-POO-Part02/Projet_task/Se_deconnecter'>Se Deconnecter</a></li>";
        }

        if(isset($_SESSION['nickname'])){
                    $span = "<span>Vous êtes : {$_SESSION['nickname']}</span>";
        }

        return "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    

                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>".$this->getTitle()."</title>
                    <link rel='stylesheet' href='".$this->getStyle()."'>
                </head>
                <body>
                    <header>
                        <nav>
                            <ul>
                                <li><a href='/Projet_task-MVC-POO-Part02/Projet_task/'>Accueil General</a></li>".$link.$linkSession."
                            </ul>
                        </nav>".$span."
                    </header>
                    <main>";
    }
}
?>


                
                
        

 