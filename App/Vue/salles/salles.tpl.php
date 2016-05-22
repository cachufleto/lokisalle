<?php $_trad = setTrad(); ?>
<div class="container">

    <div class="starter-template">
        <h1><?php echo $_trad['titre']['salles']; ?></h1>
        <hr />
    </div>
    <div class="">
        <?php echo $msg; ?>
        <table>
        <tr>
            <?php
            foreach($table['champs'] as $champ=>$info ){
                echo '<th>
                    <form action="?nav=salles" method="POST">
                        <input type="submit" name="ord" value="' . $champ . '">
                    </form>
                    </th>';
            }
            ?>
        </tr>
        <?php
        foreach($table['info'] as $ligne=>$salle){
                $class = ($ligne%2 == 1)? 'lng1':'lng2' ; ?>
            <tr class="<?php echo $class; ?>">
                <?php
                foreach($salle as $champ=>$info ){
                    echo "<td>$info</td>";
                }
            ?>
            </tr>
            <?php } ?>
        </table>

        <hr />
    </div>
</div><!-- /.container -->
