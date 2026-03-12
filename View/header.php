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
        $linkSession = '';
        $span = '';

        // 1. Si on n'est pas sur l'accueil, on met toujours un lien pour y retourner
        if ($this->getTitle() !== 'accueil TODO LIST') {
            $link = "<li><a href='/'>Accueil TODO LIST</a></li>";
        }

        // 2. Liens et infos affichés UNIQUEMENT si l'utilisateur est connecté
        if (isset($_SESSION['nickname'])) {
            
            // Si on est sur l'accueil ET connecté, on propose le lien "Vos Infos"
            if ($this->getTitle() === 'accueil TODO LIST') {
                $linkSession .= "<li><a href='/Info'>Vos Infos</a></li>";
            }

            // On ajoute les liens de base d'un utilisateur connecté
            $linkSession .= "<li><a href='/Mes_taches'>My ToDoes</a></li>
                             <li><a href='/Se_deconnecter'>Se Deconnecter</a></li>";
            
            // On affiche le petit badge avec son nom
            $span = "<span>Vous êtes : " . htmlspecialchars($_SESSION['nickname']) . "</span>";
        }

        return "<!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>".htmlspecialchars($this->getTitle())."</title>
                    <link rel='stylesheet' href='/src/style/style.css'>
                </head>
                <body>
                    <header>
                        <nav>
                            <ul>
                                <li><a href='/'>Accueil General</a></li>".$link.$linkSession."
                            </ul>
                        </nav>".$span."
                    </header>
                    <main>";
    }
}
?>

