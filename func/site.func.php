<?php
function siteHeader($_linkCss)
{
    $_link = '';
    foreach($_linkCss as $link)
        $_link .= '
    <link href="'. $link .'" rel="stylesheet">';
    return $_link;
}


function nav($_menu = '')
{
    $_trad = setTrad();
    listeMenu();
    $_link = $_SERVER["QUERY_STRING"];

    $menu = liste_nav($_menu);
    $class = $menu['class'];
    $li = $menu['menu'];

    if (isset($_SESSION['user'])) {
        $li .= "<li class='" . $class . "'><a class='admin'>[";
        $li .= ($_SESSION['user']['statut'] != 'MEM') ? $_trad['value'][$_SESSION['user']['statut']] . "::" : "";
        $li .= $_SESSION['user']['user'] . ']</a></li>';
    }

    $li .= "<li class='$class'>" . (($_SESSION['lang'] == 'es') ? "<a href='?$_link&lang=fr'>FR</a>" : "FR");
    $li .= " : " . (($_SESSION['lang'] == 'fr') ? "<a href='?$_link&lang=es'>ES</a>" : "ES") . "</li>";

    return $li;
}


# Fonction listeMenu()
# Valide le menu de navigation
# [@_pages] => array de navigation
# RETURN Boolean
function listeMenu()
{

    if(!utilisateurEstAdmin()) return;

    global $_pages, $_reglesAll, $_reglesMembre, $_reglesAdmin, $navAdmin, $navFooter;

    $_trad = setTrad();
    // control du menu principal

    foreach($_reglesAdmin as $key)
        if(!isset($_pages[$key]))
            exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuAdmin']);

    foreach($_reglesMembre as $key)
        if(!isset($_pages[$key]))
            exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuMembre']);

    foreach($_reglesAll as $key)
        if(!isset($_pages[$key]))
            exit($_trad['laRubrique'] . $key . $_trad['pasDansMenu']);

    // control du footer
    foreach($navFooter as $key)
        if(!isset($_pages[$key]))
            exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuFooter']);

    // control du menu administrateur
    foreach($navAdmin as $key)
        if(!isset($_pages[$key]))
            exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuAdmin']);

    return;
}

# Fonction liste_nav()
# affiche les informations en forme de liste du menu de navigation
# $actif => mode de connexion
# [@nav] => string action
# [@_pages] => array('nav'...)
# [@titre] => string titre de la page
# RETURN string liste <li>...</li>
function liste_nav($liste='')
{

    global $nav, $_pages, $navFooter, $navAdmin, $_reglesAdmin, $_reglesMembre, $_reglesAll;

    $_trad = setTrad();


    if(empty($liste)){

        $_liste = (utilisateurEstAdmin())?
            $_reglesAdmin :
            ((utilisateurEstConnecte())?
                $_reglesMembre :
                $_reglesAll);

    } else {
        // generation de la liste de nav
        $_liste = ${$liste};
    }

    // Pour affichage ou edition!
    $ADM = ($liste == 'navFooter' && preg_match('/BacOff/', $_SERVER['PHP_SELF']))? true : false;

    // generation de la liste de nav
    $col = count($_liste)+1;
    $menu = '';
    foreach ($_liste as $item){
        $info = $_pages[$item];
        $active = ($item == $nav)? 'active' : '';
        $class = (isset($_pages[$item]['class']))? $_pages[$item]['class'] : 'menu';
        $menu .= '
		<li class="' . $active .' '. $class.' col-'.$col.'">
			<a href="'. (($ADM)? LINKADMIN : $info['link'] ) .'?nav='. $item .'">' . $_trad['nav'][$item] . '</a>
		</li>';
    }

    return array('menu'=>$menu, 'class'=>$class . ' col-'.$col);
}

function footer()
{
    $info = liste_nav('navFooter');
    $info['version'] = file_get_contents(CONF . 'version.txt');
    return $info;
}

//Chargement des info supplementaires
function debugPhpInfo()
{
    if (isset($_GET['info']) && $_GET['info'] == 'PHP') {
        phpinfo();
    }
}

function debugTestMail()
{
    if (isset($_GET['info']) && $_GET['info'] == 'mail') {
        testmail();
    }
}

function debugCost()
{
    if (isset($_GET['info']) && $_GET['info'] == 'crypter') {
        cost();
    }
}

