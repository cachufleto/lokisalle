<?php
include_once FUNC . 'form.func.php';

# Fonction valideForm()
# Verifications des informations en provenance du formulaire
# $_form => tableau des items
# RETURN string msg
function valideForm()
{
    return usersChangerMotPasse();
}
