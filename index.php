<?php
// Intertion des parametres de fonctionement
require_once("inc/init.inc.php");

// intertion de l'entête
require_once(INC . "header.inc.php");

// insertion menu de navigation
$_menu = '';
require_once(INC . "nav.inc.php");
//CONTENEUR
echo '<div id="' . $nav . '" class="content">';
	
// insertion des pages dinamiques
if(file_exists($__page) ){

	$__param = PARAM . $nav . '.param.php';
	if(file_exists($__param) )
		require_once($__param);
	$__func = FUNC . $nav . '.func.php';
	if(file_exists($__func) )
		require_once($__func);

	require_once($__page);
}
else require_once(INC . "erreur.inc.php");

//FIN CONTENEUR
echo '</div>';

// affichage des debug
if(DEBUG) include_once(INC . "debug.inc.php");
// insertion Pied de page
require_once(INC . "footer.inc.php");
