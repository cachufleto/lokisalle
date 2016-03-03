<?php
/* résoudre le problême des alias */
//déclaration de constante pour la racine serveur

$___script = str_replace('\\', '/', '/'.$_SERVER['PHP_SELF']);
$___script = str_replace('//', '', $___script);
$___appel = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
$___param = basename($___script); //'index.php';
$___dossier = dirname(str_replace('App/', '', $___script)).'/';

define("REPADMIN", 'BacOff/');
define("RACINE_SERVER",str_replace($___script, '', $___appel));
$RACINE_SITE = str_replace('//', '/', str_replace('App/', '', dirname($___script).'/'));
define("RACINE_SITE", str_replace('//', '/', str_replace(REPADMIN.'/', '', $RACINE_SITE.'/')));
define("APP", RACINE_SERVER.RACINE_SITE);
define("ADM", APP.REPADMIN);
define("INC", APP.'inc/');
define("FUNC", APP.'func/');
define("PARAM", APP.'param/');
define("PARAMADM", ADM.'param/');
define("TEMPLATE", APP.'template/');
define("LINK", 'http://'. (str_replace(REPADMIN, '', $_SERVER["HTTP_HOST"].'/'.$___dossier . 'App/')));
define("LINKADMIN", str_replace('App/', '' , LINK.REPADMIN));

// Constantes upload images
define('TARGET', APP.'App/photo/');    // Repertoire cible
define('MAX_SIZE', 100000000);    // Taille max en octets du fichier
define('WIDTH_MAX', 10240000);    // Largeur max de l'image en pixels
define('HEIGHT_MAX', 8500000);    // Hauteur max de l'image en pixels

if(!file_exists(APP.'App/index.php')) exit();

// activation du debug en fonction de l'environnement
$debug = ('localhost' == $_SERVER["HTTP_HOST"])? true : false;
define("DEBUG", $debug);


## Ouverture des sessions
session_start();

$_linkCss[] = LINK.'css/style.css';
$_linkJs[] = LINK.'js/script.js';

require_once(PARAM . "init.php");
require_once(FUNC . "com.func.php");
// chargement de la langue
require_once(PARAM . "trad/fr/traduction.php");
include_once(PARAM . "trad/". $_SESSION['lang']."/traduction.php");

/************************************************************
 * Creation du repertoire cible si inexistant
 *************************************************************/
if( !is_dir(TARGET) ) {
  if( !mkdir(TARGET, 0755) ) {
  	exit($_trad['erreur']['leRepertoireNePeutEtreCree']);
  }
}

// gestion de session
include_once(INC . "session.inc.php");

// options du menu de navigation
require_once(PARAM . "nav.php");
// Traduction du titre de la page
$titre = $_trad['titre'][$nav];

$__link = APP . 'css/' . $nav . '.css';
if(file_exists($__link) ) {
	$_linkCss[] = LINK . 'css/' . $nav . '.css';
}

#########################################################
## retablire les tables de la base pour DEMO
#########################################################
//if(utilisateurEstAdmin() && isset($_GET['install']) && $_GET['install'] == 'BDD')
function install()
{
	if (isset($_GET['install']) && $_GET['install'] == 'BDD') {

		// initialisation des tables
		echo "chargement du fichier lokisalle.sql";
		$sql = file_get_contents(APP . '/SQL/lokisalle.sql');

		if (isset($_GET['data'])) {
			// remplisage des tables
			echo "<br>chargement du fichier data.sql";
			$sql .= file_get_contents(APP . '/SQL/data.sql');
		}
		// echo "<pre>$sql</pre>";
		if (executeMultiRequete($sql)) {

			$membres = executeRequete("SELECT id_membre, mdp FROM membres");
			while ($membre = $membres->fetch_assoc()) {
				$sql = "UPDATE membres SET mdp = '" . hashCrypt($membre['mdp']) . "' WHERE id_membre = " . $membre['id_membre'];
				executeRequete($sql);
			}
		}
		exit();

	}
}

install();
#########################################################
## SUITE
#########################################################

