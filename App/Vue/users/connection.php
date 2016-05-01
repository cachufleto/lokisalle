<principal clas="connection">
		<h1><?php echo $_trad['titre']['connection']; ?></h1>
<hr />
<div id="formulaire">
    <p><?php echo $msg; ?></p>
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
