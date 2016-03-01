<?php
/**
 * @param $_formulaire
 * @param bool $mod
 * @return string
 */
function postCheck(&$_formulaire, $mod=FALSE)
{
	if (isset($_POST['valide'])){
		return postValide($_formulaire, $mod);
	}
	return '';
}

/**
 * @param $_form
 * @return string
 */
function formulaireAfficher($_form)
{
	$_trad = siteSelectTrad();
	//global $_formIncription;
	$formulaire = '';
	foreach($_form as $champ => $info){
		$trad = ($champ == 'valide')? '' : $_trad['champ'][$champ];
		if ($info['type'] != 'hidden'){
			$formulaire .=  '
			<div class="ligneForm'. ((!empty($info['rectification']))? ' rectifier' : '') .'">
				<label class="label">' .  $trad;
			$formulaire .= (isset($info['obligatoire']))? '<span class="alert">*</span>': '';
			$formulaire .= '</label>';
			$formulaire .= '<div class="champs">' . typeForm($champ, $info) . '</div>';
			if (!empty($info['rectification'])){
				$formulaire .=  '
					<label class="label rectifier">'. $_trad['rectifier'] .' '.$_trad['champ'][$champ].' </label>
					<div class="champs">' . typeForm($champ.'2', $info) . '</div>';
			}
			$formulaire .= ((isset($info['message']))? '<div class="erreur">' .$info['message']. '</div>': '') . '</div>';
		} else {
			$formulaire .= typeForm($champ, $info);
		}
	}
	return $formulaire; // texte
}

/**
 * @param $champ
 * @param $info
 * @return string
 */
function typeForm($champ, $info)
{
	$_trad = siteSelectTrad();
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

/**
 * @param $statut
 * @param $id_membre
 * @return bool
 */
function testADMunique($statut, $id_membre)
{
	if (utilisateurEstAdmin() && $id_membre == $_SESSION['user']['id'] && $statut != 'ADM'){
		// interdiction de modifier le statut pour le super administrateur
		if ($id_membre == 1){
            return true;
        }
		return usersSelectUniqueADM();
	}
	return false;
}

/**
 * @param $_formulaire
 * @param bool $mod
 * @return string
 */
function postValide(&$_formulaire, $mod=FALSE)
{
	$ok = true;
	$msg = '';
    $_trad = siteSelectTrad();
	// on boucle sur les valeurs des champs
	$_form = $_formulaire;
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
					$_formulaire[$key]['message'] = $_trad['erreur']['veuillezDeRectifier'] . $_trad['champ'][$key];
					$msg .= $_trad['champ'][$key] . $_trad['erreur']['obligatoire'];
					$valide = '';
				} else if (empty($valeur1) XOR empty($valeur2)){
					// l'un des deux champs est remplie
					$ok = false;
					$_formulaire[$key]['message'] = $_trad['erreur']['veuillezDeRectifier'] . $_trad['champ'][$key];
					$msg .= $_trad['erreur']['vousAvezOublieDeRectifier'] . $_trad[$key];
					$valide = '';
				} else if ($valeur1 != $valeur2){
					// les deux valeurs sont differents
					$ok = false;
					$_formulaire[$key]['message'] = $_trad['erreur']['corrigerErreurDans'] . $_trad['champ'][$key];
					$msg .= $_trad['erreur']['vousAvezUneErreurDans'] . $_trad['champ'][$key];
					$valide = '';
				}
			}
			$_formulaire[$key]['valide'] = ($valide == $info['defaut'])? '' : $valide;
		} else if ($info['type'] == 'file'){
			if (isset($_FILES[$key])){
				$_formulaire[$key]['valide'] = $_FILES[$key]['name'];
            }
		} else if ($info['type'] == 'checkbox'){
			$ok = testObligatoire($info)? false : $ok;
		} else if (!$mod && $key != 'valide'){
			// si le champs n'est pas présent dans POST
			$ok = false;
			$_formulaire[$key]['valide'] = '';
			$_formulaire[$key]['message'] = $_trad['erreur']['ATTENTIONfaitQuoiAvec']. $_trad['champ'][$key] . '?';
			$msg .= $_trad['erreur']['corrigerErreurDans'];
		}
	}
	return $msg; //$ok;
}

/**
 * @param $info
 * @param $value
 * @return bool
 */
function radioCheck($info, $value)
{
	// info['valide'] => valeur du formulaire
	return (!empty($info['valide']) && $info['valide'] == $value)? true : false;
}

