<?php
editerSalles();

function editerSalles()
{
	$nav = 'editerSalles';
	$_trad = setTrad();

	// traitement du formulaire
	include PARAMADM . 'editerSalles.par1am.php';

	$msg = $_trad['erreur']['inconueConnexion'];

	if (postCheck($_formulaire)) {
		$msg = ($_POST['valide'] == 'cookie') ? 'cookie' : formulaireValider($_formulaire);
	}

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

