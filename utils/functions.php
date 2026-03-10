<?php
//Fonction utilitaire de nettoyage
function sanitize($data){
    return strip_tags(stripslashes(trim($data)));
}
?>