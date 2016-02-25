<?php 

// Fonctions navigation
require_once(FUNC."nav.func.php");

listeMenu();
$_link = $_SERVER["QUERY_STRING"];

$menu = liste_nav($_menu);
$class = $menu['class'];
$li = $menu['menu'];

if(isset($_SESSION['user'])){
	$li .= "<li class='". $class ."'><a class='admin'>[";
	$li .= ($_SESSION['user']['statut'] != 'MEM')? $_trad['value'][$_SESSION['user']['statut']]. "::" : "";
	$li .= $_SESSION['user']['user'].']</a></li>';
}

$li .= "<li class='$class'>" . (($_SESSION['lang'] == 'es')? "<a href='?$_link&lang=fr'>FR</a>" : "FR");
$li .= " : " . (($_SESSION['lang'] == 'fr')? "<a href='?$_link&lang=es'>ES</a>" : "ES") . "</li>";


?>
    <nav class="navbar principal">
	  <ul class="">
		<li><a href="?"><img src="<?php echo LINK; ?>img/Ubuntu.png" alt="Lokisalle" class="logo"></a></li>
		<?php 
			echo $li;
		?>
		
	  </ul>
	</nav>