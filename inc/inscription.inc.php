<?php

// traitement du formulaire
$msg = postCheck('_formulaire');

// affichage des messages d'erreur
if('OK' == $msg){
	// on renvoi ver connexion
	$msg = $_trad['validerInscription'];
	$form = '<a href="?nav=home">SUITE</a>';

}else{
	// RECUPERATION du formulaire
	$form = formulaireAfficher($_formulaire);

 }

include(TEMPLATE . 'inscription.html.php');
