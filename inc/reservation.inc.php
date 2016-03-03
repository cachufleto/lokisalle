<?php
function reservation()
{
	if(isset($_SESSION['panier'])){

		foreach ($_SESSION["panier"] as $key => $value) {
			echo '<br>Salle id ', $key; # code...
		}

	}
}

reservation();
