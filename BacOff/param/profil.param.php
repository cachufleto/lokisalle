<?php

// on inclus les parametres du formulaire d'inscription
include 'PARAM . "inscription.param.php';


// recuparation de l'id par GET ou POST
$_id = (int)(isset($_POST['id_membre'])? $_POST['id_membre'] : (isset($_GET['id'])? $_GET['id'] : false) );

// traitement selon le profil
$_id = utilisateurEstAdmin()? (!empty($_id)? $_id : $_SESSION['user']['id']) : $_SESSION['user']['id'];

$_autre = $_formulaire['valide'];
unset($_formulaire['valide']);


// état de présentation
// edition pour modification
$_modifier = (isset($_POST['valide']) && $_POST['valide'] == $_trad['defaut']['modifier'])? true : false;
// validation du formaulare
$_valider = (isset($_POST['valide']) && $_POST['valide'] == $_trad['defaut']['MiseAJ'])? true : false;
	
// ajout du statut

$_formulaire['statut'] = array(
	'type' => 'null',
	'option' => array('MEM', 'COL', 'ADM'),
	'obligatoire' => true,
	'defaut' => '');

if(isSuperAdmin() && $_id != 1) // interdir une balise pour modification
	$_formulaire['statut']['type'] = 'select';



unset($_formulaire['mdp']['rectification']);
$_formulaire['mdp']['acces'] = 'private';
unset($_formulaire['email']['rectification']);
	
// id_membre champ cachée
$_formulaire['id_membre'] = array(
	'type' => 'hidden',
	'acces' => 'private',
	'defaut' => $_id);

// on recharge le boutton valide
$_formulaire['valide'] = $_autre;
// affichage du boutton de validation
$_formulaire['valide']['defaut'] = $_trad['defaut']['modifier'];
// ajout du boutton annuler
$_formulaire['valide']['annuler']  = true;

