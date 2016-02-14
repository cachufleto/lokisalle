<?php

# Fonction listeMenu()
# Valide le menu de navigation
# [@_pages] => array de navigation
# RETURN Boolean
function listeMenu(){
	
	global $_trad, $_pages, $_reglesAll, $_reglesMembre, $_reglesAdmin, $navAdmin, $navFooter;

	// control du menu principal
	$_liste = (utilisateurEstAdmin())? $_reglesAdmin : ((utilisateurEstConnecte())? $_reglesMembre : $_reglesAll);
	foreach($_liste as $key)
		if(!isset($_pages[$key])) 
			exit($_trad['laRubrique'] . $key . (utilisateurEstAdmin())? $_trad['pasDansMenuAdmin'] : ((utilisateurEstConnecte())? $_trad['pasDansMenuMembre'] : $_trad['pasDansMenu']));

	// ontrol du footer
	foreach($navFooter as $key)
		if(!isset($_pages[$key])) 
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuFooter']);

	// control du menu administrateur
	if(utilisateurEstAdmin())
		foreach($navAdmin as $key)
			if(!isset($_pages[$key])) 
				exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuAdmin']);
	return; 
}

# Fonction liste_nav()
# affiche les informations en forme de liste du menu de navigation
# $actif => mode de connexion
# [@nav] => string action
# [@_pages] => array('nav'...)
# [@titre] => string titre de la page
# RETURN string liste <li>...</li>
function liste_nav($liste=''){
	
	global $_trad, $nav, $_pages, $titre, $navFooter, $navAdmin, $_reglesAdmin, $_reglesMembre, $_reglesAll;
	
	$menu = '';

	if(empty($liste)){

		$_liste = (utilisateurEstAdmin())? $_reglesAdmin : ((utilisateurEstConnecte())? $_reglesMembre : $_reglesAll);

	} else {
		// generation de la liste de nav
		$_liste = ${$liste};
	}
	
	// affichage pour affichage ou edition! 

	$ADM = ($liste == 'navFooter' && preg_match('/BacOff/', $_SERVER['PHP_SELF']))? LINKADM : '';

	// generation de la liste de nav
	foreach ($_liste as $item){
		$info = $_pages[$item];
		$active = ($item == $nav)? 'active' : '';
		$class = (isset($_pages[$item]['class']))? $_pages[$item]['class'] : 'menu';		
		$menu .= '
		<li class="' . $active .' '. $class.'"><a href="'. $info['link'] . $ADM .'?nav='. $item .'">' . $_trad['nav'][$item] . '</a></li>';
	}

	return $menu;
}
