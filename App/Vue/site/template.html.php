<!DOCTYPE html>
<?php $_trad = setTrad(); ?>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Projet DIWoo10 2015 - 2016, Lokisalle">
    <meta name="author" content="Carlos PAZ DUPRIEZ">
    <link rel="icon" href="<?php echo LINK;  ?>img/lokisalle.png">
    <?php echo $_link; ?>
    <script src="<?php echo LINK;  ?>js/script.js" type="text/javascript"></script>
    <title><?php echo $_trad['titre'][$nav]; ?></title>
</head>
<body>
<div id="content">
    <!-- Menu Principal -->
    <nav class="navbar principal">
        <ul class="">
            <li><a href="?"><img src="<?php echo LINK; ?>img/Ubuntu.png" alt="Lokisalle" class="logo"></a></li>
            <?php echo $navPp; ?>
        </ul>
    </nav>
    <!-- CORP -->
    <div id="corp">
        <div id="<?php echo $nav; ?>">
        <?php
        echo $contentPage;
        echo $debug;
        ?>
        </div>
    </div>
    <!-- Pied de Page -->
    <nav class="navbar footer">
        <ul class="">
            <?php echo $footer['menu']; ?>
            <li>
            <?php echo $footer['version']; ?>
            </li>
        </ul>
    </nav>
    <!-- fin -->
</div>
</body>
</html>
