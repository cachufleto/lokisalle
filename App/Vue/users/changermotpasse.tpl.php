<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['validerMDP']; ?></h1>
</div>
<div class="ligne">
    <div id="formulaire">
        <?php if($msg == 'OK'){
            echo $_trad['priseEnCompteMDP'];
        } else {
            echo $msg; ?>
        <form action="#" method="POST">
            <?php echo $form; ?>
        </form>
        <?php } ?>
    </div>
</div>