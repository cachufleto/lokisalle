   <principal clas="<?php echo $__nav; ?>">
		<h1><?php echo $titre; ?></h1>
		<hr />
		<div id="formulaire">
			<?php echo $msg; ?>
			<form action="#" method="POST">
			<?php echo $form; ?>
			</form>
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
