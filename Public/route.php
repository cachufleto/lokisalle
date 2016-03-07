<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 03/03/2016
 * Time: 10:06
 *
 * la spécialisation des logiques
 * controleur.1 <- model.1
 * controleur.1 <- view.1
 * controleur.2 <- model.2 <- model.1
 * controleur.2 <- view.2 <- view.1
 * controleur.3 <- view.3
 * controleur.3 <- controleur.2 [<- model.2 <- model.1]
 *
 * ------- ROUTER ----------------
 *
 * Le routeur
 * utilise les information de la rute -> http
 * pour déterminer le controleur
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

$route['backoffice']['controleur'] = 'backoffice.inc.php';
$route['backoffice']['action'] = 'backoffice';

$route['cgv']['controleur'] = 'cgv.inc.php';
$route['cgv']['action'] = 'cvg';

$route['changermotpasse']['controleur'] = 'changermotpasse.inc.php';
$route['changermotpasse']['action'] = 'changermotpasse';

$route['connection']['controleur'] = 'connection.inc.php';
$route['connection']['action'] = 'connection';

$route['contact']['controleur'] = 'contact.inc.php';
$route['contact']['action'] = 'contact';

$route['erreur404']['controleur'] = 'erreur404.inc.php';
$route['erreur404']['action'] = 'erreur404';

$route['fichesalles']['controleur'] = 'fichesalles.inc.php';
$route['fichesalles']['action'] = 'fichesalles';

$route['home']['controleur'] = 'home.inc.php';
$route['home']['action'] = 'home';

$route['inscription']['controleur'] = 'inscription.inc.php';
$route['inscription']['action'] = 'inscription';

$route['install']['controleur'] = 'install.inc.php';
$route['install']['action'] = 'install';

$route['mentions']['controleur'] = 'mentions.inc.php';
$route['mentions']['action'] = 'mentions';

$route['newsletter']['controleur'] = 'newsletter.inc.php';
$route['newsletter']['action'] = 'newsletter';

$route['plan']['controleur'] = 'plan.inc.php';
$route['plan']['action'] = 'plan';

$route['profil']['controleur'] = 'profil.inc.php';
$route['profil']['action'] = 'profil';

$route['recherche']['controleur'] = 'recherche.inc.php';
$route['recherche']['action'] = 'recherche';

$route['reservation']['controleur'] = 'reservation.inc.php';
$route['reservation']['action'] = 'reservation';

$route['salles']['controleur'] = 'salles.inc.php';
$route['salles']['action'] = 'salles';

$route['session']['controleur'] = 'session.inc.php';
$route['session']['action'] = 'session';

$route['validerInscription']['controleur'] = 'validerInscription.inc.php';
$route['validerInscription']['action'] = 'validerInscription';
