<?php
include MODEL . 'Users.php';
include CONTROLEUR . 'Users.php';

$msg = usersValiderInscription($_GET);

include TEMPLATE . 'validerinscription.html.php';