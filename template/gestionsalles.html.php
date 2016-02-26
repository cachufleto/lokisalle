<div class="container">
    <div class="">
        <h1><?php echo $titre; ?></h1>
        <hr />
        Ajouter une salle <a href="<?php echo LINKADMIN; ?>?nav=editerSalles">AJOUTER</a>
        <hr />
    </div>
    <div class="">
        <?php  echo $msg; ?>
        <table>
            <tr>
                <th><?php echo $_trad['champ']['id_salle'] ?></th>
                <th><?php echo $_trad['champ']['titre'] ?></th>
                <th><?php echo $_trad['champ']['capacite'] ?></th>
                <th><?php echo $_trad['champ']['categorie'] ?></th>
                <th><?php echo $_trad['champ']['photo'] ?></th>
                <th><?php echo $_trad['select'] ?></th>
            </tr>
            <?php echo $table; ?>
        </table>
    </div>
</div><!-- /.container -->
