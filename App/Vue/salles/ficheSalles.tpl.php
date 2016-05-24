<?php $_trad = setTrad(); ?>
<principal clas="fichesalles">
    <h1><?php echo $_trad['titre']['ficheSalles']; ?></h1>
    <hr />
    <div id="formulaire">
        <?php   echo $msg; ?>
        <form action="#<?php echo "P-".($position -1); ?>" method="POST">
        <?php   echo $form; ?>
        </form>
    </div>
    <hr />
</principal>
