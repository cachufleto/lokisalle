
<!DOCTYPE html>
<?php $_trad = setTrad(); ?>
<html lang="<?php echo $_SESSION['lang']; ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="Projet DIWoo10 2015 - 2016, Lokisalle">
	<meta name="author" content="Carlos PAZ DUPRIEZ">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo LINK;  ?>img/favicon.ico" type="image/x-icon">
	<?php echo $_link; ?>
	<link rel="stylesheet" href="css/style2.css" type="text/css">
	<script src="<?php echo LINK;  ?>js/script.js" type="text/javascript"></script>
	<title><?php echo (isset($_trad['titre'][$nav])? $_trad['titre'][$nav] : "LOKISALLE"); ?></title>
</head>

<body class="body">

<header class="mainHeader">
	<a class="logo" href="?"><img src="<?php echo LINK; ?>img/lokisalle.png" alt="Lokisalle" class="logo"></a>
	<nav>
		<ul>
			<?php echo $navPp; ?>
		</ul>
	</nav>
</header>

<div class="mainContent">
	<!-- CORP -->
	<div id="corp">
		<div id="<?php echo $nav; ?>" class="content">
			<?php
			echo $contentPage;
			?>
		</div>
		<div class="barre">&nbsp;</div>
	</div>
</div>
<div  id="debug">
	<?php echo $debug; ?>
</div>
<!-- Pied de Page -->
<footer class="mainFooter">
	<nav>
		<ul>
			<?php echo $footer['menu']; ?>
			<li>
				<a href=""><?php echo $footer['version']; ?></a>
			</li>
		</ul>
	</nav>
</footer>
</body>
</html>