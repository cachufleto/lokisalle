<?php
include(MODEL . 'Inscription.php');

$msg = $_trad['redirigeVerConnexion'];

if(isset($_GET['jeton']) && !empty($_GET['jeton'])) {

    $incription = inscriptionSelectMembreJeton($_GET['jeton']);

    if ($incription->num_rows == 1) {

        $membre = $incription->fetch_row();

        inscriptionValideJeton($membre['id_membre']);

    } else {
        $msg = $_trad['pasDeJeton'];
    }
}

include(TEMPLATE . 'validerinscription.html.php');