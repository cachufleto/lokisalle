﻿<?php
if(!defined('RACINE_SITE')) {
	header('Location:../index.php');
	exit();
}

////////////////////////////
///// menu de navigation ///
////////////////////////////

$_pages['home'] = array(
		'link' => LINK,
		'affiche' => true);

$_pages['detail'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['reservation'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['recherche'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['mdpperdu'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['inscription'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['mentions'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['cgv'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['plan'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['newsletter'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['contact'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['salles'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['ficheSalles'] = array(
		'link' => LINK,
		'affiche' => false);

/************   MEMBRE    **************/

$_pages['actif'] = array(
		'link' => LINK,
		'affiche' => false);
		
$_pages['out'] = array(
		'link' => LINK,
		'affiche' => false);

$_pages['panier'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['mjprofil'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['profil'] = array(
		'link' => LINK,
		'affiche' => false);
			
$_pages['ficheMembre'] = array(
		'link' => LINK,
		'affiche' => false);
			
/************   ADMIN    **************/

$_pages['backoffice'] = array(
		'link' => LINK.LINKADM,
		'class' => 'admin',
		'affiche' => false);
			
$_pages['boutique'] = array(
		'link' => LINK.LINKADM,
		'affiche' => false);
			
$_pages['users'] = array(
		'link' => LINK.LINKADM,
		'affiche' => false);
			
$_pages['ventes'] = array(
		'link' => LINK.LINKADM,
		'affiche' => false);
			
$_pages['gestionSalles'] = array(
		'link' => LINK.LINKADM,
		'affiche' => false);
			
$_pages['editerSalles'] = array(
		'link' => LINK.LINKADM,
		'affiche' => false);

// Onglets à activer dans le menu de navigation selon le profil listeMenu();
$_reglesAll = array('home', 'reservation', 'recherche', 'salles', 'panier', 'actif');
$_reglesMembre = array('home','reservation', 'recherche', 'salles', 'panier', 'profil', 'out');
$_reglesAdmin = array('home', 'profil', 'salles', 'backoffice', 'out');

$navAdmin = array('home', 'gestionSalles','users','ventes','out' );

$navFooter = array('mentions', 'cgv', 'plan', 'newsletter', 'contact' );


////////////////////////////
///// NAV //////////////////
///////////////////////	/////
// page de navigation
$nav = (isset ($_GET['nav']) && !empty($_GET['nav']) && isset ($_pages[ $_GET['nav'] ]))? $_GET['nav'] : 'home';

// REGLE D'orientation des pages actif et out ver connection
if('actif' == $nav || 'out' == $nav) $nav = 'connection';

// cas spécifique
$nav = ($nav=='users')? 'home' : $nav;
// page a inclure
$__page = INC . $nav . '.inc.php';