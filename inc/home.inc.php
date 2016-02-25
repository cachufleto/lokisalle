<?php

$sql = "SELECT * FROM salles";
$salles = executeRequete($sql);

$dernieresOffres = '';
while($salle = $salles->fetch_assoc()){
	$dernieresOffres .= dernieresOffres($salle);
}

include(TEMPLATE . 'home.html.php');