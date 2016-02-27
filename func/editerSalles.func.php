<?php
# FUNCTIONS Formulares
include_once FUNC . 'form.func.php';
 
// Variables
$extension = '';
$message = '';
$nomImage = '';
 
# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function formulaireValider(){

	return sallesEditer();

}