<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['salles']; ?></h1>
</div>
<div class="ligne">
    <p><?php echo $msg; ?></p>
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
</div>