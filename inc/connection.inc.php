<?php
# FORMULAIRE D'INSCRIPTION
# FUNCTIONS formulaires
include_once FUNC . 'form.func.php';

/////////////////////////////////////
if(isset($_SESSION['connexion']) && $_SESSION['connexion'] < 0) {
	// affichage
	$msg = $_trad['erreur']['acces'];

} else {

// RECUPERATION du formulaire
$form = '
			<form action="#" method="POST">
			' . formulaireAfficher($_formulaire) . ' 
			</form>';
} ?>

    <principal>
		<h1><?php echo $titre; ?></h1>
		<hr />
		<div id="formulaire">
			<?php
			// affichage
			echo $msg;
			echo $form;
			?>
		</div>
		<div>
		<a href="?nav=inscription"><?php echo $_trad['pasEncoreMembreInscrivez-vous']; ?></a>
		</div>
		<hr />
		<span class="alert">BUG // Securiser le mot passe par cryptage!!!<br>
		Donner la possibillité de garder la connexion automatique...</span>
	</principal>
