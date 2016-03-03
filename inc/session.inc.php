<?php
function session()
{
	## Ouverture des sessions
	session_start();

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
