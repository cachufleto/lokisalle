<?php
connection();

function actifUser($_formulaire)
{
	$_trad = setTrad();

	include FUNC . 'form.func.php';
	// recuperation du pseudo
	if (empty($_POST) && isset($_COOKIE['Lokisalle']['pseudo'])) {
		$_POST['valide'] = 'cookie';
		$_POST['mdp'] = '';
		$_POST['pseudo'] = $_COOKIE['Lokisalle']['pseudo'];
		$_POST['rapel'] = 'on';
	}

	// traitement du formulaire
	$msg = $_trad['erreur']['inconueConnexion'];
	if (isset($_POST['valide']) && postCheck($_formulaire)) {
		$msg = ($_POST['valide'] == 'cookie') ? 'cookie' : formulaireValider($_formulaire);
	}

	$form = '';
	// affichage des messages d'erreur
	if ('OK' == $msg) {
		// l'utilisateur est automatiquement connécté
		// et re-dirigé ver l'accueil
		$_nav = '';
		if (utilisateurEstAdmin()){
			$_nav = 'backoffice';
		}
		header('Location:index.php?nav='.$_nav);
		exit();
	}
}

function connection()
{
	$nav = 'connection';
	$msg = '';
	$_trad = setTrad();

	include PARAM . 'connection.param.php';

	actifUser($_formulaire);

	/////////////////////////////////////
	if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
		// affichage
		$msg = $_trad['erreur']['acces'];

	} else {

		// RECUPERATION du formulaire
		$form = formulaireAfficher($_formulaire);
	}

	include TEMPLATE . 'connection.php';
}