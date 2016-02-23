<?php

// traitement du formulaire
$msg = postCheck('_formulaire');

// affichage des messages d'erreur
if('OK' == $msg){
	// on renvoi ver connection
	$form = '<a href="?index.php">SUITE</a>';

}else{
	// RECUPERATION du formulaire
	$form = '
			<form action="#" method="POST">
			' . formulaireAfficher($_formulaire) . ' 
			</form>';
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
<?php } ?>