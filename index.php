<?php
// Intertion des parametres de fonctionement
require_once("inc/init.inc.php");

// insertion de l'entête
require_once(INC . "header.inc.php");

// insertion menu de navigation
require_once(INC . "nav.inc.php");

// insertion des pages dinamiques
if(file_exists($__page) ){

	$__param = PARAM . $nav . '.param.php';
	if(file_exists($__param) )
		require_once($__param);

	$__func = FUNC . $nav . '.func.php';
	if(file_exists($__func) )
		require_once($__func);

}
else {
	$__page = INC . "erreur.inc.php";
}

include(INC . 'index.inc.php');

// affichage des debug
if(DEBUG) include_once(INC . "debug.inc.php");

// insertion Pied de page
require_once(INC . "navfooter.inc.php");

// insertion Pied de page
require_once(INC . "footer.inc.php");