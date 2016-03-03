<?php
include PARAM . 'ficheSalles.param.php';
ficheSalles($_formulaire, $_id, $position, $_trad, $titre, $nav, $msg);

function ficheSalles($_formulaire, $_id, $position, $_trad, $titre, $nav, $msg)
{
	// on cherche la fiche dans la BDD
	// extraction des données SQL
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
