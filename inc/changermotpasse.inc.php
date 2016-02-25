<?php
# FORMULAIRE D'INSCRIPTION
# FUNCTIONS formulaires
include_once FUNC . 'form.func.php';

// RECUPERATION du formulaire
    $form = formulaireAfficher($_formulaire);

include(TEMPLATE . 'changermotpasse.html.php');