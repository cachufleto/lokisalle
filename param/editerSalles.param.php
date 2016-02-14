<?php
# FORMULAIRE D'INSCRIPTION

// Items du formulaire
$_formulaire = array();

$_formulaire['pays'] = array(
	'type' => 'text',
	'maxlength' => 20,
	'defaut' => $_trad['champ']['pays'],
	'obligatoire' => true);
	
$_formulaire['ville'] = array(
	'type' => 'text',
	'defaut' => $_trad['defaut']['ville'],
	'obligatoire' => true);

$_formulaire['adresse'] = array(
	'type' => 'textarea',
	'defaut' => $_trad['defaut']['Ouhabite'],
	'obligatoire' => true);

$_formulaire['cp'] = array(
	'type' => 'text',
	'defaut' => $_trad['defaut']['cp'],
	'obligatoire' => true);

$_formulaire['titre'] = array(
	'type' => 'text',
	'maxlength' => 20,
	'defaut' => $_trad['champ']['titre'],
	'obligatoire' => true);

$_formulaire['telephone'] = array(
	'type' => 'text',
	'length' => 10,
	'defaut' => $_trad['defaut']['telephone'],
	'obligatoire' => true);

$_formulaire['description'] = array(
	'type' => 'textarea',
	'defaut' => $_trad['defaut']['description'],
	'obligatoire' => true);

$_formulaire['photo'] = array(
	'type' => 'file',
	'defaut' => $_trad['defaut']['photo'],
	'obligatoire' => true);

$_formulaire['capacite'] = array(
	'type' => 'text',
	'defaut' => $_trad['defaut']['capacite'],
	'obligatoire' => true);

$_formulaire['categorie'] = array(
	'type' => 'radio',
	'option' => array('R', 'F', 'C'),
	'defaut' => 'R',
	'obligatoire' => true);

// ############## SUBMIT ############
$_formulaire['valide'] = array(
	'type' => 'submit',
	'defaut' => $_trad['defaut']["ajouter"]);
	