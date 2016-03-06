<?php
function home()
{

	$nav = 'home';
	$_trad = setTrad();


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
	$_trad = setTrad();

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

home();