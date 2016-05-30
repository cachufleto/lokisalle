<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['ficheSalles']; ?></h1>
</div>
<div class="ligne">
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
                <div class="categorie">
                    <?php echo 'Cat. ', $_trad['value'][$salle['categorie']], ' :: ', $salle['capacite'] . $_trad['personnes']; ?>
                </div>

                <div class="reserver <?php echo ((isset($_SESSION['panier'][$salle['id_salle']])? "active" : "" )); ?>">
                    <?php
                    echo (isset($_SESSION['panier'][$salle['id_salle']]) && $_SESSION['panier'][$salle['id_salle']] === true) ?
                        '<a href="' . LINK . '?nav=ficheSalles&id=' . $salle['id_salle'] . '&enlever=' . $salle['id_salle'] . '&pos=' . $position . '" >' . $_trad['enlever'] . '</a>' :
                        ' <a href="' . LINK . '?nav=ficheSalles&id=' . $salle['id_salle'] . '&reserver=' . $salle['id_salle'] . '&pos=' . $position . '">' . $_trad['reserver'] . '</a>';
                    ?>
                </div>
                <div class="reserver lien">
                    <!--
                    $salle['lien'] = '<a href="?nav=salles#P-' . $position . '">' . $_trad['revenir'] . '</a>';
                    $salle['reserver'] = '<a href="?nav=ficheSalles&id=' . $_id .
                        '&reserver=' . $salle['id_salle'] . '&pos=' . $position . '">Reserver</a>';
                    -->
                    <a href="?nav=salles#P-<?php echo $position; ?>"><?php echo $_trad['revenir']; ?></a>

                </div>
            </div>
        </div>
        <div class="ligne description">
            <div>
                <?php echo $salle['description']; ?>
            </div>
        </div>
    </div>
</div>