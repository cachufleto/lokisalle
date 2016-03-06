<?php
function session()
{
	## Ouverture des sessions
	session_start();
	// valeur par default
	$_SESSION['lang'] = (isset($_SESSION['lang']))? $_SESSION['lang'] : 'fr';
	// recuperation du cookis lang
	$_SESSION['lang'] = (isset($_COOKIE['Lokisalle']))? $_COOKIE['Lokisalle']['lang'] : $_SESSION['lang'];
	// changement de lang par le user
	$_SESSION['lang'] = (isset($_GET['lang']) && ($_GET['lang']=='fr' XOR $_GET['lang']=='es'))? $_GET['lang'] : $_SESSION['lang'];

	// définition des cookis
	setcookie( 'Lokisalle[lang]' , $_SESSION['lang'], time()+360000 );
	// Déconnection de l'utilisateur par tentative d'intrusion
	// comportement de déconnexion sur le site
	if (isset($_GET['nav']) && $_GET['nav'] == 'out' && isset($_SESSION['user'])) {
		// destruction de la navigation
		$lng = $_SESSION['lang'];
		unset($_GET['nav']);
		// destruction de la session
		unset($_SESSION['user']);

		session_destroy();
		// on relance la session avec le choix de la langue
		session_start();
		$_SESSION['lang'] = $lng;

	} elseif (isset($_GET['nav']) && $_GET['nav'] == 'out' && !isset($_SESSION['user'])) {

		// destruction de la navigation
		unset($_GET['nav']);

	} elseif (isset($_GET['nav']) && $_GET['nav'] == 'actif' && isset($_SESSION['user'])) {

		// control pour eviter d'afficher le formulaire de connexion
		// si l'utilisateur tente de le faire
		unset($_GET['nav']);

	}
}

session();