<?php
function footer()
{
	$info = liste_nav('navFooter');
	$info['version'] = file_get_contents(PARAM . 'version.txt');
	return $info;
}

