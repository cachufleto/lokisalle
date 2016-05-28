<?php

# Fonction connectMysqli()
# connection à SQL
# $req => string SQL
# BLOQUANT
# RETURN object
function connectMysqli()
{

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
function executeRequete($req)
{
	$_trad = setTrad();

	_debug($req, 'SQL REQUETTE');

	$connexion = connectMysqli();

	$resultat = $connexion->query($req);

	if(!$resultat) {
		die ($_trad['erreur']['ATTENTIONErreurSurRequeteSQL'] . $req . '<br /><b>---> : </b>' . $connexion->error . '<br />');
	}

	// deconnectMysqli();
	$connexion->close() or die ($_trad['erreur']['ATTENTIONImpossibleFermerConnexionBDD'] . ${$connexion}->error . '<br />');
	
	return $resultat;
}

# Fonction executeMultiRequete()
# Exe requette SQL
# $req => string SQL
# BLOQUANT
# RETURN object
function executeMultiRequete($req)
{

	$_trad = setTrad();

	$connexion = connectMysqli();
	_debug($req, 'SQL Multi - REQUETTE');

	if ($connexion->multi_query($req)) {

		$i = 0;
		do {
			$connexion->next_result();

			$i++;
		}
		while( $connexion->more_results() );

		$connexion->close() or die ($_trad['erreur']['ATTENTIONImpossibleFermerConnexionBDD'] . ${$connexion}->error . '<br />');

		return true;
	}

	$connexion->close() or die ($_trad['erreur']['ATTENTIONImpossibleFermerConnexionBDD'] . ${$connexion}->error . '<br />');
	return false;

}

# Fonction hashCrypt()
# RETURN string crypt
function hashCrypt ($chaine)
{

	global $options;
	return password_hash($chaine, PASSWORD_BCRYPT, $options);

}

# Fonction hashCrypt()
# RETURN string crypt
function hashDeCrypt ($info)
{

	//password_verify($password, $hash)
	return password_verify($info['valide'], $info['sql']);

}

function ouvrirSession($session, $control = false)
{

	$_SESSION['user'] = array(
		'id'=>$session['id_membre'],
		'pseudo'=>$session['pseudo'],
		'statut'=>$session['statut'],
		'user'=>$session['prenom']);

	$control = ($session['id_membre'] == 1)? false : $control;

	setcookie( 'Lokisalle[pseudo]' , ($control)? $session['pseudo'] : '' , time()+360000 );
}


# Fonction isSuperAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function isSuperAdmin()
{
	
	return(utilisateurEstAdmin() AND $_SESSION['user']['id'] == 1)? true : false;

}

# Fonction utilisateurEstAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function utilisateurEstAdmin ()
{
	
	return(utilisateurEstConnecte() AND $_SESSION['user']['statut'] == 'ADM')? true : false;

}

# Fonction utilisateurEstAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function utilisateurEstCollaborateur ()
{
	
	return(utilisateurEstConnecte() AND $_SESSION['user']['statut'] == 'COL')? true : false;

}

# Fonction utilisateurEstConnecte()
# Verifie SESSION ACTIVE
# RETURN Boolean
function utilisateurEstConnecte()
{

	return (!isset($_SESSION['user']))? false : true;
	
}


function envoiMail($message, $to = WEBMAIL)
{
	$_trad = setTrad();

	// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// En-têtes additionnels
	$headers .= 'To: ' . $to . "\r\n";
	$headers .= 'From: ' . $_trad['inscriptionLokisalle'] . ' <' . SITEMAIL . '>' . "\r\n";
	$headers .= 'Reply-To: carlos.dupriez@gmail.com' . "\r\n";
	$headers .=  'X-Mailer: PHP/' . phpversion();

	// Test d'envoi mode debug
	if (DEBUG) {
		echo "<div style='border: solid green'>TEST ENVOI MAIL: <br> $message</div>";
	}

	return mail($to, $_trad['votreCompteLokisalle'], $message, $headers);
}

function setTrad(){

	// on charge la langue de base
	require CONF . 'trad/fr/traduction.php';
	// on surcharge la langue de l'utilisateur si different à celle de base
	if ($_SESSION['lang'] != 'fr') {
		include CONF . 'trad/' . $_SESSION['lang'] . '/traduction.php';
	}

	return $_trad;

}

/**
 * function ficheContactTemplate()
 * @param $contact
 * @return string
 */
function ficheContactTemplate($contact)
{

	$_trad = setTrad();

	$offre  = "<div class=\"fiche\">\r\n";
	$offre .= "\t<div>" . $contact['prenom'] . " " . $contact['nom'] . "</div>\r\n";
	$offre .= "\t\t<div><a href=\"mailto:" . $contact['email'] . "\">" . $contact['email'] . "</a></div>\r\n";
	$offre .= "\t\t<div>" . $_trad['value'][$contact['statut']] . "</div>\r\n";
	$offre .= "\t<hr/>\r\n";
	$offre .= "</div>\r\n";

	return $offre;
}

function imageExiste($photo, $rep = 'photo'){

	if(file_exists( RACINE_SERVER . RACINE_SITE . $rep . '/' . $photo)){
		return LINK . $rep . '/' . $photo;
	}
	return LINK . 'img/salles.jpg';
}
