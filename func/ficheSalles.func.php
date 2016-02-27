<?php
# fonctions formulaires
include_once FUNC . 'form.func.php';

# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function formulaireValider(){

	$position = ($_GET['pos'])? $_GET['pos'] : 1;
	return sallesFiche($position);

}