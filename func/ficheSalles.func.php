<?php

# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
$position = ($_GET['pos'])? $_GET['pos'] : 1;

function formulaireValider(){

	global $position;
	return sallesFiche();

}