<?php
//include FUNC . 'form.func.php';
// afficher un formulaire de recherche
// menu déroulant pour la ville
// date-piqquer pour la date d'entreé
// date-pikquer pour la date de sortie
// date de sortie doit étre égale ou supperieur à la date d'entrée
// formulaire inscrit dans la liste de sales disponibles

function listeDistinc($champ, $table, $info)
{

	$_trad = setTrad();

	$sql = "SELECT DISTINCT $champ FROM $table ORDER BY $champ ASC";
	$result = executeRequete($sql);

	$balise = '<select class=" " id="' . $champ . '" name="' . $champ . '">';

	while($data = $result->fetch_assoc()){
		$value = $data[$champ];
		$libelle = (isset($_trad['value'][$value]))? $_trad['value'][$value] : $value;
		$check = selectCheck($info, $value); 
		$balise .= '<option value="' .  $value . '" ' . $check . ' >'.$libelle.'</option>';
	}
	// Balise par defaut
	$balise .= '</select>';
	
	return $balise;
}

function recherche()
{
	include FUNC . 'form.func.php';
	$echoville = listeDistinc('ville', 'salles', array('valide'=>'tokyo'));
	$echocategorie = listeDistinc('categorie', 'salles', array('valide'=>'R'));
	$echocapacite = listeDistinc('capacite', 'salles', array('valide'=>'56'));

	include TEMPLATE . 'recherche.php';
}

recherche();
?>
