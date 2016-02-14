<?php
if(!defined('RACINE_SITE')) {
	header('Location:../index.php');
	exit();
}

////////////////////////////
///// BDD //////////////////
////////////////////////////
switch($_SERVER["SERVER_NAME"]){

	case 'domoquick.fr':
		$SERVEUR_BDD = 'rdbms';
		$USER = 'U2407285';
		$PASS = '20Seajar!';
		$BDD = 'DB2407285';
	break;

	default:
		$SERVEUR_BDD = 'localhost';
		$USER = 'root';
		$PASS = '';
		$BDD = 'lokisalle';
}

$mysqli = @new mysqli($SERVEUR_BDD, $USER, $PASS, $BDD);

// Jamais de ma vie je ne metterais un @ pour cacher une erreur sauf si je le gere proprement avec ifx_affected_rows
if($mysqli->connect_error) {
	die("Un probleme est survenu lors de la connexion a la BDD" . $mysqli->connect_error);
}

$mysqli->set_charset("utf-8"); // en cas de souci d'encodage avec l'utf-8

////////////////////////////
///// VAR //////////////////
////////////////////////////
// valeur min de characteres accepté pour les champs limitées dans les formulaires
$minLen = 3;

// déclaration d'une variable qui nous servira à afficher des messages à l'utilisateur
$msg = "";

// déclaration d'une variable qui nous servira à afficher des messages de debug
$_debug = array();
