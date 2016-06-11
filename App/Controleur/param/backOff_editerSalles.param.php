<?php
# FORMULAIRE D'INSCRIPTION

// Items du formulaire
$_formulaire = array();

/*
  `id_salle` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `cp` varchar(5) NOT NULL,
  `titre` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `capacite` int(3) UNSIGNED NOT NULL,
  `categorie` enum('R','C','F') NOT NULL DEFAULT 'R',
*/

$_formulaire['pays'] = array(
	'type' => 'text',
	'content' => 'text',
	'maxlength' => 20,
	'defaut' => $_trad['champ']['pays'],
	'obligatoire' => true);
	
$_formulaire['ville'] = array(
	'type' => 'text',
	'content' => 'text',
	'defaut' => $_trad['defaut']['ville'],
	'obligatoire' => true);

$_formulaire['adresse'] = array(
	'type' => 'textarea',
	'content' => 'text',
	'maxlength' => 300,
	'defaut' => $_trad['defaut']['Ouhabite'],
	'obligatoire' => true);

$_formulaire['cp'] = array(
	'type' => 'text',
	'content' => 'num',
	'defaut' => $_trad['defaut']['cp'],
	'obligatoire' => true);

$_formulaire['titre'] = array(
	'type' => 'text',
	'content' => 'text',
	'maxlength' => 20,
	'defaut' => $_trad['champ']['titre'],
	'obligatoire' => true);

$_formulaire['telephone'] = array(
	'type' => 'text',
	'content' => 'tel',
	'length' => 10,
	'defaut' => $_trad['defaut']['telephone'],
	'obligatoire' => true);

$_formulaire['photo'] = array(
	'type' => 'file',
	'content' => 'text',
	'defaut' => $_trad['defaut']['photo'],
	'obligatoire' => true);

$_formulaire['description'] = array(
	'type' => 'textarea',
	'content' => 'text',
	'maxlength' => 800,
	'defaut' => $_trad['defaut']['description'],
	'obligatoire' => true);

$_formulaire['capacite'] = array(
	'type' => 'text',
	'content' => 'int',
	'defaut' => $_trad['defaut']['capacite'],
	'obligatoire' => true);

$_formulaire['cap_min'] = array(
	'type' => 'text',
	'content' => 'int',
	'defaut' => $_trad['defaut']['cap_min'],
	'obligatoire' => true);

$_formulaire['tranche'] = array(
	'type' => 'text',
	'content' => 'int',
	'defaut' => $_trad['defaut']['tranche'],
	'obligatoire' => true);

$_formulaire['prix_personne'] = array(
	'type' => 'text',
	'content' => 'float',
	'defaut' => $_trad['defaut']['prix_personne'],
	'obligatoire' => true);

$_formulaire['categorie'] = array(
	'type' => 'radio',
	'content' => 'text',
	'option' => array('R', 'F', 'C', 'T' ),
	'defaut' => '',
	'obligatoire' => true);

// ############## SUBMIT ############
$_formulaire['valide'] = array(
	'type' => 'submit',
	'defaut' => $_trad['defaut']["ajouter"]);
	