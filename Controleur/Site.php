<?php
/**
 * Created by PhpStorm.
 * User: Domoquick
 * Date: 29/02/2016
 * Time: 23:31
 */
function siteBackoffice()
{
    global $__nav;
    $_trad = siteSelectTrad();
    $titre = $_trad['titre'][$__nav];
    $activite = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' : 'Activité';
    $dernieresOffres = (!empty($_POST))? '<textarea name="notreAtivite"></textarea>' :  'Derniéres Offres';
    include TEMPLATE . 'backoffice.html.php';
}