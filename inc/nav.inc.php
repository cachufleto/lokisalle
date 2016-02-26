<?php 

// Fonctions navigation
require_once(FUNC."nav.func.php");

listeMenu();
$_link = $_SERVER["QUERY_STRING"];

$menu = liste_nav($_menu);
$class = $menu['class'];

$liNav = $menu['menu'];

if(isset($_SESSION['user'])){
	$liNav .= "<li class=\"$class\"><a class='admin'>[";
	$liNav .= ($_SESSION['user']['statut'] != 'MEM')? $_trad['value'][$_SESSION['user']['statut']]. "::" : "";
	$liNav .= $_SESSION['user']['user'].']</a></li>';
}

$liNav .= "<li class=\"$class\">";
$liNav .= ($_SESSION['lang'] == 'es')? "<a href=\"?$_link&lang=fr\">FR</a>" : "FR";
$liNav .= " : " . (($_SESSION['lang'] == 'fr')? "<a href='?$_link&lang=es'>ES</a>" : "ES") . "</li>";

// include(TEMPLATE . 'nav.html.php');