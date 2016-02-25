<?php
/**
 * Traitement de la variable de session panier
 */

$info = '';
if(isset($_SESSION['panier'])){

	foreach ($_SESSION["panier"] as $key => $value) {
		$info .= '<br>Salle id ' . $key;
	}

}

include(TEMPLATE . 'reservation.html.php');