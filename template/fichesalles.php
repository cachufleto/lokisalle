<principal clas="<?php echo $nav; ?>">
<h1><?php echo $titre; ?></h1>
<hr />
<div id="formulaire">
    <?php
    // affichage
    echo $msg, $form;
    ?>
</div>
<hr />
</principal>
<pre>
<?php print_r($_formulaire);	 ?>
</pre>