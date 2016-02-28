<?php
include_once (MODEL . 'Salles.php');

$salles = sallesHomeDeniersOffres();

$dernieresOffres = '';
while($salle = $salles->fetch_assoc()){
	$dernieresOffres .= dernieresOffres($salle);
}

include(TEMPLATE . 'home.html.php');