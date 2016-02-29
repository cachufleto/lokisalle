<?php
// RECUPERATION du formulaire
include PARAM . 'changermotpasse.param.php';
$form = formulaireAfficher($_formulaire);

include TEMPLATE . 'changermotpasse.html.php';