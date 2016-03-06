<?php
// Intertion des parametres de fonctionement
require __DIR__ . '/../inc/init.inc.php';

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

// insertion de l'entête
require INC . 'header.inc.php';
require INC . 'nav.inc.php';
include INC . 'debug.inc.php';
require INC . 'footer.inc.php';

/*************************************************************/
$_link = siteHeader($_linkCss);
$_menu = 'navAdmin';
$navPp = nav($_menu);

ob_start();
$__page = ADM . 'inc/' . $nav . '.inc.php';
// insertion des pages dinamiques
if(!file_exists($__page) ){
	require INC . 'erreur.inc.php';
} else {
/*
	$__paramAdm = ADM . 'param/' . $nav . '.param.php';
	$__param = PARAM . $nav . '.param.php';

	if(file_exists($__paramAdm) and false )
		require $__paramAdm;
	elseif(file_exists($__param) and false )
		require $__param;
*/
	$__funcAdm = ADM . 'func/' . $nav . '.func.php';
	$__func = FUNC . $nav . '.func.php';

	if(file_exists($__funcAdm) )
		require $__funcAdm;
	elseif(file_exists($__func) )
		require $__func;

	require $__page;
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

$footer = footer();

include TEMPLATE . 'template.html.php';