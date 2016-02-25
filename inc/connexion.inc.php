<?php
# FORMULAIRE D'INSCRIPTION
# FUNCTIONS formulaires
include_once FUNC . 'form.func.php';

/////////////////////////////////////
if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
	// affichage
	$msg = $_trad['erreur']['acces'];

} else {

// RECUPERATION du formulaire
$form = formulaireAfficher($_formulaire);
}

include(TEMPLATE . 'connexion.html.php');