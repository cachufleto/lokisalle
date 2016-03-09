<?php
include __DIR__ . '/../conf/constantes.php';
include CONF . 'connection.php';

// activation du debug en fonction de l'environnement
$debug = ('localhost' == $_SERVER["HTTP_HOST"])? true : false;
define("DEBUG", $debug);

require CONF . 'init.php';
require FUNC . 'com.func.php';
require FUNC . 'site.func.php';
session();
// chargement de la langue

/************************************************************
 * Creation du repertoire cible si inexistant
 *************************************************************/
if( !is_dir(TARGET) ) {
	if( !mkdir(TARGET, 0755) ) {
		exit($_trad['erreur']['leRepertoireNePeutEtreCree']);
	}
}

// gestion de session
include INC . 'install.inc.php';

// options du menu de navigation
require CONF . 'nav.php';
// Traduction du titre de la page

$_linkCss[] = LINK . 'css/style.css';
$_linkJs[] = LINK . 'js/script.js';

$__link = APP . 'css/' . $nav . '.css';
if(file_exists($__link) ) {
	$_linkCss[] = LINK . 'css/' . $nav . '.css';
}

require CONF . 'route.php';

