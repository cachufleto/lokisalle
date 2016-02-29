<?php
// Intertion des parametres de fonctionement
include 'inc/init.inc.php';

// insertion des pages dinamiques
/*
$__param = PARAM . $nav . '.param.php';
if (file_exists($__param) )
	include $__param;

$__func = FUNC . $nav . '.func.php';
if (file_exists($__func) )
	include $__func;
*/

include PARAM . 'routes.param.php';
include_once $_route[$nav]['controleur'];
include INC . 'index.inc.php';



