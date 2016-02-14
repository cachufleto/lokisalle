<?php

# Fonction inputValue()
# affiche les informations comptenue dans $_POST[$var]
# $var => string nom de l'indice
# $type => type de champ 'input', 'textarea'
# RETURN string
function inputValue($name, $type = 'input') {

	/**********textarea***************/
	if ($type == 'textarea' && isset($_POST[$name])) {
		return $_POST[$name];
	}
	/**************input******************/
	elseif(isset($_POST[$name])) {
		return 'value="' . $_POST[$name] . '" ';
	}
}

# Fonction de navMenu()
/*
function navMenu () {
	$page_en_cours = strrchr($_SERVER['REQUEST_URI'], '/');

	if($page_en_cours == ) {

	}
}
*/

# Fonction modCheck() 
# Control des informations Postées
# convertion avec htmlentities
# $nomFormulaire => string nom du tableau
# RETURN string alerte
function modCheck($nomFormulaire){
	
	global ${$nomFormulaire}, $mysqli;
	
	$formulaire = ${$nomFormulaire};
	$message = '';
	
	$sql = "SELECT * FROM membre WHERE id_membre=". $_SESSION['user']['id'];
	$data = $mysqli->query($sql) or die ($sql);
	$user = $data->fetch_assoc();
	
	foreach($formulaire as $key => $info){
		if($key != 'valide')
			${$nomFormulaire}[$key]['valide'] = $user[$key];
	}

	return true;
}


# Fonction afficheFormMod()
# Mise en forme des differents items du formulaire
#$_form => tableau des items
# RETURN string du formulaire
function afficheFormMod($_form){
	
	global $_trad;
	//global $_formIncription;
	$formulaire = '';
	foreach($_form as $champ => $info){
		$value = isset($info['valide'])? $info['valide'] : '';
		if(!isset($info['obligatoire'])){
			$formulaire .=  '
			<div class="col-md-12" >
				<label class="col-md-4" >' .  $_trad[$champ] . '</label>
				<div class="col-md-8">' . typeForm($champ, $info) . '</div>
			</div>';
			
			if(!empty($info['rectification']))
			{
			$formulaire .=  '
			<div class="col-md-12" >
				<label class="col-md-4" style="color:red">Rectifier</label>
				<div class="col-md-8">' . typeForm($champ.'2', $info) . '</div>
			</div>';
			}
		}else{
			$formulaire .=  '
			<div class="col-md-12" >
				<label class="col-md-4" >' . $_trad[$champ] ;
			$formulaire .= '</label>
				<div class="col-md-8">' . $value . '</div>
			</div>';
		}
		
	}
	
	return $formulaire; // texte
}

