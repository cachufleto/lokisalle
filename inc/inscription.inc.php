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
	$form = '
			<form action="#" method="POST">
			' . formulaireAfficher($_formulaire) . ' 
			</form>';


 }

include(TEMPLATE . 'inscription.html.php');
