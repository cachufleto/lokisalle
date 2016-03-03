<?php
function home($titre, $nav)
{
	$sql = "SELECT * FROM salles";
	$salles = executeRequete($sql);

	$dernieresOffres = '<div>';
	while($salle = $salles->fetch_assoc()){
		$dernieresOffres .= dernieresOffres($salle);
	}
	$dernieresOffres .= '</div>';

	include TEMPLATE . 'home.php';
}

function dernieresOffres($salle)
{
	global $_trad;

	$offre = '
	<div class="offre">
	<div>' . $salle['titre'] . '</div>
  	<figure>
	  <img class="ingOffre" src="' . LINK . 'photo/' . $salle['photo'] . '" alt="" />
  		<figcaption>Légende associée</figcaption>
	</figure>
  	<div>' . $salle['capacite'] . ' / ' . $_trad['value'][$salle['categorie']] .'</div>
  	<hr/>
	</div>
	';

	return $offre;
}

home($titre, $nav);