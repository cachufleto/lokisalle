<?php

// on inclus les parametres du formulaire d'inscription
include_once(PARAM . "inscription.param.php");


// recuparation de l'id par GET ou POST
$_id = (int)(isset($_POST['id_membre'])? $_POST['id_membre'] : (isset($_GET['id'])? $_GET['id'] : false) );

// traitement selon le profil
$_id = $_SESSION['user']['id'];

// affichage du boutton de validation
$_formulaire['valide']['defaut'] = $_trad['defaut']['modifier'];
// ajout du boutton annuler
$_formulaire['valide']['annuler']  = true;
$_formulaire['valide']['origin']  = (isset($_POST['valide']) && $_POST['valide'] == $_trad['defaut']['modifier'])? $_trad['defaut']['MiseAJ'] : '';

// état de présentation
// edition pour modification
$_modifier = (isset($_POST['valide']) && $_POST['valide'] == $_trad['defaut']['modifier'])? true : false;
// validation du formaulare
$_valider = (isset($_POST['valide']) && $_POST['valide'] == $_trad['defaut']['MiseAJ'])? true : false;

// interdir une balise pour modification
$_formulaire['pseudo']['type'] = 'null';
// id_membre champ cachée
$_formulaire['id_membre'] = array(
	'type' => 'hidden',
	'acces' => 'private',
	'defaut' => $_id);

unset($_formulaire['mdp']['rectification']);
$_formulaire['mdp']['acces'] = 'private';
unset($_formulaire['email']['rectification']);

	

