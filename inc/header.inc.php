<!DOCTYPE html>
<?php
$_link = '';
foreach($_linkCss as $link)
  $_link .= '<link href="'. $link .'" rel="stylesheet">';

?>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Projet DIWoo10 2015 - 2016, Lokisalle">
    <meta name="author" content="Carlos PAZ DUPRIEZ">
    <link rel="icon" href="<?php echo LINK;  ?>img/lokisalle.png">
	<title><?php echo $titre; ?></title>

    <?php echo $_link; ?>
    <script src="<?php echo LINK;  ?>js/script.js" type="text/javascript"></script>

  </head>

  <body>
  <div id="content">
