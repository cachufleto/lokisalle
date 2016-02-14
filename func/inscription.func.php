<?php
# FUNCTIONS Connection
include_once FUNC . 'form.func.php';

# Fonction valideForm()
# Verifications des informations en provenance du formulaire
# $_form => tableau des items
# RETURN string msg
function valideForm(){
	
	global $_trad, $_formulaire, $minLen;
	$message = '';
	$sql_champs = $sql_Value = '';
	
	foreach ($_formulaire as $key => $info){
		$label = $_trad[$key];
		$valeur = (isset($info['valide']))? $info['valide'] : NULL;
		$obligatoire = (!empty($info['obligatoire']))? true : false ;
		
		if('valide' != $key)
			if (isset($info['maxlength']) && 
				((strlen($valeur) < $minLen  || strlen($valeur) > $info['maxlength'])))
			{
				$message.= '<p> '.$_trad['erreur']['surLe'] . $label. 
					': ' . $_trad['erreur']['doitContenirEntre'] . $minLen . 
					' et ' . $info['maxlength'] . $_trad['erreur']['caracteres'].' </p>';
			}else{

				switch($key){
					case 'pseudo':
						$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );
						if (!$verif_caractere  && !empty($valeur))
						{
							$message.= '<p> '.$_trad['erreur']['surLe'] . $label. ' "' .$valeur. 
								'", ' . $_trad['erreur']['aphanumeriqueSansSpace'] . '</p>';
						} elseif(!empty($valeur)) {

							// lançons une requete nommee membre dans la BD pour voir si un pseudo est bien saisi.
							// $pseudo déjà définie
							$sql = "SELECT * FROM membre WHERE pseudo='$valeur'";
							$membre = executeRequete ($sql); 
							
							// si la requete tourne un enregisterme, c'est que 'pseudo' est deja utilisé en BD.
							if($membre->num_rows > 0) 
							{
								$message .= '<p>' . $_trad['erreur']['pseudoIndisponble']. ' ! </p>';
							}
						} else {
							$message.= '<p> '.$_trad['erreur']['surLe'] . $label. ' "'. $_trad['erreur']['nonVide'] . '" 
								, '.$_trad['erreur']['aphanumeriqueSansSpace'].'</p>';
						}

					break;
					
					case 'sexe':
						if(empty($valeur))
						{
							$message.= '<p> '.$_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['vousDevezChoisireUneOption'].' </p>';
						}
					break;
					
					case 'nom':
					case 'prenom':
						if( $obligatoire && strlen($valeur) == 0 )
						{
							$message.= '<p> '.$_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['nonVide'].' </p>';
						}
					break;

					case 'telephone':
						// il doit être obligatoire
						$valeur = str_replace(' ', '', $valeur);
						if(!preg_match('#[0-9.\s.-]#', $valeur))
						{
							$message.= '<p>'.$_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['queDesChiffres'].' </p>';
						}
					break;

					default:
						if($obligatoire && strlen($valeur) < $minLen ) 
						{
							$message.= '<p>'.$_trad['erreur']['surLe'] . $label . 
								': '.$_trad['erreur']['minimumAphaNumerique'].' ' . $minLen . ' '.$_trad['erreur']['caracteres'].'</p>';
								
						}
					break;
					
					}
					
				// Construction de la requettes
				$sql_champs .= ((!empty($sql_champs))? ", " : "") . $key;
				$sql_Value .= ((!empty($sql_Value))? ", " : "") . "'$valeur'" ;
			}
	}
	
	// si la variable $message est vide alors il n'y a pas d'erreurr !
	if(empty($message)) 
	{
		
		$sql = " INSERT INTO membre ($sql_champs, status) VALUES ($sql_Value, '0') ";
		executeRequete ($sql);
		// ouverture d'une session
		$message = "OK";
		
	}else{ 
		$message = '<div class="bg-danger message">'.$message.'</div>';
	}

	return $message;
}