<?php
// Insertion des parametres de fonctionement
include __DIR__ . '/../conf/constantes.php';

require_once INC . 'init.inc.php';

/*************************************************************/
ob_start();
$nav = array_key_exists($nav, $route)? $nav : 'erreur404';
// insertion des pages dinamiques
if ($nav != 'erreur404'){
	include_once CONTROLEUR . $route[$nav]['Controleur'];
	$function = $route[$nav]['action'];
	if (function_exists($function)){
		$function();
	} else {
		include_once CONTROLEUR . $route['erreur404']['Controleur'];
		$route['erreur404']['action']($nav);
	}
} else {
	include_once CONTROLEUR . $route['erreur404']['Controleur'];
	$route['erreur404']['action']('erreur404');
}

_debug($route[$nav], 'Route pour: ' . $nav);

$contentPage = ob_get_contents();
ob_end_clean();

ob_start();
if(DEBUG) {
	// affichage des debug
	$_trad = setTrad();
	debugParam($_trad);
	debugPhpInfo();
	debugTestMail();
	debugCost();
	debug($_debug);
}
$debug = ob_get_contents();
ob_end_clean();

$_link = siteHeader($_linkCss);
$navPp = nav((utilisateurEstAdmin() && $_SESSION['BO'])? 'navAdmin' : '');
$nav = array_key_exists($nav, $route)? $nav : 'erreur404';

$footer = footer();

include VUE . 'site/template.html.php';
