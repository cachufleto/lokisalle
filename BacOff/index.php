<?php
// Insertion des parametres de fonctionement
require __DIR__ . '/../inc/init.inc.php';
require __DIR__ . '/../Public/route.php';
// control d'acces à l'aplication ADMIN
if(!utilisateurEstAdmin()){
 	header('Location:'.LINK);
 	exit();	
}

// ajout du css admin
$_linkCss[] = LINK . 'css/admin.css';
// ajout du css de la page en cour
$__link = APP . 'css/' . $nav . '.adm.css';
if(file_exists($__link)){
	$_linkCss[] = LINK . 'css/' . $nav . '.adm.css';
}

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
$navPp = nav('navAdmin');
$nav = array_key_exists($nav, $route)? $nav : 'erreur404';

$footer = footer();

include VUE . 'site/template.html.php';