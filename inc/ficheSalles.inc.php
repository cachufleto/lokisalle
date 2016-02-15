<?php

// on cherche la fiche dans la BDD
// extraction des données SQL
if( modCheck('_formulaire', $_id, 'salles') ){

	// traitement POST du formulaire

	$form = formulaireAfficherInfo($_formulaire); 

	$form .=  '<a href="?nav=salles#P-'.$position.'">'.$_trad['revenir'].'</a>';

} else {

	header('Location:index.php');
	exit();
}	// RECUPERATION du formulaire
?>
    <principal clas="<?php echo $nav; ?>">
		<h1><?php echo $titre; ?></h1>
		<hr />
		<div id="formulaire">
			<?php  
			// affichage
			echo $msg, $form; 
			?>
		</div>
		<hr />
		</principal>
		<pre>
<?php print_r($_formulaire);	 ?>
	</pre>