<?php
connection();

function actifUser($_formulaire)
{
	$_trad = setTrad();

	include FUNC . 'form.func.php';
	// recuperation du pseudo
	if (empty($_POST) && isset($_COOKIE['Lokisalle']['pseudo'])) {
		$_POST['valide'] = 'cookie';
		$_POST['mdp'] = '';
		$_POST['pseudo'] = $_COOKIE['Lokisalle']['pseudo'];
		$_POST['rapel'] = 'on';
	}

	// traitement du formulaire
	$msg = $_trad['erreur']['inconueConnexion'];
	if (isset($_POST['valide']) && postCheck($_formulaire)) {
		$msg = ($_POST['valide'] == 'cookie') ? 'cookie' : connectionValider($_formulaire);
	}

	$form = '';
	// affichage des messages d'erreur
	if ('OK' == $msg) {
		// l'utilisateur est automatiquement connécté
		// et re-dirigé ver l'accueil
		$_nav = '';
		if (utilisateurEstAdmin()){
			$_nav = 'backoffice';
		}
		header('Location:index.php?nav='.$_nav);
		exit();
	}
}

function connection()
{
	$nav = 'connection';
	$msg = '';
	$_trad = setTrad();

	include PARAM . 'connection.param.php';

	actifUser($_formulaire);

	/////////////////////////////////////
	if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
		// affichage
		$msg = $_trad['erreur']['acces'];

	} else {

		// RECUPERATION du formulaire
		$form = formulaireAfficher($_formulaire);
	}

	include TEMPLATE . 'connection.php';
}


# Fonction valideForm()
# Verifications des informations en provenance du formulaire
# $_form => tableau des items
# RETURN string msg
function valideForm()
{

	global $_formulaire, $minLen;

	$_trad = setTrad();

	$message = '';
	$sql_Where = '';

	foreach ($_formulaire as $key => $info){

		$label = $_trad[$key];
		$valeur = (isset($info['valide']))? $info['valide'] : NULL;

		if('valide' != $key)
			if (isset($info['maxlength']) && (strlen($valeur) < $minLen  || strlen($valeur) > $info['maxlength'])) // $msg.= doit etre declarer vide avant
			{

				$message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
					': doit avoir un nombre de caracter compris entre ' . $minLen .
					' et ' . $info['maxlength'] . ' </p></div>';

			}else{
				switch($key){
					case 'mdp':
						$crypte = $key;
						break;

					case 'pseudo':
						$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );
						if (!$verif_caractere  && !empty($valeur)) // $verif_caractere si c vrai sa donne un TRUE
						{
							$message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
								', Caractere acceptés: A à Z et 0 à 9 </p></div>';
							// un message sans ecresser les messages existant avant. On place dans $msg des chaines de caracteres
						}

						break;

				}
				// Construction de la requettes
				if($key != 'mdp') $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
			}
	}

	if(empty($message)) // si la variable $msg est vide alors il n'y a pas d'erreurr !
	{
		// lançons une requete nommée membre dans la BD pour voir si un pseudo est bien saisi.
		$sql = "SELECT * FROM membre WHERE $sql_Where ";
		$membre = executeRequete ($sql); // la variable $pseudo existe grace a l'extract fait prealablemrent.

		// verifions si dans la requete lancee, si le pseudo existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne cree donc un pseudo existe
		if($membre->num_rows == 1) // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
		{
			$session = $membre->fetch_assoc();
			if(isset($crypte) && hashDeCrypt($_formulaire[$crypte])){
				$_formulaire[$crypte]['sql'] = $session[$crypte];
				if(hashDeCrypt($_formulaire[$crypte])){
					// overture d'une session Membre
					ouvrirSession($session);
					$message = 'OK';
				}
			}
		} else {  // le pseudo n'existe pas en BD

			$message .= '<div class="bg-danger message"> <p>Une erreur est survenue ! </p>';

		}

	}

	return $message;
}

function connectionValider($_formulaire)
{

	global $minLen;
	$_trad = setTrad();

	$msg = '';
	$erreur = false;
	$sql_Where = '';
	$control = true;
	$message ='';

	if(!isset($_SESSION['connexion'])) $_SESSION['connexion'] = 3;

	foreach ($_formulaire as $key => $info){

		$label = $_trad['champ'][$key];
		$valeur = (isset($info['valide']))? $info['valide'] : NULL;
		$obligatoire = (!empty($info['obligatoire']))? true : false ;

		if('valide' != $key)
			if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength']))
			{

				$erreur = true;
				$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] .$label.
					': ' . $_trad['erreur']['doitContenirEntre'] . $minLen .
					' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

			} elseif (testObligatoire($info) && empty($valeur)){

				$erreur = true;
				$_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];

			} else {

				switch($key){
					case 'mdp':
						$crypte = $key;
						break;

					case 'rapel':
						$control = ($valeur == 'ok')? true : false;
						break;

					case 'pseudo':

						if (!testAlphaNumerique($valeur))
						{
							$erreur = true;
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur.
								'", ' . $_trad['erreur']['aphanumeriqueSansSpace'];
						}

						break;

					default:
						if($obligatoire && !testLongeurChaine($valeur) )
						{
							$erreur = true;
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label .
								': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];
						}
						break;
				}
				// Construction de la requettes
				if($key != 'rapel' && $key != 'mdp') $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
			}
	}

	if($erreur) // si la variable $msg est vide alors il n'y a pas d'erreurr !
	{  // le pseudo n'existe pas en BD donc on peut lancer l'inscription

		$msg .= '<br />'.$_trad['erreur']['uneErreurEstSurvenue'];

	} else {

		// lançons une requete nommee membre dans la BD pour voir si un pseudo est bien saisi.
		$sql = "SELECT mdp, id_membre, pseudo, statut, nom, prenom FROM membres WHERE $sql_Where ";
		$membre = executeRequete ($sql); // la variable $pseudo existe grace a l'extract fait prealablemrent.
		// verifions si dans la requete lancee, si le pseudo s'il existe un nbre de ligne superieur à 0. si c >0 c kil ya une ligne creee donc un pseudo existe

		if($membre->num_rows === 1) // si la requete tourne un enregisterme,cest cest que le pseudo est deja utilisé en BD.
		{
			$session = $membre->fetch_assoc();
			if(isset($crypte)){
				$_formulaire[$crypte]['sql'] = $session[$crypte];
				if(hashDeCrypt($_formulaire[$crypte])){
					// overture d'une session Membre
					ouvrirSession($session, $control);
					$msg = 'OK';
					// on reinitialise les tentatives de connexion
					unset($_SESSION['connexion']);
				}
			}


		} elseif($membre->num_rows == 0) {
			$msg .= '<br/ >'. $_trad['erreur']['erreurConnexion'];
			$_SESSION['connexion'] -= 1;

		} else {

			$msg .= '<br />'. $_trad['erreur']['inconueConnexion'];

		}


	}
	return $msg;
}