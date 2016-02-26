<?php
// Intertion des parametres de fonctionement
require_once("../inc/init.inc.php");

// control d'acces à l'aplication ADMIN
if(!utilisateurEstAdmin()){
 	header('Location:'.LINK);
 	exit();	
}

// surcharge des parametres admin
require_once("/inc/init.inc.php");

// insertion de l'entête
require_once(INC . "header.inc.php");

// insertion menu de navigation
require_once(INC . "nav.inc.php");

// pages admin
$__page = ADM . 'inc/' . $nav . '.inc.php';
// insertion des pages dinamiques
if(file_exists($__page) ){

	$__param = PARAM . $nav . '.param.php';
	if(file_exists($__param) )
		require_once($__param);

	$__func = FUNC . $nav . '.func.php';
	if(file_exists($__func) )
		require_once($__func);

	$__paramAdm = ADM . 'param/' . $nav . '.param.php';
	if(file_exists($__paramAdm) )
		require_once($__paramAdm);

	$__funcAdm = ADM . 'func/' . $nav . '.func.php';
	if(file_exists($__funcAdm) )
		require_once($__funcAdm);


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