function cost()
{
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
}

function testmail()
{
    $to = 'carlos.paz@free.fr';
    $subject = 'le sujet';
    $message = 'Bonjour !';
    $headers = 'From: webmaster@example.com' . "\r\n" .
        'Reply-To: webmaster@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}

function debugParam()
{
    _debug(get_included_files(), 'FILES INCLUDES');
    _debug($_SESSION, 'SESSION');
    _debug($_POST, 'POST');
    _debug($_GET, 'GET');
    _debug($_FILES, '_FILES');
    _debug($_COOKIE, '_COOKIE');
    _debug($_SERVER['CONTEXT_PREFIX'], 'CONTEXT_PREFIX');
    _debug(
        array(
            'REPADMIN' => REPADMIN,
            'RACINE_SERVER' => RACINE_SERVER,
            'RACINE_SITE' => RACINE_SITE,
            'APP' => APP,
            'ADM' => ADM,
            'INC' => INC,
            'FUNC' => FUNC,
            'CONF' => CONF,
            'CONTROLEUR' => CONTROLEUR,
            'PARAM' => PARAM,
            'MODEL' => MODEL,
            'VUE' => VUE,
            'LINK' => LINK,
            'LINKADMIN' => LINKADMIN,
            'TARGET' => TARGET,
            'MAX_SIZE' => MAX_SIZE,
            'WIDTH_MAX' => WIDTH_MAX,
            'HEIGHT_MAX' => HEIGHT_MAX,
            'DEBUG' => DEBUG),
        'CONSTANTES');
}

# Fonction debug()
# affiche les informations passes dans l'argument $var
# $var => string, array, object
# $mode => defaut = 1
# RETURN NULL;
function debug($_debug, $mode=0)
{

    //global $_debug;

    echo '
	<div  id=\'debug\'>
	<hr>
	DEBUG -----------
	<hr>
	<div class="col-md-12">';

    if($mode === 1)
    {
        echo '<pre>'; var_dump($_debug); echo '</pre>';
    } else {
        echo '<pre>'; print_r($_debug); echo '</pre>';
    }

    echo '</div>';
    echo '</div>';

}

function _debug($var, $label)
{

    global $_debug;

    $_debug[][$label] = $var;

    return;
}

function session()
{
    ## Ouverture des sessions
    session_start();
    // valeur par default
    $_SESSION['lang'] = (isset($_SESSION['lang']))? $_SESSION['lang'] : 'fr';
    // recuperation du cookis lang
    $_SESSION['lang'] = (isset($_COOKIE['Lokisalle']))? $_COOKIE['Lokisalle']['lang'] : $_SESSION['lang'];
    // changement de lang par le user
    $_SESSION['lang'] = (isset($_GET['lang']) && ($_GET['lang']=='fr' XOR $_GET['lang']=='es'))? $_GET['lang'] : $_SESSION['lang'];

    if (utilisateurEstAdmin ()){
        if (isset($_GET['nav']) && $_GET['nav'] == 'backoffice') {
            $_SESSION['BO'] = TRUE;
        } else if (isset($_GET['nav']) && $_GET['nav'] == 'home') {
            $_SESSION['BO'] = FALSE;
        }
    }

    // définition des cookis
    setcookie( 'Lokisalle[lang]' , $_SESSION['lang'], time()+360000 );
    // Déconnection de l'utilisateur par tentative d'intrusion
    // comportement de déconnexion sur le site
    if (isset($_GET['nav']) && $_GET['nav'] == 'out' && isset($_SESSION['user'])) {
        // destruction de la navigation
        $lng = $_SESSION['lang'];
        unset($_GET['nav']);
        // destruction de la session
        unset($_SESSION['user']);

        session_destroy();
        // on relance la session avec le choix de la langue
        session_start();
        $_SESSION['lang'] = $lng;

    } elseif (isset($_GET['nav']) && $_GET['nav'] == 'out' && !isset($_SESSION['user'])) {

        // destruction de la navigation
        unset($_GET['nav']);

    } elseif (isset($_GET['nav']) && $_GET['nav'] == 'actif' && isset($_SESSION['user'])) {

        // control pour eviter d'afficher le formulaire de connexion
        // si l'utilisateur tente de le faire
        unset($_GET['nav']);

    }
}
