<principal class="<?php echo $__nav; ?>">
    <h1><?php echo $titre; ?></h1>
    <hr />
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
    <hr />
</principal>
