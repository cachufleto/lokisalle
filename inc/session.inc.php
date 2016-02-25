<?php
// Déconnexion de l'utilisateur par tentative d'intrusion
// comportement de déconnexion sur le site
if(isset($_GET['nav']) && $_GET['nav'] == 'out' && isset($_SESSION['user'])){

	// destruction de la navigation
	$lng = $_SESSION['lang'];
	unset($_GET['nav']);
	// destruction de la session
	unset($_SESSION['user']);
	
	session_destroy();
	// on relance la session avec le choix de la langue
	session_start();
	$_SESSION['lang'] = $lng;

} elseif (isset($_GET['nav']) && $_GET['nav'] == 'out' && !isset($_SESSION['user'])){
	
	// destruction de la navigation
	unset($_GET['nav']);

} elseif (isset($_GET['nav']) && $_GET['nav'] == 'actif' && !isset($_SESSION['user'])){
	// recuperation du pseudo
	if(empty($_POST) && isset($_COOKIE['Lokisalle']['pseudo'])) {
		
		$_POST['valide'] = 'cookie';
		$_POST['mdp'] = '';
		$_POST['pseudo'] = $_COOKIE['Lokisalle']['pseudo'];
		$_POST['rapel'] = 'on';
	}

	# FUNCTIONS formulaires
	include_once FUNC . 'form.func.php';
	// inclusion des sources requises pour executer la connexion
	include_once(PARAM.'connexion.php');
	include_once(FUNC.'connexion.php');

	// traitement du formulaire
	$msg = postCheck('_formulaire');
	$form = '';

	// affichage des messages d'erreur
	if('OK' == $msg){

		// l'utilisateur est automatiquement connécté
		// et re-dirigé ver l'accueil
		if(utilisateurEstAdmin()) $_GET['nav'] = 'backoffice';
		else unset($_GET['nav']);

	}

} elseif (isset($_GET['nav']) && $_GET['nav'] == 'actif' && isset($_SESSION['user'])){

	// control pour eviter d'afficher le formulaire de connexion
	// si l'utilisateur tente de le faire
	unset($_GET['nav']);

}
