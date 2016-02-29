<?php
if (!isset($_SESSION['user'])){
	header('Location:index.php');
	exit();	
}

extract(sallesFicheModifier());
include TEMPLATE . 'formulaire.html.php';
