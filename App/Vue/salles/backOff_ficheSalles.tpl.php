<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['ficheSalles']; ?></h1>
    <hr />
    <div id="formulaire" class="fichesalles">
        <?php echo $msg; ?>
        <form action="#<?php echo "P-".($position -1); ?>" method="POST">
        <?php echo $form; ?>
        </form>
    </div>
    <hr />
</div>
