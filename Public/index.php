<?php
// Intertion des parametres de fonctionement
require __DIR__ . '/../inc/init.inc.php';

// intertion de l'entête
require INC . 'header.inc.php';

// insertion menu de navigation
$_menu = '';
echo '<div id="content">';
require INC . 'nav.inc.php';
//CONTENEUR
echo '<div id="' . $nav . '"  >';

// insertion des pages dinamiques
if(file_exists($__page) ){

	$__param = PARAM . $nav . '.param.php';

	if(file_exists($__param) && false )
		require $__param;

	$__func = FUNC . $nav . '.func.php';

	if(file_exists($__func) )
		require $__func;

	require $__page;
} else {
	require INC . 'erreur.inc.php';
}

echo '</div>'; // fin div de la page

// affichage des debug
if(DEBUG) {
	include INC . 'debug.inc.php';
}

// insertion Pied de page
require INC . 'footer.inc.php';