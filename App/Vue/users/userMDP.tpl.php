<?php $_trad = setTrad(); ?>
<principal clas="validerInscription">
    <h1><?php echo $_trad['titre']['validerInscription']; ?></h1>
    <hr />
        <?php
        // affichage
        if ($_jeton) {
            echo '1 ' . $_trad['redirigeVerConnection'];
        } else {
            //echo '2  ' . $_trad['erreur']['redirigeVerConnection'];
            echo $msg, '
                    <form action="#" method="POST">
                    ' . $form . '
                    </form>';
        }
        ?>
    <hr />
</principal>
