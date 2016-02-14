<?php
// Intertion des parametres de fonctionement
require_once("../inc/init.inc.php");
// control d'acces à l'aplication ADMIN
if(!utilisateurEstAdmin()){
 	header('Location:'.LINK);
 	exit();	
}

$_linkCss[] = LINK . 'css/admin.css';

$__link = APP . 'css/' . $nav . '.adm.css';
if(file_exists($__link) )
	$_linkCss[] = LINK . 'css/' . $nav . '.adm.css';

$_menu = 'navAdmin';
// intertion de l'entête
require_once(INC . "header.inc.php");
// Fonctions navigation
require_once(FUNC."nav.func.php");
// insertion menu de navigation
require_once(INC . "nav.inc.php");

// insertion des pages dinamiques
//echo $__page;

// pages admin
$__page = ADM . 'inc/' . $nav . '.inc.php';

if(file_exists($__page) ){

	$__paramAdm = ADM . 'param/' . $nav . '.param.php';
	$__param = PARAM . $nav . '.param.php';
	
	if(file_exists($__paramAdm) )
		require_once($__paramAdm);
	elseif(file_exists($__param) )
		require_once($__param);
	

	$__funcAdm = ADM . 'func/' . $nav . '.func.php';
	$__func = FUNC . $nav . '.func.php';
	
	if(file_exists($__funcAdm) )
		require_once($__funcAdm);
	elseif(file_exists($__func) )
		require_once($__func);

	require_once($__page);

} else require_once(INC . "erreur.inc.php");

// insertion Pied de page
require_once(INC . "footer.inc.php");

// affichage des debug
if(defined('DEBUG')) include_once(INC . "debug.inc.php");