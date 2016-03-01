<?php
if (!defined('RACINE_SITE')) {
	header('Location:../index.php');
	exit();
}

////////////////////////////
///// menu de navigation ///
////////////////////////////

$_pages['home'] =				array('link' => LINK);
$_pages['detail'] = 			array('link' => LINK);
$_pages['reservation'] = 		array('link' => LINK);
$_pages['recherche'] = 			array('link' => LINK);
$_pages['mdpperdu'] = 			array('link' => LINK);
$_pages['inscription'] = 		array('link' => LINK);
$_pages['mentions'] = 			array('link' => LINK);
$_pages['cgv'] = 				array('link' => LINK);
$_pages['plan'] = 				array('link' => LINK);
$_pages['newsletter'] = 		array('link' => LINK);
$_pages['contact'] = 			array('link' => LINK);
$_pages['salles'] = 			array('link' => LINK);
$_pages['ficheSalles'] = 		array('link' => LINK);
$_pages['validerIncription'] = 	array('link' => LINK);
$_pages['changermotpasse'] = 	array('link' => LINK);
	/************   MEMBRE    **************/
$_pages['actif'] = 				array('link' => LINK);
$_pages['out'] = 				array('link' => LINK);
$_pages['panier'] = 			array('link' => LINK);
$_pages['mjprofil'] = 			array('link' => LINK);
$_pages['profil'] = 			array('link' => LINK);
$_pages['ficheMembre'] = 		array('link' => LINK);
	/************   ADMIN    **************/
$_pages['backoffice'] = 		array('link' => LINKADMIN, 'class' => 'admin');
$_pages['boutique'] = 			array('link' => LINKADMIN, 'class' => 'admin');
$_pages['users'] = 				array('link' => LINKADMIN, 'class' => 'admin');
$_pages['ventes'] = 			array('link' => LINKADMIN, 'class' => 'admin');
$_pages['gestionSalles'] = 		array('link' => LINKADMIN, 'class' => 'admin');
$_pages['editerSalles'] = 		array('link' => LINKADMIN, 'class' => 'admin');
$_pages['ficheSallesMod'] = 	array('link' => LINKADMIN, 'class' => 'admin');


$_reglesAll = 		array('home', 'inscription', 'salles', 'reservation', 'recherche', 'actif');
$_reglesMembre = 	array('home', 'profil', 'salles', 'reservation', 'recherche', 'out');
$_reglesAdmin = 	array('home', 'profil', 'salles', 'reservation', 'recherche', 'backoffice', 'out');
$_navAdmin = 		array('home', 'gestionSalles', 'users', 'ventes', 'out');
$_navFooter = 		array('mentions', 'cgv', 'plan', 'newsletter', 'contact');
