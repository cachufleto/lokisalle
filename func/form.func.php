<?php
# FUNCTIONS FORMULAIRES

# Fonction postCheck() 
# Control des informations Postées
# convertion avec htmlentities
# $nomFormulaire => string nom du tableau
# RETURN string alerte
function postCheck($nomFormulaire, $mod=FALSE){

	global ${$nomFormulaire};

	$fomulaire = ${$nomFormulaire};
	$msg = '';
	
	if (isset($_POST['valide'])){
		// appel à la fonction spécifique à chaque formulaire
		// la fonction doit ce trouver dans le fichier de traitement
		return postValide($nomFormulaire, $mod);
		/* else ifpostValide($nomFormulaire, $mod)) {

			// control particulier pour chaque formulaire
			// on ne fait pas la suite si les information proviennent des cookies
			$msg = ($_POST['valide'] == 'cookie')? 'cookie' : formulaireValider();
		}
		else $msg = $_trad['erreur']['inconueConnexion']; */
	}

	return $msg;
}

# Fonction formulaireAfficher()
# Mise en forme des differents items du formulaire
#$_form => tableau des items
# RETURN string du formulaire
function formulaireAfficher($_form){
	

	global $_trad;
	//global $_formIncription;
	$formulaire = '';
	foreach($_form as $champ => $info){
		$trad = ($champ == 'valide')? '' : $_trad['champ'][$champ];
		if ($info['type'] != 'hidden') {
			$formulaire .=  '
			<div class="ligneForm'. ((!empty($info['rectification']))? ' rectifier' : '') .'">
				<label class="label">' .  $trad;
			$formulaire .= (isset($info['obligatoire']))? '<span class="alert">*</span>': '';
			$formulaire .= '</label>';
			$formulaire .= '<div class="champs">' . typeForm($champ, $info) . '</div>';
			
			if (!empty($info['rectification']))
			{
			$formulaire .=  '
				<label class="label rectifier">'. $_trad['rectifier'] .' '.$_trad['champ'][$champ].' </label>
				<div class="champs">' . typeForm($champ.'2', $info) . '</div>';
			}
			$formulaire .= ((isset($info['message']))? '<div class="erreur">' .$info['message']. '</div>': '') . '</div>';
		
		} else $formulaire .= typeForm($champ, $info);
		
	}
	
	return $formulaire; // texte
}

# Fonction typeForm() de mise en forme des differents balises html
# $champ => nom de l'item
# $info => tableau des informations relatives a l'item
# RETURN [balises] texte
function typeForm($champ, $info){
	

	global $_trad;

	$valeur = (!empty($info['valide']))? $info['valide'] : $info['defaut'];
	$check = (!empty($info['valide']))? 'checked' : '' ;
	$class = (!empty($info['class']))? $info['class'] : '';
	
	// valeur par defaut si il n'existe pas une information utilisateur
	// indication sur le champs
	$condition = (!empty($info['valide']))? 'value' : 'placeholder';

	switch($info['type']){

		case 'password':
			return '<input type="password" class="' . $class . '"   id="' . $champ . '" name="' . $champ . '" placeholder="' .  $info['defaut']. '" maxlength ="' . $info['maxlength'] . '">';
		break;
		
		case 'email':
			return '<input type="email" class="' . $class . '"   id="' . $champ . '" name="' . $champ . '" ' . $condition . '="' .  $valeur. '" >';

		break;
		
		case 'radio':
			$balise = '';
			foreach($info['option'] as $value){
				$check = radioCheck($info, $value)? 'checked' : ''; 
				$balise .= $_trad['value'][$value].' <input type="radio" class="radio-inline" id="' . $champ . '" name="' . $champ . '" value="' .  $value. '" ' . $check . ' >';
			}
			// Balise par defaut
			$balise .= '<input type="radio" class="radio-inline" id="' . $champ . '" name="' . $champ . '" value="" ' . (empty($info['valide'])? 'checked' : '') . ' style="visibility:hidden;" >';
			
			return $balise;
		break;

		case 'select':

			$balise = '<select class=" " id="' . $champ . '" name="' . $champ . '">';
			foreach($info['option'] as $value){
				$check = selectCheck($info, $value); 
				$balise .= '<option value="' .  $value . '" ' . $check . ' >'.$_trad['value'][$value].'</option>';
			}
			// Balise par defaut
			$balise .= '</select>';
			
			return $balise;
		break;
		
		case 'checkbox':
			$balise = '';
			foreach($info['option'] as $value){
				$check = radioCheck($info, $value)? 'checked="checked" ': '';
				$balise .=  $_trad['value'][$value] . '<input type="checkbox" class="radio-inline" id="' . $champ . '" name="' . $champ . '" '.  $check .'>';
			}
			return $balise;
		break;
		
		case 'textarea':
			$balise = '
					<textarea id="' . $champ . '"  name="' . $champ . '" class="' . $class . '"   placeholder="' . $info['defaut'] . '">' . (isset($info['valide'])?$info['valide']:'') . '</textarea>';
			return $balise;
		break;
		
		case 'hidden':
			$value = isset($info['acces'])? $info['defaut'] : $valeur;
			return '<input type="hidden" class="' . $class . '"   name="' . $champ . '" value="' .  $value. '">';
		break;
		
		case 'text':
			$maxlength = (isset($info['maxlength']) AND !empty($info['maxlength']))? ' maxlength ="' . $info['maxlength'] . '"' : '';
			return '<input type="text" class="' . $class . '"   name="' . $champ . '" ' . $condition . '="' .  $valeur. '" ' . $maxlength . '>';
		break;
		
		case 'file':
			// $maxlength = (isset($info['maxlength']) AND !empty($info['maxlength']))? ' maxlength ="' . $info['maxlength'] . '"' : '';
			return '<input type="file" class="' . $class . '"   name="' . $champ . '" >';
		break;
		
		case 'submit':
			$boutton = '<input type="submit" class="' . $class . '"   name="' . $champ . '" value="' .  $valeur. '">';
			if (isset($info['annuler']))
				$boutton .= '<input type="submit" class="' . $class . '"   name="' . $champ . '" value="' . $_trad['Out'] . '">';
			return $boutton;
		break;
		
		default:
			return ($champ == 'statut')? $_trad['value'][$valeur] : $valeur;
			
	}
}

