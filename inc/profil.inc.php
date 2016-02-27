<?php
if(!isset($_SESSION['user'])){
	header('Location:index.php');
	exit();	
}

$form = usersProfil($_id, $_valider, $_modifier);

include(TEMPLATE . 'profil.html.php');