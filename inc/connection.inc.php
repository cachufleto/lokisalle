<?php
include_once FUNC . 'form.func.php';
include PARAM . 'connection.param.php';
connection($_formulaire, $_trad, $titre, $nav, $msg);

function actifUser($_formulaire){
	// recuperation du pseudo
	if (empty($_POST) && isset($_COOKIE['Lokisalle']['pseudo'])) {

		$_POST['valide'] = 'cookie';
		$_POST['mdp'] = '';
		$_POST['pseudo'] = $_COOKIE['Lokisalle']['pseudo'];
		$_POST['rapel'] = 'on';
	}

	# FUNCTIONS formulaires
	// include_once FUNC . 'form.func.php';
	// inclusion des sources requises pour executer la connexion
	// include_once(PARAM . 'connection.php');
	// include_once(FUNC . 'connection.php');

	// traitement du formulaire
	$msg = postCheck($_formulaire);
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

function connection($_formulaire, $_trad, $titre, $nav, $msg)
{
	actifUser($_formulaire);
	/////////////////////////////////////
	if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
		// affichage
		$msg = $_trad['erreur']['acces'];

	} else {

		// RECUPERATION du formulaire
		$form = '
				<form action="#" method="POST">
				' . formulaireAfficher($_formulaire) . '
				</form>';
	}

	include TEMPLATE . 'connection.php';
}