<?php $_trad = setTrad(); ?>
<div class="fichesalles">
    <h1><?php echo $_trad['titre']['ficheSalles']; ?></h1>
    <div id="fiche" class="salle">
        <div class="ligne">
            <div class="ville"><?php echo $salle['ville']; ?> (<?php echo $salle['pays']; ?>)</div>
            <div><?php echo $msg; ?></div>
        </div>
        <div class="ligne">
            <div class="photo">
                <div><img src="<?php echo imageExiste($salle['photo']) ; ?>"></div>
            </div>
            <div class="info">
                <div class="titre"><?php echo strtoupper($salle['titre']); ?></div>
                <div class="fiche"><?php echo $salle['adresse']; ?><br>
                    <?php echo $salle['cp'], ' ', $salle['ville']; ?><br>
                    <?php echo $salle['telephone']; ?><br>
                    <?php echo $salle['gsm']; ?></div>
                <div><?php echo 'Cat. ', $_trad['value'][$salle['categorie']], ' :: ', $salle['capacite'] . $_trad['personnes']; ?>
                </div>
                <div class="lien"><?php echo $salle['lien']; ?></div>
            </div>
        </div>
        <div class="ligne description">
            <div><?php echo $salle['description']; ?></div>
            <div><?php echo $salle['capacite'] . $_trad['personnes'] , ' Cat. ' ,
                    $_trad['value'][$salle['categorie']]; ?>
            </div>
            <form action="#<?php echo "P-".($position -1); ?>" method="POST">
                <div><?php $salle['id_salle']; ?></div>
            </form>
        </div>
        <div>
    </div>
</div>
</div>


