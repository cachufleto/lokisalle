<?php
include_once(FUNC.'form.func.php');
// afficher un formulaire de recherche
// menu déroulant pour la ville
// date-piqquer pour la date d'entreé
// date-pikquer pour la date de sortie
// date de sortie doit étre égale ou supperieur à la date d'entrée
// formulaire inscrit dans la liste de sales disponibles

if($_POST) var_dump($_POST);

function listeDistinc($champ, $table, $info){

	global $_trad;

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

?>
<form action="" method="POST">
<?php
echo listeDistinc('ville', 'salles', array('valide'=>'tokyo'));
echo listeDistinc('categorie', 'salles', array('valide'=>'R'));
echo listeDistinc('capacite', 'salles', array('valide'=>'56'));
?>
<input type="submit" value="chercher">
</form>
