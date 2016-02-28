<?php
# FUNCTIONS Formulares
include_once FUNC . 'form.func.php';
include CONTROLEUR . 'Users.php';
# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
function formulaireValider(){

	userInscritionFormulaire();

}
