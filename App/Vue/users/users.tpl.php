<div class="">
    <div class="">
        <h1><span class=" "></span><?php echo $_trad['titre']['users']; ?></h1>
        <hr />
    </div>
    <div class="">
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
            <?php foreach($table['info'] as $ligne=>$membre){
                $class = ($ligne%2 == 1)? 'lng1':'lng2' ; ?>
            <tr class="<?php echo $class; ?>">
            <?php
            foreach($membre as $champ=>$info ){
                echo "<td>$info</td>";
            }
            ?>
            </tr>
            <?php } ?>
        </table>
        <hr />
    </div>
</div><!-- /.container -->