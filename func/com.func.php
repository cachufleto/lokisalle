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
		'id'=>$session['id'],
		'pseudo'=>$session['pseudo'],
		'statut'=>$session['statut'],
		'user'=>$session['prenom']);

	$control = ($session['id'] == 1)? false : $control;

	setcookie( 'Lokisalle[pseudo]' , ($control)? $session['pseudo'] : '' , time()+360000 );
}


# Fonction isSuperAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function isSuperAdmin()
{
	
	return(utilisateurAdmin() AND $_SESSION['user']['id'] == 1)? true : false;

}

# Fonction utilisateurAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function utilisateurAdmin()
{
	
	return(utilisateurConnecte() AND $_SESSION['user']['statut'] == 'ADM')? true : false;

}

# Fonction utilisateurAdmin()
# Verifie SESSION ADMIN ACTIVE
# RETURN Boolean
function utilisateurEstCollaborateur ()
{
	
	return(utilisateurConnecte() AND $_SESSION['user']['statut'] == 'COL')? true : false;

}

# Fonction utilisateurConnecte()
# Verifie SESSION ACTIVE
# RETURN Boolean
function utilisateurConnecte()
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

function setTrad()
{
	// on charge la langue de base
	require CONF . 'trad/fr/traduction.php';
	// on surcharge la langue de l'utilisateur si different à celle de base
	if ($_SESSION['lang'] != 'fr') {
		include CONF . 'trad/' . $_SESSION['lang'] . '/traduction.php';
	}

	return $_trad;

}

function setPrixPlage()
{
	include CONF . 'parametres.param.php';
	return $_prixPlage;
}

function setPrixTranches()
{
	include CONF . 'parametres.param.php';
	return $_tranches;
}

function imageExiste($photo, $rep = 'photo')
{
	if(file_exists( RACINE_SERVER . RACINE_SITE . $rep . '/' . $photo)){
		return LINK . $rep . '/' . $photo;
	}
	return LINK . 'img/salles.jpg';
}


function urlSuivante()
{
	$_GET = isset($_SESSION['urlReservation'])? $_SESSION['urlReservation'] : $_GET;
	unset($_SESSION['urlReservation']);
	$url = isset($_GET['nav'])? '?nav='.$_GET['nav'] : false;
	if($url){
		foreach($_GET as $key => $info){
			$url .= ($key != 'nav')? "&$key=$info" : '';
		}
	}
	header('location:index.php'.$url);
}

function data_methodes($indice, $default = false)
{
	$data = (int)(isset($_POS[$indice])? $_POS[$indice] : $default);
	$data = (int)(isset($_GET[$indice])? $_GET[$indice] : $data);
	return $data;
}

function disponibilite()
{
	$_trad['choixsirDate'] = " A la date du: ";
	return "<form name='dispo' method='POST'>
			{$_trad['choixsirDate']}
			<input type='date' name='date' value='{$_SESSION['date']}'>
			<input type='text' name='numpersonne' placeholder='Num. Pers.' value='{$_SESSION['numpersonne']}'>
			<input type='submit' name='' value='OK'>
		</form>";
}

function sortIndice($data)
{
	foreach ($data as $id => $info) {
		$sort[] = $id;
	}
	sort($sort);

	return $sort;
}

