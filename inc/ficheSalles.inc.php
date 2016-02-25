<?php

// on cherche la fiche dans la BDD
// extraction des données SQL
if( modCheck('_formulaire', $_id, 'salles') ){

	// traitement POST du formulaire

	$form = formulaireAfficherInfo($_formulaire); 
	$form .=  "<a href=\"?nav=salles#P-$position\">" . $_trad['revenir'] . "</a>";

	include(TEMPLATE . 'ficheSalles.html.php');

} else {

	header('Location:index.php');
	exit();
}