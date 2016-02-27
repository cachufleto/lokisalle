<?php
include_once FUNC . 'form.func.php';
include_once CONTROLEUR . 'Users.php';

# Fonction valideForm()
# Verifications des informations en provenance du formulaire
# $_form => tableau des items
# RETURN string msg
function valideForm()
{
	return usersConnexion();
}
