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
	$message = '';
	
	if(isset($_POST['valide'])){
		$message = postValide($nomFormulaire, $mod);
		// appel à la fonction spécifique à chaque formulaire
		// la fonction doit ce trouver dans le fichier de traitement
		if(empty($message)) $message = valideForm();
	}
	
	return $message;
}

# Fonction afficheForm()
# Mise en forme des differents items du formulaire
#$_form => tableau des items
# RETURN string du formulaire
function afficheForm($_form){
	
	global $_trad;
	//global $_formIncription;
	$formulaire = '';
	foreach($_form as $champ => $info){
		$trad = ($champ == 'valide')? '' : $_trad[$champ];
		$formulaire .=  '
		<div>
			<label>' .  $trad;
		$formulaire .= (isset($info['obligatoire']))? "*": '';
		$formulaire .= '</label>
			<div>' . typeForm($champ, $info) . '</div>
		</div>';
		
		if(!empty($info['rectification']))
		{
		$formulaire .=  '
		<div>
			<label style="color:red">'. $_trad['rectifier'] .'</label>
			<div>' . typeForm($champ.'2', $info) . '</div>
		</div>';
		}
		
	}
	
	return $formulaire; // texte
}

# Fonction typeForm() de mise en forme des differents balises html
# $champ => nom de l'item
# $info => tableau des informations relatives a l'item
# RETURN [balises] texte
function typeForm($champ, $info){
	
	$condition = (!empty($info['valide']))? 'value' : 'placeholder';
	$value = (!empty($info['valide']))? $info['valide'] : $info['defaut'];
	$check = (!empty($info['valide']))? 'checked' : '' ;
	$class = (!empty($info['class']))? ' ' . $info['class'] : '';
	
	switch($info['type']){

		case 'password':
			return '<input type="password" class="' . $class . '"   id="' . $champ . '" name="' . $champ . '" placeholder="' .  $info['defaut']. '" maxlength ="' . $info['maxlength'] . '">';
		break;
		
		case 'email':
			return '<input type="email" class="' . $class . '"   id="' . $champ . '" name="' . $champ . '" ' . $condition . '="' .  $value. '" >';

		break;
		
		case 'radio':
			$balise = '';
			foreach($info['option'] as $key => $value){
				$check = radioCheck($info, $value); 
				$balise .= $key.' <input type="radio" class="radio-inline" id="' . $champ . '" name="' . $champ . '" value="' .  $value. '" ' . $check . ' >';
			}
			// Balise par defaut
			$balise .= '<input type="radio" class="radio-inline" id="' . $champ . '" name="' . $champ . '" value="" ' . (empty($info['valide'])? 'checked' : '') . ' style="visibility:hidden;" >';
			
			return $balise;
		break;
		case 'select':
			$balise = '';
			foreach($info['option'] as $key){
				$check = radioCheck($info, $key); 
				$balise .= '<input type="radio" class="radio-inline" id="' . $champ . '" name="' . $champ . '" value="' .  $key. '" ' . $check . ' >';
			}
			// Balise par defaut
			$balise .= '<input type="radio" class="radio-inline" id="' . $champ . '" name="' . $champ . '" value="" ' . (empty($info['valide'])? 'checked' : '') . ' style="visibility:hidden;" >';
			
			return $balise;
		break;
		
		case 'checkbox':
			$balise = '';
			foreach($info['option'] as $key){
				$balise .= '<input type="radio" class="radio-inline" id="' . $champ . '" name="' . $champ . '" value="' .  $key .  $check .
				'">';
			}
			return $balise;
		break;
		
		case 'textarea':
			$balise = '
					<textarea id="' . $champ . '"  name="' . $champ . '" class="form-control' . $class . '"   placeholder="' . $info['defaut'] . '">' . (isset($info['valide'])?$info['valide']:'') . '</textarea>';
			return $balise;
		break;
		
		case 'submit':
			return '<input type="submit" class="form-control' . $class . '"   name="' . $champ . '" value="' .  $value. '">';
		break;
		
		default:
			$maxlength = (isset($info['maxlength']) AND !empty($info['maxlength']))? ' maxlength ="' . $info['maxlength'] . '"' : '';
			return '<input type="text" class="form-control' . $class . '"   name="' . $champ . '" ' . $condition . '="' .  $value. '" ' . $maxlength . '>';
		
	}
}

# Fonction postValide()
# Control des informations Postées
# convertion avec htmlentities
# $nom_form => string nom du tableau des items
# [@$nom_form] tableau des items validées du formulaire
# RETURN string message d'alerte
function postValide($nom_form, $mod=FALSE){
	
	global $_trad, ${$nom_form};
	$message = '';

	$_form = ${$nom_form};
	foreach($_form as $key => $info){
		// on le verirfie pas les actions en modification pour ce qui sont obligatoires
		if(isset($_POST[$key])){
			$valide = htmlentities($_POST[$key], ENT_QUOTES);
			if(!empty($info['rectification'])){

				$champ2 = isset($_POST[$key.'2'])? $_POST[$key.'2'] : '';
				// actions pour la modification
				if($mod && empty($_POST[$key]) && empty($champ2)){
					$valide = '';
				}elseif(empty($_POST[$key]) XOR empty($champ2)){
					$message .= '<br/>'.$_trad['erreur']['vousAvezOublieDeRectifier'] . $_trad[$key] . '!!';
					$valide = '';
				}elseif($_POST[$key] != $champ2){
					$message .= '<br/>'. $_trad['erreur']['vousAvezUneErreurDans'] . $_trad[$key] . '!!';
					$valide = '';
				}
					
			}
			
			${$nom_form}[$key]['valide'] = ($valide == $info['defaut'])? '' : $valide;
		
		}elseif(!$mod){
			${$nom_form}[$key]['valide'] = '';
			$message .= '<br/>' . $_trad['erreur']['ATTENTIONfaitQuoiAvec']. $_trad[$key] . '?';
		}
			
		
	}
	
	return $message;
}

# Fonction radioCheck()
# Vérifie la valeur du check 
# $info => array(...'valide'), valeurs du champs
# $value => valeur à comparer
# RETURN string
function radioCheck($info, $value) {

	// info['valide'] => valeur du formulaire
	return (!empty($info['valide']) && $info['valide'] == $value)? 'checked' : '';

}

