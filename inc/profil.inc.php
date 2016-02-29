<?php
if (!isset($_SESSION['user'])){
	header('Location:index.php');
	exit();	
}

include CONTROLEUR . 'Users.php';

$form = usersProfil($_id, $_valider, $_modifier);

include TEMPLATE . 'profil.html.php';