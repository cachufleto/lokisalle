<?php $_trad = setTrad(); ?>
<div class="container">
    <div class="">
        <h1><?php echo $_trad['titre']['gestionSalles']; ?></h1>
        <hr />
        <?php echo $_trad['ajouterSalle']; ?>
        <hr />
    </div>
    <div class="gestionSalles">
        <?php echo $msg; ?>
        <table>
            <tr>
                <?php
                foreach($table['champs'] as $champ=>$info ){
                    $cols = ($champ == 'active')? 'colspan="2"': '';
                    echo "<th $cols>$info</th>";
                }
                ?>
            </tr>
            <?php
            foreach($table['info'] as $ligne=>$salle){
                $class = ($ligne%2 == 1)? 'lng1':'lng2';
                echo '
                <tr class="' . $class . '">
                ';
                    foreach($salle as $info ){
                        echo "<td>$info</td>";
                    }
                echo '
                </tr>';
             } ?>
        </table>
        <hr />
    </div>
</div>
