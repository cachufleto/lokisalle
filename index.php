<?php
// Intertion des parametres de fonctionement
require_once("inc/init.inc.php");

// intertion de l'entête
$_menu = '';
require_once(INC . "header.inc.php");
// Fonctions navigation
require_once(FUNC."nav.func.php");
// insertion menu de navigation
require_once(INC . "nav.inc.php");

//echo $__page;

// insertion des pages dinamiques
if(file_exists($__page) ){

	$__param = PARAM . $nav . '.param.php';
	if(file_exists($__param) )
		require_once($__param);

	$__func = FUNC . $nav . '.func.php';
	if(file_exists($__func) )
		require_once($__func);
	echo '<div id="' . $nav . '" class="content">';
	require_once($__page);
	echo '</div>';
}
else require_once(INC . "erreur.inc.php");

// affichage des debug
if(DEBUG) include_once(INC . "debug.inc.php");
// insertion Pied de page
require_once(INC . "footer.inc.php");
