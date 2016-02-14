<?php
if(!defined('RACINE_SITE')) {
	header('Location:../index.php');
	exit();
}

////////////////////////////
///// menu de navigation ///
////////////////////////////

$_pages['home'] = array(
		'affiche' => true,
		'titre' => $_trad['nav']['locationDesSalles']); //'Location des salles');
			
$_pages['detail'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['detailDuProduit']); //'Detail du produit');
		
$_pages['reservation'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['toutesNosOffres']); //'Toutes nos offres');
		
$_pages['recherche'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['resultatDeVotreRecherche']); //'Resultat de votre recherche');
		
$_pages['mdpperdu'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['MotDePasseOublie']); //'Mot de passe Oublié');
		
$_pages['inscription'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['seInscrire']); //'S\'inscrire');
		
$_pages['mentions'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['mentionsLegales']); //'Mentions légales');
		
$_pages['cgv'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['conditionsGeneralesVente']); //'Conditions Générales de Vente');
		
$_pages['plan'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['plan']); //'Plan du site');

$_pages['newsletter'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['newsletter']); //'S\'inscrire à la newsletter'); 
		
$_pages['contact'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['nousContacter']); //'Nous Contacter'); 

/************   MEMBRE    **************/

$_pages['actif'] = array(
		'affiche' => false);
		
$_pages['out'] = array(
		'affiche' => false);

$_pages['panier'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['mesAchats']); //'Mes achats');
		
$_pages['mjprofil'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['mettreJourMesInformations']);

$_pages['profil'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['monProfil']);
		
/************   ADMIN    **************/

$_pages['backoffice'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['ADMIN']);
		
$_pages['admin_boutique'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['ADMIN']);
		
$_pages['admin_users'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['ADMIN']);
		
$_pages['admin_ventes'] = array(
		'affiche' => false,
		'titre' => $_trad['nav']['ADMIN']);

// Onglets à activer dans le menu de navigation selon le profil listeMenu();
$_reglesAll = array('home', 'reservation', 'recherche', 'inscription', 'actif', 'panier');
$_reglesMembre = array('home','reservation', 'recherche', 'profil', 'out', 'panier');
$_reglesAdmin = array('profil','backoffice','admin_boutique','admin_users','admin_ventes','contact','out');
$_navFoot = array('mentions', 'cgv', 'plan', 'newsletter', 'contact' );

////////////////////////////
///// NAV //////////////////
////////////////////////////
// page de navigation
$nav = (isset ($_GET['nav']) && !empty($_GET['nav']) && isset ($_pages[ $_GET['nav'] ]))? $_GET['nav'] : 'home';

// REGLE D'orientation des pages actif et out ver connection
if('actif' == $nav || 'out' == $nav) $nav = 'connection';

// if($_SESSION[])
$_nav = explode("_", $nav);
$__page = ((empty($_nav[1]))? INC . $_nav[0] : ADM . $_nav[1]) . '.inc.php';
$__func = FUNC . ((empty($_nav[1]))? $_nav[0] : $_nav[1]) . '.func.php';
