<?php $_trad = setTrad(); ?>
<principal clas="inscription">
    <h1><?php echo $_trad['titre']['inscription']; ?></h1>
    <hr />
    <div id="formulaire">
        <?php
        // affichage
        echo $msg, $form;
        ?>
    </div>
    <hr />
</principal>
