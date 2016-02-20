    <nav class="navbar footer">
	  <ul class="">
		<?php 
			$menu = liste_nav('navFooter');			
			echo $menu['menu'];
		?>
		<li>
		<?php 
			include(PARAM.'version.txt');
		?>
		</li>
	  </ul>
	</nav>
