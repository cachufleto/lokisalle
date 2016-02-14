<?php
// Intertion des parametres de fonctionement
require_once("inc/init.inc.php");

// Fonctions communes
require_once(FUNC."com.func.php");
// intertion de l'entête
require_once(INC . "header.inc.php");
// Fonctions navigation
require_once(FUNC."nav.func.php");
// insertion menu de navigation
require_once(INC . "nav.inc.php");

// insertion des pages dinamiques
if(file_exists($__page) ){
	if(file_exists($__func) )
		require_once($__func);
	require_once($__page);
}
else require_once(INC . "erreur.inc.php");

// affichage des debug
if(defined('DEBUG')) include_once(INC . "debug.inc.php");

// insertion Pied de page
require_once(INC . "footer.inc.php");
