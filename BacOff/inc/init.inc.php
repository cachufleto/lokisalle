<?php
/**
 * Created by PhpStorm.
 * User: stagiaire
 * Date: 25/02/2016
 * Time: 16:45
 */

$_menu = 'navAdmin';

// ajout du css admin
$_linksFiles['Css'][] = REPADMIN . 'css/admin.css';
$_linksFiles['Css'][] = REPADMIN . 'css/' . $nav . '.css';
$_linksFiles['Js'][] = REPADMIN . 'js/' . $nav . '.js';
$_linksFiles['JsFooter'][] = REPADMIN . 'js/' . $nav . '.footer.js';

#########################################################
## reetablire les tables de la base pour DEMO
#########################################################

include (MODEL . 'Install.php');

install();
