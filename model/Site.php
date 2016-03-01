<?php
/**
 * @return array
 */
function siteSelectTrad()
{
    $_trad = array();
    include PARAM . 'trad' . DIRECTORY_SEPARATOR . 'fr' . DIRECTORY_SEPARATOR . 'traduction.php';
   if ($_SESSION['lang'] != 'fr'){
        include PARAM . 'trad' . DIRECTORY_SEPARATOR . $_SESSION['lang'] . DIRECTORY_SEPARATOR . 'traduction.php';
    }
    return $_trad;
}

/**
 * @param $install
 */
function siteInstall($install)
{
   if (isset($install['install']) && $install['install'] == 'BDD')
    {
        $data = isset($install['data'])? true : false;
        echo "<br>chargement du fichier lokisalle.sql";
       if (installBDD()){
            echo "<br>chargement du fichier data.sql";
           if ($data){
                installData();
            }
            $membres = executeRequete("SELECT id_membre, mdp FROM membres");
            while($membre = $membres->fetch_assoc()){
                installUpdateMotPasseCrypte($membre['id_membre'], $membre['mdp']);
            }
        }
        exit();
    }
}

/**
 * @return bool
 */
function siteInstallBDD()
{
    // initialisation des tables
    $sql = file_get_contents(APP.'SQL' . DIRECTORY_SEPARATOR . 'lokisalle.sql');
    return executeMultiRequete($sql);
}

/**
 * @return bool
 */
function siteInstallData()
{
    // remplisage des tables
    $sql = file_get_contents(APP.'SQL' . DIRECTORY_SEPARATOR . 'data.sql');
    return executeMultiRequete($sql);
}

/**
 * @param $_id
 * @param $_mdp
 */
function siteInstallUpdateMotPasseCrypte($_id, $_mdp)
{
    $sql = "UPDATE membres SET mdp = '". hashCrypt($_mdp) ."' WHERE id_membre = $_id;";
    executeRequete($sql);
}

/**
 * @return mixed
 */
function siteSelectPages()
{
    include PARAM . 'nav.php';
    return $_pages;
}

/**
 * @return mixed
 */
function siteNavReglesAll()
{
// Onglets à activer dans le menu de navigation selon le profil listeMenu();
    include PARAM . 'nav.php';
    return $_reglesAll;
}

/**
 * @return mixed
 */
function siteNavReglesMembre()
{
    include PARAM . 'nav.php';
    return $_reglesMembre;
}

/**
 * @return mixed
 */
function siteNavReglesAdmin()
{
    include PARAM . 'nav.php';
    return $_reglesAdmin;
}

/**
 * @return mixed
 */
function siteNavAdmin()
{
    include PARAM . 'nav.php';
    return $_navAdmin;
}

/**
 * @return mixed
 */
function siteNavFooter()
{
    include PARAM . 'nav.php';
    return $_navFooter;
}

/**
 * @param $__nav
 * @return mixed
 */
function siteErreur404()
{
    global $__nav;
    $_pages = siteSelectPages();
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    if (array_key_exists($__nav, $_pages)){
        $msg = $_trad['enConstruccion'];
    } else {
        $msg = $_trad['ERROR404'];
    }
    include TEMPLATE . 'erreur404.html.php';
}

/**
 * @return string
 */
function siteNav()
{
    global $_menu;
    $_trad = siteSelectTrad();
    listeMenu();
    $_link = $_SERVER["QUERY_STRING"];
    $menu = liste_nav($_menu);
    $class = $menu['class'];
    $liNav = $menu['menu'];
    if (isset($_SESSION['user'])) {
        $liNav .= "<li class=\"$class\"><a class='admin'>[";
        $liNav .= ($_SESSION['user']['statut'] != 'MEM') ? $_trad['value'][$_SESSION['user']['statut']] . "::" : "";
        $liNav .= $_SESSION['user']['user'] . ']</a></li>';
    }
    $liNav .= "<li class=\"$class\">";
    $liNav .= ($_SESSION['lang'] == 'es') ? "<a href=\"?$_link&lang=fr\">FR</a>" : "FR";
    $liNav .= " : " . (($_SESSION['lang'] == 'fr') ? "<a href='?$_link&lang=es'>ES</a>" : "ES") . "</li>";
    return $liNav;
}
