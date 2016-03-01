<?php
// Intertion des parametres de fonctionement
include 'inc/init.inc.php';
include PARAM . 'routes.param.php';
_debug($_POST, '$_POST', __FILE__);
_debug($_GET, '$_GET', __FILE__);
_debug($_route[$__nav], '$_route[$__nav]', __FILE__);
include_once $_route[$__nav]['controleur'];
include INC . 'index.inc.php';



