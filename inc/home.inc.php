    <principal>
		<h1><?php echo $titre; ?></h1>
		<hr />
		<div>
			<h2>HOME</h2>
			<p>Zone de Texte qui présente notre activité (location de salles)</p>
			<p>Zone promotionelle, Trois derniéres offres (évidemment supérieur à la date du jour et "reservable")
			<br />"Voir la fiche détaillée" pointe sur reservation_details et correspond à la fiche produit.
			<br />Deux possiblitées sont à consider : le visiteur 
			<?php if(isset($_SESSION['user'])){ ?>
			est connecté:<br />Lien "ajouter au pannier" pointe sur la page panier.
			<?php } else { ?>
			n'est pas connecté:<br />Lien "connectez-vous pour l'ajout au pannier" pointe sur la page connexion, garder l'option du client
			<?php } ?>
			</p>
		</div>
		<hr />
	</principal>