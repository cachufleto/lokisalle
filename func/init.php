<?php

# Fonction inputValue()
# affiche les informations comptenue dans $_POST[$var]
# $var => string nom de l'indice
# $type => type de champ 'input', 'textarea'
# RETURN string
function ___inputValue($name, $type = 'input')
{

	/**********textarea***************/
	if ($type == 'textarea' && isset($_POST[$name])){
		return $_POST[$name];
	}
	/**************input******************/
	elseif(isset($_POST[$name])) {
		return 'value="' . $_POST[$name] . '" ';
	}
}

