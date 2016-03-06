<?php
ficheSalles($msg);

function ficheSalles($msg)
{
	$nav = 'ficheSalles';
	$_trad = setTrad();


	include PARAM . 'ficheSalles.param.php';
	// on cherche la fiche dans la BDD
	// extraction des données SQL
	include FUNC . 'form.func.php';
	if (modCheck($_formulaire, $_id, 'salles')) {
		// traitement POST du formulaire
		$form = formulaireAfficherInfo($_formulaire);
		$form .= '<a href="?nav=salles#P-' . $position . '">' . $_trad['revenir'] . '</a>';
	} else {
		header('Location:index.php');
		exit();
	}

	include TEMPLATE . 'fichesalles.php';
}
