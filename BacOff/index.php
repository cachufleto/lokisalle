<?php
// Intertion des parametres de fonctionement
require __DIR__ . '/../inc/init.inc.php';

// control d'acces à l'aplication ADMIN
if(!utilisateurEstAdmin()){
 	header('Location:'.LINK);
 	exit();	
}

// ajout du css admin
$_linkCss[] = LINK . 'css/admin.css';
// ajout du css de la page en cour
$__link = APP . 'css/' . $nav . '.adm.css';
if(file_exists($__link) )
	$_linkCss[] = LINK . 'css/' . $nav . '.adm.css';

// insertion de l'entête
require INC . 'header.inc.php';

// insertion menu de navigation
$_menu = 'navAdmin';
echo '<div id="content">';
require INC . 'nav.inc.php';
//CONTENER
echo '<div id="' . $nav . '"  >';
// pages admin
$__page = ADM . 'inc/' . $nav . '.inc.php';
// insertion des pages dinamiques
if(file_exists($__page) ){

	$__paramAdm = ADM . 'param/' . $nav . '.param.php';
	$__param = PARAM . $nav . '.param.php';

	if(file_exists($__paramAdm) and false )
		require $__paramAdm;
	elseif(file_exists($__param) and false )
		require $__param;

	$__funcAdm = ADM . 'func/' . $nav . '.func.php';
	$__func = FUNC . $nav . '.func.php';

	if(file_exists($__funcAdm) )
		require $__funcAdm;
	elseif(file_exists($__func) )
		require $__func;

	require $__page;

} else {
	require INC . 'erreur.inc.php';
}

echo '</div>'; // fin div du content

// affichage des debug
if(DEBUG) {
	include INC . 'debug.inc.php';
}

// insertion Pied de page
require INC . 'footer.inc.php';