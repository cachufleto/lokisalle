<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Projet DIWoo10 2015 - 2016, Lokisalle">
    <meta name="author" content="Carlos PAZ DUPRIEZ">
    <link rel="icon" href="<?php echo LINK;  ?>img/lokisalle.png">
    <?php echo $_linkNavCss; ?>
    <?php echo $_linkNavJs; ?>
    <title><?php echo $titre; ?></title>
</head>
<body>
<div id="content">
    <nav class="navbar principal">
        <ul class="">
            <li><a href="?"><img src="<?php echo LINK; ?>img/Ubuntu.png" alt="Lokisalle" class="logo"></a></li>
            <?php
            echo $liNav;
            ?>

        </ul>
    </nav>
    <div id="<?php echo $__nav?>">
    <?php
    // ***************************************************** //
    echo $page;
    // ***************************************************** //
    ?>
    </div>
    <?php echo $debug; ?>
    <nav class="navbar footer">
        <ul class="">
            <?php echo $liFooter; ?>
            <li>
                <?php echo $version ?>
            </li>
        </ul>
    </nav>
</div>
<?php echo $_linkNavJsFooter; ?>
</body>
</html>