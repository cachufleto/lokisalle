<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 11/06/2016
 * Time: 16:22
 */
$_trad['value']['matinee'] = "Matinee";
$_trad['value']['journee'] = "Journee";
$_trad['value']['soiree'] = "Soiree";
$_trad['value']['nocturne'] = "Nocturne";
?>
<style>
    .ligne{

    }
    #formulaire.produits{
        background-color: #55787D;
        color: #E7E5E5;
    }
</style>
<div class="ligne">
    <div id="formulaire" class="fichesalle produits">
    <form action="?nav=produits&id=<?php echo $_GET['id']; ?>&pos=<?php echo $_GET['pos']; ?>" method="POST">
        <?php echo $form; ?>
    </form>
    </div>
</div>
