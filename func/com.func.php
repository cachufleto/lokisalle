<?php
/**
 * Fonction connectMysqli()
 * connexion à SQL
 * @param $req => string SQL
 * BLOQUANT
 * RETURN object
 */
function connectMysqli()
{

	global $BDD;

	$connexion = @new mysqli($BDD['SERVEUR_BDD'], $BDD['USER'], $BDD['PASS'], $BDD['BDD']);

	// Jamais de ma vie je ne metterais un @ pour cacher une erreur sauf si je le gere proprement avec ifx_affected_rows
	if ($connexion->connect_error) {
		die("Un probleme est survenu lors de la connexion a la BDD" . $connexion->connect_error);
	}

	$connexion->set_charset("utf-8"); // en cas de souci d'encodage avec l'utf-8
	
	//$connexion->host_info;
	
	return $connexion;

}

/**
 * Fonction executeRequete()
 * Exe requette SQL
 * $req => string SQL
 * BLOQUANT
 * RETURN object
 */
function executeRequete($req)
{

	$connexion = connectMysqli();

	$resultat = $connexion->query($req);

	if (!$resultat) {
		die ('<span style="color:red">ATTENTION! Erreur sur la requete SQL</span><br /><b>Message : </b>' . $connexion->error . '<br />');
	}

	// deconnectMysqli();
	$connexion->close() or die ('<span style="color:red">ATTENTION! Il est impossible de fermer la connexion à la BDD</span><br /><b>Message : </b>' . ${$connexion}->error . '<br />');

	return $resultat;
}

/**
 * Fonction executeRequeteExist()
 * Exe requette SQL
 * $req => string SQL
 * BLOQUANT
 * RETURN object
 */
function executeRequeteExist($req)
{
	global $_trad;

	$connexion = connectMysqli();

	$resultat = $connexion->query($req);

	if (!$resultat) {
		die ($_trad['ATTENTIONErreurRequeteSQL'] . $connexion->error);
	}

	// deconnectMysqli();
	$connexion->close() or die ($_trad['ATTENTIONImpossibleFermerConnexionBDD'] . ${$connexion}->error . '<br />');

	return ($resultat->num_rows > 0)? true : false;
}

/**
 * Fonction returnExecuteRequeteAssoc()
 * Exe requette SQL
 * $req => string SQL
 * BLOQUANT
 * RETURN object
 */
function executeRequeteAssoc($req)
{
	global $_trad;

	$connexion = connectMysqli();

	$resultat = $connexion->query($req);

	if (!$resultat) {
		die ($_trad['ATTENTIONErreurRequeteSQL'] . $connexion->error);
	}

	// deconnectMysqli();
	$connexion->close() or die ($_trad['ATTENTIONImpossibleFermerConnexionBDD'] . ${$connexion}->error . '<br />');

	return ($resultat->num_rows === 1)? $resultat->fetch_assoc() : false;
}

/**
 * Fonction executeMultiRequete()
 * Exe requette SQL
 * $req => string SQL
 * BLOQUANT
 * RETURN object
 */
