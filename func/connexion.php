<?php
include(MODEL . 'Connexion.php');
include(CONTROLEUR . 'Connexion.php');
# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# $_form => tableau des items
# RETURN string msg1
// creation du control d'acces par répetition
if(!isset($_SESSION['connexion'])) $_SESSION['connexion'] = 3;

function formulaireValider(){

	return connexion();

}