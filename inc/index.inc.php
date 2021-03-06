<?php

ob_start();

if($__nav == 'actif'){
    if(isset($_POST['valide'])){
        $_route['actif']['action']['valider']();
    } else {
        $_route['actif']['action']['afficher']();
    }

} else {
    $_route[$__nav]['action']();
}

$page = ob_get_contents();
ob_end_clean();

ob_start();

if (DEBUG){
    include INC . 'debug.inc.php';
}

$debug = ob_get_contents();
ob_end_clean();

ob_start();

include PARAM.'version.txt';

$version = ob_get_contents();
ob_end_clean();


// Entête
include  INC . 'header.inc.php';

// Navigation
include  INC . 'nav.inc.php';

// Navigation Pied de page
include  INC . 'navfooter.inc.php';

// Pied de page
include  INC . 'footer.inc.php';

// AFFICHAGE APPLICATION
include TEMPLATE . 'index.html.php';