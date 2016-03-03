<?php
include __DIR__ . '/../param/constantes.php';
include PARAM . 'connection.php';

// activation du debug en fonction de l'environnement
$debug = ('localhost' == $_SERVER["HTTP_HOST"])? true : false;
define("DEBUG", $debug);

require PARAM . 'init.php';
require FUNC . 'com.func.php';
// chargement de la langue
require PARAM . 'trad/fr/traduction.php';
include PARAM . 'trad/' . $_SESSION['lang'] . '/traduction.php';

/************************************************************
 * Creation du repertoire cible si inexistant
 *************************************************************/
if( !is_dir(TARGET) ) {
	if( !mkdir(TARGET, 0755) ) {
		exit($_trad['erreur']['leRepertoireNePeutEtreCree']);
	}
}

// gestion de session
include INC . 'session.inc.php';
include INC . 'install.inc.php';

// options du menu de navigation
require PARAM . 'nav.php';
// Traduction du titre de la page
$titre = $_trad['titre'][$nav];

$_linkCss[] = LINK . 'css/style.css';
$_linkJs[] = LINK . 'js/script.js';

$__link = APP . 'css/' . $nav . '.css';
if(file_exists($__link) ) {
	$_linkCss[] = LINK . 'css/' . $nav . '.css';
}


