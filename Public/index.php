<?php
// Insertion des parametres de fonctionement
require __DIR__ . '/../inc/init.inc.php';
require __DIR__ . '/route.php';

/*************************************************************/
$_link = siteHeader($_linkCss);
$navPp = nav();

ob_start();
// insertion des pages dinamiques

if(!file_exists($__page) ){
	require INC . 'erreur.inc.php';
} else {
	require $__page;
}

$contentPage = ob_get_contents();
ob_end_clean();

ob_start();
if(DEBUG) {
	$_trad = setTrad();
	// affichage des debug
	debugParam($_trad);
	debugPhpInfo();
	debugTestMail();
	debugCost();
	debug($_debug);
}
$debug = ob_get_contents();
ob_end_clean();

$footer = footer();

include TEMPLATE . 'template.html.php';