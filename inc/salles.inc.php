<?php
include MODEL . 'Salles.php';
include CONTROLEUR . 'Salles.php';

$table = sallesListe();

include TEMPLATE . 'salles.html.php';