<?php

// recuparation de l'id par GET ou POST
$_id = (int)(isset($_POST['id_membre'])? $_POST['id_membre'] : (isset($_GET['id'])? $_GET['id'] : $_id) );
$_id = utilisateurEstAdmin()? (!empty($_id)? $_id : $_SESSION['user']['id']) : $_SESSION['user']['id'];

$_formulaire['statut'] = array(
	'type' => 'null',
	'content' => 'text',
	'option' => array('MEM', 'COL', 'ADM'),
	'obligatoire' => true,
	'defaut' => '');

if(isSuperAdmin() && $_id != 1)
	$_formulaire['statut']['type'] = 'select';

// id_membre champ cachée
$_formulaire['id_membre'] = array(
	'type' => 'hidden',
	'content' => 'int',
	'acces' => 'private',
	'defaut' => $_id);

// on recharge le boutton valide
$_Form_valide = $_formulaire['valide'];
unset($_formulaire['valide']);
$_formulaire['valide'] = $_Form_valide;

