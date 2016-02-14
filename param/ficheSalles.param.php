<?php

// on inclus les parametres du formulaire d'inscription
include_once(PARAM."editerSalles.param.php");


// recuparation de l'id par GET ou POST
$_id = (int)(isset($_GET['id'])? $_GET['id'] : false);
$position = (int)(isset($_GET['pos'])? $_GET['pos'] : 1);

unset($_formulaire['valide']);