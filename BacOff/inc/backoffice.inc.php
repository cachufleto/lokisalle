
<?php

$activite = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' : 'Activité';

$dernieresOffres = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' :  'Derniéres Offres'
?>
    <principal class="<?php echo $nav; ?>">
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
<?php
// Après le rechargemet de la page, nous les affichons
if (isset($_COOKIE['cookie'])) {
    foreach ($_COOKIE['cookie'] as $name => $value) {
        $name = htmlspecialchars($name);
        $value = htmlspecialchars($value);        
        echo "$name : $value <br />\n";
    }
}
?>