/**
 * @param $info
 * @param $value
 * @return string
 */
function selectCheck($info, $value)
{
	// info['valide'] => valeur du formulaire
	return (!empty($info['valide']) && $info['valide'] == $value)? 'selected="selected"' : '';
}

/**
 * @param $valeur
 * @return int
 */
function testNumerique($valeur)
{
	return preg_match('#[a-zA-Z.\s.-]#', $valeur);
}

/**
 * @param $valeur
 * @return int
 */
function testAlphaNumerique($valeur)
{
	return preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );
}

/**
 * @param $valeur
 * @return mixed
 */
function testFormatMail($valeur)
{
	return filter_var($valeur, FILTER_VALIDATE_EMAIL);
}

/**
 * @param $info
 * @return bool
 */
function testObligatoire($info)
{
	return isset($info['obligatoire'])? $info['obligatoire'] : false;
}

/**
 * @param $valeur
 * @param int $maxLen
 * @return bool
 */
function testLongeurChaine($valeur, $maxLen=250)
{
	global $minLen;
	$taille = strlen($valeur);
	_debug("$taille < $minLen  || $taille > $maxLen", 'TEST', __FUNCTION__);
	return ($taille < $minLen  || $taille > $maxLen)? false : true;
}


/**
 * @param $_form
 * @return string
 */
function formulaireAfficherInfo($_form)
{
	$_trad = siteSelectTrad();
	//global $_formIncription;
	$formulaire = '';
	foreach($_form as $champ => $info){
		$value = isset($info['valide'])? $info['valide'] : '';
		if ($info['type'] != 'hidden'){
			if ($champ == 'valide'){
				$formulaire .=  '
				<div class="ligneForm" >
					<label class="label" >&nbsp;</label>
					<div class="champs">' . typeForm($champ, $info) . '</div>
				</div>';
			} else {
				$formulaire .=  '
				<div class="ligneForm" >
					<label class="label" >' . $_trad['champ'][$champ] ;
				if ($info['type'] == 'file') {
					$formulaire .= '</label>
						<div class="champs"><img src="' . LINK. 'photo/' .$value . '">	</div>
					</div>';
				} else {
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

/**
 * @param $_form
 * @return string
 */
function formulaireAfficherMod($_form)
{
	$_trad = siteSelectTrad();
	//global $_formIncription;
	$formulaire = '';
	foreach($_form as $champ => $info){
		$value = isset($info['valide'])? $info['valide'] : '';
		if ($champ == 'sexe'){
			if (isset($_trad['value'][$value])){
				$value = $_trad['value'][$value];
			} else {
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
				if (!empty($info['rectification'])){
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

/**
 * @param $key
 * @param $info
 * @return bool
 */
function controlImageUpload($key, &$info)
{
	$_trad = siteSelectTrad();
	// Tableaux de donnees
	$tabExt = array('jpg','gif','png','jpeg');    // Extensions autorisees
	$infosImg = array();
	$info['valide'] = '';
	if (!empty($_FILES[$key]['name'])){
		// Recuperation de l'extension du fichier
		$extension  = pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);
		// On verifie l'extension du fichier
		if (in_array(strtolower($extension),$tabExt)){
			// On recupere les dimensions du fichier
			$infosImg = getimagesize($_FILES[$key]['tmp_name']);
			// On verifie le type de l'image
			if ($infosImg[2] >= 1 && $infosImg[2] <= 14){
				// On verifie les dimensions et taille de l'image;
				// echo $infosImg[0]." <= " . WIDTH_MAX. ") && (" . $infosImg[1]." <= ".HEIGHT_MAX.") && ("; 
				// echo filesize($_FILES[$key]['tmp_name'])." <= ".MAX_SIZE."))";
				if (($infosImg[0] <= WIDTH_MAX) && ($infosImg[1] <= HEIGHT_MAX) && (filesize($_FILES[$key]['tmp_name']) <= MAX_SIZE)){
					// Parcours du tableau d'erreurs
					if (isset($_FILES[$key]['error']) && UPLOAD_ERR_OK === $_FILES[$key]['error']){
						// On renomme le fichier
						$nomImage = md5(uniqid()) .'.'. $extension;
						// Si c'est OK, on teste l'upload
						if (move_uploaded_file($_FILES[$key]['tmp_name'], TARGET.$nomImage)){
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