<?php
// Intertion des parametres de fonctionement
require __DIR__ . '/../inc/init.inc.php';

require INC . 'header.inc.php';
require INC . 'nav.inc.php';
include INC . 'debug.inc.php';
require INC . 'footer.inc.php';

/*************************************************************/
$_link = siteHeader($_linkCss);
$_menu = '';
$navPp = nav($_trad, $_menu);

ob_start();
// insertion des pages dinamiques
if(!file_exists($__page) ){
	require INC . 'erreur.inc.php';
} else {

	$__param = PARAM . $nav . '.param.php';

	if(file_exists($__param) && false )
		require $__param;

	$__func = FUNC . $nav . '.func.php';

	if(file_exists($__func) )
		require $__func;

	require $__page;
}
$contentPage = ob_get_contents();
ob_end_clean();

ob_start();
if(DEBUG) {
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