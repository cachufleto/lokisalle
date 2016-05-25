<?php $_trad = setTrad(); ?>
<div class="home">
    <h1><?php echo $_trad['titre']['home']; ?></h1>
    <hr />
    <div id="homeG">
        <?php include 'activite.xhtml'; ?>
    </div>
    <div id="homeD">
        <h3><?php echo $_trad['dernieresOffres']; ?></h3>
        <?php echo $dernieresOffres; ?>
    </div>
</div>