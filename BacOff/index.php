<?php
// Intertion des parametres de fonctionement
require_once("../inc/init.inc.php");
$_menu = 'navAdmin';

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
require_once(INC . "header.inc.php");

// insertion menu de navigation
echo '<div id="content">';
require_once(INC . "nav.inc.php");
//CONTENER
echo '<div id="' . $nav . '"  >';
// pages admin
$__page = ADM . 'inc/' . $nav . '.inc.php';
// insertion des pages dinamiques
if(file_exists($__page) ){

	$__paramAdm = ADM . 'param/' . $nav . '.param.php';
	$__param = PARAM . $nav . '.param.php';

	if(file_exists($__paramAdm) )
		require_once($__paramAdm);
	elseif(file_exists($__param) )
		require_once($__param);

	$__funcAdm = ADM . 'func/' . $nav . '.func.php';
	$__func = FUNC . $nav . '.func.php';

	if(file_exists($__funcAdm) )
		require_once($__funcAdm);
	elseif(file_exists($__func) )
		require_once($__func);

	require_once($__page);
} else require_once(INC . "erreur.inc.php");

echo '</div>'; // fin div du content

// affichage des debug
if(DEBUG) include_once(INC . "debug.inc.php");

// insertion Pied de page
require_once(INC . "footer.inc.php");