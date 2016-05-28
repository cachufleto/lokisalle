<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['contact']; ?></h1>
</div>
<div class="ligne">
    <?php
    foreach($listConctact as $fiche){
        echo $fiche;
    }
    ?>
</div>