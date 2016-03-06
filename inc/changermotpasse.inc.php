<?php
// RECUPERATION du formulaire
changermotpasse();

function changermotpasse()
{
    $_trad = setTrad();
    include PARAM . 'changermotpasse.param.php';

    include FUNC . 'form.func.php';

    $msg = usersChangerMotPasse();

    $form = formulaireAfficher($_formulaire);

    include TEMPLATE . 'changermotpasse.html.php';
}