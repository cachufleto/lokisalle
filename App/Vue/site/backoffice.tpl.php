<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['backoffice']; ?></h1>
</div>
<div class="ligne">
    <form name="index" action="?nav=backoffice" method="POST">
        <div class=" col-2">
            <input type="submit" value="modifier" name="activite">
            <?php echo $activite; ?>
        </div>
        <div class=" col-2">
            <input type="submit" value="modifier" name="dernieresOffres">
            <?php echo $dernieresOffres; ?>
        </div>
    </form>
</div>