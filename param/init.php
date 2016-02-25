<?php
if(!defined('RACINE_SITE')) {
	header('Location:../index.php');
	exit();
}

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

////////////////////////////
///// BDD //////////////////
////////////////////////////
$BDD = array();
switch($_SERVER["SERVER_NAME"]){

	case 'lokisalle.domoquick.fr':
		$BDD['SERVEUR_BDD'] = 'rdbms';
		$BDD['USER'] = 'U2407285';
		$BDD['PASS'] = '20Seajar!';
		$BDD['BDD'] = 'DB2407285';
	break;

	default:
		$BDD['SERVEUR_BDD'] = 'localhost';
		$BDD['USER'] = 'root';
		$BDD['PASS'] = '';
		$BDD['BDD'] = 'lokisalle';
}

////////////////////////////
///// VAR //////////////////
////////////////////////////

// chiffrement des données
$cost = 10;
$options = [
	'cost' => $cost,
	'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];

// valeur min de characteres accepté pour les champs limitées dans les formulaires
$minLen = 3;

// déclaration d'une variable qui nous servira à afficher des messages à l'utilisateur
$msg = "";

// déclaration d'une variable qui nous servira à afficher des messages de debug
$_debug = array();

// valeur par default
$_SESSION['lang'] = (isset($_SESSION['lang']))? $_SESSION['lang'] : 'fr';
// recuperation du cookis lang
$_SESSION['lang'] = (isset($_COOKIE['Lokisalle']))? $_COOKIE['Lokisalle']['lang'] : $_SESSION['lang'];
// changement de lang par le user
$_SESSION['lang'] = (isset($_GET['lang']) && ($_GET['lang']=='fr' XOR $_GET['lang']=='es'))? $_GET['lang'] : $_SESSION['lang'];

// définition des cookis
setcookie( 'Lokisalle[lang]' , $_SESSION['lang'], time()+360000 );
