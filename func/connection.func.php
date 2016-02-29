<?php
include FUNC . 'form.func.php';
include CONTROLEUR . 'Users.php';

# Fonction valideForm()
# Verifications des informations en provenance du formulaire
# $_form => tableau des items
# RETURN string msg
function valideForm()
{
	return usersConnexion();
}
