<?php
include PARAMADM . 'editerSalles.param.php';
editerSalles($_trad, $_formulaire, $titre, $nav);

function editerSalles($_trad, $_formulaire, $titre, $nav)
{
// traitement du formulaire
	$msg = postCheck($_formulaire);

// affichage des messages d'erreur
	if ('OK' == $msg) {
		// on renvoi ver connection
		header('Location:' . REPADMIN . 'index.php?nav=gestionSalles&pos='.$_formulaire['position']['value']);
		exit();
	} else {
		// RECUPERATION du formulaire
		$form = formulaireAfficher($_formulaire);
		include TEMPLATE . 'editerSalles.php';

	}
}

