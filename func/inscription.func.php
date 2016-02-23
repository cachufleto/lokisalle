<?php
# FUNCTIONS Formulares
include_once FUNC . 'form.func.php';

# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function formulaireValider(){
	
	global $_trad, $_formulaire, $minLen;

	$msg = 	$erreur = false;
	$sql_champs = $sql_Value = '';
	// active le controle pour les champs telephone et gsm
	$controlTelephone = true;

	foreach ($_formulaire as $key => $info){
		
		$label = $_trad['champ'][$key];
		$valeur = (isset($info['valide']))? $info['valide'] : NULL;
		
		if('valide' != $key)
			if (isset($info['maxlength']) && !testLongeurChaine($valeur, $info['maxlength'])  && !empty($valeur))
			{

				$erreur = true; 
				$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. 
					': ' . $_trad['erreur']['doitContenirEntre'] . $minLen . 
					' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'];

			} elseif (testObligatoire($info) && empty($valeur)){

				$erreur = true; 
				$_formulaire[$key]['message'] = $label . $_trad['erreur']['obligatoire'];
			
			} else {

				switch($key){
					case 'mdp': // il est obligatoire
						$valeur = hashCrypt($valeur);
					break;

					case 'pseudo': // il est obligatoire

						if (!testAlphaNumerique($valeur)) 
						{
							$erreur = true; 
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label. ' "' .$valeur. 
								'", ' . $_trad['erreur']['aphanumeriqueSansSpace'];
						
						} else {

							$sql = "SELECT pseudo FROM membres WHERE pseudo='$valeur'";
							$membre = executeRequete ($sql); 
							
							// si la requete tourne un enregistreme, c'est que 'pseudo' est déjà utilisé en BDD.
							if($membre->num_rows > 0) 
							{
								$erreur = true; 
								$msg .= '<br/>' . $_trad['erreur']['pseudoIndisponble'];
							}
						}
						
					break;
					
					case 'email': // il est obligatoire

						if (testFormatMail($valeur)) {
							
							$sql = "SELECT email FROM membres WHERE email='$valeur'";
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
					
					default:
						if(!empty($valeur) && !testLongeurChaine($valeur)) 
						{
							$erreur = true; 
							$_formulaire[$key]['message'] = $_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'];
								
						}
					
					}
				
				// Construction de la requettes
				if(!empty($valeur)){
					$sql_champs .= ((!empty($sql_champs))? ", " : "") . $key;
					$sql_Value .= ((!empty($sql_Value))? ", " : "") . (($key != 'cp')? "'$valeur'" : $valeur) ;
				}
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
		// insertion en BDD
		$sql = "INSERT INTO membres ($sql_champs) VALUES ($sql_Value) ";
		executeRequete ($sql);

		$email = $_formulaire['email']['valide'];
		$checkinscription = hashCrypt($email);

		$sql = "INSERT INTO checkinscription (id_membre, checkinscription)
			VALUES ( (SELECT id_membre FROM membres WHERE email = '$email'), '$checkinscription')";

		if(executeRequete ($sql)){
			$msg = (envoiMail($checkinscription, $email))? "OK" : $msg;
		}
		
	}

	return $msg;
}