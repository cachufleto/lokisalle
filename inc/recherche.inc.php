<?php
include_once(FUNC.'form.func.php');
// afficher un formulaire de recherche
// menu déroulant pour la ville
// date-piqquer pour la date d'entreé
// date-pikquer pour la date de sortie
// date de sortie doit étre égale ou supperieur à la date d'entrée
// formulaire inscrit dans la liste de sales disponibles

$villes = listeDistinc('ville', 'salles', array('valide'=>'tokyo'));
$categories = listeDistinc('categorie', 'salles', array('valide'=>'R'));
$capacite = listeDistinc('capacite', 'salles', array('valide'=>'56'));

$resultat = 'RESULTAT';

include (TEMPLATE . 'recherche.html.php');