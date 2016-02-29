<?php
// Intertion des parametres de fonctionement
include '..' . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.inc.php';

// control d'acces à l'aplication ADMIN
if (!utilisateurEstAdmin()){
 	header('Location:'.LINK);
 	exit();	
}

// surcharge des parametres admin
include DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'init.inc.php';

// pages admin
$__page = ADM . 'inc' . DIRECTORY_SEPARATOR . $nav . '.inc.php';
// insertion des pages dinamiques
if (file_exists($__page) ){

	$__param = PARAM . $nav . '.param.php';
	if (file_exists($__param) )
		include $__param;

	$__func = FUNC . $nav . '.func.php';
	if (file_exists($__func) )
		include $__func;

	$__paramAdm = ADM . 'param' . DIRECTORY_SEPARATOR . $nav . '.param.php';
	if (file_exists($__paramAdm) )
		include $__paramAdm;

	$__funcAdm = ADM . 'func' . DIRECTORY_SEPARATOR . $nav . '.func.php';
	if (file_exists($__funcAdm) )
		include $__funcAdm;


}
else {
	$__page = INC . 'erreur404.inc.php';
}

include INC . 'index.inc.php';

