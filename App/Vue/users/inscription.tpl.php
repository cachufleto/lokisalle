<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['inscription']; ?></h1>
</div>
<div class="ligne">
    <div id="formulaire">
        <?php
        if('OK' == $msg){ ?>
            <a href="?index.php"> <?php echo $_trad['validerInscription'] ?> </a>;
        <?php } else{
            echo $msg; ?>
            <form action="#" method="POST">
        <?php echo $form; ?>
            </form>
        <?php } ?>
    </div>
</div>