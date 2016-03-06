<?php
# fonctions formulaires
//include FUNC . 'form.func.php';

# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function formulaireValider($_formulaire)
{
	global $minLen;

	$_trad = setTrad();

	// control d'intrusion du membre
	if($_formulaire['id_membre']['sql'] != $_formulaire['id_membre']['defaut']){
		//_debug($_formulaire, 'SQL');
		return '<div class="alert">'.$_trad['erreur']['NULL'].'!!!!!</div>';
	}
	$msg = 	$erreur = false;
	$sql_set = '';
	// active le controle pour les champs telephone et gsm
	$controlTelephone = true;

	$id_membre = $_formulaire['id_membre']['sql'];

	foreach ($_formulaire as $key => $info){
		
		$label = $_trad['champ'][$key];
		$valeur = (isset($info['valide']))? $info['valide'] : NULL;
		
		if('valide' != $key && 'id_membre' != $key){
			
			if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength']) && !empty($valeur))
			{

				$erreur = true; 
				$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. 
					': ' . $_trad['erreur']['doitContenirEntre'] . $minLen . 
					' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

			} 
			
			if ('vide' != testObligatoire($info) && !testObligatoire($info) && empty($valeur)){

				$erreur = true; 
				$_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];
			
			} else {

				switch($key){
					
					case 'pseudo': 
					case 'id_membre':
						// je ne fait riens
					break;

					case 'mdp':
						$valeur = (!empty($valeur))? hashCrypt($valeur) : '';
						break;

					case 'email': // il est obligatoire

						if (testFormatMail($valeur)) {
							
							$sql = "SELECT email FROM membres WHERE id_membre != ". $_formulaire['id_membre']['sql'] ." and email='$valeur'";
							$membre = executeRequete($sql); 
							
							// si la requete retourne un enregisterme, c'est que 'email' est deja utilisé en BD.
							if($membre->num_rows > 0) 
							{
								$erreur = true; 
								$msg .= '<br/>' . $_trad['erreur']['emailexistant'];
							}
							
						} else {
							
							$erreur = true; 
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur. 
								'", ' . $_trad['erreur']['aphanumeriqueSansSpace'];
						
						}

					break;
					
					case 'sexe':
					
						if(empty($valeur))
						{
							$erreur = true; 
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['vousDevezChoisireUneOption'];
						}

					break;
					
					case 'nom': // est obligatoire
					case 'prenom': // il est obligatoire
						if(!testLongeurChaine($valeur) )
						{
							$erreur = true; 
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['nonVide'];

						} elseif (!testAlphaNumerique($valeur)){
							
							$erreur = true; 
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur. 
								'", ' . $_trad['erreur']['aphanumeriqueSansSpace'] ;

						}
						
						
					break;

					case 'telephone':
					case 'gsm':

						if(!empty($valeur)){
							
							// un des deux doit être renseigné
							$controlTelephone = false;
							$valeur = str_replace(' ', '', $valeur);
							
							if (isset($info['length']) && (strlen($valeur) < $info['length'] || strlen($valeur)> $info['length']+4))
							{

								$erreur = true; 
								$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. 
									': ' . $_trad['erreur']['doitContenir'] . $info['length'] . $_trad['erreur']['caracteres'];
							}
							
							if(testNumerique($valeur))
							{
								$erreur = true;
								$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label . 
									': '.$_trad['erreur']['queDesChiffres'];
							}
					
						}
						
					break;
					
					case 'statut':

						if(testADMunique($valeur, $id_membre)){
								$erreur = true; 
								$msg .= '<br/>' . $_trad['numAdmInsufisant'];
								$_formulaire['statut']['valide'] = 'ADM';
						}

					break;
					
					default:
						if(!empty($valeur) && !testLongeurChaine($valeur)) 
						{
							$erreur = true; 
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];
								
						}
					
					}
			}
			// Construction de la requettes
			// le mot de passe doit être traité differement
		
			$sql_set .= ((!empty($sql_set) && !empty($valeur))? ", " : "") . ((!empty($valeur))? "$key = '$valeur'" : '');
				
		}
	}

	// control sur les numero de telephones
	// au moins un doit être sonseigné
	if($controlTelephone) {
		$erreur = true;
		$_formulaire['telephone']['message'] =  $_trad['erreur']['controlTelephone'] ;
	}

	// si une erreur c'est produite
	if($erreur) 
	{ 
		$msg = '<div class="alert">'.$_trad['ERRORSaisie']. $msg . '</div>';

	}else{
		
		// mise à jour de la base des données
		$sql = 'UPDATE membres SET '.$sql_set.'  WHERE id_membre = '.$_formulaire['id_membre']['sql'];
		echo $sql;

		if (!empty($sql_set)) 
			executeRequete ($sql);
		else echo 'ATTENTION';
		// ouverture d'une session
		$msg = "OK";
		
	}

	return $msg;
}