function executeMultiRequete($req)
{

	global $_trad;

	$connexion = connectMysqli();

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

/**
 * Fonction hashCrypt()
 * RETURN string crypt
 */
function hashCrypt ($chaine)
{

	global $options;
	return password_hash($chaine, PASSWORD_BCRYPT, $options);

}

/**
 * Fonction hashCrypt()
 * RETURN string crypt
 */
function hashDeCrypt ($info)
{
	// password_verify (password, hash)
	return password_verify($info['valide'], $info['sql']);

}

/**
 * Fonction ouvrirSession()
 * RETURN string crypt
 */
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

/**
 * Fonction isSuperAdmin()
 * Verifie SESSION ADMIN ACTIVE
 * RETURN Boolean
 */
function isSuperAdmin()
{
	
	return(utilisateurEstAdmin() AND $_SESSION['user']['id'] == 1)? true : false;

}

/**
 * Fonction utilisateurEstAdmin()
 * Verifie SESSION ADMIN ACTIVE
 * RETURN Boolean
 */
function utilisateurEstAdmin ()
{
	
	return(utilisateurEstConnecte() AND $_SESSION['user']['statut'] == 'ADM')? true : false;

}

/**
 * Fonction utilisateurEstAdmin()
 * Verifie SESSION ADMIN ACTIVE
 * RETURN Boolean
 */
function utilisateurEstCollaborateur ()
{
	
	return(utilisateurEstConnecte() AND $_SESSION['user']['statut'] == 'COL')? true : false;

}

/**
 * Fonction utilisateurEstConnecte()
 * Verifie SESSION ACTIVE
 * RETURN Boolean
 */
function utilisateurEstConnecte()
{

	return (!isset($_SESSION['user']))? false : true;
	
}


/**
 * Fonction envoiMail()
 * envoi un mail
 * RETURN Boolean
 */
function envoiMail($key, $to = 'carlos.paz@free.fr')
{
	global $_trad;

	// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// En-têtes additionnels
	$headers .= 'To: ' . $to . "\r\n";
	$headers .= 'From: ' . $_trad['inscriptionLokisalle'] . ' <webmaster@lokisalle.domoquick.fr>' . "\r\n";
	$headers .= 'Reply-To: carlos.dupriez@gmail.com' . "\r\n";
	$headers .=  'X-Mailer: PHP/' . phpversion();

	// chargement de la var $message
	$message = '';
	include TEMPLATE . 'validationpassword.html.php';

	// Envoi
	return mail($to, $_trad['votreCompteLokisalle'], $message, $headers);
}


/**
 * Fonction listeDistinc()
 * genere une liste avec des valeurs distinc d'un champ
 * RETURN Boolean
 */

function selectDistinct($champ, $table)
{
	$sql = "SELECT DISTINCT $champ FROM $table ORDER BY $champ ASC";
	return executeRequete($sql);
}

function listeDistinc($champ, $table, $info)
{

	$_trad = siteSelectTrad();

	$result = selectDistinct($champ, $table);

	$balise = '';

	if ($result->num_rows > 0) {
		$balise = "<select class=\"\" id=\"$champ\" name=\"$champ\">";

		while ($data = $result->fetch_assoc()) {
			$value = $data[$champ];
			$libelle = (isset($_trad['value'][$value])) ? $_trad['value'][$value] : $value;
			$check = selectCheck($info, $value);
			$balise .= "<option value=\"$value\" $check >$libelle</option>";
		}
		// Balise par defaut
		$balise .= '</select>';
	}
	return $balise;
}

/**
 * function dernieresOffresTemplate()
 * @param $salle
 * @return string
 */
function dernieresOffresTemplate($salle)
{

	global $_trad;

	$offre  = "<div class=\"offre\">\r\n";
	$offre .= "\t<div>" . $salle['titre'] . "</div>\r\n";
	$offre .= "\t<figure>\r\n";
	$offre .= "\t\t<img class=\"ingOffre\" src=\"" . LINK . "photo/" . $salle['photo'] . "\" alt=\"\" />\r\n";
	$offre .= "\t\t<figcaption>Légende associée</figcaption>";
	$offre .= "\t</figure>";
	$offre .= "\t<div>" . $salle['capacite'] . " / " . $_trad['value'][$salle['categorie']] . "</div>\r\n";
	$offre .= "\t<hr/>\r\n";
	$offre .= "</div>\r\n";

	return $offre;
}

/**
 * function ficheContactTemplate()
 * @param $contact
 * @return string
 */
function ficheContactTemplate($contact)
{

	global $_trad;

	$offre  = "<div class=\"fiche\">\r\n";
	$offre .= "\t<div>" . $contact['prenom'] . " " . $contact['nom'] . "</div>\r\n";
	$offre .= "\t\t<div><a href=\"mailto:" . $contact['email'] . "\">" . $contact['email'] . "</a></div>\r\n";
	$offre .= "\t\t<div>" . $_trad['value'][$contact['statut']] . "</div>\r\n";
	$offre .= "\t<hr/>\r\n";
	$offre .= "</div>\r\n";

	return $offre;
}


/**
 * Fonction debug()
 * affiche les informations passes dans l'argument $var
 * @param $var => string, array, object
 * @param $mode => defaut = 1
 * RETURN NULL;
 */
function debug($mode=0)
{

	global $_debug;
	
	echo "<div class=\"col-md-12\">";

	if ($mode === 1)
	{
		echo '<pre>'; var_dump($_debug); echo '</pre>';
	} else {
		echo '<pre>'; print_r($_debug); echo '</pre>';
	}

	echo '</div>';

	return;
}


/**
 * Fonction _debug()
 * rengement dans un tableau les informations passes dans l'argument $var
 * @param $var => string, array, object
 * @param $mode => defaut = 1
 * RETURN NULL;
 */
function _debug($var, $label)
{
	
	global $_debug;
	
	$_debug[][$label] = $var;
	
	return;
}

/**
 * Fonction __link()
 * rengement dans un tableau les informations passes dans l'argument $var
 * @param $files => array
 * RETURN array;
 */
function __link($type){

	global $_linksFiles;

	$files = $_linksFiles[$type];
	$_link = '';

	if (!empty($files) AND is_array($files)){
		foreach($files as $link){
			if (file_exists(APP . str_replace('/', DIRECTORY_SEPARATOR, $link))){
				$_link[] = LINK . $link;
			}
		}
	}

	return $_link;
}

/**
 * Fonction __linkCss()
 * rengement dans un tableau les informations passes dans l'argument $var
 * @param $links => array
 * RETURN string;
 */
function __linkCss($links){

	$_link = '';

	if (!empty($links)){
		foreach($links as $link)
			$_link .= "<link href=\"$link\" rel=\"stylesheet\">";
	}

	return $_link;
}

/**
 * Fonction __linkJs()
 * rengement dans un tableau les informations passes dans l'argument $var
 * @param $links => array
 * RETURN string;
 */
function __linkJs($links){

	$_link = '';

	if (!empty($links)){
		foreach($links as $link)
			$_link .= "<script src=\"" . LINK . $link . "\" type=\"text/javascript\"></script>";
	}

	return $_link;
}