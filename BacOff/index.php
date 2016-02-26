<?php
// Intertion des parametres de fonctionement
require_once(".." . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "init.inc.php");

// control d'acces à l'aplication ADMIN
if(!utilisateurEstAdmin()){
 	header('Location:'.LINK);
 	exit();	
}

// surcharge des parametres admin
require_once(DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "init.inc.php");

// pages admin
$__page = ADM . 'inc' . DIRECTORY_SEPARATOR . $nav . '.inc.php';
// insertion des pages dinamiques
if(file_exists($__page) ){

	$__param = PARAM . $nav . '.param.php';
	if(file_exists($__param) )
		require_once($__param);

	$__func = FUNC . $nav . '.func.php';
	if(file_exists($__func) )
		require_once($__func);

	$__paramAdm = ADM . 'param' . DIRECTORY_SEPARATOR . $nav . '.param.php';
	if(file_exists($__paramAdm) )
		require_once($__paramAdm);

	$__funcAdm = ADM . 'func' . DIRECTORY_SEPARATOR . $nav . '.func.php';
	if(file_exists($__funcAdm) )
		require_once($__funcAdm);


}
else {
	$__page = INC . "erreur.inc.php";
}

include(INC . 'index.inc.php');

