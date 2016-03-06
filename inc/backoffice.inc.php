<?php

function backoffice()
{
    $nav = 'backoffice';
    $_trad = setTrad();


    $activite = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' : 'Activité';
    $dernieresOffres = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' :  'Derniéres Offres';

   include TEMPLATE . 'backoffice.php';

}

backoffice();