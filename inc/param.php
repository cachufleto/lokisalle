<?php

$a = '<br>'; $b = ' ==> ';
$i = 0;
echo $a, $i++, ' - ', $_SERVER['SCRIPT_FILENAME']; //C:/Users/stagiaire/Dropbox/IFOCOP/projet/lokisalle/index.php
echo $a, $i++,' - ', $_SERVER['SCRIPT_NAME']; ///lokisalle/index.php
echo $a, $i++, ' - ',  $_SERVER['PHP_SELF']; ///lokisalle/index.php
echo $a;

$___appel = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
echo $a, $i++, ' - ',  $___appel; ///lokisalle/index.php
$___script = str_replace('\\', '/', '/'.$_SERVER['PHP_SELF']);
$___script = str_replace('//', '', $___script);
echo $a, $i++, ' - ', $___script;
$___param = 'inc/param.php';
echo $a, $i++, ' - ', $___param;
echo $a;


define("RACINE_SERVER",str_replace($___script, '', $___appel));
echo $a, $i++, ' - ', "define('RACINE_SERVER', str_replace($___script, '', $___appel));", $a, $b, RACINE_SERVER;

$___param = 'inc/param.php';
define("RACINE_SITE", str_replace($___param, '', $___script));
echo $a, $i++, ' - ',  "define('RACINE_SITE', str_replace($___param, '', $___script));", $a, $b, RACINE_SITE;


//déclaration de constante pour la racine site
define("APP", RACINE_SERVER.RACINE_SITE);
echo $a, $i++, ' - ',  "define('APP', RACINE_SERVER.RACINE_SITE));", $a, $b, APP;

define("LINKADM", 'BacOff/');
echo $a, $i++, ' - ',  "define('LINKADM', 'BacOff/'));", $a, $b, LINKADM;

define("ADM", APP.LINKADM);
echo $a, $i++, ' - ',  "define('ADM', APP.LINKADM));", $a, $b, ADM;

define("INC", APP.'inc/');
echo $a, $i++, ' - ',  "define('INC', APP.'inc/'));", $a, $b, INC;

define("FUNC", APP.'func/');
echo $a, $i++, ' - ',  "define('FUNC', APP.'func/'));", $a, $b, FUNC;

define("PARAM", APP.'param/');
echo $a, $i++, ' - ',  "define('PARAM',  APP.'param/'));", $a, $b, PARAM;

define("LINK", 'http://'.$_SERVER["HTTP_HOST"].$_SERVER['CONTEXT_PREFIX'].'/');
echo $a, $i++, ' - ',  "define('LINK', 'http://_SERVER[HTTP_HOST]_SERVER['CONTEXT_PREFIX']/');", $a, $b, LINK;


