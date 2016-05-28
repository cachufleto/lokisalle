<?php $_trad = setTrad(); ?>
<div class="ligne">
    <h1><?php echo $_trad['titre']['home']; ?></h1>
</div>
<div class="ligne">
    <div id="homeG">
        <?php include 'activite.xhtml'; ?>
    </div>
    <div id="homeD">
        <h3><?php echo $_trad['dernieresOffres']; ?></h3>
        <?php echo $dernieresOffres; ?>
    </div>
</div>