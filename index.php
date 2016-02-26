<?php
// Intertion des parametres de fonctionement
require_once("inc/init.inc.php");

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

