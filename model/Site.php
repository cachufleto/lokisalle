<?php

function siteSelectTrad()
{
    $_trad = array();
    include PARAM . 'trad' . DIRECTORY_SEPARATOR . 'fr' . DIRECTORY_SEPARATOR . 'traduction.php';
   if ($_SESSION['lang'] != 'fr'){
        include PARAM . 'trad' . DIRECTORY_SEPARATOR . $_SESSION['lang'] . DIRECTORY_SEPARATOR . 'traduction.php';
    }
    return $_trad;
}

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

function siteInstallBDD()
{
    // initialisation des tables
    $sql = file_get_contents(APP.'SQL' . DIRECTORY_SEPARATOR . 'lokisalle.sql');
    return executeMultiRequete($sql);
}

function siteInstallData()
{
    // remplisage des tables
    $sql = file_get_contents(APP.'SQL' . DIRECTORY_SEPARATOR . 'data.sql');
    return executeMultiRequete($sql);
}

function siteInstallUpdateMotPasseCrypte($_id, $_mdp)
{
    $sql = "UPDATE membres SET mdp = '". hashCrypt($_mdp) ."' WHERE id_membre = $_id";
    executeRequete($sql);
}

function siteSelectPages()
{
    include PARAM . 'nav.php';
    return $_pages;
}

function siteNavReglesAll()
{
// Onglets à activer dans le menu de navigation selon le profil listeMenu();
    include PARAM . 'nav.php';
    return $_reglesAll;
}

function siteNavReglesMembre()
{
    include PARAM . 'nav.php';
    return $_reglesMembre;
}

function siteNavReglesAdmin()
{
    include PARAM . 'nav.php';
    return $_reglesAdmin;
}

function siteNavAdmin()
{
    include PARAM . 'nav.php';
    return $navAdmin;
}

function siteNavFooter()
{
    include PARAM . 'nav.php';
    return $navFooter;
}
