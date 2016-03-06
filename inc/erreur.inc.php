<?php

function erreur($nav)
{
	$_trad = setTrad();

	$msg = ($nav=='erreur404')?
			$_trad['erreur']['erreur404'] :
			$_trad['enConstruccion'];

	include TEMPLATE . 'erreur404.php';
}

erreur($nav);