# Fonction testADMunique()
# Control des informations Postées
# $info tableau des items validées du formulaire
# RETURN boolean

function testADMunique($statut, $id_membre){


	if (utilisateurEstAdmin() && $id_membre == $_SESSION['user']['id'] && $statut != 'ADM')
	{
		
		// interdiction de modifier le statut pour le super administrateur
		if ($id_membre == 1){
            return true;
        }

		return usersSelectUniqueADM();
	}
	return false;
}

# Fonction postValide()
# Control des informations Postées
# convertion avec htmlentities
# $nom_form => string nom du tableau des items
# [@$nom_form] tableau des items validées du formulaire
# $mod => condition pour une action de mise à jour en BDD
# RETURN string message d'alerte
function postValide($nom_form, $mod=FALSE){

	
	global $msg, ${$nom_form};
	$ok = true;

    $_trad = siteSelectTrad();

	// on boucle sur les valeurs des champs
	$_form = ${$nom_form};
	foreach($_form as $key => $info){
		
		// on le verifie pas les actions en modification pour ce qui sont obligatoires
		if (isset($_POST[$key]) && $key != 'valide'){

			// on encode en htmlentities
			$valide = htmlentities($_POST[$key], ENT_QUOTES);
			// si le champ fait objet d'une rectification
			if (!empty($info['rectification'])){

				$valeur1 = $_POST[$key];
				$valeur2 = $_POST[$key.'2'];
				
				// actions pour la modification
				if (testObligatoire($info) && empty($valeur1)){

					$ok = false;
					${$nom_form}[$key]['message'] = $_trad['erreur']['veuillezDeRectifier'] . $_trad['champ'][$key];
					$msg .= $_trad['champ'][$key] . $_trad['erreur']['obligatoire'];
					$valide = '';

				} else if (empty($valeur1) XOR empty($valeur2)){

					// l'un des deux champs est remplie
					$ok = false;
					${$nom_form}[$key]['message'] = $_trad['erreur']['veuillezDeRectifier'] . $_trad['champ'][$key];
					$msg .= $_trad['erreur']['vousAvezOublieDeRectifier'] . $_trad[$key];
					$valide = '';
				
				} else if ($valeur1 != $valeur2){

					// les deux valeurs sont differents
					$ok = false;
					${$nom_form}[$key]['message'] = $_trad['erreur']['corrigerErreurDans'] . $_trad['champ'][$key];
					$msg .= $_trad['erreur']['vousAvezUneErreurDans'] . $_trad['champ'][$key];
					$valide = '';
				
				}
					
			}
			
			${$nom_form}[$key]['valide'] = ($valide == $info['defaut'])? '' : $valide;
		
		} else if ($info['type'] == 'file'){
			if (isset($_FILES[$key])){
                ${$nom_form}[$key]['valide'] = $_FILES[$key]['name'];
            }
		} else if ($info['type'] == 'checkbox'){
			
			$ok = testObligatoire($info)? false : $ok;

		} else if (!$mod && $key != 'valide'){

			// si le champs n'est pas présent dans POST
			$ok = false;
			${$nom_form}[$key]['valide'] = '';
			${$nom_form}[$key]['message'] = $_trad['erreur']['ATTENTIONfaitQuoiAvec']. $_trad['champ'][$key] . '?';
			$msg .= $_trad['erreur']['corrigerErreurDans'];
		
		}
	}
	
	return $ok;
}

