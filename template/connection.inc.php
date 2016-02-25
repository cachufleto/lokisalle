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
}

?>

    <principal clas="<?php echo $nav; ?>">
		<h1><?php echo $titre; ?></h1>
		<hr />
		<div id="formulaire">
			<?php
			// affichage
			echo $msg;
			echo $form;
			?>
			<div class="ligneForm">
				<label class="label"><?php echo $_trad['pasEncoreMembre']; ?></label>
				<div class="champs"><a href="?nav=inscription"><?php echo $_trad['inscrivezVous']; ?></a></div>
			</div>
			<div class="ligneForm">
				<label class="label"><?php echo $_trad['motPasseOublie']; ?></label>
				<div class="champs"><a href="?nav=changermotpasse"><?php echo $_trad['demandeDeMotPasse']; ?></a></div>
			</div>
		<hr />
		</div>
	</principal>
