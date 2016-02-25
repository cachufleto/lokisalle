<?php

# Fonction listeMenu()
# Valide le menu de navigation
# [@_pages] => array de navigation
# RETURN Boolean
function listeMenu(){

	if(!utilisateurEstAdmin()) return;

	global $_trad, $_pages, $_reglesAll, $_reglesMembre, $_reglesAdmin, $navAdmin, $navFooter;

	// control du menu principal

	foreach($_reglesAdmin as $key)
		if(!isset($_pages[$key]))
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuAdmin']);

	foreach($_reglesMembre as $key)
		if(!isset($_pages[$key]))
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuMembre']);

	foreach($_reglesAll as $key)
		if(!isset($_pages[$key]))
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenu']);

	// control du footer
	foreach($navFooter as $key)
		if(!isset($_pages[$key])) 
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuFooter']);

	// control du menu administrateur
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
	
	if(empty($liste)){

		$_liste = (utilisateurEstAdmin())?
			$_reglesAdmin :
			((utilisateurEstConnecte())?
				$_reglesMembre :
				$_reglesAll);

	} else {
		// generation de la liste de nav
		$_liste = ${$liste};
	}
	
	// Pour affichage ou edition!
	$ADM = ($liste == 'navFooter' && preg_match('/BacOff/', $_SERVER['PHP_SELF']))? true : false;

	// generation de la liste de nav
	$col = count($_liste)+1;
	$menu = '';
	foreach ($_liste as $item){
		$info = $_pages[$item];
		$active = ($item == $nav)? 'active' : '';
		$class = (isset($_pages[$item]['class']))? $_pages[$item]['class'] : 'menu';
		$menu .= '
		<li class="' . $active .' '. $class.' col-'.$col.'">
			<a href="'. (($ADM)? LINKADMIN : $info['link'] ) .'?nav='. $item .'">' . $_trad['nav'][$item] . '</a>
		</li>';
	}

	return array('menu'=>$menu, 'class'=>$class . ' col-'.$col);
}
