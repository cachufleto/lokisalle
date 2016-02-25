<?php
//array('menu'=>$menu, 'class'=>$class . ' col-'.$col);
$menu = liste_nav('navFooter');
$li = $menu['menu'];
include(TEMPLATE . 'navfooter.html.php');