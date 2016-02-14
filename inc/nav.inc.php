<?php 

listeMenu();
$_link = $_SERVER["QUERY_STRING"];
?>
    <nav class="navbar">
	  <ul class="">
		<li><a href="?"><img src="img/Ubuntu.png" alt="Lokisalle" class="logo"></a></li>
		<?php echo liste_nav(); 
			echo (isset($_SESSION['user']) && $_SESSION['user']['status'] == 1)? 
				'<li><a class="admin">[ADMIN '.$_SESSION['user']['id'].']</a></li>' : 
				((isset($_SESSION['user']))? 
				'<li><a class="admin">"'.$_SESSION['user']['pseudo'].'"</a></li>' : 
				'');
			echo "<li><a href='?$_link&lang=fr'>FR</a> : <a href='?$_link&lang=es'>ES</a></li>";
		?>
		
	  </ul>
	</nav>
