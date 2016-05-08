<?php
include_once FUNC . 'users.func.php';

function inscription()
{
    $_trad = setTrad();
    include PARAM . 'inscription.param.php';
    include FUNC . 'form.func.php';

    // traitement POST du formulaire
    $msg = '';
    if (isset($_POST['valide']) && postCheck($_formulaire, true)) {
        $msg = ($_POST['valide'] == 'cookie') ? 'cookie' : inscriptionValider($_formulaire);
    }

    // affichage des messages d'erreur
    if('OK' == $msg){
        // on renvoi ver connection
        $form = '<a href="?index.php"> SUITE </a>';

    }else{
        // RECUPERATION du formulaire
        $form = '
				<form action="#" method="POST">
				' . formulaireAfficher($_formulaire) . '
				</form>';
    }
    include VUE . 'users/inscription.php';
}

