<?php
/* résoudre le problême des alias */
//déclaration de constante pour la racine serveur

$___script = str_replace('\\', '/', '/' . $_SERVER['PHP_SELF']);
$___script = str_replace('//', '', $___script);
$___appel = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
$___param = basename($___script); //'index.php';
$___dossier = dirname(str_replace('Public/', '', $___script)) . '/';

define("REPADMIN", 'BacOff/');
define("RACINE_SERVER",str_replace($___script, '', $___appel));
$RACINE_SITE = str_replace('//', '/', str_replace('Public/', '', dirname($___script) . '/'));
define("RACINE_SITE", str_replace('//', '/', str_replace(REPADMIN . '/', '', $RACINE_SITE . '/')));
define("APP", RACINE_SERVER . RACINE_SITE);
define("ADM", APP . REPADMIN);
define("INC", APP . 'inc/');
define("FUNC", APP . 'func/');
define("PARAM", APP . 'param/');
define("PARAMADM", ADM . 'param/');
define("TEMPLATE", APP . 'App/Vue/');
define("LINK", 'http://' . (str_replace(REPADMIN, '', $_SERVER["HTTP_HOST"] . '/' . $___dossier . 'Public/')));
define("LINKADMIN", str_replace('Public/', '' , LINK . REPADMIN));

// Constantes upload images
define('TARGET', APP . 'Public/photo/');    // Repertoire cible
define('MAX_SIZE', 100000000);    // Taille max en octets du fichier
define('WIDTH_MAX', 10240000);    // Largeur max de l'image en pixels
define('HEIGHT_MAX', 8500000);    // Hauteur max de l'image en pixels

if(!file_exists(APP . 'Public/index.php')) exit("<br>" . APP . ' ---> Public/index.php');
