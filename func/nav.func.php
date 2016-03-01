<?php

/**
 * Control des menus
 */
function listeMenu()
{
	if (!utilisateurEstAdmin()) return;
	$_trad = siteSelectTrad();
	$_pages = siteSelectPages();
	// control du menu principal
	$_reglesAdmin = siteNavReglesAdmin();
	foreach($_reglesAdmin as $key){
		if (!isset($_pages[$key])){
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuAdmin']);
		}
	}
	$_reglesMembre = siteNavReglesMembre();
	foreach($_reglesMembre as $key){
		if (!isset($_pages[$key])){
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuMembre']);
		}
	}
	$_reglesAll = siteNavReglesAdmin();
	foreach($_reglesAll as $key){
		if (!isset($_pages[$key])){
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenu']);
		}
	}
	// control du footer
	$_navFooter = siteNavFooter();
	foreach($_navFooter as $key){
		if (!isset($_pages[$key])){
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuFooter']);
		}
	}
	// control du menu administrateur
	$_navAdmin = siteNavAdmin();
	foreach($_navAdmin as $key){
		if (!isset($_pages[$key])){
			exit($_trad['laRubrique'] . $key . $_trad['pasDansMenuAdmin']);
		}
	}
}

/**
 * @param string $liste
 * @return array
 */
function liste_nav($liste='')
{
	global $__nav;
	$_pages = siteSelectPages();
	$_trad = siteSelectTrad();
	if (empty($liste)){
		$_liste = (utilisateurEstAdmin())?
			siteNavReglesAdmin() :
			((utilisateurEstConnecte())?
				siteNavReglesMembre() :
				siteNavReglesAll());
	} else {
		// generation de la liste de nav
		$_liste = $liste();
	}
	// Pour affichage ou edition!
	$ADM = ($liste == 'navFooter' && preg_match('/BacOff/', $_SERVER['PHP_SELF']))? true : false;
	// generation de la liste de nav
	$col = count($_liste)+1;
	$menu = '';
	foreach ($_liste as $item){
		$info = $_pages[$item];
		$active = ($item == $__nav)? 'active' : '';
		$class = (isset($_pages[$item]['class']))? $_pages[$item]['class'] : 'menu';
		$menu .= '
		<li class="' . $active .' '. $class.' col-'.$col.'">
			<a href="'. (($ADM)? LINKADMIN : $info['link'] ) .'?nav='. $item .'">' . $_trad['nav'][$item] . '</a>
		</li>';
	}
	return array('menu'=>$menu, 'class'=>$class . ' col-'.$col);
}
