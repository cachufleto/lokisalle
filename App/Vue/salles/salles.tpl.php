<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['nav'][$nav]; ?></h1>
</div>
<div class="ligne">
    <p><?php echo $msg; ?></p>
    <div class="trier"><div>Trier par: </div>
            <div>
                <form action="?nav=<?php echo $nav; ?>" method="POST">
                    <input type="hidden" name="ord" value="id_salle">
                    <input type="submit" name="" value="REF">
                </form>
            </div><div>
                <form action="?nav=<?php echo $nav; ?>" method="POST">
                    <input type="hidden" name="ord" value="titre">
                    <input type="submit" name="" value="<?php echo $_trad['champ']['titre']; ?>">
                </form>
            </div><div>
                <form action="?nav=<?php echo $nav; ?>" method="POST">
                    <input type="hidden" name="ord" value="capacite">
                    <input type="submit" name="" value="<?php echo $_trad['champ']['capacite']; ?>">
                </form>
            </div><div>
                <form action="?nav=<?php echo $nav; ?>" method="POST">
                    <input type="hidden" name="ord" value="categorie">
                    <input type="submit" name="" value="<?php echo $_trad['champ']['categorie']; ?>">
                </form>
            </div>
        <div>&nbsp;&nbsp;</div>
    </div>
</div>
<div class="ligne">
<?php
    if(!empty($table['info'])){
        foreach($table['info'] as $ligne=>$salle){
                $class = ($ligne%2 == 1)? 'lng1':'lng2' ; ?>
            <div class="quart">
                <?php echo $salle['position']; ?>
                <h3><?php echo strtoupper($salle['nom']); ?></h3>
                <div class="quart_photo">
                    <?php echo $salle['photo']; ?>
                </div>
                <div class="ligne">

                        <h4 class="in_catalogue"><?php echo $salle['categorie']; ?></h4>
                        <p>Jusqu'à <?php echo $salle['capacite']; ?> personnes<br>
                            REF:<?php echo $salle['ref']; ?>
                        </p>
                </div>
                <div class="ligne">
                </div>
                <div class="reserver <?php echo ((isset($_SESSION['panier'][$salle['ref']])? "active" : "" )); ?>"><?php echo $salle['reservation']; ?></div>
            </div>

        <?php }
    } ?>
</div>