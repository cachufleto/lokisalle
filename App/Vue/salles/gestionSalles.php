<div class="container">
    <div class="">
        <h1><?php echo $_trad['titre']['gestionSalles']; ?></h1>
        <hr />
        Ajouter une salle <a href="<?php echo LINKADMIN; ?>?nav=editerSalles">AJOUTER</a>
        <hr />
    </div>
    <div class="gestionSalles">
        <?php echo $msg; ?>
        <table>
            <?php echo $table; ?>
        </table>
        <hr />
    </div>
</div>
