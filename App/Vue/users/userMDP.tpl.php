<?php $_trad = setTrad(); ?>
<div clas="validerInscription">
    <h1><?php echo $_trad['titre']['validerMDP']; ?></h1>
    <hr />
    <div id="formulaire">
        <?php
        // affichage
        if ($_jeton) {
            echo $_trad['redirigeVerConnection'];
        } else {
            echo $msg, '
                    <form action="#" method="POST">
                    ' . $form . '
                    </form>';
        }
        ?>
    </div>
    <hr />
</div>