# Fonction radioCheck()
# Vérifie la valeur du check 
# $info => array(...'valide'), valeurs du champs
# $value => valeur à comparer
# RETURN string
function radioCheck($info, $value) {


	// info['valide'] => valeur du formulaire
	return (!empty($info['valide']) && $info['valide'] == $value)? true : false;

}

# Fonction selectCheck()
# Vérifie la valeur du check 
# $info => array(...'valide'), valeurs du champs
# $value => valeur à comparer
# RETURN string
function selectCheck($info, $value) {


	// info['valide'] => valeur du formulaire
	return (!empty($info['valide']) && $info['valide'] == $value)? 'selected="selected"' : '';

}

# Fonction testNumerique()
# Vérifie la valeur alphanumerique d'une chaine de caracteres 
# $value => valeur à tester
# RETURN Boolean
function testNumerique($valeur){	


	return preg_match('#[a-zA-Z.\s.-]#', $valeur);

}

# Fonction testAlphaNumerique()
# Vérifie la valeur alphanumerique d'une chaine de caracteres 
# $value => valeur à tester
# RETURN Boolean
function testAlphaNumerique($valeur){	

		
	return preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );

}

# Fonction testFormatMail()
# Vérifie la valeur alphanumerique d'une chaine de caracteres 
# $value => valeur à tester
# RETURN Boolean
function testFormatMail($valeur){	

		
	return filter_var($valeur, FILTER_VALIDATE_EMAIL);

}

# Fonction testObligatoire()
# Vérifie la valeur alphanumerique d'une chaine de caracteres 
# $value => valeur à tester
# RETURN Boolean
function testObligatoire($info){	

	
	return isset($info['obligatoire'])? $info['obligatoire'] : false;			

}

# Fonction testLongeurChaine()
# Vérifie la longeure d'une chaine de caracteres 
# $value => valeur à tester
# $maxLen => limite maximal 250 par default
# @minLen => limite minimal établi par default
# RETURN Boolean true si authorizé
function testLongeurChaine($valeur, $maxLen=250){

	
	global $minLen;
	
	$taille = strlen($valeur);
	
	return ($taille < $minLen  || $taille > $maxLen)? false : true;

}

# Fonction modCheck() 
# Control des informations Postées
# convertion avec htmlentities
# $nomFormulaire => string nom du tableau
# RETURN string alerte
function modCheck($nomFormulaire, $_id, $table){

	
	global ${$nomFormulaire};
	
	$formulaire = ${$nomFormulaire};
	$message = '';
	
	$sql_membres = "SELECT * FROM membres WHERE id_membre = ". $_id . ( !isSuperAdmin()? " AND active != 0" : "" );
	$sql_salles = "SELECT * FROM salles WHERE id_salle = ". $_id . ( !isSuperAdmin()? " AND active != 0" : "" );

	$data = executeRequete(${'sql_'.$table}) or die (${'sql_'.$table});
	$user = $data->fetch_assoc();
	
	if ($data->num_rows < 1) return false;

	foreach($formulaire as $key => $info){
		if ($key != 'valide' && key_exists ( $key , $user )){
			${$nomFormulaire}[$key]['valide'] = $user[$key];
			${$nomFormulaire}[$key]['sql'] = $user[$key];
		}
	}

	return true;
}


# Fonction formulaireAfficherInfo()
# Mise en forme des differents items du formulaire
#$_form => tableau des items
# RETURN string du formulaire
function formulaireAfficherInfo($_form){

	global $_trad;
	//global $_formIncription;
	$formulaire = '';
	foreach($_form as $champ => $info){
		
		$value = isset($info['valide'])? $info['valide'] : '';
		
		if ($info['type'] != 'hidden')
		{
			if ($champ == 'valide'){
				$formulaire .=  '
				<div class="ligneForm" >
					<label class="label" >&nbsp;</label>
					<div class="champs">' . typeForm($champ, $info) . '</div>
				</div>';
				
			} else{
				$formulaire .=  '
				<div class="ligneForm" >
					<label class="label" >' . $_trad['champ'][$champ] ;
				if ($info['type'] == 'file') {
					$formulaire .= '</label>
						<div class="champs"><img src="' . LINK. 'photo/' .$value . '">	</div>
					</div>';
				}
				else {
					$formulaire .= '</label>
						<div class="champs">' . (isset($info['option'])? 
							$_trad['value'][$value] : 
							$value ) . '</div>
					</div>';
				}
			}
		} else if (isset($info['acces'])) {
			$formulaire .= typeForm($champ, $info);
		}
	}
	
	return $formulaire; // texte
}

