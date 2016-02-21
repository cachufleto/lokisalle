
<div  id='debug'>
<hr>
DEBUG -----------
<hr>
<?php
// test ooption de hashage pour les mot de passe
$timeTarget = 0.05; // 50 millisecondes

$cost = 8;
do {
    $cost++;
    $start = microtime(true);
    password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
    $end = microtime(true);
} while (($end - $start) < $timeTarget);

echo "Valeur de 'cost' la plus appropriée : " . $cost . "\n";

_debug(get_included_files(), 'FILES INCLUDES');
//Chargement des info supplementaires

_debug($_SESSION, 'SESSION');
_debug($_POST, 'POST');
_debug($_GET, 'GET');
_debug($_FILES, '_FILES');
_debug($_COOKIE, '_COOKIE');
_debug($_SERVER['CONTEXT_PREFIX'], 'CONTEXT_PREFIX');
_debug(RACINE_SERVER, 'RACINE_SERVER');
_debug(RACINE_SITE, 'RACINE_SITE');
_debug(APP, 'APP');
_debug(ADM, 'ADM');
_debug(INC, 'INC');
_debug(FUNC, 'FUNC');
_debug(RACINE_SERVER, 'RACINE_SERVER');
_debug(LINK, 'LINK');
///home/strato/http/premium/rid/02/16/54400216/htdocs
// _debug($__page, '__page');
// _debug($__func, '__func');
// _debug($_pages, '_pages');
_debug($_trad, '_trad');

debug();
echo '</div>';
if(isset($_GET['info']) && $_GET['info'] == 'PHP') phpinfo();