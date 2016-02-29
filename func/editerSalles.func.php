<?php
# FUNCTIONS Formulares
include FUNC . 'form.func.php';
 
// Variables
// que faire avec ça?

$extension = '';
$message = '';
$nomImage = '';

# Fonction formulaireValider()
# Verifications des informations en provenance du formulaire
# @_formulaire => tableau des items
# RETURN string msg
extract(sallesEditer());