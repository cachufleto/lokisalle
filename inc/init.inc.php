<?php
/* résoudre le problême des alias */
//déclaration de constante pour la racine serveur

$___script = str_replace('\\', '/', '/' . $_SERVER['PHP_SELF']);
$___script = str_replace('//', '', $___script);
$___appel = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
$___param = basename($___script); //'index . php';
$___dossier = dirname($___script) . DIRECTORY_SEPARATOR;

$___script = $_SERVER['PHP_SELF'];
$___script = str_replace('/', DIRECTORY_SEPARATOR, $___script);
$___appel = str_replace('/', DIRECTORY_SEPARATOR, $_SERVER['SCRIPT_FILENAME']);
$___param = basename($___script); //'index . php';
$___dossier = dirname($___script) . DIRECTORY_SEPARATOR;

define('__REPADMIN', 'BacOff');
define('REPADMIN', __REPADMIN . DIRECTORY_SEPARATOR);
define('RACINE_SERVER',str_replace($___script, '', $___appel));
define('RACINE_SITE', str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, str_replace(REPADMIN, '', dirname($___script) . DIRECTORY_SEPARATOR)));
define('APP', RACINE_SERVER . RACINE_SITE);
define('ADM', APP . REPADMIN);
define('INC', APP . 'inc' . DIRECTORY_SEPARATOR);
define('FUNC', APP . 'func' . DIRECTORY_SEPARATOR);
define('MODEL', APP . 'model' . DIRECTORY_SEPARATOR);
define('CONTROLEUR', APP . 'Controleur' . DIRECTORY_SEPARATOR);
define('PARAM', APP . 'param' . DIRECTORY_SEPARATOR);
define('TEMPLATE', APP . 'template' . DIRECTORY_SEPARATOR);
$link = str_replace(DIRECTORY_SEPARATOR, '/', str_replace(__REPADMIN, '', $_SERVER['HTTP_HOST'] . '/' . $___dossier));
define('LINK', 'http://' . (str_replace('//', '/', $link)));
define('LINKADMIN', LINK . __REPADMIN . '/');

// Constantes upload images
define('TARGET', APP . 'photo' . DIRECTORY_SEPARATOR);    // Repertoire cible
define('MAX_SIZE', 2000);    // Taille max en octets du fichier
define('WIDTH_MAX', 1024);    // Largeur max de l'image en pixels
define('HEIGHT_MAX', 830);    // Hauteur max de l'image en pixels

if (!file_exists(APP . 'index.php')) exit();

## Ouverture des sessions
session_start();

// activation du debug en fonction de l'environnement
$debug = ('localhost' == $_SERVER['HTTP_HOST'])? true : false;
define('DEBUG', $debug);

include  PARAM . 'init.php';
include  FUNC . 'com.func.php';
include  MODEL . 'Site.php';
include  CONTROLEUR . 'Users.php';

// gestion de session
usersControlSession($_formulaire);

// chargement de la langue
$_trad = siteSelectTrad();

/************************************************************
 * Creation du repertoire cible si inexistant
 *************************************************************/
if (!is_dir(TARGET) ) {
  if (!mkdir(TARGET, 0755) ) {
  	exit($_trad['erreur']['leRepertoireNePeutEtreCree']);
  }
}


////////////////////////////
///// NAV //////////////////
///////////////////////	/////
// page de navigation
$_pages = siteSelectPages();

$nav = (isset ($_GET['nav']) && !empty($_GET['nav']))? $_GET['nav'] : 'home';
$nav = (isset ($_pages[$nav]))? $nav : 'erreur404';

// REGLE D'orientation des pages actif et out ver connexion
if ('actif' == $nav || 'out' == $nav) $nav = 'connexion';

// cas spécifique
$nav = (!utilisateurEstAdmin() && $nav == 'users')? 'home' : $nav;
// page a inclure
$__page = INC . $nav . '.inc.php';

// options du menu de navigation
// Traduction du titre de la page
$titre = $_trad['titre'][$nav];

/** @var $menu */
$_menu = '';
/**
 * @var $_fileCss
 * @var $_fileJs
 * @var $_fileJsFooter
 */

$_linksFiles = array();
$_linksFiles['Css'][] = 'css/style.css';
$_linksFiles['Css'][] = 'css/' . $nav . '.css';
$_linksFiles['Js'][] = 'js/' . $nav . '.js';
$_linksFiles['JsFooter'][] = 'js/' . $nav . '.footer.js';

