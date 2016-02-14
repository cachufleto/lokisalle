<?php

$sql = 'SELECT nom, prenom, email, telephone, sexe, ville, cp, adresse FROM membre WHERE id_membre='.$_SESSION['user']['id'];
$membre = executeRequete ($sql);
$profil = $membre->fetch_assoc();
$info = '';

foreach($profil as $champ => $valeur){
	
	$info .= "<br/>".$_trad[$champ]."	 : ";
	if($champ == 'sexe') $info .= ($valeur=='m')? 'Homme' : 'Femme';
	else $info .= $valeur;

	}

?>
    <principal>
		<h1><?php echo $titre; ?></h1>
		<hr />
		<div>
			<p>liste info profil
			<br/><?php echo $info; ?></p>
			<p>liste des commandes</p>
		</div>
		<hr />
	</principal>