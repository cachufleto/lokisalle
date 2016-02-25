<?php
// Intertion des parametres de fonctionement
require_once("inc/init.inc.php");
$_menu = '';

// intertion de l'entête
require_once(INC . "header.inc.php");

// insertion menu de navigation
echo '<div id="content">';
require_once(INC . "nav.inc.php");
//CONTENEUR
echo '<div id="' . $nav . '"  >';

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
echo '</div>'; // fin div de la page

// affichage des debug
if(DEBUG) include_once(INC . "debug.inc.php");

// insertion Pied de page
require_once(INC . "footer.inc.php");