# Fonction formulaireAfficherMod()
# Mise en forme des differents items du formulaire
#$_form => tableau des items
# RETURN string du formulaire
function formulaireAfficherMod($_form){

	
	global $_trad;
	//global $_formIncription;
	$formulaire = '';

	foreach($_form as $champ => $info){
		
		$value = isset($info['valide'])? $info['valide'] : '';
		if ($champ == 'sexe') {
			if (isset($_trad['value'][$value]))
				$value = $_trad['value'][$value];
			else{
				$info['type'] = 'select';
			}
		}
		
		if ($info['type'] != 'hidden'){

			if (!isset($info['obligatoire']) || utilisateurEstAdmin()){
				$label = key_exists($champ, $_trad['champ'])? $_trad['champ'][$champ] : $champ;
				$formulaire .=  '
				<div class="ligneForm" >
					<label class="label" >' . (($champ != 'valide')? $label : '&nbsp;') . '</label>
					<div class="champs">' . typeForm($champ, $info);

				$formulaire .= '</div>
				</div>';
				
				if (!empty($info['rectification']))
				{
				$formulaire .=  '
				<div class="ligneForm" >
					<label class="label rectifier" style="color:red">Rectifier</label>
					<div class="champs">' . typeForm($champ.'2', $info) . '</div>
				</div>';
				}
				
				$formulaire .= ((isset($info['message']))? '<div class="erreur">' .$info['message']. '</div>': '');
			
			} else if ($champ != 'statut'){
				$formulaire .=  '
				<div class="ligneForm" >
					<label class="label" >' . $_trad['champ'][$champ] ;
				$formulaire .= '</label>
					<div class="champs">' . $value . '</div>
				</div>';
			}
		
		} else $formulaire .= typeForm($champ, $info);		
	}
	
	return $formulaire; // texte
}

# Fonction controlImageUpload()
# Upload des images dans le repertoire photo
#$key => champ
#$info => donées relatives au champ
# RETURN boolean
function controlImageUpload($key, &$info) {


	global $_trad;
	// Tableaux de donnees
	$tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
	$infosImg = array();
	$info['valide'] = '';

	if (!empty($_FILES[$key]['name']) )
	{
		// Recuperation de l'extension du fichier
		$extension  = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);

		// On verifie l'extension du fichier
		if (in_array(strtolower($extension),$tabExt))
		{
			// On recupere les dimensions du fichier
			$infosImg = getimagesize($_FILES[$key]['tmp_name']);

			// On verifie le type de l'image
			if ($infosImg[2] >= 1 && $infosImg[2] <= 14)
            {
				// On verifie les dimensions et taille de l'image;
				// echo $infosImg[0]." <= " . WIDTH_MAX. ") && (" . $infosImg[1]." <= ".HEIGHT_MAX.") && ("; 
				// echo filesize($_FILES[$key]['tmp_name'])." <= ".MAX_SIZE."))";
				
				if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES[$key]['tmp_name']) <= MAX_SIZE))
                {
					// Parcours du tableau d'erreurs
					if (isset($_FILES[$key]['error']) && UPLOAD_ERR_OK === $_FILES[$key]['error'])
					{
						// On renomme le fichier
						$nomImage = md5(uniqid()) .'.'. $extension;

						// Si c'est OK, on teste l'upload
						if (move_uploaded_file($_FILES[$key]['tmp_name'], TARGET.$nomImage))
						{

							$info['valide'] = $nomImage;
							return false;

						} else {
							// Sinon on affiche une erreur systeme

							$info['message'] = $_trad['erreur']['problemeLorsUpload'];
						}
					} else {
						$info['message'] = $_trad['erreur']['erreurInterneEmpecheUplaodImage'];
					}
				} else {
					// Sinon erreur sur les dimensions et taille de l'image
					$info['message'] = $_trad['erreur']['erreurDansDimensionsImage'];
				}
			} else {
				// Sinon erreur sur le type de l'image
				$info['message'] = $_trad['erreur']['fichierUploaderNestPasUneImage'];
			}
		} else {
			// Sinon on affiche une erreur pour l'extension
			$info['message'] = $_trad['erreur']['extensionFichierEstIncorrecte'];
		}
	} else {
		// Sinon on affiche une erreur pour le champ vide
		$info['message'] = $_trad['erreur']['veuillezRemplirFormulaire'];
	}

	//echo $info['message'];
	return true;

}

