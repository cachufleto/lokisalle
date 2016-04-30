<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 03/03/2016
 * Time: 10:06
 *
 * la spécialisation des logiques
 * Controleur.1 <- Model.1
 * Controleur.1 <- view.1
 * Controleur.2 <- Model.2 <- Model.1
 * Controleur.2 <- view.2 <- view.1
 * Controleur.3 <- view.3
 * Controleur.3 <- Controleur.2 [<- Model.2 <- Model.1]
 *
 * ------- ROUTER ----------------
 *
 * Le routeur
 * utilise les information de la rute -> http
 * pour déterminer le Controleur
 * pour déterminer l'action
 *
 * -------- SUPER MODEL ----------
 * partage des des information configuration, paramettres, environnement
 * partage des des methodes acces BDD, valeurs des configuration ...
 *
 * SUPER CONTROLER <- SUPER MODEL
 * SUPER CONTROLER <- SUPER MODEL <- fichier conf
 * SUPER CONTROLER <- SUPER VIEW <- layaout
 * SUPER CONTROLER <- fichier router
 *
 * temporisation
 * ob_start
 * .....
 * $_V <- ob_get_content
 * ob_emptide
 * echo $_V
 */
$route = array();

$route['cgv']['Controleur'] = 'site.php';
$route['cgv']['action'] = 'cgv';

$route['changermotpasse']['Controleur'] = 'users.php';
$route['changermotpasse']['action'] = 'changermotpasse';

$route['connection']['Controleur'] = 'users.php';
$route['connection']['action'] = 'connection';

$route['contact']['Controleur'] = 'site.php';
$route['contact']['action'] = 'contact';

$route['erreur404']['Controleur'] = 'site.php';
$route['erreur404']['action'] = 'erreur404';

$route['ficheSalles']['Controleur'] = 'salles.php';
$route['ficheSalles']['action'] = 'ficheSalles';

$route['home']['Controleur'] = 'site.php';
$route['home']['action'] = 'home';

$route['inscription']['Controleur'] = 'users.php';
$route['inscription']['action'] = 'inscription';

$route['install']['Controleur'] = 'site.php';
$route['install']['action'] = 'install';

$route['mentions']['Controleur'] = 'site.php';
$route['mentions']['action'] = 'mentions';

$route['newsletter']['Controleur'] = 'site.php';
$route['newsletter']['action'] = 'newsletter';

$route['plan']['Controleur'] = 'site.php';
$route['plan']['action'] = 'plan';

$route['profil']['Controleur'] = 'users.php';
$route['profil']['action'] = 'profil';

$route['recherche']['Controleur'] = 'salles.php';
$route['recherche']['action'] = 'recherche';

$route['reservation']['Controleur'] = 'salles.php';
$route['reservation']['action'] = 'reservation';

$route['session']['Controleur'] = 'site.php';
$route['session']['action'] = 'session';

$route['validerInscription']['Controleur'] = 'users.php';
$route['validerInscription']['action'] = 'validerInscription';

$route['salles']['Controleur'] = 'salles.php';
$route['salles']['action'] = 'salles';

if (utilisateurEstAdmin()) {
    $route['backoffice']['Controleur'] = 'site.php';
    $route['backoffice']['action'] = 'backoffice';

    $route['ficheSalles']['action'] = 'backOff_ficheSalles';

    $route['gestionSalles']['Controleur'] = 'salles.php';
    $route['gestionSalles']['action'] = 'backOff_gestionSalles';

    $route['editerSalles']['Controleur'] = 'salles.php';
    $route['editerSalles']['action'] = 'backOff_editerSalles';

    $route['users']['Controleur'] = 'users.php';
    $route['users']['action'] = 'backOff_users';
}
