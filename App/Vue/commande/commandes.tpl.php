<style>
.row{
   background-color: #FF7F0E;
}
.row_0{
   background-color: #FFFFF8;
}
.row_1{
    background-color: #CCCCCC;
}
.row_2{
    background-color: #b58896;
    color: #CCCCCC;
}
</style>
<?php
$lien = LINK . '?nav=' . $nav;
include CONF . 'parametres.param.php';

$_liste = '';
$_total = 0;
$i = 1;
foreach($listePrix as $reservee=>$produit){
    $i++;
    $d1 = new DateTime($produit['date_reserve'], new DateTimeZone('Europe/Paris'));
    $t1 = $d1->getTimestamp();
    $row = ($t1 < time())? 'row_2' : 'row_'.($i%2);

    $_liste .= "<div class='ligne $row'>
                    <a href='?nav=ficheSalles&id={$produit['id_salle']}'>
                    <div class='titre'>{$produit['titre']}</div>
                    <div class='tronche'>".
                    date('d M Y ', $t1)
                    ."</div>
                    <div class='personne'>{$_trad['value'][$_prixPlage[$produit['tranche']]['libelle']]} / {$produit['capacitee']} pers.</div>
                    <div class='prix'>{$produit['prix']}€</div>
                    </a>
                </div>";
}

echo <<<EOL
<div class="ligne">
    <h1>{$_trad['nav'][$nav]}</h1>
</div>
<div id="commandes" class="ligne commandes">
<div class='ligne row'>
<div class='titre'>Salle</div>
<div class='tronche'>Date reservée</div>
<div class='personne'>Horarire / pers.</div>
<div class='prix'>Prix €</div>
</div>
    $_liste
</div>
EOL;
