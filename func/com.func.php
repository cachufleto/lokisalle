<?php

# Fonction connectMysqli()
# connection à SQL
# $req => string SQL
# BLOQUANT
# RETURN object
function connectMysqli(){

	global $BDD;

	$connexion = @new mysqli($BDD['SERVEUR_BDD'], $BDD['USER'], $BDD['PASS'], $BDD['BDD']);

	// Jamais de ma vie je ne metterais un @ pour cacher une erreur sauf si je le gere proprement avec ifx_affected_rows
	if($connexion->connect_error) {
		die("Un probleme est survenu lors de la connexion a la BDD" . $connexion->connect_error);
	}

	$connexion->set_charset("utf-8"); // en cas de souci d'encodage avec l'utf-8
	
	//$connexion->host_info;
	
	return $connexion;

}

# Fonction executeRequete()
# Exe requette SQL
# $req => string SQL
# BLOQUANT
# RETURN object
//function executeRequete($req, $connexion = 'mysqli') {
function executeRequete($req) {

	
	$connexion = connectMysqli();

	$resultat = $connexion->query($req);

	if(!$resultat) {
		die ('<span style="color:red">ATTENTION! Erreur sur la requete SQL</span><br /><b>Message : </b>' . $connexion->error . '<br />');
	}

	// deconnectMysqli();
	$connexion->close() or die ('<span style="color:red">ATTENTION! Il est impossible de fermer la connexion à la BDD</span><br /><b>Message : </b>' . ${$connexion}->error . '<br />');
	
	return $resultat;
}

# Fonction executeRequete()
# Exe requette SQL
# $req => string SQL
# BLOQUANT
# RETURN object
//function executeRequete($req, $connexion = 'mysqli') {
function executeMultiRequete($req) {

	
	$connexion = connectMysqli();

	$resultat = $connexion->multi_query($req);

	if(!$resultat) {
		die ('<span style="color:red">ATTENTION! Erreur sur la requete SQL</span><br /><b>Message : </b>' . $connexion->error . '<br />');
	}

	// deconnectMysqli();
	$connexion->close() or die ('<span style="color:red">ATTENTION! Il est impossible de fermer la connexion à la BDD</span><br /><b>Message : </b>' . ${$connexion}->error . '<br />');
	
	return $resultat;
}

# Fonction isSuperAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function isSuperAdmin() {
	
	return(utilisateurEstAdmin() AND $_SESSION['user']['id'] == 1)? true : false;

}

# Fonction utilisateurEstAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function utilisateurEstAdmin () {
	
	return(utilisateurEstConnecte() AND $_SESSION['user']['statut'] == 'ADM')? true : false;

}

# Fonction utilisateurEstAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function utilisateurEstCollaborateur () {
	
	return(utilisateurEstConnecte() AND $_SESSION['user']['statut'] == 'COL')? true : false;

}

# Fonction utilisateurEstConnecte()
# Verifie SESSION ACTIVE
# RETURN Boolean
function utilisateurEstConnecte() {

	return (!isset($_SESSION['user']))? false : true;
	
}

# Fonction debug()
# affiche les informations passes dans l'argument $var
# $var => string, array, object
# $mode => defaut = 1
# RETURN NULL;
function debug($mode=0){

	global $_debug;
	
	echo '<div class="col-md-12">';

	if($mode === 1)
	{
		echo '<pre>'; var_dump($_debug); echo '</pre>';
	}
	else {
		echo '<pre>'; print_r($_debug); echo '</pre>';
	}

	echo '</div>';

	return;
}

function _debug($var, $label){
	
	global $_debug;
	
	$_debug[][$label] = $var;
	
	return;
}
