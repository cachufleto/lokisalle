<div class="container">
    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-pencil "></span><?php echo $titre; ?></h1>
        <hr />
        Ajouter une salle <a href="<?php echo LINKADMIN; ?>?nav=editerSalles">AJOUTER</a>
        <hr />
    </div>
    <div class="<?php echo $nav; ?>">
        <?php echo $msg; ?>
        <table>
            <?php echo $table; ?>
        </table>
        <hr />
    </div>
</div>
