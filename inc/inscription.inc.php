<?php

include PARAM . 'inscription.param.php';
inscription($_formulaire, $titre, $nav);

function inscription($_formulaire, $titre, $nav)
{
	// traitement du formulaire
	$msg = postCheck($_formulaire);

	// affichage des messages d'erreur
	if('OK' == $msg){
		// on renvoi ver connection
		$form = '<a href="?index.php">SUITE</a>';

	}else{
		// RECUPERATION du formulaire
		$form = '
				<form action="#" method="POST">
				' . formulaireAfficher($_formulaire) . '
				</form>';
	}
	include TEMPLATE . 'inscription.php';
}
