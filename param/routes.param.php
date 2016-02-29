<?php

$_route = array();

$_route['erreur404']['controleur'] = CONTROLEUR . 'Site.php';
$_route['erreur404']['action'] = 'siteErreur404';

$_route['connexion']['controleur'] = CONTROLEUR . 'Users.php';
$_route['connexion']['action'] = 'connexion';

/**
 * ????? à trouver
 */
$_route['connexion2']['controleur'] = CONTROLEUR . 'Users.php';
$_route['connexion2']['action'] = 'usersConnexion';

$_route['changermotpasse']['controleur'] = CONTROLEUR . 'Users.php';
$_route['changermotpasse']['action'] = 'usersChangerMotPasse';

$_route['home']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['home']['action'] = 'sallesHome';

$_route['detail']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['detail']['action'] = '';

$_route['reservation']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['reservation']['action'] = 'sallesPanier';

$_route['recherche']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['recherche']['action'] = 'sallesRecherche';

$_route['mdpperdu']['controleur'] = CONTROLEUR . 'Users.php';
$_route['mdpperdu']['action'] = '';

$_route['inscription']['controleur'] = CONTROLEUR . 'Users.php';
$_route['inscription']['action'] = 'usersInscription';

$_route['mentions']['controleur'] = CONTROLEUR . 'Entreprise.php';
$_route['mentions']['action'] = '';

$_route['cgv']['controleur'] = CONTROLEUR . 'Entreprise.php';
$_route['cgv']['action'] = '';

$_route['plan']['controleur'] = CONTROLEUR . 'Site.php';
$_route['plan']['action'] = '';

$_route['newsletter']['controleur'] = CONTROLEUR . 'Users.php';
$_route['newsletter']['action'] = '';

$_route['contact']['controleur'] = CONTROLEUR . 'Entreprise.php';
$_route['contact']['action'] = '';

$_route['salles']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['salles']['action'] = 'sallesListe';

$_route['ficheSalles']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['ficheSalles']['action'] = '';

$_route['validerIncription']['controleur'] = CONTROLEUR . 'Users.php';
$_route['validerIncription']['action'] = 'usersValiderInscription';

/************   MEMBRE    **************/

$_route['actif']['controleur'] = CONTROLEUR . 'Users.php';
$_route['actif']['action'] = 'usersConnexionForm';

$_route['out']['controleur'] = CONTROLEUR . 'Users.php';
$_route['out']['action'] = 'usersConnexionForm';

$_route['panier']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['panier']['action'] = '';

$_route['mjprofil']['controleur'] = CONTROLEUR . 'Users.php';
$_route['mjprofil']['action'] = '';

$_route['profil']['controleur'] = CONTROLEUR . 'Users.php';
$_route['profil']['action'] = '';

$_route['ficheMembre']['controleur'] = CONTROLEUR . 'Users.php';
$_route['ficheMembre']['action'] = '';

/************   ADMIN    **************/

$_route['backoffice']['controleur'] = CONTROLEUR . 'Site.php';
$_route['backoffice']['action'] = '';

$_route['boutique']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['boutique']['action'] = '';

$_route['users']['controleur'] = CONTROLEUR . 'Users.php';
$_route['users']['action'] = '';

$_route['ventes']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['ventes']['action'] = '';

$_route['gestionSalles']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['gestionSalles']['action'] = '';

$_route['editerSalles']['controleur'] = CONTROLEUR . 'Salles.php';
$_route['editerSalles']['action'] = '';

