<?php $_trad = setTrad(); ?>
<div clas="validerInscription">
    <h1><?php echo $_trad['titre']['validerInscription']; ?></h1>
    <hr />
        <?php
        // affichage
        if ($_jeton) {
            echo $_trad['redirigeVerConnection'];
        } else {
            echo $_trad['erreur']['redirigeVerConnection'];
        }
        ?>
    <hr />
</div>
