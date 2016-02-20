<?php 

// Fonctions navigation
require_once(FUNC."nav.func.php");

listeMenu();
$_link = $_SERVER["QUERY_STRING"];

?>
    <nav class="navbar principal">
	  <ul class="">
		<li><a href="?"><img src="<?php echo LINK; ?>img/Ubuntu.png" alt="Lokisalle" class="logo"></a></li>
		<?php 

			$menu = liste_nav($_menu);
			$class = $menu['class']; 
			$li = $menu['menu'];
			$li .= (isset($_SESSION['user']) && $_SESSION['user']['statut'] != 'MEM')? 
				'<li class="'. $class .'"><a class="admin">['.$_trad['value'][$_SESSION['user']['statut']]. '::' .$_SESSION['user']['pseudo'].']</a></li>' : 
				((isset($_SESSION['user']))? '<li class="'. $class .'"><a class="admin">"'.$_SESSION['user']['user'].'"</a></li>' : '');

			$li .= "<li class='$class'>" . (($_SESSION['lang'] == 'es')? "<a href='?$_link&lang=fr'>FR</a>" : "FR");
			$li .= " : " . (($_SESSION['lang'] == 'fr')? "<a href='?$_link&lang=es'>ES</a>" : "ES") . "</li>";
			echo $li;
		?>
		
	  </ul>
	</nav>
