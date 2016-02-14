<div style="display:block; height: 100px;">&nbsp;</div>
<hr>
DEBUG -----------
<hr>
<?php
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

if(isset($_GET['info']) && $_GET['info'] == 'PHP') phpinfo();