<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 25/02/2016
 * Time: 15:19
 */

$listConctact = array();

$membres = userSelectContactAll();

if ($membres->num_rows > 0){
    while($membre = $membres->fetch_assoc()){
        $listConctact[]  = ficheContactTemplate($membre);
    }
}

include TEMPLATE . 'contact.html.php';