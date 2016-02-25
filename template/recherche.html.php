<div class="container">

	<div class="starter-template">
		<h1><span class="glyphicon glyphicon-pencil "></span><?php echo $titre; ?></h1>
		<hr />
	</div>
	<div class="">
		<form action="" method="POST">
			<?php
			echo $villes;
			echo $categories;
			echo $capacite;
			?>
			<input type="submit" value="chercher">
		</form>

	</div>
	<div class="">
	<?php
		echo $resultat;
	?>
	</div>
</div>




