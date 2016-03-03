<?php
include PARAMADM . 'ficheSalles.param.php';
ficheSalles($_valider, $_modifier, $_formulaire, $_id, $position, $_trad, $titre, $nav, $msg);

function ficheSalles($_valider, $_modifier, $_formulaire, $_id, $position, $_trad, $titre, $nav, $msg)
{
	include FUNC . 'form.func.php';
	if (!isset($_SESSION['user'])) {
		header('Location:index.php');
		exit();
	}

	// extraction des données SQL
	$form = $msg = '';
	if (modCheck($_formulaire, $_id, 'salles')) {

		// traitement POST du formulaire
		if ($_valider){
			$msg = $_trad['erreur']['inconueConnexion'];
			if(postCheck($_formulaire, TRUE)) {
				$msg = ($_POST['valide'] == 'cookie') ? 'cookie' : formulaireValider($_formulaire);
			}
		}

		if ('OK' == $msg) {
			// on renvoi ver connection
			$msg = $_trad['lesModificationOntEteEffectues'];
			// on évite d'afficher les info du mot de passe
			unset($_formulaire['mdp']);
			$form = formulaireAfficherInfo($_formulaire);

		} else {

			if (!empty($msg) || $_modifier) {

				$_formulaire['valide']['defaut'] = $_trad['defaut']['MiseAJ'];

				$form = formulaireAfficherMod($_formulaire);

			} elseif (
				!empty($_POST['valide']) &&
				$_POST['valide'] == $_trad['Out'] &&
				$_POST['origin'] != $_trad['defaut']['MiseAJ']
			){
				header('Location:?nav=gestionSalles&pos=P-' . $position . '');
				exit();

			} else {

				unset($_formulaire['mdp']);
				$form = formulaireAfficherInfo($_formulaire);

			}

		}

	} else {

		$form = 'Erreur 500: ' . $_trad['erreur']['NULL'];

	}

	include TEMPLATE . 'fichesalles.php';
}

