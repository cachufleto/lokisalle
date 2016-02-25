<?php

// Items du formulaire
$_formulaire = array();

$_formulaire['pseudo'] = array(
	'type' => 'text',
	'maxlength' => 14,
	'defaut' => $_trad['champ']['pseudo']);

$_formulaire['mdp'] = array(
	'type' => 'password',
	'maxlength' => 14,
	'defaut' => $_trad['defaut']['MotPasse']);

// ############## SUBMIT ############
$_formulaire['valide'] = array(
	'type' => 'submit',
	'defaut' => $_trad['defaut']['SeConnecter']);

$_formulaire['rapel'] = array(
	'type' => 'checkbox',
	'option' => array('deMoi'=>'on'),
	'defaut' => $_trad['defaut']['MotPasse']);


