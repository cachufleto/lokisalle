<?php

function erreur($_trad, $titre, $nav)
{

	$msg = ($nav=='erreur404')? $_trad['erreur']['erreur404'] : $_trad['enConstruccion'];
	include TEMPLATE . 'erreur404.php';
}

erreur($_trad, $titre, $nav);
