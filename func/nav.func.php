<?php

# Fonction listeMenu()
# Valide le menu de navigation
# [@_pages] => array de navigation
# RETURN Boolean
function listeMenu(){
	
	global $_pages, $_reglesAll, $_reglesMembre, $_reglesAdmin;
	
	if(utilisateurEstAdmin())
		foreach($_reglesAdmin as $key)
			if(isset($_pages[$key])) $_pages[$key]['affiche'] = true;
			else exit("La rubrique '$key' n'exsite pas dans le menu Admin!");
	elseif(utilisateurEstConnecte())
		foreach($_reglesMembre as $key)
			if(isset($_pages[$key])) $_pages[$key]['affiche'] = true;
			else exit("La rubrique '$key' n'exsite pas dans le menu des membres!");
	else
		foreach($_reglesAll as $key)
			if(isset($_pages[$key])) $_pages[$key]['affiche'] = true;
			else exit("La rubrique '$key' n'exsite pas dans le menu!");
	return; 
}

# Fonction liste_nav()
# affiche les informations en forme de liste du menu de navigation
# $actif => mode de connexion
# [@nav] => string action
# [@_pages] => array('nav'...)
# [@titre] => string titre de la page
# RETURN string liste <li>...</li>
function liste_nav(){
	
	global $_trad, $nav, $_pages, $titre;
	
	$menu = '';
	$titre = '';
	
	// generation de la liste de nav
	foreach ($_pages as $item => $info){
		if($info['affiche']) {
			$active = ($item == $nav)? 'active' : '';
			if(empty($titre) && ($item == $nav)) 
				$titre = $info['titre'];
			
			$menu .= '
			<li class="' . $active . '">
			<a href="?nav='. $item .'">' . 
			$_trad['nav'][$item] . '</a></li>';
		}
	}
	
	return $menu;
}
