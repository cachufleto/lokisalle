<?php

// traitement du formulaire
$msg = postCheck('_formulaire');

// affichage des messages d'erreur
if('OK' == $msg){
	// on renvoi ver connection
	header('Location:index.php?nav=actif&qui='.$_formulaire['pseudo']['valide'].
		'&mp='.$_formulaire['mdp']['valide'].'');
	exit();
}else{
	// RECUPERATION du formulaire
	$form = '
			<form action="#" method="POST">
			' . formulaireAfficher($_formulaire) . ' 
			</form>';
?>
    <principal>
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