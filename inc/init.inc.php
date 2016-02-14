<?php
define("DEBUG", 1);

//déclaration de constante pour la racine serveur
define("RACINE_SERVER", str_replace('\\	', '/', $_SERVER['DOCUMENT_ROOT']));

//déclaration de constante pour la racine site
$dir = explode('/', RACINE_SERVER);
$dossier = explode($dir[count($dir)-1], __DIR__);
$dossier = str_replace('/inc', '/', str_replace('\\', '/', $dossier[1]));
define("RACINE_SITE", $dossier);
define("APP", RACINE_SERVER.RACINE_SITE);
define("ADM", APP.'BacOff/');
define("INC", APP.'inc/');
define("FUNC", APP.'func/');
define("PARAM", APP.'param/');

// parametres generales
require_once(PARAM . "init.php");

## Ouverture des sessions
session_start();

$_SESSION['lang'] = (isset($_SESSION['lang']))? $_SESSION['lang'] : 'fr';	
$_SESSION['lang'] = (isset($_GET['lang']) && ($_GET['lang']=='fr' XOR $_GET['lang']=='es'))? $_GET['lang'] : $_SESSION['lang'];
// chargement de la langue
require_once(PARAM . "trad/fr/traduction.php");
include_once(PARAM . "trad/". $_SESSION['lang']."/traduction.php");

// options du menu de navigation
require_once(PARAM . "nav.php");
