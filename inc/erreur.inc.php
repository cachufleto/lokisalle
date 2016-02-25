<?php
if(array_key_exists($nav, $_pages)){
	$msg = $_trad['enConstruccion'];
} else {
	$msg = $_trad['ERROR404'];
}

include(TEMPLATE . 'erreur.html.php');
