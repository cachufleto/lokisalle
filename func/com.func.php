<?php

# Fonction executeRequete()
# Exe requette SQL
# $req => string SQL
# BLOQUANT
# RETURN object
function executeRequete($req) {
	global $mysqli;

	$resultat = $mysqli->query($req);

	if(!$resultat) {
		die ('Erreur sur la requete SQL <br /><b>Message : </b>' . $mysqli->error . '<br />');
	}

	return $resultat;
}

# Fonction utilisateurEstAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function utilisateurEstAdmin () {
	
	return(utilisateurEstConnecte() && $_SESSION['user']['status'] == 1)? true : false;

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
